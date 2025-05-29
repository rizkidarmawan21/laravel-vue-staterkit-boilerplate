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
        Schema::create('partners', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('store_name');
            $table->string('store_phone')->nullable();
            $table->text('store_address')->nullable();
            $table->decimal('store_latitude', 10, 8)->nullable();
            $table->decimal('store_longitude', 11, 8)->nullable();
            $table->string('owner_name');
            $table->string('owner_phone')->nullable();
            $table->text('owner_address')->nullable();
            $table->foreignId('account_id')->unique()->constrained('users')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('sales_id')->constrained('sales')->cascadeOnUpdate()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('partners');
    }
};
