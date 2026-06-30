<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('quotes', function (Blueprint $table) {
            $table->dateTime('werkbon_laatst_bewerkt_op')->nullable()->after('inclusief_overeenkomst');
            $table->foreignId('werkbon_laatst_bewerkt_door')->nullable()->constrained('users')->nullOnDelete()->after('werkbon_laatst_bewerkt_op');
        });
    }

    public function down(): void
    {
        Schema::table('quotes', function (Blueprint $table) {
            $table->dropForeign(['werkbon_laatst_bewerkt_door']);
            $table->dropColumn(['werkbon_laatst_bewerkt_op', 'werkbon_laatst_bewerkt_door']);
        });
    }
};
