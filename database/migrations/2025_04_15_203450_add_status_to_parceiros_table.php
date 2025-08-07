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
        Schema::table('parceiros', function (Blueprint $table) {
            $table->string('status', 20)->default('pendente')->after('ativo');
            $table->text('motivo_reprovacao')->nullable()->after('status');
            $table->timestamp('data_aprovacao')->nullable()->after('motivo_reprovacao');
            $table->unsignedBigInteger('aprovado_por_id')->nullable()->after('data_aprovacao');
            $table->foreign('aprovado_por_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('parceiros', function (Blueprint $table) {
            $table->dropForeign(['aprovado_por_id']);
            $table->dropColumn(['status', 'motivo_reprovacao', 'data_aprovacao', 'aprovado_por_id']);
        });
    }
};
