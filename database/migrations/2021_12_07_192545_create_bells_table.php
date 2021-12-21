<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBellsTable extends Migration
{
    public function up()
    {
        Schema::create('bells', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class);
            $table->string('name');
            $table->unsignedDecimal('threshold')->unique();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('bells');
    }
}
