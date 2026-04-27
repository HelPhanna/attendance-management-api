<?php

namespace App\Actions\UserProfile;

use App\Models\UserProfile;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UpdateUserProfile
{
    public function execute(Request $request): UserProfile
    {
        $user = $request->user();

        if (!$user) {
            throw new \Exception('Unauthorized.');
        }

        $validated = $request->validate([
            'first_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'gender' => ['nullable', 'string'],
            'date_of_birth' => ['nullable', 'string'],
            'address' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ]);

        $profile = UserProfile::where('user_id', $user->id)->first();

        if (!$profile) {
            throw new \Exception('Profile not found.');
        }

        if (!empty($validated['date_of_birth'])) {
            $validated['date_of_birth'] = Carbon::createFromFormat('d/m/Y', $validated['date_of_birth'])
                ->format('Y-m-d');
        }

        if ($request->hasFile('image')) {
            if ($profile->image && Storage::disk('public')->exists($profile->image)) {
                Storage::disk('public')->delete($profile->image);
            }

            $validated['image'] = $request->file('image')->store('profiles', 'public');
        }

        $profile->update($validated);

        return $profile->fresh();
    }
}
