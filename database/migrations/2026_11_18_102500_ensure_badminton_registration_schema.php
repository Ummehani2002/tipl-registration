<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('run_registrations')) {
            Schema::create('run_registrations', function (Blueprint $table) {
                $table->id();
                $table->string('registration_id')->nullable()->unique();
                $table->string('employee_id')->unique();
                $table->string('name');
                $table->string('designation')->nullable();
                $table->string('company');
                $table->string('entity')->nullable();
                $table->date('dob')->nullable();
                $table->string('run_category')->nullable();
                $table->string('contact_number');
                $table->date('planned_vacation')->nullable();
                $table->string('tshirt_size')->nullable();
                $table->string('trial_availability')->nullable();
                $table->string('trial_transportation')->nullable();
                $table->string('trial_pickup_location')->nullable();
                $table->string('tournament_availability')->nullable();
                $table->string('tournament_transportation')->nullable();
                $table->string('tournament_pickup_location')->nullable();
                $table->string('bib_number')->nullable();
                $table->string('attendance_status')->nullable()->default('pending');
                $table->timestamps();
            });

            return;
        }

        $add = [
            'registration_id' => fn (Blueprint $t) => $t->string('registration_id')->nullable(),
            'entity' => fn (Blueprint $t) => $t->string('entity')->nullable(),
            'planned_vacation' => fn (Blueprint $t) => $t->date('planned_vacation')->nullable(),
            'trial_availability' => fn (Blueprint $t) => $t->string('trial_availability')->nullable(),
            'trial_transportation' => fn (Blueprint $t) => $t->string('trial_transportation')->nullable(),
            'trial_pickup_location' => fn (Blueprint $t) => $t->string('trial_pickup_location')->nullable(),
            'tournament_availability' => fn (Blueprint $t) => $t->string('tournament_availability')->nullable(),
            'tournament_transportation' => fn (Blueprint $t) => $t->string('tournament_transportation')->nullable(),
            'tournament_pickup_location' => fn (Blueprint $t) => $t->string('tournament_pickup_location')->nullable(),
            'bib_number' => fn (Blueprint $t) => $t->string('bib_number')->nullable(),
            'attendance_status' => fn (Blueprint $t) => $t->string('attendance_status')->nullable()->default('pending'),
        ];

        foreach ($add as $column => $definition) {
            if (!Schema::hasColumn('run_registrations', $column)) {
                Schema::table('run_registrations', function (Blueprint $table) use ($definition) {
                    $definition($table);
                });
            }
        }
    }

    public function down(): void
    {
        // Keep data; no destructive rollback.
    }
};
