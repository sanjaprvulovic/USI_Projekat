<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        
        if (!Schema::hasColumn('degustacija_pakets', 'degustacija_id')) {
            Schema::table('degustacija_pakets', function (Blueprint $table) {
                $table->unsignedBigInteger('degustacija_id')->nullable()->after('id');
            });
        }

        
        if (Schema::hasColumn('degustacija_pakets', 'DegustacijaID')) {
            DB::statement("
                UPDATE degustacija_pakets
                SET degustacija_id = COALESCE(degustacija_id, DegustacijaID)
            ");
        }

        
        DB::statement("
            DELETE dp1 FROM degustacija_pakets dp1
            JOIN degustacija_pakets dp2
              ON dp1.degustacija_id = dp2.degustacija_id
             AND dp1.degustacioni_paket_id = dp2.degustacioni_paket_id
             AND dp1.id > dp2.id
        ");

        
        try {
            Schema::table('degustacija_pakets', function (Blueprint $table) {
                $table->unsignedBigInteger('degustacija_id')->nullable(false)->change();
            });
        } catch (\Throwable $e) {
            
        }

        
        try {
            Schema::table('degustacija_pakets', function (Blueprint $table) {
                $table->unique(['degustacija_id', 'degustacioni_paket_id'], 'deg_pak_unique');
            });
        } catch (\Throwable $e) {
            
        }

        
        if (Schema::hasColumn('degustacija_pakets', 'DegustacijaID')) {
            Schema::table('degustacija_pakets', function (Blueprint $table) {
                $table->dropColumn('DegustacijaID');
            });
        }
    }

    public function down(): void
    {
        Schema::table('degustacija_pakets', function (Blueprint $table) {
            
            if (!Schema::hasColumn('degustacija_pakets', 'DegustacijaID')) {
                $table->unsignedBigInteger('DegustacijaID')->nullable()->after('id');
            }

            
            try { $table->dropUnique('deg_pak_unique'); } catch (\Throwable $e) {}
        });
    }
};
