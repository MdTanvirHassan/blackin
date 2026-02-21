<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupProductSlotItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_product_slot_items', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('group_product_slot_id');
            $table->unsignedInteger('product_id');
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->foreign('group_product_slot_id', 'slot_items_slot_id_foreign')
                ->references('id')
                ->on('group_product_slots')
                ->onDelete('cascade');

            $table->foreign('product_id')
                ->references('id')
                ->on('products')
                ->onDelete('cascade');

            $table->unique(['group_product_slot_id', 'product_id'], 'slot_product_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('group_product_slot_items');
    }
}


