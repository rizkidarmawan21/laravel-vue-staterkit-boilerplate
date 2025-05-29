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
        Schema::create('log_partner_product_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('partner_product_id')
                ->constrained('partner_products')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->integer('unit_quantity')->default(1);
            $table->integer('last_quantity')->default(1);
            $table->integer('new_quantity')->default(1);
            $table->foreignId('created_by')
                ->constrained('users')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->text('note')->nullable();
            $table->text('system_note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_partner_product_histories');
    }
};
