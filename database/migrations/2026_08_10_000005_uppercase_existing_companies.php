<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Update existing registrations to store company names in uppercase
        DB::table('registrations')
            ->whereNotNull('company')
            ->update(['company' => DB::raw('UPPER(company)')]);
    }

    public function down()
    {
        // No easy down migration; leave as-is
    }
};
