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
        Schema::create('availabilities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mechanic_info_id');
            $table->date('date'); 
            $table->string('client_name')->nullable(); 
            $table->enum('state', ['available', 'not_available', 'not_picked_yet'])->default('not_picked_yet');
            $table->timestamps();
            $table->foreign('mechanic_info_id')->references('id')->on('mechanic_infos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('availabilities');
    }
};
