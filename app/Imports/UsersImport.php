<?php

namespace App\Imports;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Illuminate\Support\Facades\Cache;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Validators\Failure;
use Exception;

class UsersImport implements ToModel, WithHeadingRow, WithValidation, WithChunkReading, SkipsOnFailure
{
    use SkipsFailures;

    /**
     * Define the model creation logic.
     *
     * @param array $row
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        try {
            $temporaryPassword = Str::random(10);

            // Create the user with a temporary password
            $user = User::create([
                'name' => $row['name'],
                'email' => $row['email'],
                'password' => Hash::make($temporaryPassword),
                'needs_password_change' => true, // Optional: If you have a field to track
            ]);

            // Fetch roles from cache or database
            $roles = Cache::remember('roles_list', now()->addHours(1), function () {
                return Role::pluck('name')->toArray();
            });

            // Assign the role
            if (isset($row['role']) && in_array($row['role'], $roles)) {
                $user->assignRole($row['role']);
            } else {
                Log::warning("Role '{$row['role']}' does not exist for user '{$row['email']}'.");
            }

            // Send password reset link using Fortify's password broker
            $status = Password::sendResetLink(['email' => $user->email]);

            if ($status !== Password::RESET_LINK_SENT) {
                Log::error("Failed to send password reset link to '{$user->email}'. Status: {$status}");
            }

            return $user;
        } catch (Exception $e) {
            Log::error("Error importing user '{$row['email']}': " . $e->getMessage());
            // Optionally, you can skip the row or handle it as needed
            return null;
        }
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

    /**
     * Handle skipped failures.
     *
     * @param \Maatwebsite\Excel\Validators\Failure[] $failures
     */
    public function onFailure(Failure ...$failures)
    {
        foreach ($failures as $failure) {
            Log::error("Import Failure on row {$failure->row()}: " . implode(', ', $failure->errors()));
        }
    }
}
