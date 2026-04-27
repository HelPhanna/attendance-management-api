<?php

namespace App\Actions\User;

use App\Models\User;

class ShowUser
{
    public function execute(int $id): User
    {
        return User::findOrFail($id);
    }
}
