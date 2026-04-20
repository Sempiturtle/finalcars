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
        // Clear existing default promos and their associations
        \Illuminate\Support\Facades\DB::table('reward_user')->truncate();
        \App\Models\Reward::query()->delete();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No reverse logic needed for data clearing
    }
};
