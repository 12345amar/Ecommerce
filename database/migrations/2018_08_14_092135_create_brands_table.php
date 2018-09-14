<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBrandsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $status = config('constant.status');
        Schema::create('brands', function (Blueprint $table) use ($status) {
            $table->increments('id');
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->enum('status', [$status['inactive'], $status['active']])->default($status['active']);
            $table->integer('added_by');
            $table->integer('updated_by');
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
        Schema::dropIfExists('brands');
    }
}
