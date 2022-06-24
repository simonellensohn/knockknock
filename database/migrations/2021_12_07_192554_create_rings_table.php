<?php

use App\Models\Bell;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('rings', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Bell::class);
            $table->unsignedDecimal('volume');
            $table->timestamps();
        });
    }
};
