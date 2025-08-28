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
        Schema::create('degustacijas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('Naziv');
            $table->dateTime('Datum');
            $table->string('Lokacija');
            $table->integer('Kapacitet');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('status_degustacija_id');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('degustacijas');
    }
};
