<?php

namespace App\Http\Controllers;

use App\Actions\UserProfile\CreateUserProfile;
use App\Actions\UserProfile\DeleteUserProfile;
use App\Actions\UserProfile\UpdateUserProfile;
use App\Models\UserProfile;
use Illuminate\Http\Request;

class UserProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, CreateUserProfile $createUserProfile)
    {
        try {
            $profile = $createUserProfile->execute($request);

            return response()->json([
                'success' => true,
                'message' => 'User profile created successfully.',
                'data' => $profile,
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create profile.',
                'error' => $e->getMessage(), // remove in production
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        try {
            $user = $request->user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized.'
                ], 401);
            }

            $profile = UserProfile::where('user_id', $user->id)->first();

            if (!$profile) {
                return response()->json([
                    'success' => false,
                    'message' => 'Profile not found.'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $profile
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch profile.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, UpdateUserProfile $updateUserProfile)
    {
        try {
            $profile = $updateUserProfile->execute($request);

            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully.',
                'data' => $profile,
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, DeleteUserProfile $deleteUserProfile)
    {
        try {
            $deleteUserProfile->execute($request);

            return response()->json([
                'success' => true,
                'message' => 'Profile deleted successfully.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }
}
