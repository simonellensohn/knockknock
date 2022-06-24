<?php

use App\Models\Bell;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('bells', function (Blueprint $table) {
            $table->after('name', function (Blueprint $table) {
                $table->float('min_volume')->nullable();
                $table->float('max_volume')->nullable();
            });
        });

        Bell::all()->each(function (Bell $bell) {
            $bell->update([
                'min_volume' => $bell->threshold,
                'max_volume' => $bell->threshold,
            ]);
        });

        Schema::table('bells', function (Blueprint $table) {
            $table->after('name', function (Blueprint $table) {
                $table->float('min_volume')->nullable(false)->change();
                $table->float('max_volume')->nullable(false)->change();
            });

            $table->dropColumn('threshold');
        });
    }
};
