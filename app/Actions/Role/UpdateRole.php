<?php

namespace App\Actions\Role;

use App\Models\Role;
use Illuminate\Http\Request;

class UpdateRole
{
    public function update(Request $request, Role $role): Role
    {
        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'key'         => ['required', 'string', 'max:255', 'unique:roles,key,' . $role->id],
            'status'      => ['required', 'in:active,inactive'],
            'description' => ['nullable', 'string'],
        ]);

        $role->update($validated);

        return $role->fresh();
    }
}
