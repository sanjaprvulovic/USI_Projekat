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
        Schema::create('pr_ijavas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->dateTime('Datum');
            $table->unsignedBigInteger('status_prijava_id');
            $table->unsignedBigInteger('degustacija_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('degustacioni_paket_id');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pr_ijavas');
    }
};
