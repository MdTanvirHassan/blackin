<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('slug')->unique()->nullable();
            $table->string('thumbnail_img')->nullable();
            
            // Deal types: buy_3_get_1_free, buy_5_get_2_free, signature_polo_bundle, custom
            $table->enum('deal_type', ['buy_3_get_1_free', 'buy_5_get_2_free', 'signature_polo_bundle', 'custom'])->default('custom');
            
            // For buy_3_get_1_free: buy_quantity = 3, free_quantity = 1
            // For buy_5_get_2_free: buy_quantity = 5, free_quantity = 2
            $table->integer('buy_quantity')->default(1);
            $table->integer('free_quantity')->default(0);
            
            // Random selection for free products
            $table->boolean('random_free_products')->default(false);
            
            // Discount settings
            $table->boolean('has_discount')->default(false);
            $table->decimal('discount_amount', 10, 2)->nullable();
            $table->enum('discount_type', ['percentage', 'fixed'])->nullable();
            
            // Pricing
            $table->decimal('bundle_price', 10, 2)->nullable();
            
            // Status
            $table->boolean('published')->default(true);
            $table->boolean('active')->default(true);
            
            // SEO
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            
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
        Schema::dropIfExists('group_products');
    }
}

