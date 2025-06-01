<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::create('login_logs', function (Blueprint $table) {
        $table->id();
        $table->string('type'); // student or employee
        $table->string('identifier'); // student_id or emp_id
        $table->string('status'); // SUCCESS, FAIL, LOGOUT
        $table->ipAddress('ip_address');
        $table->timestamp('created_at')->useCurrent();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('login_logs');
    }
};
