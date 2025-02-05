<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;
use App\Models\Deadline;
use App\Models\User; 
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Check if user is authenticated
        if (!$user) {
            // Redirect to login or show an error if not authenticated
            return redirect()->route('login');
        }

        // Retrieve user roles (assuming Spatie's package)
        $roles = $user->getRoleNames(); // Collection

        // Get the first role (assuming the user has one)
        $role = $roles->isNotEmpty() ? strtolower($roles->first()) : null;

        // List of valid roles
        $validRoles = ['admin', 'user', 'records', 'hr', 'regionaldirector', 'technical', 'accounting', 'supervisor', 'unifast'];

        // Check if the role is valid
        if (!$role || !in_array($role, $validRoles)) {
            abort(403, 'Unauthorized');
        }

        Log::info("Redirecting user with role: {$role} to view: {$role}.dashboard");

        // Fetch data based on role
        switch ($role) {
            case 'admin':
                // Fetch data required for admin dashboard
                $totalDocuments = Document::count();
                $pendingApprovals = Document::where('status', 'Pending')->count();
                $approvedThisMonth = Document::where('status', 'Approved')
                                              ->whereMonth('updated_at', Carbon::now()->month)
                                              ->count();

                // Fetch data for chart (monthly documents)
                $monthlyDocuments = Document::selectRaw('MONTHNAME(created_at) as month, COUNT(*) as count')
                                            ->groupBy('month')
                                            ->orderByRaw('MONTH(created_at)')
                                            ->get();

                // Upcoming deadlines
                $upcomingDeadlines = Document::selectRaw('MONTHNAME(created_at) as month, COUNT(*) as count')
                ->groupBy('month')
                ->orderByRaw('MONTH(created_at)')
                ->get();

                // Recent documents
                $recentDocuments = Document::orderBy('updated_at', 'desc')
                                           ->take(10)
                                           ->get();

                // Fetch users for User Management section
                $users = User::with('roles')->orderBy('created_at', 'desc')->take(10)->get();

                return view('admin.dashboard', compact(
                    'totalDocuments', 
                    'pendingApprovals', 
                    'approvedThisMonth', 
                    'monthlyDocuments', 
                    'upcomingDeadlines',
                    'recentDocuments',
                    'users' // Pass the users variable to the view
                ));
            
            // Add similar cases for other roles if they require specific data
            case 'user':
                // Fetch data required for user dashboard
                return view('user.dashboard');
            case 'hr':
                // Fetch data required for HR dashboard
                return view('hr.dashboard');
            case 'records':
                // Fetch data required for Records dashboard
                return view('records.dashboard');
            case 'regionaldirector':
                // Fetch data required for Regional Director dashboard
                return view('regionaldirector.dashboard');
            case 'supervisor':
                // Fetch data required for Supervisor dashboard
                return view('supervisor.dashboard');
            case 'technical':
                // Fetch data required for Technical dashboard
                return view('technical.dashboard');
            case 'unifast':
                // Fetch data required for Unifast dashboard
                return view('unifast.dashboard');
            case 'accounting':
                // Fetch data required for Accounting dashboard
                return view('accounting.dashboard');
            default:
                // Fallback to a general dashboard if no matching role is found
                abort(403, 'Unauthorized');
        }
    }

    public function details($id)
    {
        $document = Document::findOrFail($id);
        return view('admin.documents.partials.details', compact('document'));
    }

}
