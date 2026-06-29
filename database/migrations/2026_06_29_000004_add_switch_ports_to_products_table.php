<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->integer('switch_ports_total')->nullable()->after('price_per_meter');
            $table->integer('switch_ports_poe')->nullable()->after('switch_ports_total');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['switch_ports_total', 'switch_ports_poe']);
        });
    }
};
