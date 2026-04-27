<?php

namespace App\Actions\User;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class Login
{
    public function execute(array $data): array
    {
        // Validate manually (since we're inside Action)
        if (!isset($data['email']) || !isset($data['password'])) {
            throw ValidationException::withMessages([
                'email' => ['Email and password are required.']
            ]);
        }

        $user = User::with('userProfile')->where('email', $data['email'])->first();

        // Check credentials
        if (!$user || !Hash::check($data['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.']
            ]);
        }

        // Check status
        if ($user->status !== 'active') {
            throw new \Exception('Account is inactive.');
        }

        // Create Sanctum token
        $token = $user->createToken('api-token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token,
        ];
    }
}
