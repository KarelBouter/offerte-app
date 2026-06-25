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
        // pdf_path already present in original create_quotes_table migration
    }

    public function down(): void
    {
        // nothing to reverse
    }
};
