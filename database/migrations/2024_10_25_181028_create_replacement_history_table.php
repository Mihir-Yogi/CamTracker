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
        Schema::create('replacement_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('combo_id')->constrained()->onDelete('cascade');
            $table->enum('item_type', ['NVR', 'DVR', 'HDD', 'CCTV']);
            $table->integer('old_item_id');
            $table->integer('new_item_id');
            $table->date('replacement_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('replacment_history', function (Blueprint $table) {
            $table->dropForeign(['combo_id']);
        });

        Schema::dropIfExists('replacement_history');
    }
};
