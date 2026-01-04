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
        Schema::table('order_addresses', function (Blueprint $table) {
            // Re-add the columns that were dropped but are needed by the code
            if (!Schema::hasColumn('order_addresses', 'address')) {
                $table->string('address')->nullable()->after('full_address');
            }
            if (!Schema::hasColumn('order_addresses', 'latitude')) {
                $table->string('latitude')->nullable()->after('address');
            }
            if (!Schema::hasColumn('order_addresses', 'longitude')) {
                $table->string('longitude')->nullable()->after('latitude');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_addresses', function (Blueprint $table) {
            $table->dropColumn(['address', 'latitude', 'longitude']);
        });
    }
};
