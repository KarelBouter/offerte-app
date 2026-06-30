<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('onderhoudsgroepen', function (Blueprint $table) {
            $table->id();
            $table->string('naam');
            $table->unsignedBigInteger('basisproduct_id')->nullable();
            $table->unsignedBigInteger('per_stuk_product_id')->nullable();
            $table->boolean('is_actief')->default(true);
            $table->timestamps();

            $table->foreign('basisproduct_id')->references('id')->on('products')->nullOnDelete();
            $table->foreign('per_stuk_product_id')->references('id')->on('products')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('onderhoudsgroepen');
    }
};
