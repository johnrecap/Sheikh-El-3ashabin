<?php

use App\Enums\Status;
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
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained();
            $table->string('model_type');
            $table->unsignedBigInteger('model_id');
            $table->string('item_type');
            $table->unsignedBigInteger('item_id');
            $table->string('variation_names')->nullable();
            $table->string('sku')->nullable();
            $table->unsignedDecimal('price', 19, 6)->default(0);
            $table->decimal('quantity', 19, 6)->default(1);
            $table->unsignedDecimal('discount', 19, 6)->default(0);
            $table->unsignedDecimal('subtotal', 19, 6)->default(0);
            $table->unsignedDecimal('tax', 19, 6)->default(0);
            $table->unsignedDecimal('total', 19, 6)->default(0);
            $table->unsignedTinyInteger('status')->default(Status::INACTIVE);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stocks');
    }
};