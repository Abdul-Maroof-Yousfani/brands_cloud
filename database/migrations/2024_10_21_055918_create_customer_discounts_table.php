<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerDiscountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {


        Schema::connection('mysql2')->create('customer_discounts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('status')->nullable()->default(1);
            $table->integer('customer_id')->nullable()->default(NULL);
            $table->integer('brand_id')->nullable()->default(NULL);
            $table->integer('product_id')->nullable()->default(NULL);
            $table->string('discount_percentage')->nullable()->default(NULL);
            $table->string('discount_price')->nullable()->default(NULL);
//            $table->integer('mrp_price')->nullable()->default(NULL);
//            $table->integer('sale_price')->nullable()->default(NULL);
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
        Schema::dropIfExists('customer_discounts');
    }
}
