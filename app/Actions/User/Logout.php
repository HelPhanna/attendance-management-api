<?php

namespace App\Actions\User;

use Illuminate\Http\Request;

class Logout
{
    public function execute(Request $request): void
    {
        // Delete current token only
        $request->user()->currentAccessToken()->delete();
    }
}
