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
            $table->boolean('aprovado')->default(false); // Valor padrão: não aprovado
            $table->timestamp('aprovado_em')->nullable(); // Pode ser nulo
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('itens', function (Blueprint $table) {
            $table->dropColumn('aprovado'); 
            $table->dropColumn('aprovado_em'); 
        });
    }
};
