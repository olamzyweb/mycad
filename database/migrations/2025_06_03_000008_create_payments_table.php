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
       Schema::create('payments', function (Blueprint $table) {
    $table->id();
    $table->foreignId('school_id')->constrained()->cascadeOnDelete();
    $table->foreignId('student_id')->constrained()->cascadeOnDelete();
    $table->foreignId('fee_id')->constrained()->cascadeOnDelete();
    $table->integer('amount_paid');
    $table->string('payment_method')->nullable();
    $table->string('transaction_id')->nullable(); // For tracking payment transactions
    $table->string('status')->default('pending'); // e.g. pending, completed, failed
    $table->text('notes')->nullable(); // Additional notes about the payment
    $table->timestamp('paid_at')->nullable();
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
