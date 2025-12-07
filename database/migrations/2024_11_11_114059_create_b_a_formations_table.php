<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBAFormationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::connection('mysql2')->create('b_a_formations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ba_no');
            $table->integer('employee_id');
            $table->integer('customer_id')->nullable();
            $table->json('brands_ids');
            $table->integer('status')->default(1);
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
        Schema::dropIfExists('b_a_formations');
    }
}
