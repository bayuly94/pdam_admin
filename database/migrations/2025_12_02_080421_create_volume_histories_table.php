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
        Schema::create('volume_histories', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer('before');
            $table->integer('volume');
            $table->integer("after");

            $table->datetime('date');

            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('volume_histories');
    }
};
