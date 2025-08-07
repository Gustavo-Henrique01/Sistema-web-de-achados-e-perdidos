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
        Schema::create('parceiros', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('id_localizacao')->constrained('localizacoes')->onDelete('cascade');
            $table->string('nome_estabelecimento');
            $table->text('descricao')->nullable();
            $table->string('horario_funcionamento')->nullable();
            $table->string('telefone_comercial')->nullable();
            $table->string('logo')->nullable();
            $table->enum('tipo_parceiro', ['ponto_coleta', 'evento', 'ambos'])->default('ponto_coleta');
            $table->date('data_inicio_parceria');
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });

        // Adicionar campo parceiro_id na tabela itens
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remover campo parceiro_id da tabela itens
        

        Schema::dropIfExists('parceiros');
    }
}; 