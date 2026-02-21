<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRefundFieldsToGroupProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('group_products', function (Blueprint $table) {
            $table->boolean('refundable')->default(0)->after('bundle_price');
            $table->unsignedBigInteger('refund_note_id')->nullable()->after('refundable');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('group_products', function (Blueprint $table) {
            $table->dropColumn(['refundable', 'refund_note_id']);
        });
    }
}


