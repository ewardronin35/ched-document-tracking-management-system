<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;
use App\Models\Deadline;
use App\Models\User;
use App\Models\GmailToken;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\SoMasterList;
use App\Models\Cav;
use Illuminate\Support\Facades\Storage;
class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Ensure user is authenticated
        if (!$user) {
            return redirect()->route('login');
        }

        // Retrieve user roles (assuming using Spatie's package)
        $roles = $user->getRoleNames(); // Collection of role names
        $role = $roles->isNotEmpty() ? strtolower($roles->first()) : null;

        // List of valid roles
        $validRoles = [
            'admin', 'user', 'records', 'hr', 
            'regionaldirector', 'technical', 
            'accounting', 'supervisor', 'unifast'
        ];

        if (!$role || !in_array($role, $validRoles)) {
            abort(403, 'Unauthorized');
        }

        Log::info("Redirecting user with role: {$role} to view: {$role}.dashboard");

        switch ($role) {
            case 'admin':
                // Gather document statistics
                $totalDocuments = Document::count();
                $pendingApprovals = Document::where('status', 'Pending')->count();
                $approvedThisMonth = Document::where('status', 'Approved')
                    ->whereMonth('updated_at', Carbon::now()->month)
                    ->count();

                // Data for monthly submissions chart
                $monthlyDocuments = Document::selectRaw('MONTHNAME(created_at) as month, COUNT(*) as count')
                    ->groupBy('month')
                    ->orderByRaw('MONTH(created_at)')
                    ->get();

                // Upcoming deadlines: if a Deadline model exists, use it; otherwise, fallback to Document
                if (class_exists('App\Models\Deadline')) {
                    $upcomingDeadlines = \App\Models\Deadline::where('deadline_date', '>=', Carbon::now())
                        ->orderBy('deadline_date')
                        ->get();
                } else {
                    $upcomingDeadlines = Document::selectRaw('MONTHNAME(created_at) as month, COUNT(*) as count')
                        ->groupBy('month')
                        ->orderByRaw('MONTH(created_at)')
                        ->get();
                }

                // Latest documents for "Recent Documents" table
                $recentDocuments = Document::orderBy('updated_at', 'desc')
                    ->take(10)
                    ->get();
                    $soMasterListCount = SoMasterList::count();

                // Users for management section
                $users = User::with('roles')
                    ->orderBy('created_at', 'desc')
                    ->take(10)
                    ->get();
                $cavCount = Cav::count();
                // Additional stats: total Gmail authentications and total registered users
                $gmailLogins = GmailToken::count();
                $totalUsers = User::count();

                return view('admin.dashboard', compact(
                    'totalDocuments',
                    'pendingApprovals',
                    'approvedThisMonth',
                    'monthlyDocuments',
                    'upcomingDeadlines',
                    'recentDocuments',
                    'cavCount',
                    'users',
                    'soMasterListCount',
                    'gmailLogins',
                    'totalUsers'
                ));
            
            // For other roles, you can load role-specific dashboards
            case 'user':
                return view('user.dashboard');
            case 'hr':
                return view('hr.dashboard');
            case 'records':
                return view('records.dashboard');
            case 'regionaldirector':
                return view('regionaldirector.dashboard');
            case 'supervisor':
                return view('supervisor.dashboard');
            case 'technical':
                return view('technical.dashboard');
            case 'unifast':
                return view('unifast.dashboard');
            case 'accounting':
                return view('accounting.dashboard');
            default:
                abort(403, 'Unauthorized');
        }
    }

    public function details($id)
    {
        try {
            $document = Document::findOrFail($id);
            return view('admin.documents._detailss', compact('document'));
        } catch (\Exception $e) {
            Log::error('Document Details Error: ' . $e->getMessage());
            return response()->json([
                'error' => 'Document not found',
                'message' => 'The requested document could not be found.'
            ], 404);
        }
    }
}
