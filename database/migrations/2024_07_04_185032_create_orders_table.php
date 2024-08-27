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
        Schema::create('orders', function (Blueprint $table)
        {
            $table->bigIncrements('id');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            $table->unsignedBigInteger('driver_id')->nullable();
            $table->foreign('driver_id')->references('id')->on('drivers')->onDelete('set null');

            $table->uuid();
            $table->json('vehicleProperties')->nullable();
            $table->json('totalWeight')->nullable();
            $table->float('offerPrice', 20)->default(0);

            $table->string('assign_number_car')->nullable();
            $table->string('assign_number_track')->nullable();
            $table->dateTime('date_loading')->nullable();
            $table->dateTime('date_unloading')->nullable();

            $table->json('specialEquipment')->nullable();
            $table->json('specialRequirements')->nullable();
            $table->json('expiredDocuments')->nullable();

            $table->tinyInteger('status')->default(0);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
