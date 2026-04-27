<?php
 namespace App\Actions\Role;

use App\Models\Role;
use Illuminate\Http\Request;

class CreateRole{
    public function create(Request $request): Role
    {
        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'key'         => ['required', 'string', 'max:255', 'unique:roles,key'],
            'status'      => ['required', 'in:active,inactive'],
            'description' => ['nullable', 'string'],
        ]);

        return Role::create($validated);
    }
}