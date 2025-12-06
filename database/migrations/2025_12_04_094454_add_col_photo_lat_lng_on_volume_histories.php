<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('volume_histories', function (Blueprint $table) {
            //
            $table->string('photo')->nullable();
            $table->float('latitude')->nullable();
            $table->float('longitude')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('volume_histories', function (Blueprint $table) {
            //
            $table->dropColumn('photo');
            $table->dropColumn('latitude');
            $table->dropColumn('longitude');
        });
    }
};
