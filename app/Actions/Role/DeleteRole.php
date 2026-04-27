<?php

namespace App\Actions\Role;

use App\Models\Role;

class DeleteRole
{
    public function delete(Role $role): bool
    {
        $role->delete();
        return true;
    }
}
