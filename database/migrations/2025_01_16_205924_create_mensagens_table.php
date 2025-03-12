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
        Schema::create('mensagens', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // FK para usuarios
            $table->unsignedBigInteger('id_item'); // FK para itens
            $table->text('texto');
            $table->dateTime('data_envio');
            $table->foreign('id_usuario')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('id_item')->references('id')->on('itens')->onDelete('cascade');
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mensagens');
    }
};
