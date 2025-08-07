<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('item_fotos', function (Blueprint $table) {
            $table->boolean('is_principal')->default(false)->after('ordem');
        });
    }

    public function down()
    {
        Schema::table('item_fotos', function (Blueprint $table) {
            $table->dropColumn('is_principal');
        });
    }
};