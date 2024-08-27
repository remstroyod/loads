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
        Schema::create('order_goods', function (Blueprint $table)
        {
            $table->bigIncrements('id');
            $table->foreignId('order_id')->constrained()->onDelete('cascade');

            $table->mediumText('description')->nullable();
            $table->json('weight')->nullable();
            $table->json('quantity')->nullable();
            $table->string('goodsTypeCode')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_goods');
    }
};
