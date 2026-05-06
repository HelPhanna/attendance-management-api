<?php

namespace App\Actions\User;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class Login
{
    public function execute(array $data): array
    {
        if (!isset($data['email']) || !isset($data['password'])) {
            throw ValidationException::withMessages([
                'email' => ['Email and password are required.']
            ]);
        }

        // Load both userProfile AND roles so the session has full data
        $user = User::with(['userProfile', 'roles'])
            ->where('email', $data['email'])
            ->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.']
            ]);
        }

        if ($user->status !== 'active') {
            throw new \Exception('Account is inactive.');
        }

        $token = $user->createToken('api-token')->plainTextToken;

        return [
            'user'  => $user,
            'token' => $token,
        ];
    }
}
