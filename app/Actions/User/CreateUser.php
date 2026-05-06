<?php

namespace App\Actions\User;

use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class CreateUser
{
    public function execute(array $data): User
    {
        $validator = Validator::make($data, [
            'name'      => 'required|string|max:255',
            'email'     => 'required|string|email|max:255|unique:users,email',
            'password'  => 'required|string|min:6|confirmed',
            'status'    => ['nullable', Rule::in(['active', 'inactive'])],
            'role'      => ['nullable', 'string', 'exists:roles,name'],
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return DB::transaction(function () use ($data) {

            // Create user
            $user = User::create([
                'name'     => $data['name'],
                'email'    => $data['email'],
                'password' => Hash::make($data['password']),
                'status'   => $data['status'] ?? 'active',
            ]);

            // Use provided role name, fall back to 'student'
            $roleName = $data['role'] ?? 'student';
            $role = Role::where('name', $roleName)->first();

            if (!$role) {
                throw new \Exception("Role \"{$roleName}\" not found.");
            }

            $user->roles()->attach($role->id);

            return $user->load('roles');
        });
    }
}
