<?php

namespace App\Http\Controllers;

use App\Actions\Term\CreateTerm;
use App\Actions\Term\DeleteTerm;
use App\Actions\Term\UpdateTerm;
use App\Models\Term;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class TermController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $allData = Term::all();
        return response()->json([
            'list' => $allData
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request , CreateTerm $createTerm,)
    {
        try{
        $term = $createTerm->create($request);
        return response()->json([
            'data'=>$term,
            'message'=>'Term created successfully'
        ]);
        }catch(ValidationException $e){
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error creating class session',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $term = Term::findOrFail($id);
        return response()->json([
            'data'=>$term,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Term $term )
    {
        try{

            $action = new UpdateTerm($term);
            $updateTerm = $action->update($request);
            return response()->json([
                'data'=>$updateTerm,
                'message'=>'Update term successfully'
            ]);

        }catch(ValidationException $e){
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error updating class session',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DeleteTerm $singleDelete,Term $idTerm)
    {
         $term = $singleDelete->delete($idTerm);
         return response()->json([
            'data' => $term,
            'message'=>'Term Deleted Successfully'
         ]);
    }

    public function destroyMulti(Request $request,DeleteTerm $multiDelete)
    {       
        $request->validate([
            "ids" => "requred|array",
            "ids.*" => "integer|exists:terms,id",
        ]);

        $ids = $request->input('ids');

        $data = $multiDelete->multiDelete($ids) ;

        return response()->json([
            'data'=>$data,
            'deleted_count' => $data->count(),
            'message' => 'Terms delete successfully'
        ]);
    }

    public function destroyAll (DeleteTerm $deleteAll) {

        try {
            $deletedRecords = $deleteAll->deleteAll();

            return response()->json([
                'data' => $deletedRecords,
                'message' => 'All term deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error deleting term',
                'error' => $e->getMessage()
            ], 500);
        }        
    }   
}
