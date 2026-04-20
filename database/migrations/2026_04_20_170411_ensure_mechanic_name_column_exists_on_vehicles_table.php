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
        Schema::table('vehicles', function (Blueprint $table) {
            if (!Schema::hasColumn('vehicles', 'mechanic_name')) {
                $table->string('mechanic_name')->nullable()->after('owner_name');
            }
            if (!Schema::hasColumn('vehicles', 'services')) {
                $table->json('services')->nullable()->after('status');
            }
            if (!Schema::hasColumn('vehicles', 'total_cost')) {
                $table->decimal('total_cost', 10, 2)->nullable()->after('services');
            }
            if (!Schema::hasColumn('vehicles', 'last_ai_message')) {
                $table->text('last_ai_message')->nullable()->after('status');
            }
            if (!Schema::hasColumn('vehicles', 'last_notification_at')) {
                $table->timestamp('last_notification_at')->nullable()->after('last_ai_message');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            // We don't drop in down because this is a fix-up migration 
            // and we don't want to accidentally lose data if someone rolls back.
        });
    }
};
