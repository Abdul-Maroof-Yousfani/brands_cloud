<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnInSupplierTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql2')->table('supplier', function (Blueprint $table) {
            $table->unsignedBigInteger('to_type_id')->nullable()->default(null)->after('vendor_code');
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
            $table->dropColumn('to_type_id');
        });
    }
}
