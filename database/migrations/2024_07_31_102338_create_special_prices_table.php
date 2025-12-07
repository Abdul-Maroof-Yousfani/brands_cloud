<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSpecialPricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql2')->create('special_prices', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('customer_id')->nullable()->default(NULL);
            $table->integer('product_id')->nullable()->default(NULL);
            $table->string('product_code')->nullable()->default(NULL);
            $table->integer('rate')->nullable()->default(NULL);
            $table->integer('mrp_price')->nullable()->default(NULL);
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
        Schema::connection('mysql2')::dropIfExists('special_prices');
    }
}
