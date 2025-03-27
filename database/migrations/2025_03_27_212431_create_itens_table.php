<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('itens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_categoria')->constrained('categorias');
            $table->string('foto')->nullable(); // Coluna temporária (será removida posteriormente)
            $table->text('descricao');
            $table->date('data_perdido')->nullable();
            $table->date('data_encontrado')->nullable();
            $table->enum('status', ['pendente', 'aprovado', 'reprovado'])->default('pendente'); // Corrigido para enum
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('id_localizacao')->constrained('localizacoes');
            $table->boolean('aprovado')->default(false);
            $table->enum('tipo', ['achado', 'perdido']);
            $table->timestamp('aprovado_em')->nullable();
            $table->timestamp('data_registro')->useCurrent(); // Define a data atual como padrão
            $table->foreignId('parceiro_id')->nullable()->constrained('parceiros');
            $table->timestamps();
            
            // Índices para melhor performance
            $table->index('id_categoria');
            $table->index('user_id');
            $table->index('id_localizacao');
            $table->index('parceiro_id');
            $table->index('status');
            $table->index('tipo');
        });
    }

    public function down()
    {
        Schema::dropIfExists('itens');
    }
};