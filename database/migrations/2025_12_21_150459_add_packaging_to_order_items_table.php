<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('order_items', function (Blueprint $table) {
            // Menambahkan kolom packaging_type dan packaging_price
            $table->string('packaging_type')->nullable()->after('quantity'); 
            $table->integer('packaging_price')->default(0)->after('packaging_type');
        });
    }

    public function down()
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn(['packaging_type', 'packaging_price']);
        });
    }
};