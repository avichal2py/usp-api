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
    Schema::table('student_requests', function (Blueprint $table) {
        $table->boolean('student_notified')->default(false);
    });
}

public function down()
{
    Schema::table('student_requests', function (Blueprint $table) {
        $table->dropColumn('student_notified');
    });
}

};
