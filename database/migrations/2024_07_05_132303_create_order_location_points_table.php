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
        Schema::create('order_location_points', function (Blueprint $table)
        {
            $table->bigIncrements('id');
            $table->foreignId('order_location_id')->references('id')->on('order_locations')->onDelete('cascade');

            $table->string('type')->nullable();
            $table->json('coordinates')->nullable();
            $table->json('intermediatePoints')->nullable();
            $table->integer('loadingPointNumber')->default(0);
            $table->uuid('milestoneId')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_location_points');
    }
};
