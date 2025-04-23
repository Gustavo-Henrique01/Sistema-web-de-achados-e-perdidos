<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('parceiros', function (Blueprint $table) {
            $table->string('cnpj', 18)->nullable()->after('nome_estabelecimento');
        });
    }

    public function down()
    {
        Schema::table('parceiros', function (Blueprint $table) {
            $table->dropColumn('cnpj');
        });
    }
}; 