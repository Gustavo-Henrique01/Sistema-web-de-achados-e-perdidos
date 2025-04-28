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
            // Adiciona a coluna metodo_devolucao após a coluna observacoes_devolucao
            $table->enum('metodo_devolucao', ['usuario', 'proprio', 'parceiro'])
                  ->nullable()
                  ->after('observacoes_devolucao')
                  ->comment('Método de devolução: usuario (por outro usuário), proprio (pelo dono), parceiro (via parceiro)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('itens', function (Blueprint $table) {
            $table->dropColumn('metodo_devolucao');
        });
    }
};
