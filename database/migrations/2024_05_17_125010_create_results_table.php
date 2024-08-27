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
        Schema::create('results', function (Blueprint $table)
        {

            $table->bigIncrements('id');
            $table->uuid('offerId');
            $table->float('offerPrice', 20)->default(0);
            $table->integer('roadDistanceKm')->default(0);
            $table->dateTime('date_parse')->default(now());
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('results');
    }
};
