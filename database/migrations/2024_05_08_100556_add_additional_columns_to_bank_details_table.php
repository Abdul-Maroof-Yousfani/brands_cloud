<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAdditionalColumnsToBankDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql2')->table('bank_detail', function (Blueprint $table) {
            $table->unsignedBigInteger('acc_id')->nullable();
            $table->string('account_title')->nullable();
            $table->string('account_no')->nullable();
            $table->string('iban_no')->nullable();
            $table->string('swift_code')->nullable();
            $table->string('bank_address')->nullable();
            $table->decimal('max_funded_facility', 10, 2)->nullable(); // Assuming max_funded_facility is a decimal field
            $table->decimal('max_non_funded_facility', 10, 2)->nullable(); // Assuming max_non_funded_facility is a decimal field

            // $table->foreign('acc_id')->references('id')->on('accounts')->onDelete('cascade');
            // Assuming 'accounts' is the table containing the 'id' referenced by 'acc_id'
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mysql2')->table('bank_detail', function (Blueprint $table) {
            // $table->dropForeign(['acc_id']);
            $table->dropColumn(['acc_id', 'account_title', 'account_no', 'iban_no', 'swift_code', 'bank_address', 'max_funded_facility', 'max_non_funded_facility']);
        });
    }
}