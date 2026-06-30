<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->unsignedBigInteger('onderhoudsgroep_id')->nullable()->after('is_ups');
            $table->foreign('onderhoudsgroep_id')->references('id')->on('onderhoudsgroepen')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['onderhoudsgroep_id']);
            $table->dropColumn('onderhoudsgroep_id');
        });
    }
};
