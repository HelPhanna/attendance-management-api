<?php

namespace App\Http\Controllers;

use App\Models\AttendanceRule;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AttendanceRuleController extends Controller
{
    public function show()
    {
        $rule = AttendanceRule::first();

        if (!$rule) {
            $rule = AttendanceRule::create([
                'late_threshold_minutes' => 15,
                'absent_threshold_minutes' => 45,
                'exclude_weekends' => true,
                'auto_exclude_public_holidays' => true,
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => $rule,
        ]);
    }

    public function update(Request $request)
    {
        try {
            $validated = $request->validate([
                'late_threshold_minutes' => ['required', 'integer', 'min:1', 'max:300'],
                'absent_threshold_minutes' => ['required', 'integer', 'min:1', 'max:600'],
                'exclude_weekends' => ['required', 'boolean'],
                'auto_exclude_public_holidays' => ['required', 'boolean'],
            ]);

            $rule = AttendanceRule::first();
            if (!$rule) {
                $rule = new AttendanceRule();
            }

            $rule->fill($validated);
            $rule->save();

            return response()->json([
                'success' => true,
                'message' => 'Attendance rules updated successfully.',
                'data' => $rule,
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors(),
            ], 422);
        }
    }
}
