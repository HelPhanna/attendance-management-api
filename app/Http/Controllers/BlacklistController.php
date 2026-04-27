<?php

namespace App\Http\Controllers;

use App\Actions\Blacklist\CreateBlacklist;
use App\Actions\Blacklist\DeleteBlacklist;
use App\Actions\Blacklist\UpdateBlacklist;
use App\Models\Blacklist;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class BlacklistController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $blacklists = Blacklist::all();

        return response()->json([
            'list' => [
                'data' => $blacklists,
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $action = new CreateBlacklist();
            $blacklist = $action->create($request);

            return response()->json([
                'data' => $blacklist,
                'message' => 'Blacklist created successfully',
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error creating blacklist',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $blacklist = Blacklist::findOrFail($id);

        return response()->json([
            'data' => $blacklist,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Blacklist $blacklist)
    {
        try {
            $action = new UpdateBlacklist();
            $updatedBlacklist = $action->update($request, $blacklist);

            return response()->json([
                'data' => $updatedBlacklist,
                'message' => 'Blacklist updated successfully',
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error updating blacklist',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Blacklist $blacklist)
    {
        try {
            $action = new DeleteBlacklist();
            $action->delete($blacklist);

            return response()->json([
                'data' => $blacklist,
                'message' => 'Blacklist deleted successfully',
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Cannot delete blacklist',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error deleting blacklist',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
