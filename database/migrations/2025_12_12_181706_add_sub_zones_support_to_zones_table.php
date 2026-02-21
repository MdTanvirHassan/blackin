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
        Schema::table('zones', function (Blueprint $table) {
            $table->unsignedBigInteger('parent_zone_id')->nullable()->after('status');
            $table->foreign('parent_zone_id')->references('id')->on('zones')->onDelete('cascade');
        });

        Schema::create('zone_area', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('zone_id');
            $table->unsignedBigInteger('area_id');
            $table->timestamps();

            $table->foreign('zone_id')->references('id')->on('zones')->onDelete('cascade');
            $table->foreign('area_id')->references('id')->on('areas')->onDelete('cascade');
            
            $table->unique(['zone_id', 'area_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('zone_area');
        
        Schema::table('zones', function (Blueprint $table) {
            $table->dropForeign(['parent_zone_id']);
            $table->dropColumn('parent_zone_id');
        });
    }
};
