<?php

namespace App\Actions\Permission;

use App\Models\Permission;
use Illuminate\Http\Request;

class CreatePermission
{
    public function create(Request $request)
    {
        $validated = $request->validate([
            'name'        => ['required', 'string', 'unique:permissions,name'],
            'key'         => ['required', 'string', 'unique:permissions,key'],
            'status'      => ['required', 'in:active,inactive'],
            'description' => ['nullable', 'string'],
        ]);

        return Permission::create($validated);
    }
}
