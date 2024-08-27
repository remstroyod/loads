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
        Schema::create('order_documents', function (Blueprint $table)
        {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('order_id');
            $table->integer('type')->default(1);
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');

            $table->string('original_name');
            $table->string('extension');
            $table->string('name');
            $table->string('path')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_documents');
    }
};
