<?php

namespace App\Actions\Permission;

use App\Models\Permission;

class DeletePermission
{
    public function delete(Permission $permission)
    {
        $permission->delete();

        return true;
    }
}
