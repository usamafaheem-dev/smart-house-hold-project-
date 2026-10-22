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
        Schema::table('users', function (Blueprint $table) {
            $table->string('available_days')->nullable()->after('address');
            $table->time('available_from')->nullable()->after('available_days');
            $table->time('available_to')->nullable()->after('available_from');
            $table->text('service_areas')->nullable()->after('available_to');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['available_days', 'available_from', 'available_to', 'service_areas']);
        });
    }
};
