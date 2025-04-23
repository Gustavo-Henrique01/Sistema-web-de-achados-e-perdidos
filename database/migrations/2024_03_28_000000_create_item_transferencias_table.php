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
        Schema::create('item_transferencias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained('itens')->onDelete('cascade');
            $table->foreignId('parceiro_id')->constrained('parceiros')->onDelete('cascade');
            $table->foreignId('usuario_id')->constrained('users')->onDelete('cascade');
            $table->text('observacoes')->nullable();
            $table->enum('status', ['pendente', 'confirmada', 'rejeitada'])->default('pendente');
            $table->timestamp('data_confirmacao')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_transferencias');
    }
}; 