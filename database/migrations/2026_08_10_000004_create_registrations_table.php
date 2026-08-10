<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('form_link_id')->nullable()->constrained('form_links')->nullOnDelete();
            $table->string('full_name');
            $table->date('date_of_birth')->nullable();
            $table->string('company')->nullable();
            $table->string('employee_id')->nullable();
            $table->string('designation')->nullable();
            $table->string('mobile_number')->nullable();
            $table->string('email')->nullable();
            $table->string('cric_contact_no')->nullable();
            $table->string('cric_id_name')->nullable();
            $table->string('playing_role')->nullable();
            $table->string('previous_team')->nullable();
            $table->date('availability_from')->nullable();
            $table->date('availability_to')->nullable();
            $table->boolean('availability_none')->default(false);
            $table->string('current_location')->nullable();
            $table->boolean('company_transport_required')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('registrations');
    }
};
