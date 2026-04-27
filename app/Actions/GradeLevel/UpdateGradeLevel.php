<?php

namespace App\Actions\GradeLevel;

use App\Models\GradeLevel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;

class UpdateGradeLevel
{
    public function execute(int $id, array $data): GradeLevel
    {
        $gradeLevel = GradeLevel::find($id);

        if (!$gradeLevel) {
            throw new \Exception('Grade level not found.');
        }

        $validator = Validator::make($data, [
            'code' => [
                'required',
                'string',
                'max:255',
                Rule::unique('grade_levels', 'code')->ignore($gradeLevel->id),
            ],
            'name' => ['required', 'string', 'max:255'],
            'order_no' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $gradeLevel->update([
            'code' => $data['code'],
            'name' => $data['name'],
            'order_no' => $data['order_no'] ?? $gradeLevel->order_no,
            'is_active' => $data['is_active'] ?? $gradeLevel->is_active,
        ]);

        return $gradeLevel->fresh();
    }
}
