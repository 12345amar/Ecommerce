<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->string('session')->nullable();
            $table->integer('user_id')->unsigned()->nullable();            
            $table->decimal('shipping_price')->nullable();
            $table->decimal('order_total');
            $table->string('payment_type');
            $table->string('transaction_number');
            $table->string('payment_status');
            $table->string('order_status');
            $table->string('payment_message');
            $table->string('order_reciept');
            $table->timestamps();
            
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            
            $table->index('transaction_number');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
