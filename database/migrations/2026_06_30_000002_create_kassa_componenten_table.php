<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kassa_componenten', function (Blueprint $table) {
            $table->id();
            $table->string('naam');
            $table->integer('poorten_per_kassa')->default(1);
            $table->boolean('poe_required')->default(false);
            $table->boolean('is_actief')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kassa_componenten');
    }
};
