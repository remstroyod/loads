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
        Schema::create('result_offloading_points', function (Blueprint $table)
        {

            $table->bigIncrements('id');
            $table->unsignedBigInteger('result_offloading_id')->nullable();
            $table->foreign('result_offloading_id')->references('id')->on('result_offloadings')->onDelete('cascade');

            $table->unsignedBigInteger('countryCode')->nullable();
            $table->foreign('countryCode')->references('id')->on('countries')->onDelete('set null');
            //$table->string('countryCode')->nullable();
            $table->string('city')->nullable();
            $table->string('zipCode')->nullable();
            $table->dateTime('rtaStart')->nullable();
            $table->dateTime('rtaEnd')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('result_offloading_points');
    }
};
