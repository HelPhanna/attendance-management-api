<?php

namespace App\Http\Controllers;

use App\Actions\User\CreateUser;
use App\Actions\User\Login;
use App\Actions\User\Logout;
use App\Actions\User\ShowUser;
use App\Actions\User\UpdateUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = User::with('roles')->get();
        return response()->json($user);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function login(Request $request, Login $loginAction)
    {
        try {

            $result = $loginAction->execute($request->all());

            return response()->json([
                'message' => 'Login successful',
                'token' => $result['token'],
                'user' => $result['user'],
            ], 200);
        } catch (ValidationException $e) {

            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (Exception $e) {

            return response()->json([
                'message' => $e->getMessage(),
            ], 403);
        } catch (\Throwable $e) {

            return response()->json([
                'message' => 'Server Error',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }
    public function logout(Request $request, Logout $logoutAction)
    {
        try {

            $logoutAction->execute($request);

            return response()->json([
                'message' => 'Logged out successfully'
            ], 200);
        } catch (\Throwable $e) {

            return response()->json([
                'message' => 'Server Error',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }
    public function store(Request $request, CreateUser $createUser)
    {
        try {

            $user = $createUser->execute($request->all());

            return response()->json([
                'message' => 'User created successfully',
                'data' => $user
            ], 201);
        } catch (ValidationException $e) {

            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (Exception $e) {

            return response()->json([
                'message' => 'Something went wrong',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id, ShowUser $showUser)
    {
        try {

            $user = $showUser->execute((int)$id);

            return response()->json([
                'message' => 'User retrieved successfully',
                'data' => $user
            ], 200);
        } catch (ModelNotFoundException $e) {

            return response()->json([
                'message' => 'User not found'
            ], 404);
        } catch (\Throwable $e) {

            return response()->json([
                'message' => 'Server Error',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, UpdateUser $updateUser)
    {
        try {
            $user = $updateUser->execute(
                $request->user()->id,   // logged-in user id
                $request->all()
            );

            return response()->json([
                'message' => 'User updated successfully',
                'data' => $user,
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Server Error',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
