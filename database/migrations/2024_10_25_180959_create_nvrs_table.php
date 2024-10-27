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
        Schema::create('nvrs', function (Blueprint $table) {
            $table->id(); 
            $table->string('model');
            $table->string('serial_number')->unique();
            $table->enum('status', ['working', 'failed'])->default('working');
            $table->string('failure_reason')->nullable();
            $table->date('purchase_date')->nullable();          // Added field
            $table->date('installation_date')->nullable();      // Added field
            $table->date('warranty_expiration')->nullable();  
            $table->unsignedBigInteger('depot_id')->nullable();
            $table->unsignedBigInteger('location_id')->nullable();
            
            // Add foreign key constraints if needed
            $table->foreign('depot_id')->references('id')->on('depots')->onDelete('set null');
            $table->foreign('location_id')->references('id')->on('locations')->onDelete('set null');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('nvrs', function (Blueprint $table) {
            if (Schema::hasColumn('nvrs', 'depot_id')) {
                $table->dropForeign(['depot_id']);
                $table->dropColumn('depot_id');
            }

            if (Schema::hasColumn('nvrs', 'location_id')) {
                $table->dropForeign(['location_id']);
                $table->dropColumn('location_id');
            }
        });
    }
};
