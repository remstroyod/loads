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
        Schema::create('order_locations', function (Blueprint $table)
        {
            $table->bigIncrements('id');
            $table->foreignId('order_id')->constrained()->onDelete('cascade');

            $table->integer('totalRoadKm')->default(0);
            $table->integer('totalFerryKm')->default(0);
            $table->integer('totalTrainKm')->default(0);
            $table->integer('totalSumKm')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_locations');
    }
};
