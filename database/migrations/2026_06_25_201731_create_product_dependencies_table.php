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
        Schema::create('product_dependencies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('depends_on_product_id')->constrained('products')->cascadeOnDelete();
            $table->enum('rule_type', ['REQUIRED', 'REQUIRED_CALCULATED', 'THRESHOLD_SWITCH', 'RECOMMENDED', 'EXCLUDES']);
            $table->integer('trigger_quantity_min')->nullable();
            $table->integer('trigger_quantity_max')->nullable();
            $table->integer('resulting_quantity')->nullable();
            $table->string('resulting_quantity_formula')->nullable();
            $table->foreignId('replaces_product_id')->nullable()->constrained('products')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_dependencies');
    }
};
