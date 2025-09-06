<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasColumn('degustacija_pakets', 'DegustacijaID')) {
            DB::statement("
                UPDATE degustacija_pakets
                SET degustacija_id = COALESCE(degustacija_id, DegustacijaID)
            ");
        }

        Schema::table('degustacija_pakets', function (Blueprint $table) {
            if (Schema::hasColumn('degustacija_pakets', 'DegustacijaID')) {
                $table->dropColumn('DegustacijaID'); // ako traži dbal: composer require doctrine/dbal
            }

            // Unique da ne možeš dva puta isti paket na istu degustaciju
            if (!Schema::hasColumn('degustacija_pakets','degustacija_id') ||
                !Schema::hasColumn('degustacija_pakets','degustacioni_paket_id')) return;

            $table->unique(['degustacija_id','degustacioni_paket_id'], 'deg_pak_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('degustacija_pakets', function (Blueprint $table) {
            // Vrati kolonu (praznu) i skini unique (opciono)
            if (!Schema::hasColumn('degustacija_pakets', 'DegustacijaID')) {
                $table->unsignedBigInteger('DegustacijaID')->nullable()->after('id');
            }
            try { $table->dropUnique('deg_pak_unique'); } catch (\Throwable $e) {}
        });
    }
};
