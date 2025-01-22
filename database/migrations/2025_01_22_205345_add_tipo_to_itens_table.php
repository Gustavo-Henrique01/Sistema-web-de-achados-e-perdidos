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
    Schema::table('itens', function (Blueprint $table) {
        $table->enum('tipo', ['achado', 'perdido'])->default('perdido'); // A coluna tipo, com valores possíveis 'achado' ou 'perdido'
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('itens', function (Blueprint $table) {
            //
        });
    }
};
