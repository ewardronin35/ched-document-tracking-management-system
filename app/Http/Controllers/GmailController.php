<?php

namespace App\Http\Controllers;

use Google_Client;
use Google_Service_Gmail;
use Google_Service_Gmail_Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Models\GmailToken;
use Illuminate\Support\Facades\Auth;

class GmailController extends Controller
{
    protected $credentialsPath;

    public function __construct()
    {
        // Path to your Google API credentials
        $this->credentialsPath = storage_path('app/credentials.json');
    }

    /**
     * Display the main email management page.
     */
    public function index()
    {
        return view('admin.gmail.index');
    }

    /**
     * Handle sending an email via Gmail API.
     */
    public function sendEmail(Request $request)
    {
        // Validate input
        $request->validate([
            'to'            => 'required|email',
            'subject'       => 'required|string',
            'body'          => 'required|string',
            'attachments.*' => 'file|mimes:jpg,jpeg,png,pdf,doc,docx,xls,xlsx,txt|max:2048',
        ]);

        // Initialize Google Client with read + send scopes
        $client = new Google_Client();
        $client->setAuthConfig($this->credentialsPath);
        $client->setScopes([
            Google_Service_Gmail::GMAIL_READONLY,
            Google_Service_Gmail::GMAIL_SEND,
        ]);
        $client->setAccessType('offline');
        $client->setPrompt('select_account consent');

        // **Retrieve Token from Database**
        $user = Auth::user();
        if (!$user) {
            Log::error('No authenticated user found.');
            return redirect()->route('login')->with('error', 'Please log in to continue.');
        }

        $gmailToken = GmailToken::where('user_id', $user->id)->first();
        if ($gmailToken) {
            $client->setAccessToken($gmailToken->access_token);
        } else {
            // No token found, redirect to OAuth
            $authUrl = $client->createAuthUrl();
            return redirect($authUrl)->with('error', 'Please authenticate with Google to send emails.');
        }

        // Check if token has the required scopes
        if (!$this->tokenHasRequiredScopes($client->getAccessToken(), [
            Google_Service_Gmail::GMAIL_READONLY,
            Google_Service_Gmail::GMAIL_SEND
        ])) {
            // Force reacquisition
            $gmailToken->delete();
            $authUrl = $client->createAuthUrl();
            return redirect($authUrl)->with('error', 'Please authenticate with Google to send emails.');
        }

        // Refresh token if expired
        if ($client->isAccessTokenExpired()) {
            if ($client->getRefreshToken()) {
                $newAccessToken = $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
                // Update token in database
                $gmailToken->access_token = $newAccessToken;
                $gmailToken->save();
            } else {
                // No refresh token, must re-authenticate
                $gmailToken->delete();
                $authUrl = $client->createAuthUrl();
                return redirect($authUrl)->with('error', 'Please re-authenticate with Google to send emails.');
            }
        }

        // Now we have a valid token with correct scopes
        $service = new Google_Service_Gmail($client);

        // Prepare the raw email
        $fromEmail = session('email_user') ?? 'your-email@gmail.com'; // Adjust as needed
        $to = $request->input('to');
        $subject = $request->input('subject');
        $bodyContent = $request->input('body');

        // Create MIME message
        $boundary = uniqid(rand(), true);
        $delimiter = '==Multipart_Boundary_x' . $boundary . 'x';

        $headers = "From: \"Your Name\" <{$fromEmail}>\r\n";
        $headers .= "To: {$to}\r\n";
        $headers .= "Subject: {$subject}\r\n";
        $headers .= "MIME-Version: 1.0\r\n";

        if ($request->hasFile('attachments')) {
            $headers .= "Content-Type: multipart/mixed; boundary=\"{$delimiter}\"\r\n\r\n";
            $message = "--{$delimiter}\r\n";
            $message .= "Content-Type: text/plain; charset=\"utf-8\"\r\n";
            $message .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
            $message .= "{$bodyContent}\r\n\r\n";

            foreach ($request->file('attachments') as $file) {
                $filename = $file->getClientOriginalName();
                $fileData = file_get_contents($file->getRealPath());
                $encodedFile = chunk_split(base64_encode($fileData));

                $message .= "--{$delimiter}\r\n";
                $message .= "Content-Type: {$file->getMimeType()}; name=\"{$filename}\"\r\n";
                $message .= "Content-Transfer-Encoding: base64\r\n";
                $message .= "Content-Disposition: attachment; filename=\"{$filename}\"\r\n\r\n";
                $message .= "{$encodedFile}\r\n\r\n";
            }

            $message .= "--{$delimiter}--";
        } else {
            $headers .= "Content-Type: text/plain; charset=\"utf-8\"\r\n\r\n";
            $message = "{$bodyContent}";
        }

        $rawMessageString = $headers . $message;
        $rawMessage = rtrim(strtr(base64_encode($rawMessageString), '+/', '-_'), '=');

        // Create and send the message
        try {
            $messageObj = new Google_Service_Gmail_Message();
            $messageObj->setRaw($rawMessage);

            $sentMessage = $service->users_messages->send('me', $messageObj);

            return redirect()->back()->with('success', "Message sent successfully. ID: {$sentMessage->getId()}");
        } catch (\Exception $e) {
            Log::error('Error sending email: ' . $e->getMessage());
            return redirect()->back()->with('error', "Error sending email: " . $e->getMessage());
        }
    }

    /**
     * OAuth callback to fetch or refresh tokens.
     */
    public function handleOAuthCallback(Request $request)
    {
        Log::debug('Callback triggered with query', $request->all());

        if ($request->has('code')) {
            $client = new Google_Client();
            $client->setAuthConfig($this->credentialsPath);
            
            // **Set Redirect URI Consistently**
            $redirectUri = route('admin.gmail.oauthCallback'); // Resolves to http://127.0.0.1:8000/admin/gmail/oauth/callback
            Log::debug('Redirect URI set to: ' . $redirectUri);
            $client->setRedirectUri($redirectUri); 

            // Request both read + send scopes
            $client->setScopes([
                Google_Service_Gmail::GMAIL_READONLY,
                Google_Service_Gmail::GMAIL_SEND
            ]);
            $client->setAccessType('offline');
            $client->setPrompt('consent');

            $authCode = $request->get('code');
            Log::debug('Authorization Code:', ['code' => $authCode]);

            $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);

            if (isset($accessToken['error'])) {
                Log::error('Error fetching token:', $accessToken);
                return redirect()->route('admin.gmail.emails')->with('error', "Error fetching token: " . $accessToken['error_description']);
            }

            // Get user profile
            $service = new Google_Service_Gmail($client);
            $profile = $service->users->getProfile('me');
            $userEmail = $profile->getEmailAddress();

            // Associate the token with the authenticated user
            $user = Auth::user();
            if (!$user) {
                Log::error('No authenticated user found during OAuth callback.');
                return redirect()->route('login')->with('error', 'Please log in to continue.');
            }

            // Store or update the token in the database
            GmailToken::updateOrCreate(
                ['user_id' => $user->id],
                ['access_token' => $accessToken]
            );

            // Store email in session (optional)
            session(['email_user' => $userEmail]);

            Log::info('Google authenticated successfully for user ID: ' . $user->id);

            return redirect()->route('admin.gmail.emails')->with('success', 'Google authenticated successfully.');
        } elseif ($request->has('error')) {
            $error = $request->get('error');
            Log::error("OAuth Error: {$error}");
            return redirect()->route('admin.gmail.emails')->with('error', "OAuth Error: {$error}. Please try again.");
        } else {
            Log::warning('OAuth callback without authorization code or error.');
            return redirect()->route('admin.gmail.emails')->with('error', "Invalid OAuth callback request.");
        }
    }

    /**
     * Initialize Gmail Client.
     */
    private function initializeGmailClient()
    {
        $client = new Google_Client();
        $client->setAuthConfig($this->credentialsPath);
        $client->setScopes([
            Google_Service_Gmail::GMAIL_READONLY,
            Google_Service_Gmail::GMAIL_SEND
        ]);
        $client->setAccessType('offline');
        $client->setPrompt('select_account consent');

        $user = Auth::user();
        if (!$user) {
            Log::error('No authenticated user found.');
            return redirect()->route('login')->with('error', 'Please log in to continue.');
        }

        // Retrieve token from database
        $gmailToken = GmailToken::where('user_id', $user->id)->first();

        if ($gmailToken) {
            $client->setAccessToken($gmailToken->access_token);
        }

        // Check if token has the required scopes
        if (!$this->tokenHasRequiredScopes($client->getAccessToken(), [
            Google_Service_Gmail::GMAIL_READONLY,
            Google_Service_Gmail::GMAIL_SEND
        ])) {
            // Force reacquisition
            if ($gmailToken) {
                $gmailToken->delete();
            }
            $authUrl = $client->createAuthUrl();
            return redirect($authUrl)->with('error', 'Please authenticate with Google.');
        }

        // Refresh token if expired
        if ($client->isAccessTokenExpired()) {
            if ($client->getRefreshToken()) {
                $newAccessToken = $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());

                // Update token in database
                if ($gmailToken) {
                    $gmailToken->access_token = $newAccessToken;
                    $gmailToken->save();
                } else {
                    GmailToken::create([
                        'user_id' => $user->id,
                        'access_token' => $newAccessToken,
                    ]);
                }
            } else {
                // No refresh token, must re-authenticate
                if ($gmailToken) {
                    $gmailToken->delete();
                }
                $authUrl = $client->createAuthUrl();
                return redirect($authUrl)->with('error', 'Please re-authenticate with Google.');
            }
        }

        return $client;
    }

    /**
     * Logout and delete the stored token.
     */
    public function logout()
    {
        // **Delete Token from Database**
        $user = Auth::user();
        if ($user) {
            GmailToken::where('user_id', $user->id)->delete();
        }

        // Remove the user email session
        session()->forget('email_user');

        // Retrieve the authenticated user
        if ($user) {
            // Get the user's roles using Spatie's Role package
            $roles = $user->getRoleNames(); 
            $role = $roles->isNotEmpty() ? strtolower($roles->first()) : null;

            // Define valid roles
            $validRoles = [
                'admin', 'user', 'records', 'hr', 
                'regionaldirector', 'technical', 
                'accounting', 'supervisor'
            ];

            // If a valid role exists for the user, redirect to the corresponding dashboard
            if ($role && in_array($role, $validRoles)) {
                return redirect()->route("{$role}.dashboard")
                                 ->with('success', 'Logged out successfully.');
            }
        }

        // Fallback redirect in case no user or valid role found
        return redirect('/')->with('success', 'Logged out successfully.');
    }

    /**
     * Helper method to check if the current token includes ALL required scopes.
     */
    private function tokenHasRequiredScopes($tokenInfo, array $requiredScopes)
    {
        if (!isset($tokenInfo['scope'])) {
            return false;
        }

        // The scope field can be a space-separated string of scopes
        $existingScopes = explode(' ', $tokenInfo['scope']);

        foreach ($requiredScopes as $scope) {
            if (!in_array($scope, $existingScopes, true)) {
                return false;
            }
        }
        return true;
    }
    
    /**
     * List sent emails.
     */
    public function listSentEmails()
    {
        return $this->index(); // Reuse the index view
    }

    /**
     * List draft emails.
     */
    public function listDraftEmails()
    {
        return $this->index(); // Reuse the index view
    }

    /**
     * List spam emails.
     */
    public function listSpamEmails()
    {
        return $this->index(); // Reuse the index view
    }

    /**
     * List trash emails.
     */
    public function listTrashEmails()
    {
        return $this->index(); // Reuse the index view
    }

    /**
     * List all emails.
     */
    public function listEmails()
    {
        $client = new Google_Client();
        $client->setAuthConfig($this->credentialsPath);
        // Always request both scopes to avoid overwriting the token with partial scopes
        $client->setScopes([
            Google_Service_Gmail::GMAIL_READONLY,
            Google_Service_Gmail::GMAIL_SEND
        ]);
        $client->setAccessType('offline');
        $client->setPrompt('select_account consent');

        // **Retrieve Token from Database**
        $user = Auth::user();
        if (!$user) {
            Log::error('No authenticated user found.');
            return redirect()->route('login')->with('error', 'Please log in to continue.');
        }

        $gmailToken = GmailToken::where('user_id', $user->id)->first();
        if ($gmailToken) {
            $client->setAccessToken($gmailToken->access_token);
        } else {
            // No token found, redirect to OAuth
            $authUrl = $client->createAuthUrl();
            return redirect($authUrl);
        }

        // Check if token has the required scopes
        if (!$this->tokenHasRequiredScopes($client->getAccessToken(), [
            Google_Service_Gmail::GMAIL_READONLY,
            Google_Service_Gmail::GMAIL_SEND
        ])) {
            // Force reacquisition
            if ($gmailToken) {
                $gmailToken->delete();
            }
            $authUrl = $client->createAuthUrl();
            return redirect($authUrl);
        }

        // Refresh token if expired
        if ($client->isAccessTokenExpired()) {
            if ($client->getRefreshToken()) {
                $newAccessToken = $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
                // Update token in database
                $gmailToken->access_token = $newAccessToken;
                $gmailToken->save();
            } else {
                // No refresh token, must re-authenticate
                if ($gmailToken) {
                    $gmailToken->delete();
                }
                $authUrl = $client->createAuthUrl();
                return redirect($authUrl);
            }
        }

        // Now call the Gmail API
        $service = new Google_Service_Gmail($client);
        $messagesResponse = $service->users_messages->listUsersMessages('me', ['maxResults' => 20]);
        $messages = $messagesResponse->getMessages() ?: [];

        $emails = [];
        foreach ($messages as $msgItem) {
            $msg = $service->users_messages->get('me', $msgItem->getId(), [
                'format' => 'metadata',
                'metadataHeaders' => ['Subject', 'From', 'Date']
            ]);
            $headers = $msg->getPayload()->getHeaders();

            $emailData = [
                'id'      => $msg->getId(),
                'snippet' => $msg->getSnippet(),
                'subject' => '',
                'from'    => '',
                'date'    => ''
            ];

            foreach ($headers as $header) {
                $name = strtolower($header->getName());
                if ($name === 'subject') {
                    $emailData['subject'] = $header->getValue();
                } elseif ($name === 'from') {
                    $emailData['from'] = $header->getValue();
                } elseif ($name === 'date') {
                    $emailData['date'] = $header->getValue();
                }
            }
            $emails[] = $emailData;
        }

        return view('admin.gmail.index', compact('emails'));
    }
    /**
 * Fetches emails from Gmail API
 */
public function getEmails(Request $request)
{
    // Initialize Google Client
    $client = new Google_Client();
    $client->setAuthConfig($this->credentialsPath);
    $client->setScopes([
        Google_Service_Gmail::GMAIL_READONLY,
        Google_Service_Gmail::GMAIL_SEND
    ]);
    $client->setAccessType('offline');
    $client->setPrompt('select_account consent');

    $user = Auth::user();
    if (!$user) {
        Log::error('No authenticated user found.');
        return redirect()->route('login')->with('error', 'Please log in to continue.');
    }

    // Retrieve token from database
    $gmailToken = GmailToken::where('user_id', $user->id)->first();
    if ($gmailToken) {
        $client->setAccessToken($gmailToken->access_token);
    } else {
        // No token found, redirect to OAuth
        $authUrl = $client->createAuthUrl();
        return redirect($authUrl)->with('error', 'Please authenticate with Google.');
    }

    // Check if token has the required scopes
    if (!$this->tokenHasRequiredScopes($client->getAccessToken(), [
        Google_Service_Gmail::GMAIL_READONLY,
        Google_Service_Gmail::GMAIL_SEND
    ])) {
        // Force reacquisition
        $gmailToken->delete();
        $authUrl = $client->createAuthUrl();
        return redirect($authUrl)->with('error', 'Please authenticate with Google.');
    }

    // Refresh token if expired
    if ($client->isAccessTokenExpired()) {
        if ($client->getRefreshToken()) {
            $newAccessToken = $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
            $gmailToken->access_token = $newAccessToken;
            $gmailToken->save();
        } else {
            $gmailToken->delete();
            $authUrl = $client->createAuthUrl();
            return redirect($authUrl)->with('error', 'Please re-authenticate with Google.');
        }
    }

    try {
        // Initialize Gmail Service
        $service = new Google_Service_Gmail($client);
        $messagesResponse = $service->users_messages->listUsersMessages('me', ['maxResults' => 20]);
        $messages = $messagesResponse->getMessages() ?: [];

        $emails = [];
        foreach ($messages as $msgItem) {
            $msg = $service->users_messages->get('me', $msgItem->getId(), [
                'format' => 'metadata',
                'metadataHeaders' => ['Subject', 'From', 'Date']
            ]);
            $headers = $msg->getPayload()->getHeaders();

            $emailData = [
                'id'      => $msg->getId(),
                'snippet' => $msg->getSnippet(),
                'subject' => '',
                'from'    => '',
                'date'    => ''
            ];

            foreach ($headers as $header) {
                $name = strtolower($header->getName());
                if ($name === 'subject') {
                    $emailData['subject'] = $header->getValue();
                } elseif ($name === 'from') {
                    $emailData['from'] = $header->getValue();
                } elseif ($name === 'date') {
                    $emailData['date'] = Carbon::parse($header->getValue())->format('M d, Y h:i A');
                }
            }
            $emails[] = $emailData;
        }

        return response()->json([
            'data' => $emails
        ]);
    } catch (\Exception $e) {
        Log::error('Error fetching emails: ' . $e->getMessage());
        return response()->json(['error' => 'Error fetching emails.'], 500);
    }
}
/**
 * Fetch details of a single email.
 * AJAX endpoint.
 */
public function getEmailDetails(Request $request)
{
    $emailId = $request->input('id');

    // Initialize Gmail Client
    $client = new Google_Client();
    $client->setAuthConfig($this->credentialsPath);
    $client->setScopes([
        Google_Service_Gmail::GMAIL_READONLY,
        Google_Service_Gmail::GMAIL_SEND
    ]);
    $client->setAccessType('offline');
    $client->setPrompt('select_account consent');

    $user = Auth::user();
    if (!$user) {
        Log::error('No authenticated user found.');
        return response()->json(['error' => 'Please log in to continue.'], 401);
    }

    // Retrieve token from database
    $gmailToken = GmailToken::where('user_id', $user->id)->first();
    if ($gmailToken) {
        $client->setAccessToken($gmailToken->access_token);
    } else {
        // No token found, redirect to OAuth
        $authUrl = $client->createAuthUrl();
        return response()->json(['error' => 'Please authenticate with Google.', 'redirect' => $authUrl], 401);
    }

    // Check if token has the required scopes
    if (!$this->tokenHasRequiredScopes($client->getAccessToken(), [
        Google_Service_Gmail::GMAIL_READONLY,
        Google_Service_Gmail::GMAIL_SEND
    ])) {
        // Force reacquisition
        $gmailToken->delete();
        $authUrl = $client->createAuthUrl();
        return response()->json(['error' => 'Please authenticate with Google.', 'redirect' => $authUrl], 401);
    }

    // Refresh token if expired
    if ($client->isAccessTokenExpired()) {
        if ($client->getRefreshToken()) {
            $newAccessToken = $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
            $gmailToken->access_token = $newAccessToken;
            $gmailToken->save();
        } else {
            $gmailToken->delete();
            $authUrl = $client->createAuthUrl();
            return response()->json(['error' => 'Please re-authenticate with Google.', 'redirect' => $authUrl], 401);
        }
    }

    try {
        $service = new Google_Service_Gmail($client);
        $message = $service->users_messages->get('me', $emailId, ['format' => 'full']);
        $headers = $message->getPayload()->getHeaders();

        $emailData = [
            'subject'     => '',
            'from'        => '',
            'date'        => '',
            'bodyHtml'    => '',
            'bodyText'    => '',
            'attachments' => []
        ];

        foreach ($headers as $header) {
            $name = strtolower($header->getName());
            if ($name === 'subject') {
                $emailData['subject'] = $header->getValue();
            } elseif ($name === 'from') {
                $emailData['from'] = $header->getValue();
            } elseif ($name === 'date') {
                $emailData['date'] = Carbon::parse($header->getValue())->format('M d, Y h:i A');
            }
        }

        // Parse body
        $payload = $message->getPayload();
        if ($payload->getMimeType() === 'text/plain') {
            $body = $payload->getBody()->getData();
            $emailData['bodyText'] = base64_decode(strtr($body, '-_', '+/'));
        } else {
            $parts = $payload->getParts();
            foreach ($parts as $part) {
                if ($part->getMimeType() === 'text/html') {
                    $body = $part->getBody()->getData();
                    $emailData['bodyHtml'] = base64_decode(strtr($body, '-_', '+/'));
                }
                // Handle attachments
                if ($part->getFilename() && $part->getBody()->getAttachmentId()) {
                    $attachmentId = $part->getBody()->getAttachmentId();
                    $attachment = $service->users_messages_attachments->get('me', $emailId, $attachmentId);
                    $attachmentData = $attachment->getData();
                    $mimeType = $part->getMimeType();
                    $filename = $part->getFilename();
                    $isImage = false;
                    $url = null;

                    if (strpos($mimeType, 'image/') === 0) {
                        $isImage = true;
                        // Convert Gmail's URL-safe base64 to standard base64 for data URI
                        $base64Data = base64_encode(base64_decode(strtr($attachmentData, '-_', '+/')));
                        $url = 'data:' . $mimeType . ';base64,' . $base64Data;
                    }
                    // For non-image files, you can implement a download route or similar

                    $emailData['attachments'][] = [
                        'filename' => $filename,
                        'url'      => $url,
                        'isImage'  => $isImage,
                    ];
                }
            }
        }

        return response()->json($emailData);
    } catch (\Exception $e) {
        Log::error('Error fetching email details: ' . $e->getMessage());
        return response()->json(['error' => 'Error fetching email details.'], 500);
    }
}

}
