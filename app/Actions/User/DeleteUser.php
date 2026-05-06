<?php

namespace App\Actions\User;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DeleteUser
{
    public function execute(int $targetUserId, int $requestingUserId): void
    {
        if ($targetUserId === $requestingUserId) {
            throw new \Exception('You cannot delete your own account.');
        }

        $target = User::findOrFail($targetUserId);

        // Prevent deleting another super_admin
        if ($target->roles()->where('key', 'super_admin')->exists()) {
            throw new \Exception('Super admin accounts cannot be deleted.');
        }

        DB::transaction(function () use ($target) {
            // Delete avatar/profile image if present
            $profile = $target->userProfile;
            if ($profile && $profile->image && Storage::disk('public')->exists($profile->image)) {
                Storage::disk('public')->delete($profile->image);
            }

            // Hard-delete the user (cascades to user_role, student, teacher, etc.)
            $target->delete();
        });
    }
}