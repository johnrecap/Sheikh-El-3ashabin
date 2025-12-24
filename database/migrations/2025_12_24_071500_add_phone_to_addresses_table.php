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
        Schema::table('addresses', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('full_address');
        });

        Schema::table('order_addresses', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('full_address');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('addresses', function (Blueprint $table) {
            $table->dropColumn('phone');
        });

        Schema::table('order_addresses', function (Blueprint $table) {
            $table->dropColumn('phone');
        });
    }
};
