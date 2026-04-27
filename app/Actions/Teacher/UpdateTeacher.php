<?php

namespace App\Actions\Teacher;

use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;

class UpdateTeacher
{
    public function execute(Request $request, string $id)
    {
        $teacher = Teacher::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'status' => ['nullable', Rule::in(['active', 'inactive'])],
            'teacher_code' => [
                'nullable',
                'string',
                Rule::unique('teachers', 'teacher_code')->ignore($teacher->id)
            ],
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        if ($request->filled('status')) {
            $teacher->status = $request->status;
        }

        if ($request->filled('teacher_code')) {
            $teacher->teacher_code = $request->teacher_code;
        }

        $teacher->save();

        return $teacher;
    }
}
