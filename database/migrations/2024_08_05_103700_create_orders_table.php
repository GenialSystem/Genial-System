<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreign('customer_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('customer_id');
            $table->enum('state', ['Nuova', 'Riparata', 'In lavorazione', 'Annullata']);
            $table->enum('car_size', ['Piccolo', 'Grande', 'Medio', 'Veicolo commerciale']);
            $table->string('plate');
            $table->string('notes');
            $table->string('brand');
            $table->integer('earn_mechanic_percentage')->default(0);
            $table->boolean('assembly_disassembly')->default(false);
            $table->boolean('aluminium')->default(false);
            $table->string('damage_diameter');
            $table->string('replacements');
            $table->decimal('price', 10, 2);
            $table->date('finish_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
