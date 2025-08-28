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
        Schema::table('pr_ijavas', function (Blueprint $table) {
            $table
                ->foreign('status_prijava_id')
                ->references('id')
                ->on('status_prijavas')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');

            $table
                ->foreign('degustacija_id')
                ->references('id')
                ->on('degustacijas')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');

            $table
                ->foreign('user_id')
                ->references('id')
                ->on('users')
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
        Schema::table('pr_ijavas', function (Blueprint $table) {
            $table->dropForeign(['status_prijava_id']);
            $table->dropForeign(['degustacija_id']);
            $table->dropForeign(['user_id']);
            $table->dropForeign(['degustacioni_paket_id']);
        });
    }
};
