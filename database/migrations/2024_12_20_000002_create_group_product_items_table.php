<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupProductItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_product_items', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('group_product_id');
            $table->unsignedInteger('product_id');
            $table->boolean('is_free')->default(false); // For free products in deals
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            
            $table->foreign('group_product_id')->references('id')->on('group_products')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            
            $table->unique(['group_product_id', 'product_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('group_product_items');
    }
}

