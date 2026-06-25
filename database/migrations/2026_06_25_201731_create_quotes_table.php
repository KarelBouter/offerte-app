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
        Schema::create('quotes', function (Blueprint $table) {
            $table->id();
            $table->string('quote_number')->unique();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('customer_id')->constrained('customers');
            $table->string('installation_address')->nullable();
            $table->enum('status', ['concept', 'verzonden', 'ondertekend', 'verlopen', 'geannuleerd'])->default('concept');
            $table->date('valid_until');
            $table->text('notes')->nullable();
            $table->decimal('total_onetime_excl_vat', 10, 2)->default(0);
            $table->decimal('total_yearly_excl_vat', 10, 2)->default(0);
            $table->timestamp('signed_at')->nullable();
            $table->string('signed_by_name')->nullable();
            $table->string('sign_token')->nullable();
            $table->string('pdf_path')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotes');
    }
};
