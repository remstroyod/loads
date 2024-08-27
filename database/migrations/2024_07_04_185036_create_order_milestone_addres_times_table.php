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
        Schema::create('order_milestone_addres_times', function (Blueprint $table)
        {
            $table->bigIncrements('id');
            $table->foreignId('order_milestone_addres_id')->references('id')->on('order_milestone_addres')->onDelete('cascade');

            $table->json('onloading')->nullable();
            $table->json('offloading')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_milestone_addres_times');
    }
};
