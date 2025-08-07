<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Primeiro, altera o tipo da coluna para string temporariamente
        Schema::table('itens', function (Blueprint $table) {
            $table->string('status_temp')->default('pendente');
        });

        // Copia os dados da coluna antiga para a nova
        DB::table('itens')->update([
            'status_temp' => DB::raw('status')
        ]);

        // Remove a coluna antiga
        Schema::table('itens', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        // Cria a nova coluna enum com os novos valores
        Schema::table('itens', function (Blueprint $table) {
            $table->enum('status', ['pendente', 'aprovado', 'reprovado', 'em_transferencia', 'em_estabelecimento', 'devolvido'])
                ->default('pendente')
                ->after('status_temp');
        });

        // Copia os dados da coluna temporária para a nova coluna
        DB::table('itens')->update([
            'status' => DB::raw('status_temp')
        ]);

        // Remove a coluna temporária
        Schema::table('itens', function (Blueprint $table) {
            $table->dropColumn('status_temp');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Primeiro, altera o tipo da coluna para string temporariamente
        Schema::table('itens', function (Blueprint $table) {
            $table->string('status_temp')->default('pendente');
        });

        // Copia os dados da coluna antiga para a nova
        DB::table('itens')->update([
            'status_temp' => DB::raw('status')
        ]);

        // Remove a coluna antiga
        Schema::table('itens', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        // Cria a nova coluna enum com os valores originais
        Schema::table('itens', function (Blueprint $table) {
            $table->enum('status', ['pendente', 'aprovado', 'reprovado', 'em_transferencia', 'em_estabelecimento'])
                ->default('pendente')
                ->after('status_temp');
        });

        // Copia os dados da coluna temporária para a nova coluna
        DB::table('itens')->update([
            'status' => DB::raw('status_temp')
        ]);

        // Remove a coluna temporária
        Schema::table('itens', function (Blueprint $table) {
            $table->dropColumn('status_temp');
        });
    }
}; 