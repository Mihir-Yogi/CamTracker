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
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('depot_id')->constrained()->onDelete('cascade');
            $table->foreignId('location_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('nvr_id')->nullable()->index(); // Nullable foreign key for nvr_id
            $table->unsignedBigInteger('dvr_id')->nullable()->index(); // Nullable foreign key for dvr_id
            $table->unsignedBigInteger('hdd_id')->index();  
            $table->enum('nvr_status', ['ON', 'OFF'])->default('ON')->nullable();
            $table->enum('dvr_status', ['ON', 'OFF'])->default('ON')->nullable();
            $table->enum('hdd_status', ['ON', 'OFF'])->default('ON');
            $table->integer('cctv_off_count')->nullable();
            $table->text('nvr_reason')->nullable();
            $table->text('dvr_reason')->nullable();
            $table->text('hdd_reason')->nullable();
            $table->integer('cctv_on_count')->nullable();
            $table->text('remark_image')->nullable();
            
            $table->timestamps();

            $table->foreign('nvr_id')->references('id')->on('nvrs')->onDelete('set null'); // Assuming 'nvrs' table
            $table->foreign('dvr_id')->references('id')->on('dvrs')->onDelete('set null'); // Assuming 'dvrs' table
            $table->foreign('hdd_id')->references('id')->on('hdds')->onDelete('cascade');  // Assuming 'hdds' table
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

        Schema::table('reports', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['depot_id']);
            $table->dropForeign(['location_id']);
            $table->dropForeign(['nvr_id']);
            $table->dropForeign(['dvr_id']);
            $table->dropForeign(['hdd_id']);
        });

        Schema::dropIfExists('reports');
    }
};
