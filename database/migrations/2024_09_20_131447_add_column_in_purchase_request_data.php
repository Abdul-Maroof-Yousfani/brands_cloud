<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnInPurchaseRequestData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql2')->table('purchase_request_data', function (Blueprint $table) {
            $table->integer('brand_id')->nullable()->default(NULL)->after('demand_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mysql2')->table('purchase_request_data', function (Blueprint $table) {
            $table->dropColumn('brand_id');
        });
    }
}
