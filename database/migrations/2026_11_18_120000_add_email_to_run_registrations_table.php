<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('run_registrations')) {
            return;
        }

        Schema::table('run_registrations', function (Blueprint $table) {
            if (!Schema::hasColumn('run_registrations', 'email')) {
                $table->string('email')->nullable()->unique()->after('employee_id');
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('run_registrations')) {
            return;
        }

        Schema::table('run_registrations', function (Blueprint $table) {
            if (Schema::hasColumn('run_registrations', 'email')) {
                $table->dropUnique(['email']);
                $table->dropColumn('email');
            }
        });
    }
};
