<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('itens', function (Blueprint $table) {
            // Verifica se a coluna existe antes de remover
            if (Schema::hasColumn('itens', 'foto')) {
                $table->dropColumn('foto');
            }
        });
    }

    public function down()
    {
        Schema::table('itens', function (Blueprint $table) {
            $table->string('foto')->nullable()->after('id_categoria');
        });
    }
};