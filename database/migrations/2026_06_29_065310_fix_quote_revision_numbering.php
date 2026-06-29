<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // revision now means "the version of the last save".
        // Old logic: started at 1, saved snapshot, then incremented → first save left revision=2.
        // New logic: starts at 0, increments first, saves snapshot → first save leaves revision=1.
        // Fix existing rows: set revision = actual number of saved versions.
        DB::statement('UPDATE quotes SET revision = (SELECT COUNT(*) FROM quote_versions WHERE quote_id = quotes.id)');
    }

    public function down(): void
    {
        // Not reversible without original values.
    }
};
