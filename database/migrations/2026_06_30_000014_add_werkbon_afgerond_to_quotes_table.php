<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('quotes', function (Blueprint $table) {
            $table->boolean('werkbon_afgerond')->default(false)->after('werkbon_laatst_bewerkt_door');
            $table->date('werkbon_afgerond_op')->nullable()->after('werkbon_afgerond');
            $table->string('werkbon_afgerond_door')->nullable()->after('werkbon_afgerond_op');
        });
    }

    public function down(): void
    {
        Schema::table('quotes', function (Blueprint $table) {
            $table->dropColumn(['werkbon_afgerond', 'werkbon_afgerond_op', 'werkbon_afgerond_door']);
        });
    }
};
