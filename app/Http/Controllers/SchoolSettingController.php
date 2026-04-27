<?php

namespace App\Http\Controllers;

use App\Models\SchoolSetting;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class SchoolSettingController extends Controller
{
    public function show()
    {
        $setting = SchoolSetting::first();

        if (!$setting) {
            $setting = SchoolSetting::create([
                'school_name' => 'SETEC Institute',
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => $setting,
        ]);
    }

    public function update(Request $request)
    {
        try {
            $validated = $request->validate([
                'school_name' => ['required', 'string', 'max:255'],
                'address' => ['nullable', 'string', 'max:255'],
                'city' => ['nullable', 'string', 'max:120'],
                'state_province' => ['nullable', 'string', 'max:120'],
                'postal_code' => ['nullable', 'string', 'max:40'],
                'country' => ['nullable', 'string', 'max:120'],
                'current_academic_year' => ['nullable', 'string', 'max:120'],
                'term_semester' => ['nullable', 'string', 'max:120'],
            ]);

            $setting = SchoolSetting::first();
            if (!$setting) {
                $setting = new SchoolSetting();
            }

            $setting->fill($validated);
            $setting->save();

            return response()->json([
                'success' => true,
                'message' => 'School settings updated successfully.',
                'data' => $setting,
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors(),
            ], 422);
        }
    }
}
