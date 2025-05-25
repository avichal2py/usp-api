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
       Schema::create('student_requests', function (Blueprint $table) {
            $table->id();
            $table->string('student_id');
            $table->enum('request_type', ['Graduation', 'Compassionate Pass', 'Aegrotat Pass', 'Re-sit']);
            $table->string('document_path')->nullable(); // Uploaded form
            $table->enum('status', ['Pending', 'Approved', 'Rejected'])->default('Pending');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_notifications');
    }
};
