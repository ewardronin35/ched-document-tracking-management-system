<?php

namespace App\Imports;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password; // Ensure this is imported
use App\Mail\PasswordResetMail;
use App\Mail\PasswordGeneratedMail;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Illuminate\Support\Facades\Cache;

class UsersImport implements ToModel, WithHeadingRow, WithValidation, WithChunkReading
{
    /**
     * Define the model creation logic.
     *
     * @param array $row
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Generate a temporary password
        $temporaryPassword = Str::random(10);
    
        // Create the user with the temporary password
        $user = User::create([
            'name' => $row['name'],
            'email' => $row['email'],
            'password' => Hash::make($temporaryPassword),
        ]);
    
        // Fetch roles from cache or database
        $roles = Cache::remember('roles_list', now()->addHours(1), function () {
            return Role::all()->pluck('name', 'id');
        });
    
        // Assign the role
        if (isset($row['role']) && array_key_exists($row['role'], $roles->toArray())) {
            $role = $roles->get($row['role']);
            if ($role) {
                $user->assignRole($role);
            }
        }
    
        // Generate a password reset token
        $token = Password::createToken($user);
    
        // Send the password reset email
        Mail::to($user->email)->queue(new PasswordResetMail($user, $token));
    
        return $user;
    }
    
    /**
     * Define validation rules for each row.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            '*.name' => 'required|string|max:255',
            '*.email' => 'required|email|unique:users,email',
            '*.role' => 'required|exists:roles,name',
        ];
    }

    /**
     * Define the chunk size for reading the file.
     *
     * @return int
     */
    public function chunkSize(): int
    {
        return 1000;
    }
}
