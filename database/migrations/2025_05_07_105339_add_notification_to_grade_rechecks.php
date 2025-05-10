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
        Schema::table('grade_rechecks', function (Blueprint $table) {
            $table->boolean('student_notified')->default(false);
            $table->text('lecturer_message')->nullable(); // e.g. "Grade updated to B+" or "Request rejected"
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('grade_rechecks', function (Blueprint $table) {
            //
        });
    }
};
