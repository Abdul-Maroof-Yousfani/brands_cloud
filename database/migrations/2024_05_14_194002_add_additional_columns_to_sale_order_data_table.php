<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAdditionalColumnsToSaleOrderDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql2')->table('sales_order_data', function (Blueprint $table) {
            $table->decimal('discount_percent',10,2)->default(0)->nullable();
            $table->decimal('discount_amount',10,2)->default(0)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mysql2')->table('sales_order_data', function (Blueprint $table) {
            $table->dropColumn('discount_percent');
            $table->dropColumn('discount_amount');
        });
    }
}
