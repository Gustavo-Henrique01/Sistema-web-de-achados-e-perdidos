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
        // Verifica se a chave estrangeira já existe
        $constraints = DB::select("
            SELECT constraint_name
            FROM information_schema.table_constraints
            WHERE table_name = 'itens'
            AND constraint_type = 'FOREIGN KEY'
            AND constraint_name LIKE '%parceiro_id%'
        ");
        
        // Se não existir nenhuma constraint relacionada a parceiro_id, cria uma
        if (empty($constraints)) {
            Schema::table('itens', function (Blueprint $table) {
                $table->foreign('parceiro_id')
                    ->references('id')
                    ->on('parceiros')
                    ->onDelete('set null');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('itens', function (Blueprint $table) {
            // Remove a chave estrangeira
            $table->dropForeign(['parceiro_id']);
        });
    }
};
