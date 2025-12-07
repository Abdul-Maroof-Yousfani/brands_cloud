<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTransferTypeInNewRvsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
   

    public function up()
    {
        Schema::connection('mysql2')->table('new_rvs', function (Blueprint $table) {
            $table->integer('transfer_type')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mysql2')->table('new_rvs', function (Blueprint $table) {
            $table->dropColumn('transfer_type');
            
        });
    }
}
