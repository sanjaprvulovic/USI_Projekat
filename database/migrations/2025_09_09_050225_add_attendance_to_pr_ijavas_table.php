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
        Schema::table('pr_ijavas', function (Blueprint $table) {
            $table->boolean('prisutan')->default(false)->after('degustacioni_paket_id');
            $table->timestamp('checked_in_at')->nullable()->after('prisutan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pr_ijavas', function (Blueprint $table) {
            $table->dropColumn(['prisutan','checked_in_at']);
        });
    }
};
