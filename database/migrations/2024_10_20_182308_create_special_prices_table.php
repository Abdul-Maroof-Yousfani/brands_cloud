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
        Schema::create('special_prices', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
        });

        Schema::connection('mysql2')->create('special_prices', function (Blueprint $table) {
            $table->increments('id');
            $table->string('product_id');
            $table->enum('status',[0,1]);
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
        Schema::dropIfExists('special_prices');
    }
}
