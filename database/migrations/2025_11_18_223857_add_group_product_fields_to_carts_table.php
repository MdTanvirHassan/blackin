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
        if (!Schema::hasColumn('carts', 'group_product_id')) {
            Schema::table('carts', function (Blueprint $table) {
                $table->unsignedInteger('group_product_id')->nullable()->after('product_id');
            });
        }

        if (!Schema::hasColumn('carts', 'group_product_slot_id')) {
            Schema::table('carts', function (Blueprint $table) {
                $table->unsignedInteger('group_product_slot_id')->nullable()->after('group_product_id');
            });
        }

        // Add foreign keys if they don't exist
        try {
            Schema::table('carts', function (Blueprint $table) {
                $table->foreign('group_product_id', 'carts_group_product_id_foreign')
                    ->references('id')
                    ->on('group_products')
                    ->onDelete('cascade');
            });
        } catch (\Exception $e) {
            // Foreign key might already exist, ignore
        }

        try {
            Schema::table('carts', function (Blueprint $table) {
                $table->foreign('group_product_slot_id', 'carts_group_product_slot_id_foreign')
                    ->references('id')
                    ->on('group_product_slots')
                    ->onDelete('cascade');
            });
        } catch (\Exception $e) {
            // Foreign key might already exist, ignore
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('carts', function (Blueprint $table) {
            $table->dropForeign('carts_group_product_id_foreign');
            $table->dropForeign('carts_group_product_slot_id_foreign');
        });

        Schema::table('carts', function (Blueprint $table) {
            $table->dropColumn(['group_product_id', 'group_product_slot_id']);
        });
    }
};
