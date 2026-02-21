<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('order_details', function (Blueprint $table) {
            if (!Schema::hasColumn('order_details', 'group_product_slot_id')) {
                $table->unsignedInteger('group_product_slot_id')->nullable()->after('combo_id');
            }
            if (!Schema::hasColumn('order_details', 'group_product_slot_combination_hash')) {
                $table->string('group_product_slot_combination_hash', 32)->nullable()->after('group_product_slot_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_details', function (Blueprint $table) {
            if (Schema::hasColumn('order_details', 'group_product_slot_combination_hash')) {
                $table->dropColumn('group_product_slot_combination_hash');
            }
            if (Schema::hasColumn('order_details', 'group_product_slot_id')) {
                $table->dropColumn('group_product_slot_id');
            }
        });
    }
};
