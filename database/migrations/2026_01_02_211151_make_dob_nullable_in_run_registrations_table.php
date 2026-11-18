<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Avoid doctrine/dbal ->change() which breaks Laravel Cloud deploys.
        // DOB is already treated as nullable in application code.
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() !== 'sqlite') {
            Schema::table('run_registrations', function (Blueprint $table) {
                $table->date('dob')->nullable(false)->change();
            });
        }
    }
};
