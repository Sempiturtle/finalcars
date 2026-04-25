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
        Schema::table('service_logs', function (Blueprint $table) {
            $table->text('notes')->nullable()->after('mechanic_name');
            $table->string('verification_photo')->nullable()->after('notes');
            $table->foreignId('completed_by_id')->nullable()->constrained('users')->onDelete('set null')->after('verification_photo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('service_logs', function (Blueprint $table) {
            $table->dropColumn(['notes', 'verification_photo', 'completed_by_id']);
        });
    }
};
