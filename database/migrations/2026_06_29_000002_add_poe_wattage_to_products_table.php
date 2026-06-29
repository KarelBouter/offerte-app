<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->integer('poe_wattage_output')->nullable()->after('sort_order');
            $table->integer('poe_wattage_input')->nullable()->after('poe_wattage_output');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['poe_wattage_output', 'poe_wattage_input']);
        });
    }
};
