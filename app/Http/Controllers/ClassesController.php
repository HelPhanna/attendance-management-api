<?php

namespace App\Http\Controllers;

use App\Actions\Classes\CreateClass;
use App\Actions\Classes\DeleteClass;
use App\Actions\Classes\UpdateClass;
use App\Models\Classes;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ClassesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $classes = Classes::with('students')->get();

        return response()->json([
            'list' => [
                'data' => $classes,
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $action = new CreateClass();
            $class = $action->create($request);

            return response()->json([
                'data' => $class,
                'message' => 'Class created successfully',
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error creating class',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $class = Classes::findOrFail($id);

        return response()->json([
            'data' => $class,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Classes $class)
    {
        try {
            $action = new UpdateClass();
            $updatedClass = $action->update($request, $class);

            return response()->json([
                'data' => $updatedClass,
                'message' => 'Class updated successfully',
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error updating class',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Classes $class)
    {
        try {
            $action = new DeleteClass();
            $action->delete($class);

            return response()->json([
                'data' => $class,
                'message' => 'Class deleted successfully',
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Cannot delete class',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error deleting class',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
