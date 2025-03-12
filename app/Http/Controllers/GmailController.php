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
use Google_Service_Gmail_ModifyMessageRequest; // Add this at the top of your file

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
     * Return to dashboard based on user role
     */
    public function backToDashboard()
    {
        $user = Auth::user();
        
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
                                 ->with('success', 'Returned to dashboard.');
            }
        }

        // Fallback redirect if no user or valid role found
        return redirect('/')->with('success', 'Returned to home page.');
    }

    /**
     * Handle sending an email via Gmail API.
     */
    public function sendEmail(Request $request)
    {
        $request->validate([
            'to'            => 'required|email',
            'subject'       => 'required|string',
            'body'          => 'required|string',
            'attachments.*' => 'file|mimes:jpg,jpeg,png,pdf,doc,docx,xls,xlsx,txt|max:2048',
        ]);

        try {
            $client = $this->getGmailClient();
            $service = new Google_Service_Gmail($client);

            // You can adjust the "from" address as needed.
            $fromEmail = session('email_user') ?? 'your-email@gmail.com';
            $to = $request->input('to');
            $subject = $request->input('subject');
            $bodyContent = $request->input('body');
            $selectedLabels = $request->input('labels', []);

            // Create a MIME message (using a multipart MIME format if attachments exist)
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
                $message = $bodyContent;
            }

            $rawMessageString = $headers . $message;
            $rawMessage = rtrim(strtr(base64_encode($rawMessageString), '+/', '-_'), '=');

            $messageObj = new Google_Service_Gmail_Message();
            $messageObj->setRaw($rawMessage);
            $sentMessage = $service->users_messages->send('me', $messageObj);
            
            return redirect()->back()->with('success', "Message sent successfully. ID: {$sentMessage->getId()}");
        } catch (\Exception $e) {
            Log::error('Error sending email: ' . $e->getMessage());
            
            // Check if the error is related to authentication
            if (strpos($e->getMessage(), 'invalid_grant') !== false || 
                strpos($e->getMessage(), 'unauthorized') !== false) {
                // Delete the token to force re-authentication
                $this->forceReauthentication();
                return redirect()->back()->with('error', "Authentication error. Please try again.");
            }
            
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
            $redirectUri = route('admin.gmail.oauthCallback');
            Log::debug('Redirect URI set to: ' . $redirectUri);
            $client->setRedirectUri($redirectUri); 

            // Request both read + send scopes
            $client->setScopes([
                Google_Service_Gmail::GMAIL_MODIFY,  // Add this scope
                Google_Service_Gmail::GMAIL_READONLY,
                Google_Service_Gmail::GMAIL_SEND,
                'https://www.googleapis.com/auth/contacts.readonly'
            ]);
            $client->setAccessType('offline');
            $client->setPrompt('consent');

            $authCode = $request->get('code');
            Log::debug('Authorization Code:', ['code' => $authCode]);

            try {
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

                // Store email in session
                session(['email_user' => $userEmail]);

                Log::info('Google authenticated successfully for user ID: ' . $user->id);

                return redirect()->route('admin.gmail.emails')->with('success', 'Google authenticated successfully.');
            } catch (\Exception $e) {
                Log::error('OAuth Error: ' . $e->getMessage());
                return redirect()->route('admin.gmail.emails')->with('error', "OAuth Error: " . $e->getMessage());
            }
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
     * Helper method to force re-authentication by deleting the token
     */
    private function forceReauthentication()
    {
        $user = Auth::user();
        if ($user) {
            Log::info('Forcing re-authentication for user ID: ' . $user->id);
            $deletedRows = GmailToken::where('user_id', $user->id)->delete();
            Log::info('Deleted ' . $deletedRows . ' token(s)');
            
            session()->forget('email_user');
        }
    }

    /**
     * Logout and delete the stored token.
     */
    public function logout()
    {
        // Delete Token from Database
        $this->forceReauthentication();

        // Redirect with success message
        return redirect()->route('admin.gmail.emails')->with('success', 'Logged out successfully. You can now log in with a different Google account.');
    }

    /**
     * Force token deletion and re-authentication
     */
    public function resetToken()
    {
        $this->forceReauthentication();
        
        // Redirect to OAuth flow
        $client = new Google_Client();
        $client->setAuthConfig($this->credentialsPath);
        $client->setRedirectUri(route('admin.gmail.oauthCallback'));
        $client->setScopes([
            Google_Service_Gmail::GMAIL_MODIFY,  // Add this scope
            Google_Service_Gmail::GMAIL_READONLY,
            Google_Service_Gmail::GMAIL_SEND,
            'https://www.googleapis.com/auth/contacts.readonly'
        ]);
        $client->setAccessType('offline');
        $client->setPrompt('consent');
        
        $authUrl = $client->createAuthUrl();
        return redirect($authUrl)->with('info', 'Please authenticate with Google to continue.');
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
        try {
            $client = $this->getGmailClient();
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
        } catch (\Exception $e) {
            Log::error('Error listing emails: ' . $e->getMessage());
            
            if (strpos($e->getMessage(), 'invalid_grant') !== false || 
                strpos($e->getMessage(), 'unauthorized') !== false) {
                // Delete the token to force re-authentication
                $this->forceReauthentication();
                
                // Redirect to OAuth
                $client = new Google_Client();
                $client->setAuthConfig($this->credentialsPath);
                $client->setRedirectUri(route('admin.gmail.oauthCallback'));
                $client->setScopes([
                    Google_Service_Gmail::GMAIL_MODIFY,  // Add this scope
                    Google_Service_Gmail::GMAIL_READONLY,
                    Google_Service_Gmail::GMAIL_SEND,
                    'https://www.googleapis.com/auth/contacts.readonly'
                ]);
                $client->setAccessType('offline');
                $client->setPrompt('consent');
                
                $authUrl = $client->createAuthUrl();
                return redirect($authUrl)->with('info', 'Please authenticate with Google to continue.');
            }
            
            return view('admin.gmail.index')->with('error', 'Error loading emails. Please try again later.');
        }
    }

    /**
     * Fetches emails from Gmail API filtered by category with pagination.
     */
    public function getEmails(Request $request)
    {
        // Get pagination parameters
        $page = $request->input('page', 1);
        $perPage = $request->input('per_page', 20);
        
        // Calculate pagination offsets
        $startIndex = ($page - 1) * $perPage;
        
        // Map the category parameter to Gmail label(s)
        $category = strtolower($request->input('category', 'inbox'));
        $labelIds = [];
        switch ($category) {
            case 'inbox':
                $labelIds[] = 'INBOX';
                break;
            case 'sent':
                $labelIds[] = 'SENT';
                break;
            case 'starred':
                $labelIds[] = 'STARRED';
                break;
            case 'important':
                $labelIds[] = 'IMPORTANT';
                break;
            case 'drafts':
                $labelIds[] = 'DRAFT';
                break;
            case 'spam':
                $labelIds[] = 'SPAM';
                break;
            case 'trash':
                $labelIds[] = 'TRASH';
                break;
            case 'snoozed':
                $labelIds[] = 'SNOOZED';
                break;
            default:
                $labelIds[] = 'INBOX';
                break;
        }

        try {
            $client = $this->getGmailClient();
            $service = new Google_Service_Gmail($client);

            // Build parameters for listing messages
            $params = [
                'maxResults' => $perPage + 1, // Get one extra to check if there are more
                'labelIds'   => $labelIds,
            ];
            
            // Add search query if provided
            if ($request->has('q') && trim($request->input('q')) !== '') {
                $params['q'] = trim($request->input('q'));
            }
            
            // Add pagination parameters
            if ($startIndex > 0) {
                // Get messages list without the pageToken first to get total count
                $totalListReq = $service->users_messages->listUsersMessages('me', ['labelIds' => $labelIds]);
                $totalCount = $totalListReq->getResultSizeEstimate();
                
                // Then get the specific page
                $initialList = $service->users_messages->listUsersMessages('me', $params);
                $pageToken = $initialList->getNextPageToken();
                
                // If we need to skip forward multiple pages
                $currentStartIndex = 0;
                while ($pageToken && $currentStartIndex < $startIndex) {
                    $params['pageToken'] = $pageToken;
                    $nextList = $service->users_messages->listUsersMessages('me', $params);
                    $pageToken = $nextList->getNextPageToken();
                    $currentStartIndex += $perPage;
                }
                
                // Add the page token to our final request
                if ($pageToken) {
                    $params['pageToken'] = $pageToken;
                }
            } else {
                // First page, get total count
                $totalListReq = $service->users_messages->listUsersMessages('me', ['labelIds' => $labelIds]);
                $totalCount = $totalListReq->getResultSizeEstimate();
            }
            
            // Get messages for the current page
            $messagesResponse = $service->users_messages->listUsersMessages('me', $params);
            $messages = $messagesResponse->getMessages() ?: [];
            
            // Calculate if there are more pages
            $hasNextPage = count($messages) > $perPage;
            if ($hasNextPage) {
                // Remove the extra message we used to check for more pages
                array_pop($messages);
            }
            
            // Calculate total pages
            $totalPages = ceil($totalCount / $perPage);
            
            $emails = [];
            foreach ($messages as $msgItem) {
                $msg = $service->users_messages->get('me', $msgItem->getId(), [
                    'format' => 'metadata',
                    'metadataHeaders' => ['Subject', 'From', 'Date']
                ]);
                
                // Get Gmail labels to check if message is read/starred
                $labels = $msg->getLabelIds() ?: [];
                $isUnread = in_array('UNREAD', $labels);
                $isStarred = in_array('STARRED', $labels);
                
                $headers = $msg->getPayload()->getHeaders();
                $emailData = [
                    'id'      => $msg->getId(),
                    'snippet' => $msg->getSnippet(),
                    'subject' => '',
                    'from'    => '',
                    'date'    => '',
                    'read'    => !$isUnread,
                    'starred' => $isStarred
                ];
                
                foreach ($headers as $header) {
                    $name = strtolower($header->getName());
                    if ($name === 'subject') {
                        $emailData['subject'] = $header->getValue();
                    } elseif ($name === 'from') {
                        $emailData['from'] = $header->getValue();
                    } elseif ($name === 'date') {
                        $emailData['date'] = \Carbon\Carbon::parse($header->getValue())->format('M d, Y h:i A');
                    }
                }
                $emails[] = $emailData;
            }
            
            return response()->json([
                'data'         => $emails,
                'current_page' => (int)$page,
                'last_page'    => $totalPages,
                'total'        => $totalCount,
                'per_page'     => $perPage
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching emails: ' . $e->getMessage());
            
            if (strpos($e->getMessage(), 'invalid_grant') !== false || 
                strpos($e->getMessage(), 'unauthorized') !== false) {
                // For AJAX requests, return a special error code
                return response()->json([
                    'error' => 'Authentication error. Please refresh the page to re-authenticate.',
                    'auth_error' => true
                ], 401);
            }
            
            return response()->json(['error' => 'Error fetching emails.'], 500);
        }
    }

    /**
     * Download an email attachment.
     */
    public function downloadAttachment($emailId, $attachmentId, $filename)
    {
        try {
            $client = $this->getGmailClient();
            $service = new Google_Service_Gmail($client);
            
            $attachment = $service->users_messages_attachments->get('me', $emailId, $attachmentId);
            $attachmentData = $attachment->getData();
            
            // Decode the URL-safe base64 data
            $fileData = base64_decode(strtr($attachmentData, '-_', '+/'));

            return response()->streamDownload(function() use ($fileData) {
                echo $fileData;
            }, urldecode($filename), [
                'Content-Type' => 'application/octet-stream'
            ]);
        } catch (\Exception $e) {
            Log::error('Error downloading attachment: ' . $e->getMessage());
            
            if (strpos($e->getMessage(), 'invalid_grant') !== false || 
                strpos($e->getMessage(), 'unauthorized') !== false) {
                // Delete the token to force re-authentication
                $this->forceReauthentication();
                return redirect()->route('admin.gmail.emails')->with('error', 'Authentication error. Please re-login to Google.');
            }
            
            return redirect()->back()->with('error', 'Error downloading attachment: ' . $e->getMessage());
        }
    }

    /**
     * Fetch details of a single email - AJAX endpoint.
     */
    public function getEmailDetails(Request $request)
    {
        $emailId = $request->input('id');
        
        try {
            $client = $this->getGmailClient();
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
            $this->parseMessageParts($message->getPayload(), $emailData, $service, $emailId);
        
            // Optional: Mark email as read, with error handling
            try {
                $modifyRequest = new Google_Service_Gmail_ModifyMessageRequest();
                $modifyRequest->setRemoveLabelIds(['UNREAD']);
                $service->users_messages->modify('me', $emailId, $modifyRequest);
            } catch (\Exception $markReadError) {
                Log::warning('Could not mark email as read: ' . $markReadError->getMessage());
            }
    
            return response()->json($emailData);
        } catch (\Exception $e) {
            Log::error('Error fetching email details: ' . $e->getMessage());
            
            if (strpos($e->getMessage(), 'invalid_grant') !== false || 
                strpos($e->getMessage(), 'unauthorized') !== false) {
                // Force re-authentication
                $this->forceReauthentication();
                
                return response()->json([
                    'error' => 'Authentication error. Please refresh the page to re-authenticate.',
                    'auth_error' => true
                ], 401);
            }
            
            Log::debug('Full Error Details', [
                'error_code' => $e->getCode(),
                'error_message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json(['error' => 'Error fetching email details: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Recursively parse message parts to extract body and attachments
     */
    private function parseMessageParts($part, &$emailData, $service, $emailId, $path = '')
    {
        $mimeType = $part->getMimeType();
        
        if ($mimeType === 'text/plain' && empty($emailData['bodyText'])) {
            $body = $part->getBody()->getData();
            $emailData['bodyText'] = base64_decode(strtr($body, '-_', '+/'));
        } elseif ($mimeType === 'text/html' && empty($emailData['bodyHtml'])) {
            $body = $part->getBody()->getData();
            $emailData['bodyHtml'] = base64_decode(strtr($body, '-_', '+/'));
        } elseif ($part->getFilename() && $part->getBody()->getAttachmentId()) {
            $attachmentId = $part->getBody()->getAttachmentId();
            $filename = $part->getFilename();
            $mimeType = $part->getMimeType();
            
            $isImage = strpos($mimeType, 'image/') === 0;
            
            // For small attachments, we can include inline data
            if ($isImage && $part->getBody()->getSize() < 5000000) { // 5MB limit for inline images
                try {
                    $attachment = $service->users_messages_attachments->get('me', $emailId, $attachmentId);
                    $attachmentData = $attachment->getData();
                    
                    // Convert Gmail's URL-safe base64 to standard base64 for data URI
                    $base64Data = strtr($attachmentData, '-_', '+/');
                    $url = 'data:' . $mimeType . ';base64,' . $base64Data;
                } catch (\Exception $e) {
                    Log::error('Error fetching attachment: ' . $e->getMessage());
                    $url = null;
                }
            } else {
                // For larger files, use a download route
                $url = route('admin.gmail.downloadAttachment', [
                    'emailId' => $emailId,
                    'attachmentId' => $attachmentId,
                    'filename' => urlencode($filename)
                ]);
            }
            
            $emailData['attachments'][] = [
                'filename' => $filename,
                'url'      => $url,
                'isImage'  => $isImage,
                'mimeType' => $mimeType
            ];
        }
        
        // Process nested parts recursively
        if (property_exists($part, 'parts') && is_array($part->getParts())) {
            foreach ($part->getParts() as $i => $subPart) {
                $this->parseMessageParts($subPart, $emailData, $service, $emailId, $path . '.' . $i);
            }
        }
    }

    /**
     * Get contacts for autofill in the compose "To:" field.
     */
    public function getContacts(Request $request)
    {
        $query = $request->input('query', '');
        
        try {
            $client = $this->getGmailClient();
            
            // Include the contacts scope
            $client->addScope('https://www.googleapis.com/auth/contacts.readonly');
            
            // Use Google People API to fetch contacts
            $peopleService = new \Google_Service_PeopleService($client);
            
            // Fetch connections with names and email addresses
            $connections = $peopleService->people_connections->listPeopleConnections(
                'people/me',
                ['personFields' => 'names,emailAddresses']
            );

            $contacts = [];
            if ($connections->getConnections()) {
                foreach ($connections->getConnections() as $person) {
                    $names = $person->getNames();
                    $emails = $person->getEmailAddresses();
                    
                    if ($names && $emails) {
                        $name = $names[0]->getDisplayName();
                        $email = $emails[0]->getValue();
                        
                        // Filter by query if provided
                        if (empty($query) || 
                            stripos($name, $query) !== false || 
                            stripos($email, $query) !== false) {
                            $contacts[] = [
                                'name'  => $name,
                                'email' => $email
                            ];
                        }
                    }
                }
            }
            
            return response()->json($contacts);
        } catch (\Exception $e) {
            Log::error('Error fetching contacts: ' . $e->getMessage());
            return response()->json([], 500);
        }
    }

    /**
     * Get the Gmail client with proper authentication and error handling.
     */
    private function getGmailClient()
    {
        $client = new Google_Client();
        $client->setAuthConfig($this->credentialsPath);
        $client->setScopes([
            Google_Service_Gmail::GMAIL_MODIFY,  // Add this scope
            Google_Service_Gmail::GMAIL_READONLY,
            Google_Service_Gmail::GMAIL_SEND,
            'https://www.googleapis.com/auth/contacts.readonly'
        ]);
        $client->setAccessType('offline');
        $client->setPrompt('select_account consent');

        $user = Auth::user();
        if (!$user) {
            Log::error('No authenticated user found.');
            abort(401, 'Please log in to continue.');
        }

        $gmailToken = GmailToken::where('user_id', $user->id)->first();
        if (!$gmailToken) {
            $authUrl = $client->createAuthUrl();
            abort(401, 'Please authenticate with Google.');
        }
        
        $client->setAccessToken($gmailToken->access_token);

        // Refresh token if expired
        if ($client->isAccessTokenExpired()) {
            if ($client->getRefreshToken()) {
                try {
                    $newAccessToken = $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
                    
                    if (isset($newAccessToken['error'])) {
                        Log::error('Error refreshing token: ' . $newAccessToken['error_description']);
                        $gmailToken->delete();
                        $authUrl = $client->createAuthUrl();
                        abort(401, 'Authentication error. Please log in again.');
                    }
                    
                    $gmailToken->access_token = $newAccessToken;
                    $gmailToken->save();
                } catch (\Exception $e) {
                    Log::error('Error refreshing token: ' . $e->getMessage());
                    $gmailToken->delete();
                    $authUrl = $client->createAuthUrl();
                    abort(401, 'Authentication error. Please log in again.');
                }
            } else {
                // No refresh token, must re-authenticate
                $gmailToken->delete();
                $authUrl = $client->createAuthUrl();
                abort(401, 'Authentication error. Please log in again.');
            }
        }
        
        return $client;
    }
}