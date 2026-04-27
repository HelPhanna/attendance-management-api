<?php

namespace App\Actions\ClassSession;

use App\Models\ClassSession;
use Illuminate\Validation\ValidationException;

class DeleteClassSession
{
    /**
     * Create a new class instance.
     */
    public function delete(ClassSession $classSession):bool
    {
        return $classSession->delete();
    }

    public function multiDelete (array $ids){
        if(empty($ids)){
            throw ValidationException::withMessages([
                'ids' => ['no session id provide']
            ]);
        }
        
        $session = ClassSession::whereIn('id',$ids)->get();

        if(empty($session)){

            throw ValidationException::withMessages([
                'ids' => ['No matching records found for the given IDs']
            ]);
        }
        ClassSession::whereIn('id',$ids)->delete();
        return $session;
    }

    public function deleteAll() {
        $session = ClassSession::all();

        if($session->isEmpty()){
            throw new \Exception("All record not found");
        };       
        ClassSession::query()->delete();
        return $session;
    }
}
