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
        Schema::create('driver_files', function (Blueprint $table)
        {

            $table->bigIncrements('id');
            $table->unsignedBiginteger('driver_id');
            $table->tinyInteger('type')->default(\App\Enums\UserDriverDocumentTypeEnum::Passport);
            $table->string('number')->nullable();
            $table->date('valid_from')->nullable();
            $table->date('valid_until')->nullable();
            $table->string('extension')->nullable();
            $table->string('name')->nullable();
            $table->string('path')->nullable();
            $table->string('original_name')->nullable();

            $table->timestamps();

            $table->foreign('driver_id')->references('id')
                ->on('drivers')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('driver_files');
    }
};
