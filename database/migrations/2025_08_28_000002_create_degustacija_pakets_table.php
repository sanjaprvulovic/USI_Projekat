<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('degustacija_pakets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('DegustacijaID');
            $table->unsignedBigInteger('degustacija_id');
            $table->unsignedBigInteger('degustacioni_paket_id');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('degustacija_pakets');
    }
};
