<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnInGoodsReceiptNote extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql2')->table('goods_receipt_note', function (Blueprint $table) {
            $table->string('grn_data_status')->nullable()->default("Partial");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mysql2')->table('goods_receipt_note', function (Blueprint $table) {
            $table->dropColumn('grn_data_status');
        });
    }
}
