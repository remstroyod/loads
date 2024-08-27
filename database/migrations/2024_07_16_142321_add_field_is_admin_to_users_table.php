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
        Schema::table('users', function (Blueprint $table)
        {
            $table->tinyInteger('role')->default(\App\Enums\UserRoleEnum::User)->after('subcontractors');
        });

        \App\Models\User::where('id', 1)->update(['role' => \App\Enums\UserRoleEnum::Admin]);
        \App\Models\User::whereNotIn('id', [1])->update(['role' => \App\Enums\UserRoleEnum::User]);

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //Schema::dropIfExists('menus');
    }
};
