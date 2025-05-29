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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('base_product_id')->constrained('base_products')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('warehouse_id')->constrained('warehouses')->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('production_code')->nullable();
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->integer('item_quantity')->default(1);
            $table->tinyInteger('status')->default(1)->comment('1: Draft, 2: Published, 3: Out of Stock, 4: Discontinued, 5: Archived, 6: Deleted');
            $table->decimal('selling_price', 15, 2)->nullable();
            $table->string('unit')->default('kardus');
            $table->string('item_unit')->default('botol');
            $table->morphs('ownership');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
