<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('run_registrations')) {
            return;
        }

        Schema::table('run_registrations', function (Blueprint $table) {
            if (!Schema::hasColumn('run_registrations', 'has_planned_vacation')) {
                $table->string('has_planned_vacation')->nullable()->after('contact_number');
            }
            if (!Schema::hasColumn('run_registrations', 'planned_vacation_from')) {
                $table->date('planned_vacation_from')->nullable()->after('has_planned_vacation');
            }
            if (!Schema::hasColumn('run_registrations', 'planned_vacation_to')) {
                $table->date('planned_vacation_to')->nullable()->after('planned_vacation_from');
            }
        });

        // Migrate legacy single-date values into the new Yes + from/to shape.
        if (Schema::hasColumn('run_registrations', 'planned_vacation')) {
            DB::table('run_registrations')
                ->whereNotNull('planned_vacation')
                ->whereNull('has_planned_vacation')
                ->orderBy('id')
                ->chunkById(100, function ($rows) {
                    foreach ($rows as $row) {
                        DB::table('run_registrations')->where('id', $row->id)->update([
                            'has_planned_vacation' => 'Yes',
                            'planned_vacation_from' => $row->planned_vacation,
                            'planned_vacation_to' => $row->planned_vacation,
                        ]);
                    }
                });
        }
    }

    public function down(): void
    {
        if (!Schema::hasTable('run_registrations')) {
            return;
        }

        Schema::table('run_registrations', function (Blueprint $table) {
            foreach (['planned_vacation_to', 'planned_vacation_from', 'has_planned_vacation'] as $column) {
                if (Schema::hasColumn('run_registrations', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
