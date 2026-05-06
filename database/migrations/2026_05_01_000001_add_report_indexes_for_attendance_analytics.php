<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('attendance_records', function (Blueprint $table) {
            $table->index('created_at', 'attendance_records_created_at_idx');
            $table->index(['class_session_id', 'created_at'], 'attendance_records_session_created_idx');
            $table->index(['student_id', 'status'], 'attendance_records_student_status_idx');
        });

        Schema::table('class_sessions', function (Blueprint $table) {
            $table->index(['class_id', 'term_id'], 'class_sessions_class_term_idx');
            $table->index(['term_id', 'class_id', 'day_of_week'], 'class_sessions_term_class_day_idx');
        });
    }

    public function down(): void
    {
        Schema::table('attendance_records', function (Blueprint $table) {
            $table->dropIndex('attendance_records_created_at_idx');
            $table->dropIndex('attendance_records_session_created_idx');
            $table->dropIndex('attendance_records_student_status_idx');
        });

        Schema::table('class_sessions', function (Blueprint $table) {
            $table->dropIndex('class_sessions_class_term_idx');
            $table->dropIndex('class_sessions_term_class_day_idx');
        });
    }
};

