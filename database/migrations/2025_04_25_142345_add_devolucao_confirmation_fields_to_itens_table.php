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
            $table->foreignId('usuario_devolucao_id')->nullable()->after('observacoes_devolucao')
                  ->constrained('users')->nullOnDelete();
            $table->foreignId('parceiro_devolucao_id')->nullable()->after('usuario_devolucao_id')
                  ->constrained('parceiros')->nullOnDelete();
            $table->string('email_usuario_devolucao')->nullable()->after('parceiro_devolucao_id');
            $table->boolean('devolucao_confirmada')->default(false)->after('email_usuario_devolucao');
            $table->timestamp('data_confirmacao_devolucao')->nullable()->after('devolucao_confirmada');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('itens', function (Blueprint $table) {
            //
        });
    }
};
