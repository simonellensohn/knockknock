<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEventsColumnToRingsTable extends Migration
{
    public function up()
    {
        Schema::table('rings', function (Blueprint $table) {
            $table->json('events')->after('volume');
        });
    }

    public function down()
    {
        Schema::table('rings', function (Blueprint $table) {
            $table->dropColumn('events');
        });
    }
}