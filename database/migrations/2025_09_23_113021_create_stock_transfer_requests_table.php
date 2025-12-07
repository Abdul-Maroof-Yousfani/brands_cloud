<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStockTransferRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_transfer_requests', function (Blueprint $table) {
            $table->increments('id');
            $table->string('tr_no')->unique(); // Transfer Request Number
            $table->date('tr_date');
            $table->text('description')->nullable();
            $table->integer('status')->default(0); // 0=Pending, 1=Approved, 2=Rejected
            $table->string('username');
             $table->integer('user_id');
            $table->decimal('total_amount', 15, 2)->default(0);
            $table->text('rejection_reason')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->string('approved_by')->nullable();
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
        Schema::dropIfExists('stock_transfer_requests');
    }
}
