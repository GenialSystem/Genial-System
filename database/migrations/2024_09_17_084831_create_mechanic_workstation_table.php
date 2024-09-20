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
        Schema::create('mechanic_workstation', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mechanic_id'); // Ensure this is unsignedBigInteger
            $table->unsignedBigInteger('workstation_id'); // Ensure this is unsignedBigInteger
            
            // Foreign key constraints
            $table->foreign('mechanic_id')->references('id')->on('mechanic_infos')->onDelete('cascade');
            $table->foreign('workstation_id')->references('id')->on('workstations')->onDelete('cascade');
        
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mechanic_workstation');
    }
};
