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
            $table->string('model');
            $table->string('serial_number')->unique();
            $table->enum('status', ['working', 'failed'])->default('working');
            $table->string('sublocation');
            $table->string('failure_reason')->nullable();
            $table->date('purchase_date')->nullable();          // Added field
            $table->date('installation_date')->nullable();      // Added field
            $table->date('warranty_expiration')->nullable(); 
            $table->unsignedBigInteger('replaced_by')->nullable()->after('failure_reason'); // Add this after failure_reason
            $table->foreign('replaced_by')->references('id')->on('users')->onDelete('set null'); // Assuming 'users' table   // Added field
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cctvs', function (Blueprint $table) {
            $table->dropForeign(['replaced_by']);
            $table->dropColumn('replaced_by');
        });
    }
};
