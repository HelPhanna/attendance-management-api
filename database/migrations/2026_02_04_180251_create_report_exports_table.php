<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('report_exports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->restrictOnDelete();
            $table->foreignId('class_id')->constrained('classes')->restrictOnDelete();

            $table->string('report_type');
            $table->string('file_type');

            $table->enum('status', ['completed', 'failed'])->default('completed');
            $table->string('file_path')->nullable();
            $table->unsignedInteger('file_size_kb')->default(0);

            $table->json('filters')->nullable();

            $table->dateTime('exported_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('report_exports');
    }
};
