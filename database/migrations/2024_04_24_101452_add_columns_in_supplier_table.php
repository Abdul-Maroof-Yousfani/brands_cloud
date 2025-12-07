<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsInSupplierTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
  
    public function up()
    {
        Schema::connection('mysql2')->table('supplier', function (Blueprint $table) {
            $table->string('credit_days')->nullable();
            $table->decimal('discount_percent',10,2)->nullable()->defalut(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mysql2')->table('supplier', function (Blueprint $table) {
            $table->dropColumn('credit_days');
            $table->dropColumn('discount_percent');
        });
    }
}
