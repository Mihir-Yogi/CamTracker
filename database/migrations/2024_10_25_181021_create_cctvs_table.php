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
        Schema::create('cctvs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('combo_id')->constrained()->onDelete('cascade');
            $table->foreignId('location_id')->constrained()->onDelete('cascade'); 
            $table->foreignId('depot_id')->constrained()->onDelete('cascade');
            $table->string('model');
            $table->string('serial_number')->unique();
            $table->integer('megapixel')->unsigned();
            $table->enum('status', ['working', 'failed'])->default('working');
            $table->string('sublocation');
            $table->string('failure_reason')->nullable();
            $table->unsignedBigInteger('replaced_by')->nullable(); // Add this after failure_reason
            $table->text('image_replace')->nullable();
            $table->date('purchase_date')->nullable();          // Added field
            $table->date('installation_date')->nullable();      // Added field
            $table->date('warranty_expiration')->nullable(); 
            $table->timestamps();
            
            $table->foreign('replaced_by')->references('id')->on('users')->onDelete('set null'); // Assuming 'users' table   // Added field
            $table->foreign('depot_id')->references('id')->on('depots')->onDelete('set null');
            $table->foreign('location_id')->references('id')->on('locations')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cctvs', function (Blueprint $table) {
            $table->dropForeign(['replaced_by']);
            $table->dropForeign(['combo_id']);
            $table->dropForeign(['depot_id']);
            $table->dropForeign(['location_id']);
            $table->dropColumn('replaced_by');
        });

        Schema::dropIfExists('cctvs');
    }
};
