<?php

namespace App\Actions\User;

class DeleteUser
{
    public function execute()
    {
        return response()->json([
            'message' => "Hello everyone"
        ]);
    }
}
