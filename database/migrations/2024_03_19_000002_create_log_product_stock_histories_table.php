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
        Schema::create('log_product_stock_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')
                ->constrained('products')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->morphs('last_location');
            $table->morphs('new_location');
            $table->foreignId('created_by')
                ->constrained('users')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->integer('unit_quantity');
            $table->integer('last_unit_quantity');
            $table->string('last_status');
            $table->integer('new_unit_quantity');
            $table->string('new_status');
            $table->text('system_note')->nullable();
            $table->text('note')->nullable();
            $table->boolean('is_current')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_product_stock_histories');
    }
};
