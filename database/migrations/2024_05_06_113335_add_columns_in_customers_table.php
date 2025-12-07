<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsInCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    

    public function up()
    {
        Schema::connection('mysql2')->table('customers', function (Blueprint $table) {
            $table->text('category_id')->nullable();
            $table->text('discount')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mysql2')->table('customers', function (Blueprint $table) {
            $table->dropColumn('category_id');
            $table->dropColumn('discount');
        });
    }
}
