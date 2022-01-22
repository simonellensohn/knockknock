<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddActiveColumnToBellsTable extends Migration
{
    public function up()
    {
        Schema::table('bells', function (Blueprint $table) {
            $table->boolean('active')->default(true)->after('threshold');

        });
    }

    public function down()
    {
        Schema::table('bells', function (Blueprint $table) {
            $table->dropColumn('active');
        });
    }
}
