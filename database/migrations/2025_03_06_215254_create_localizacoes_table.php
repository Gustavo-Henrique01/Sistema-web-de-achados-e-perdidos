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
        Schema::create('localizacoes', function (Blueprint $table) {
            $table->id(); // Chave primária (id)
            $table->string('nome_local'); // Nome do local
            $table->string('endereco'); // Endereço completo
            $table->string('latitude'); // Coordenada de latitude
            $table->string('longitude'); // Coordenada de longitude
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
        Schema::dropIfExists('localizacoes'); // Remove a tabela se a migração for revertida
    }

};
