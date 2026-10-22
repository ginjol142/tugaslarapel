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
    Schema::create('classrooms', function (Blueprint $table) {
        $table->id();
        $table->string('name', 50);
        $table->unsignedTinyInteger('grade_level');
        $table->timestamps();
    });
}

public function down(): void
{
    Schema::dropIfExists('classrooms');
}
};
