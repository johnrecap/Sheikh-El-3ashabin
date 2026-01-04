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
            if (!Schema::hasColumn('addresses', 'landline')) {
                $table->string('landline', 20)->nullable()->after('phone');
            }
        });

        Schema::table('order_addresses', function (Blueprint $table) {
            if (!Schema::hasColumn('order_addresses', 'landline')) {
                $table->string('landline', 20)->nullable()->after('phone');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('addresses', function (Blueprint $table) {
            $table->dropColumn('landline');
        });

        Schema::table('order_addresses', function (Blueprint $table) {
            $table->dropColumn('landline');
        });
    }
};
