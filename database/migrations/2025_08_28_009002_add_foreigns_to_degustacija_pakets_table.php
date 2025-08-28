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
        Schema::table('degustacija_pakets', function (Blueprint $table) {
            $table
                ->foreign('degustacija_id')
                ->references('id')
                ->on('degustacijas')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');

            $table
                ->foreign('degustacioni_paket_id')
                ->references('id')
                ->on('degustacioni_pakets')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('degustacija_pakets', function (Blueprint $table) {
            $table->dropForeign(['degustacija_id']);
            $table->dropForeign(['degustacioni_paket_id']);
        });
    }
};
