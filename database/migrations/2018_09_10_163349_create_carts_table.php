<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('session')->nullable();
            $table->integer('user_id')->unsigned()->nullable();
            $table->integer('order_id')->unsigned()->nullable();
            $table->integer('product_id')->unsigned();
            $table->integer('quantity')->unsigned();
            $table->decimal('unit_price');
            $table->decimal('total_price');
            $table->integer('address_id')->unsigned()->nullable();           
            $table->timestamps();
            
            $table->index('user_id');
            $table->index('session');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('carts');
    }
}
