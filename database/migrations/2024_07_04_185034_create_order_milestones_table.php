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
        Schema::create('order_milestones', function (Blueprint $table)
        {
            $table->bigIncrements('id');
            $table->foreignId('order_id')->references('id')->on('orders')->onDelete('cascade');

            $table->uuid('milestoneId')->nullable();
            $table->string('type')->nullable();
            $table->json('rta')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_milestones');
    }
};
