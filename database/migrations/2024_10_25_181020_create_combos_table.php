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
        Schema::create('combos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('location_id')->constrained()->onDelete('cascade'); 
            $table->foreignId('depot_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('nvr_id')->nullable();
            $table->unsignedBigInteger('dvr_id')->nullable();
            $table->unsignedBigInteger('hdd_id')->nullable();
            $table->integer('camera_capacity');
            $table->integer('current_cctv_count')->default(0);
            $table->timestamps();
        
            // Foreign keys with cascading options
            $table->foreign('nvr_id')->references('id')->on('nvrs')->onDelete('set null');
            $table->foreign('dvr_id')->references('id')->on('dvrs')->onDelete('set null');
            $table->foreign('hdd_id')->references('id')->on('hdds')->onDelete('set null');
            $table->foreign('depot_id')->references('id')->on('depots')->onDelete('set null');
            $table->foreign('location_id')->references('id')->on('locations')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('combos', function (Blueprint $table) {
            if (Schema::hasColumn('combos', 'combos')) {
                $table->dropForeign(['depot_id']);
                $table->dropColumn('depot_id');
            }

            if (Schema::hasColumn('combos', 'location_id')) {
                $table->dropForeign(['location_id']);
                $table->dropColumn('location_id');
            }
        });
    }
};
