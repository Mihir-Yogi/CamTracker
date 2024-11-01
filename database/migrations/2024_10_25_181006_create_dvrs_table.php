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
        Schema::create('dvrs', function (Blueprint $table) {
            $table->id();
            $table->string('model');
            $table->string('serial_number')->unique();
            $table->enum('status', ['working', 'failed'])->default('working');
            $table->string('failure_reason')->nullable();
            $table->text('image_replace')->nullable();
            $table->foreignId('location_id')->constrained()->onDelete('cascade'); 
            $table->foreignId('depot_id')->constrained()->onDelete('cascade');
            $table->date('purchase_date')->nullable();          // Added field
            $table->date('installation_date')->nullable();      // Added field
            $table->date('warranty_expiration')->nullable(); 
            $table->string('sublocation');   // Added field
            $table->timestamps();

            $table->foreign('depot_id')->references('id')->on('depots')->onDelete('set null');
            $table->foreign('location_id')->references('id')->on('locations')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dvrs', function (Blueprint $table) {
            if (Schema::hasColumn('dvrs', 'depot_id')) {
                $table->dropForeign(['depot_id']);
                $table->dropColumn('depot_id');
            }

            if (Schema::hasColumn('dvrs', 'location_id')) {
                $table->dropForeign(['location_id']);
                $table->dropColumn('location_id');
            }
        });
    }
};
