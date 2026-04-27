<?php

namespace App\Actions\UserProfile;

use App\Models\UserProfile;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CreateUserProfile
{
    public function execute(Request $request)
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

        if (!empty($validated['date_of_birth'])) {
            $validated['date_of_birth'] = Carbon::createFromFormat('d/m/Y', $validated['date_of_birth'])
                ->format('Y-m-d');
        }

        $userId = $user->id;

        $profile = UserProfile::where('user_id', $userId)->first();

        // If new image uploaded
        if ($request->hasFile('image')) {

            // Delete old image FIRST
            if ($profile && $profile->image) {
                if (Storage::disk('public')->exists($profile->image)) {
                    Storage::disk('public')->delete($profile->image);
                }
            }

            // Store new image with RANDOM name (like before)
            $validated['image'] = $request->file('image')->store('profiles', 'public');
        }

        if ($profile) {
            $profile->update($validated);
            return $profile->fresh();
        }

        $validated['user_id'] = $userId;
        return UserProfile::create($validated);
    }
}
