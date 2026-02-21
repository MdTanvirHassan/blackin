<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupProductSlotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_product_slots', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('group_product_id');
            $table->string('name');
            $table->enum('discount_type', ['none', 'flat', 'percent'])->default('none');
            $table->decimal('discount_value', 10, 2)->default(0);
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->foreign('group_product_id')
                ->references('id')
                ->on('group_products')
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
        Schema::dropIfExists('group_product_slots');
    }
}


