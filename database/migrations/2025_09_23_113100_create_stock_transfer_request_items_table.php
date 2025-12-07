<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStockTransferRequestItemsTable extends Migration
{
    public function up()
    {
        Schema::create('stock_transfer_request_items', function (Blueprint $table) {
            $table->increments('id');
            
            // Foreign key to stock_transfer_requests
            $table->unsignedInteger('stock_transfer_request_id');
            $table->foreign('stock_transfer_request_id')
                  ->references('id')->on('stock_transfer_requests')
                  ->onDelete('cascade');

            // Item/product details
            $table->unsignedInteger('product_id');
            $table->string('sku')->nullable();
            $table->integer('quantity')->default(0);
            $table->decimal('unit_price', 15, 2)->default(0);
            $table->decimal('total_price', 15, 2)->default(0);

            // Warehouse information (ADD THESE FIELDS)
            $table->unsignedInteger('warehouse_from_id');
            $table->unsignedInteger('warehouse_to_id');
            $table->string('batch_code')->nullable();
            $table->text('remarks')->nullable();

            $table->timestamps();

            // Add foreign keys for warehouses if you have warehouses table
            // $table->foreign('warehouse_from_id')->references('id')->on('warehouses');
            // $table->foreign('warehouse_to_id')->references('id')->on('warehouses');
        });
    }

    public function down()
    {
        Schema::dropIfExists('stock_transfer_request_items');
    }
}