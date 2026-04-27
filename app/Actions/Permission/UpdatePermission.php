<?php

namespace App\Actions\Permission;

use App\Models\Permission;
use Illuminate\Http\Request;

class UpdatePermission
{
    public function update(Request $request, Permission $permission)
    {
        $validated = $request->validate([
            'name'        => ['required', 'string', 'unique:permissions,name,' . $permission->id],
            'key'         => ['required', 'string', 'unique:permissions,key,' . $permission->id],
            'status'      => ['required', 'in:active,inactive'],
            'description' => ['nullable', 'string'],
        ]);

        $permission->update($validated);

        return $permission->fresh();
    }
    
}
