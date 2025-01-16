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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_reff');
            $table->string('company_id')->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('status', ['pending', 'processing', 'completed', 'cancelled'])
            ->default('pending');
            $table->string('order_tax_rate')->nullable();
            $table->string('order_tax_value')->nullable();
            $table->string('order_tax')->nullable();
            $table->string('order_discount_type')->nullable();
            $table->decimal('order_discount_value',10, 2)->nullable();
            $table->decimal('total_discount',10, 2)->nullable();
            $table->decimal('shipping_cost',10, 2)->nullable();
            $table->decimal('total', 10, 2);
            $table->decimal('grand_total', 10, 2);
            $table->decimal('paid_amount', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
