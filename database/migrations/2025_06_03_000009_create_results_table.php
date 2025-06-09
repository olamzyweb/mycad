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
      Schema::create('results', function (Blueprint $table) {
    $table->id();
    $table->foreignId('school_id')->constrained()->cascadeOnDelete();
    $table->foreignId('student_id')->constrained()->cascadeOnDelete();
    $table->foreignId('classroom_id')->constrained()->cascadeOnDelete();
    $table->string('term'); // e.g. 1st Term, 2nd Term
    $table->integer('overall_grade'); // e.g. 85
    $table->text('remarks')->nullable(); // e.g. "Excellent performance"
    $table->text('teacher_comment')->nullable(); // e.g. "Keep up the good work!"
    $table->foreignId('subject_id')->constrained()->cascadeOnDelete();
    $table->string('session'); // e.g. 2023/2024
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('results');
    }
};
