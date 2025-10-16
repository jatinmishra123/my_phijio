<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('type'); // e.g. plan_purchase, kit_payment
            $table->string('payment_id')->nullable(); // Generated or gateway payment ID
            $table->decimal('amount', 10, 2)->nullable();
            $table->string('status')->nullable(); // pending, paid, failed, etc.
            $table->json('details')->nullable(); // gateway response or metadata
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
