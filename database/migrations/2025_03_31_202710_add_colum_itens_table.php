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
            $table->foreignId('aprovado_por_id')->nullable()->constrained('users')->after('status');
            $table->foreignId('reprovado_por_id')->nullable()->constrained('users')->after('aprovado_por_id');
            $table->foreignId('excluido_por_id')->nullable()->constrained('users')->after('reprovado_por_id');
        
            $table->timestamp('reprovado_em')->nullable()->after('reprovado_por_id');
            $table->timestamp('excluido_em')->nullable()->after('excluido_por_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('itens', function (Blueprint $table) {
            $table->dropForeign(['aprovado_por_id']);
            $table->dropForeign(['reprovado_por_id']);
            $table->dropForeign(['excluido_por_id']);
            $table->dropColumn([
                'aprovado_por_id',
                'reprovado_por_id',
                'excluido_por_id',
                'aprovado_em',
                'reprovado_em',
                'excluido_em'
            ]);
        });
    }
}; 