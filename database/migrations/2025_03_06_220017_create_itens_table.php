<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('itens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_categoria')->constrained('categorias'); // Chave estrangeira para categorias
            $table->string('foto')->nullable(); // Foto do item (opcional)
            $table->text('descricao'); // Descrição do item
            $table->date('data_perdido')->nullable(); // Data em que o item foi perdido (opcional)
            $table->date('data_encontrado')->nullable(); // Data em que o item foi encontrado (opcional)
            $table->enum('status', ['aprovado', 'pendente', 'reprovado'])->default('pendente'); // Status do item
            $table->foreignId('user_id')->constrained('users'); // Chave estrangeira para usuários
            $table->foreignId('id_localizacao')->constrained('localizacoes'); // Chave estrangeira para localizações
            $table->boolean('aprovado')->default(false); // Item aprovado ou não
            $table->timestamp('aprovado_em')->nullable(); // Data de aprovação (opcional)
            $table->timestamps(); // created_at e updated_at
        });
    }

    /**
     * Reverte as migrações.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('itens'); // Remove a tabela se a migração for revertida
    }
};
