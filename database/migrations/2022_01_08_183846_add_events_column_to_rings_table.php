<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('rings', function (Blueprint $table) {
            $table->json('events')->nullable()->after('volume');
        });
    }

    public function down()
    {
        Schema::table('rings', function (Blueprint $table) {
            $table->dropColumn('events');
        });
    }
};
