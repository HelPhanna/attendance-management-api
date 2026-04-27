<?php

namespace App\Actions\UserProfile;

use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DeleteUserProfile
{
    public function execute(Request $request): bool
    {
        $user = $request->user();

        if (!$user) {
            throw new \Exception('Unauthorized.');
        }

        $profile = UserProfile::where('user_id', $user->id)->first();

        if (!$profile) {
            throw new \Exception('Profile not found.');
        }

        // Delete image file if exists
        if ($profile->image && Storage::disk('public')->exists($profile->image)) {
            Storage::disk('public')->delete($profile->image);
        }

        // Delete profile record
        return UserProfile::where('user_id', $user->id)->delete();
    }
}
