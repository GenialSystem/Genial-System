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
        Schema::create('estimates', function (Blueprint $table) {
            $table->id();
            $table->string('number')->nullable();
            $table->string('brand')->nullable();
            $table->string('plate')->nullable();
            $table->unsignedBigInteger('mechanic_id')->nullable();
            $table->foreign('mechanic_id')->references('id')->on('mechanic_infos')->onDelete('cascade');
            $table->unsignedBigInteger('customer_id');
            $table->foreign('customer_id')->references('id')->on('customer_infos')->onDelete('cascade');
            $table->enum('type', ['Preventivo combinato', 'Preventivo leva bolli', 'Carrozzeria']);
            $table->enum('state', ['Archiviato', 'Nuovo', 'Confermato', 'Poco interessati', 'Rifiutato']);
            $table->decimal('price', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estimates');
    }
};
