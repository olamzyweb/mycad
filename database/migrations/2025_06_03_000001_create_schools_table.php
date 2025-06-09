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
        Schema::create('schools', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // Unique name for the school
            $table->string('address')->nullable(); // Optional address field
            $table->string('phone')->nullable(); // Optional phone field
            $table->string('email')->nullable(); // Optional email field
            $table->string('logo')->nullable(); // Optional logo field
            $table->string('website')->nullable(); // Optional website field
            $table->string('type')->default('primary'); // Default type is primary school
            $table->string('status')->default('active'); // Default status is active
            $table->string('timezone')->default('Africa/Lagos'); // Default timezone
            $table->string('currency')->default('NGN'); // Default currency is Nigerian Naira
            $table->string('locale')->default('en'); // Default locale is English
            $table->string('country')->default('Nigeria'); // Default country is Nigeria
            $table->string('state')->nullable(); // Optional state field
            $table->string('postal_code')->nullable(); // Optional postal code field
            $table->string('registration_number')->nullable(); // Optional registration number field
            $table->string('tax_identification_number')->nullable(); // Optional tax identification number field
            $table->string('bank_name')->nullable(); // Optional bank name field
            $table->string('bank_account_number')->nullable(); // Optional bank account number field
            $table->string('bank_account_name')->nullable(); // Optional bank account name field
            $table->string('bank_branch')->nullable(); // Optional bank branch field
            $table->string('bank_address')->nullable(); // Optional bank address field
            $table->string('bank_website')->nullable(); // Optional bank website field
            $table->string('bank_country')->nullable(); // Optional bank country field
 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schools');
    }
};
