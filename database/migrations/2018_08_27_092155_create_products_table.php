<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $status = config('constant.status');
        Schema::create('products', function (Blueprint $table) use ($status) {
            $table->increments('id');
            $table->unsignedInteger('category_id');
            $table->unsignedInteger('brand_id');
            $table->string('name');
            $table->text('short_description')->nullable();
            $table->text('description')->nullable();
            $table->text('image');
            $table->float('price', 8, 2);
            $table->enum('discount_type', [$status['old'], $status['new']])->default($status['old']);
            $table->unsignedInteger('discount')->default(0);
            $table->unsignedInteger('stock');
            $table->enum('is_new', [$status['old'], $status['new']])->default($status['old']);
            $table->enum('is_featured', [$status['old'], $status['new']])->default($status['old']);
            $table->enum('status', [$status['inactive'], $status['active']])->default($status['active']);
            $table->integer('added_by');
            $table->integer('updated_by');          
            $table->timestamps();
            
            $table->foreign('category_id')
                ->references('id')
                ->on('categories')
                ->onDelete('cascade');

            $table->foreign('brand_id')
                ->references('id')
                ->on('brands')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
