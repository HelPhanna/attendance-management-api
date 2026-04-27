<?php

namespace App\Http\Controllers;

use App\Actions\AcademicYear\CreateAcademicYear;
use App\Actions\AcademicYear\DeleteAcademicYear;
use App\Actions\AcademicYear\UpdateAcademicYear;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AcademicYearController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $allData = AcademicYear::all();
        return response()->json([
            'list'=>$allData
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request , CreateAcademicYear $action)
    {
        try{
            $academicYear = $action->create($request);
            return response()->json([
                'data' => $academicYear,
                'message' => 'Academic year created successfully'
            ]);
        }catch(ValidationException $e){
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error creating academic year',
                'error' => $e->getMessage(),
            ], 500);
        }
        
        }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = AcademicYear::findOrFail($id);

        return response()->json([
            'data' => $data
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request , AcademicYear $academicYear)
    {
        try{
            $action = new UpdateAcademicYear($academicYear);
            $data = $action->update($request);
            return response()->json([
                'data' => $data,
                'message' => 'Academic year updated successfully'
            ]);
        }catch(ValidationException $e){
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error updating academic year',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AcademicYear $academicYear , DeleteAcademicYear $singleDelete)
    {
        $record = $singleDelete->delete($academicYear);
        return response()->json([
            'data' =>$record,
            'message'=> 'Academic year delete successfully'
        ]);
    }

    public function destroyMulti(Request $request, DeleteAcademicYear $multiDelete)
    {
        // Validate request
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:academic_years,id'
        ]);

        $ids = $request->input('ids');

        $deletedRecords = $multiDelete->multiDelete($ids);

        return response()->json([
            'data' => $deletedRecords,
            'message' => 'Selected academic years deleted successfully'
        ]);
    }

    public function destroyAll(DeleteAcademicYear $deleteAction)
    {
        try {
            $deletedRecords = $deleteAction->deleteAll();

            return response()->json([
                'data' => $deletedRecords,
                'message' => 'All academic years deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error deleting academic years',
                'error' => $e->getMessage()
            ], 500);
        }
    }
        
}
