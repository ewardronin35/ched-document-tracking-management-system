<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\UsersImport;
use App\Mail\PasswordGeneratedMail;
use Illuminate\Support\Facades\Mail;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Response;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::with('roles')->paginate(10);
        $roles = Role::all();
        return view('manage-users.index', compact('users', 'roles'));
    }

    /**
     * Show the form for creating a new user.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::all();
        return view('manage-users.create', compact('roles'));
    }
    public function downloadTemplate()
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="users_template.csv"',
        ];

        $columns = ['name', 'email', 'role'];

        $callback = function() use ($columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            // Optionally, add sample data
            fputcsv($file, ['John Doe', 'john@example.com', 'admin']);
            fputcsv($file, ['Jane Smith', 'jane@example.com', 'user']);

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }
    /**
     * Store a newly created user in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'role' => 'required|exists:roles,name',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->assignRole($request->role);

        return redirect()->route('manage.users.index')->with('success', 'User created successfully.');
    }

    /**
     * Show the form for editing the specified user.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $roles = Role::all();
        return view('manage-users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified user in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|exists:roles,name',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->password) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        // Sync roles
        $user->syncRoles([$request->role]);

        return redirect()->route('manage.users.index')->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified user from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('manage.users.index')->with('success', 'User deleted successfully.');
    }

    /**
     * Show the form for importing users via CSV.
     *
     * @return \Illuminate\Http\Response
     */
    public function showImportForm()
    {
        return view('manage-users.import');
    }

    /**
     * Import users from a CSV file.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,txt',
        ]);

        try {
            Excel::import(new UsersImport, $request->file('file'));
            return redirect()->route('manage.users.index')->with('success', 'Users imported successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'There was an error importing the CSV file.');
        }
    }

    /**
     * Generate a random password for the specified user.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function generatePassword(User $user)
    {
        // Generate a new random password
        $newPassword = Str::random(10);
    
        // Hash the password and save it to the user
        $user->password = Hash::make($newPassword);
        $user->save();
    
        // Send the email with both user and password
        Mail::to($user->email)->send(new PasswordGeneratedMail($user, $newPassword));
    
        return redirect()->route('manage.users.index')->with('success', "Password generated successfully for {$user->name}.");
    }
      public function getUsers(Request $request)
    {
        if ($request->ajax()) {
            $data = User::with('roles')->select('users.*');

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('roles', function($row){
                    $roles = $row->roles->pluck('name')->toArray();
                    $badgeHtml = '';
                    foreach ($roles as $role) {
                        switch(strtolower($role)) {
                            case 'admin':
                                $badgeClass = 'badge-role-admin';
                                break;
                            case 'user':
                                $badgeClass = 'badge-role-user';
                                break;
                            default:
                                $badgeClass = 'bg-secondary';
                                break;
                        }
                        $badgeHtml .= '<span class="badge '.$badgeClass.'">'.ucfirst($role).'</span> ';
                    }
                    return $badgeHtml;
                })
                ->addColumn('actions', function($row){
                    $editUrl = route('manage.users.edit', $row->id);
                    $deleteUrl = route('manage.users.destroy', $row->id);
                    $csrf = csrf_field();
                    $method = method_field('DELETE');

                    $buttons = '
                        <a href="'.$editUrl.'" class="btn btn-sm btn-warning me-1" title="Edit User" data-bs-toggle="tooltip" data-bs-placement="top">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <form action="'.$deleteUrl.'" method="POST" style="display:inline-block;">
                            '.$csrf.'
                            '.$method.'
                            <button type="submit" class="btn btn-sm btn-danger" title="Delete User" data-bs-toggle="tooltip" data-bs-placement="top" onclick="return confirm(\'Are you sure you want to delete this user?\');">
                                <i class="fas fa-trash-alt"></i> Delete
                            </button>
                        </form>
                    ';
                    return $buttons;
                })
                ->rawColumns(['roles', 'actions'])
                ->make(true);
        }

        abort(404);
    }
}
