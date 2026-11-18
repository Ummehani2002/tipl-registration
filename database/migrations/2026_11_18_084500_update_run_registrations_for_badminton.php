<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('run_registrations', function (Blueprint $table) {
            if (!Schema::hasColumn('run_registrations', 'trial_availability')) {
                $table->string('trial_availability')->nullable()->after('contact_number');
            }
            if (!Schema::hasColumn('run_registrations', 'trial_transportation')) {
                $table->string('trial_transportation')->nullable()->after('trial_availability');
            }
            if (!Schema::hasColumn('run_registrations', 'trial_pickup_location')) {
                $table->string('trial_pickup_location')->nullable()->after('trial_transportation');
            }
            if (!Schema::hasColumn('run_registrations', 'tournament_availability')) {
                $table->string('tournament_availability')->nullable()->after('trial_pickup_location');
            }
            if (!Schema::hasColumn('run_registrations', 'tournament_transportation')) {
                $table->string('tournament_transportation')->nullable()->after('tournament_availability');
            }
            if (!Schema::hasColumn('run_registrations', 'tournament_pickup_location')) {
                $table->string('tournament_pickup_location')->nullable()->after('tournament_transportation');
            }
        });
    }

    public function down(): void
    {
        Schema::table('run_registrations', function (Blueprint $table) {
            $columns = [
                'trial_availability',
                'trial_transportation',
                'trial_pickup_location',
                'tournament_availability',
                'tournament_transportation',
                'tournament_pickup_location',
            ];

            $existing = array_filter($columns, fn ($column) => Schema::hasColumn('run_registrations', $column));

            if (!empty($existing)) {
                $table->dropColumn($existing);
            }
        });
    }
};
