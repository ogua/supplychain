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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('order_id')->nullable();
            $table->unsignedBigInteger('sale_return_id')->nullable();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->enum('payment_type',['debit','credit'])->default("debit");
            $table->string('payment_ref')->nullable();
            $table->text('description')->nullable();
            $table->decimal('amount',10,2)->nullable();
            $table->decimal('change',10,2)->nullable();
            $table->string('cheque_no')->nullable();
            $table->string('paying_method')->nullable();
            $table->text('payment_note')->nullable();
            $table->string('bankname')->nullable();
            $table->date('paid_on')->nullable();
            $table->string('accountnumber')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
