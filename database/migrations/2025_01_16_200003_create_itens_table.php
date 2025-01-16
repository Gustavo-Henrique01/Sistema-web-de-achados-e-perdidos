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
        Schema::create('itens', function (Blueprint $table) {
            $table->id();
            $table->string('categoria');
            $table->string('foto')->nullable();
            $table->text('descricao');
            $table->date('data_registro');
            $table->string('status'); // Exemplo: ativo, inativo
            $table->unsignedBigInteger('id_usuario'); // FK para usuarios
            $table->unsignedBigInteger('id_endereco'); // FK para enderecos
            $table->foreign('id_usuario')->references('id')->on('usuarios')->onDelete('cascade');
            $table->foreign('id_endereco')->references('id')->on('enderecos')->onDelete('cascade');
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('itens');
    }
};
