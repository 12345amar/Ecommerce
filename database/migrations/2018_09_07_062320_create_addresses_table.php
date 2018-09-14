<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $status = config('constant.status');
        Schema::create('addresses', function (Blueprint $table) use ($status) {
            $table->increments('id');
            $table->unsignedInteger('user_id');          
            $table->string('fullname');
            $table->string('mobile');
            $table->text('address');
            $table->text('near_by_landmark')->nullable();
            $table->integer('pincode');
            $table->string('city');
            $table->string('state');
            $table->enum('address_type', [$status['home'], $status['office']])->default($status['home']);            
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
        Schema::dropIfExists('addresses');
    }
}
