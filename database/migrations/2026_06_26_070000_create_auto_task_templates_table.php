<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('auto_task_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('trigger_status', ['verzonden', 'ondertekend', 'verlopen', 'geannuleerd']);
            $table->string('title_template');
            $table->text('description_template')->nullable();
            $table->foreignId('assign_to_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->unsignedSmallInteger('due_days')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('auto_task_templates');
    }
};
