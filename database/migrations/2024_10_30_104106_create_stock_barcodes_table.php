<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStockBarcodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::connection('mysql2')->create('stock_barcodes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('type')->nullable()->default(1)->comment('1 - stock-in, 2 - stock-out');
            $table->integer('voucher_type')->nullable()->default(NULL)->comment('1 - grn, 2 - gdn , 3 = Sale return');
            $table->integer('voucher_no')->nullable()->default(NULL);
            $table->integer('product_id')->nullable()->default(NULL);
            $table->string('barcode')->nullable()->default(NULL);
            $table->string('status')->nullable()->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stock_barcodes');
    }
}
