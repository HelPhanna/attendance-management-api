<?php

namespace App\Actions\GradeLevel;

use App\Models\GradeLevel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class CreateGradeLevel
{
    public function execute(array $data): GradeLevel
    {
        $validator = Validator::make($data, [
            'code' => ['required', 'string', 'max:255', 'unique:grade_levels,code'],
            'name' => ['required', 'string', 'max:255'],
            'order_no' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return GradeLevel::create([
            'code' => $data['code'],
            'name' => $data['name'],
            'order_no' => $data['order_no'] ?? 0,
            'is_active' => $data['is_active'] ?? true,
        ]);
    }
}
