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
        if (!Schema::hasColumn('carts', 'group_product_slot_combination_hash')) {
            Schema::table('carts', function (Blueprint $table) {
                $table->string('group_product_slot_combination_hash', 64)->nullable()->after('group_product_slot_id')->index();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('carts', 'group_product_slot_combination_hash')) {
            Schema::table('carts', function (Blueprint $table) {
                $table->dropIndex(['group_product_slot_combination_hash']);
                $table->dropColumn('group_product_slot_combination_hash');
            });
        }
    }
};
