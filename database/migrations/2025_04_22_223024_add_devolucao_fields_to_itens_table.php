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
        Schema::table('itens', function (Blueprint $table) {
            $table->timestamp('data_devolucao')->nullable()->after('data_registro');
            $table->text('observacoes_devolucao')->nullable()->after('data_devolucao');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('itens', function (Blueprint $table) {
            $table->dropColumn(['data_devolucao', 'observacoes_devolucao']);
        });
    }
};
