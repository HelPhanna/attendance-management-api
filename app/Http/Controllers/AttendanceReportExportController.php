<?php

namespace App\Http\Controllers;

use App\Actions\ReportExport\LogAttendanceExport;
use App\Actions\ReportExport\ExportAttendanceToEXCEL;
use App\Actions\ReportExport\ExportAttendanceToPDF;
use App\Actions\ReportExport\GenerateAttendanceReportData;
use App\Models\ReportExports;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class AttendanceReportExportController extends Controller
{
    public function history(Request $request)
    {
        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'date'   => ['nullable', 'date'],
        ]);

        $query = ReportExports::query()
            ->with(['classes:id,name'])
            ->where('user_id', Auth::id())
            ->orderByDesc('exported_at')
            ->orderByDesc('id');

        if (!empty($validated['date'])) {
            $query->whereDate('exported_at', $validated['date']);
        }

        if (!empty($validated['search'])) {
            $search = $validated['search'];
            $query->where(function ($innerQuery) use ($search) {
                $innerQuery
                    ->where('file_type', 'like', '%' . $search . '%')
                    ->orWhere('status', 'like', '%' . $search . '%')
                    ->orWhereHas('classes', function ($classQuery) use ($search) {
                        $classQuery->where('name', 'like', '%' . $search . '%');
                    });
            });
        }

        $rows = $query->limit(100)->get()->map(function (ReportExports $item) {
            return [
                'id'         => $item->id,
                'date'       => optional($item->exported_at ?? $item->created_at)->toDateString(),
                'class_name' => $item->classes?->name ?? '-',
                'file_type'  => strtoupper($item->file_type),
                'status'     => $item->status,
                'size_kb'    => (float) $item->file_size_kb,
                'file_path'  => $item->file_path,
            ];
        });

        return response()->json([
            'success' => true,
            'data'    => $rows,
        ]);
    }

    public function download(int $id)
    {
        $record = ReportExports::query()
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if ($record->status !== 'completed' || empty($record->file_path)) {
            return response()->json([
                'message' => 'This export is not available for download.',
            ], 422);
        }

        /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
        $disk = Storage::disk('public');

        if (!$disk->exists($record->file_path)) {
            return response()->json([
                'message' => 'Export file not found on server.',
            ], 404);
        }

        return $disk->download(
            $record->file_path,
            basename($record->file_path),
        );
    }

    public function export(Request $request, string $format)
    {
        if (!in_array($format, ['pdf', 'xlsx'])) {
            return response()->json([
                'message' => 'Only pdf and xlsx are allowed.',
            ], 400);
        }

        $user = Auth::user();

        try {
            $reportData = app(GenerateAttendanceReportData::class)->execute($request->all());
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed.',
                'errors'  => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 422);
        }

        try {
            if ($format === 'pdf') {
                $result = app(ExportAttendanceToPDF::class)->execute($reportData);
            } else {
                $result = app(ExportAttendanceToEXCEL::class)->execute($reportData);
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to generate file: ' . $e->getMessage(),
            ], 500);
        }

        if (Auth::check()) {
            app(LogAttendanceExport::class)->execute(
                $user,
                $format,
                $result['path'],
                $result['size_kb'],
                $request->all()
            );
        }

        /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
        $disk = Storage::disk('public');

        return $disk->download(
            $result['path'],
            $result['filename']
        );
    }
}
