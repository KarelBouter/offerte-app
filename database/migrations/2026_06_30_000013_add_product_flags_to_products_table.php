<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->boolean('verberg_in_configurator')->default(false)->after('vereist_servicecontract');
            $table->boolean('is_hardware_basisoptie')->default(false)->after('verberg_in_configurator');
            $table->boolean('is_ups')->default(false)->after('is_hardware_basisoptie');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['verberg_in_configurator', 'is_hardware_basisoptie', 'is_ups']);
        });
    }
};
