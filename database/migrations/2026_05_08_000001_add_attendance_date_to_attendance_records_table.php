<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('attendance_records', function (Blueprint $table) {
            $table->date('attendance_date')->nullable()->after('recorded_by');
        });

        DB::table('attendance_records')->update([
            'attendance_date' => DB::raw('DATE(created_at)'),
        ]);

        Schema::table('attendance_records', function (Blueprint $table) {
            $table->dropUnique('attendance_records_class_session_id_student_id_unique');
            $table->unique(
                ['class_session_id', 'student_id', 'attendance_date'],
                'attendance_records_session_student_date_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::table('attendance_records', function (Blueprint $table) {
            $table->dropUnique('attendance_records_session_student_date_unique');
            $table->unique(
                ['class_session_id', 'student_id'],
                'attendance_records_class_session_id_student_id_unique'
            );
            $table->dropColumn('attendance_date');
        });
    }
};

