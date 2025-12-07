<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaleTaxesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::connection('mysql2')->create('sale_taxes', function (Blueprint $table) {
        $table->increments('id');
        $table->string('name')->nullable()->default(NULL);
        $table->string('discount_percentage')->nullable()->default(NULL);
        $table->integer('status')->nullable()->default(1);
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
        Schema::dropIfExists('sale_taxes');
        
    }
}
