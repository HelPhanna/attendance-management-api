<?php

namespace App\Http\Controllers;

use App\Actions\User\DeleteUser;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function destroy(Request $request, int $id, DeleteUser $deleteUser)
    {
        try {
            $deleteUser->execute($id, $request->user()->id);

            return response()->json([
                'success' => true,
                'message' => 'User deleted successfully.',
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException) {
            return response()->json([
                'success' => false,
                'message' => 'User not found.',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}