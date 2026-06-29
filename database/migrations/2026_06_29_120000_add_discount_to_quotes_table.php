<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('quotes', function (Blueprint $table) {
            $table->string('discount_type')->nullable()->after('notes');
            $table->decimal('discount_value', 10, 2)->nullable()->after('discount_type');
            $table->decimal('onetime_subtotal_excl_vat', 10, 2)->nullable()->after('discount_value');
        });
    }

    public function down(): void
    {
        Schema::table('quotes', function (Blueprint $table) {
            $table->dropColumn(['discount_type', 'discount_value', 'onetime_subtotal_excl_vat']);
        });
    }
};
