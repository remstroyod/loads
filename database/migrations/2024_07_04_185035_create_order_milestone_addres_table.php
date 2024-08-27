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
        Schema::create('order_milestone_addres', function (Blueprint $table)
        {
            $table->bigIncrements('id');
            $table->foreignId('order_milestone_id')->references('id')->on('order_milestones')->onDelete('cascade');

            $table->string('countryCode')->nullable();
            $table->string('zipCode')->nullable();
            $table->string('city')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_milestone_addres');
    }
};
