<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations - Add guest checkout support
     */
    public function up(): void
    {
        // Step 1: Make user_id nullable in orders table
        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->change();
        });

        // Step 2: Add guest fields to orders table
        Schema::table('orders', function (Blueprint $table) {
            $table->string('guest_name', 100)->nullable()->after('user_id');
            $table->string('guest_email', 100)->nullable()->after('guest_name');
            $table->string('guest_phone', 20)->nullable()->after('guest_email');
            $table->boolean('is_guest_order')->default(false)->after('guest_phone');
        });

        // Step 3: Make user_id nullable in order_addresses table
        Schema::table('order_addresses', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations
     */
    public function down(): void
    {
        // Remove guest fields from orders
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['guest_name', 'guest_email', 'guest_phone', 'is_guest_order']);
        });

        // Revert user_id to NOT NULL (only if no null values exist)
        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable(false)->change();
        });

        Schema::table('order_addresses', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable(false)->change();
        });
    }
};
