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
            $table->enum('status', ['completed', 'in progress', 'scheduled', 'inactive', 'overdue'])->default('scheduled')->change();
            $table->dropColumn('mechanic_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->enum('status', ['completed', 'in progress', 'scheduled', 'inactive', 'overdue'])->default('completed')->change();
            $table->string('mechanic_name')->nullable();
        });
    }
};
