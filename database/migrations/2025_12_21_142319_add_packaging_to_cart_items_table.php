<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('cart_items', function (Blueprint $table) {
            $table->string('packaging_type')->nullable()->after('quantity'); // 'biasa' atau 'mika'
            $table->integer('packaging_price')->default(0)->after('packaging_type'); // 10000 atau 15000
        });
    }

    public function down()
    {
        Schema::table('cart_items', function (Blueprint $table) {
            $table->dropColumn(['packaging_type', 'packaging_price']);
        });
    }
};
