<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('order_addresses', function (Blueprint $table) {
            // Add new columns for manual address input
            $table->string('governorate', 100)->nullable()->after('label');
            $table->string('city', 100)->nullable()->after('governorate');
            $table->string('street', 200)->nullable()->after('city');
            $table->string('building_number', 50)->nullable()->after('street');
            $table->text('full_address')->nullable()->after('building_number');
            
            // Make old columns nullable for migration
            $table->string('latitude')->nullable()->change();
            $table->string('longitude')->nullable()->change();
        });

        // Migrate existing data: move old address to full_address
        DB::table('order_addresses')->whereNotNull('address')->update([
            'full_address' => DB::raw('address'),
            'governorate' => 'غير محدد',
            'city' => 'غير محدد',
        ]);

        // Now we can drop the old columns and make new ones required
        Schema::table('order_addresses', function (Blueprint $table) {
            $table->dropColumn(['latitude', 'longitude', 'address']);
            
            // Make required fields NOT NULL
            $table->string('governorate', 100)->nullable(false)->change();
            $table->string('city', 100)->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_addresses', function (Blueprint $table) {
            // Re-add old columns
            $table->string('address')->nullable()->after('label');
            $table->string('latitude')->nullable()->after('full_address');
            $table->string('longitude')->nullable()->after('latitude');
            
            // Remove new columns
            $table->dropColumn(['governorate', 'city', 'street', 'building_number', 'full_address']);
        });
    }
};
