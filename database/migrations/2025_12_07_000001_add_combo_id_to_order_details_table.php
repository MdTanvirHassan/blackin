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
        if (!Schema::hasColumn('order_details', 'combo_id')) {
            Schema::table('order_details', function (Blueprint $table) {
                $table->unsignedInteger('combo_id')->nullable()->after('product_id');
            });
        }

        // Add foreign key if it doesn't exist
        try {
            Schema::table('order_details', function (Blueprint $table) {
                $table->foreign('combo_id', 'order_details_combo_id_foreign')
                    ->references('id')
                    ->on('group_products')
                    ->onDelete('set null');
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
        try {
            Schema::table('order_details', function (Blueprint $table) {
                $table->dropForeign('order_details_combo_id_foreign');
            });
        } catch (\Exception $e) {
            // Foreign key might not exist, ignore
        }

        if (Schema::hasColumn('order_details', 'combo_id')) {
            Schema::table('order_details', function (Blueprint $table) {
                $table->dropColumn('combo_id');
            });
        }
    }
};

