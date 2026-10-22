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
    Schema::create('students', function (Blueprint $table) {
        $table->id();
        $table->foreignId('classroom_id')
            ->constrained('classrooms')
            ->restrictOnDelete();
        $table->string('nis', 20)->unique();
        $table->string('name', 100);
        $table->enum('gender', ['L', 'P']);
        $table->date('birth_date')->nullable();
        $table->enum('status', [
            'aktif', 'lulus', 'pindah'
        ])->default('aktif');
        $table->timestamps();
    });
}

public function down(): void
{
    Schema::dropIfExists('students');
}
};
