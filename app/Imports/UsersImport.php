<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Spatie\Permission\Models\Role;

class UsersImport implements ToModel, WithHeadingRow, WithValidation
{
    /**
     * Define the model creation logic.
     *
     * @param array $row
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $user = User::create([
            'name' => $row['name'],
            'email' => $row['email'],
            'password' => Hash::make(Str::random(10)), // Generate random password
        ]);

        if (isset($row['role'])) {
            $role = Role::where('name', $row['role'])->first();
            if ($role) {
                $user->assignRole($role);
            }
        }

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
}
