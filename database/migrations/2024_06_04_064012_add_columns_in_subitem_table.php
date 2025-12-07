<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsInSubitemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql2')->table('subitem', function (Blueprint $table) {
            $table->string('sys_no')->nullable()->default(NULL);
            $table->string('product_name')->nullable();
            $table->string('product_description')->nullable();
            $table->integer('packing')->nullable();
            $table->string('product_barcode')->nullable();
            $table->integer('group_id')->nullable();
            $table->integer('product_classification_id')->nullable();
            $table->integer('product_type_id')->nullable();
            $table->integer('product_trend_id')->nullable();
            $table->string('purchase_price')->nullable();
            $table->string('sale_price')->nullable();
            $table->string('mrp_price')->nullable();
            $table->boolean('is_tax_apply')->nullable();
            $table->integer('tax_type_id')->nullable();
            $table->string('tax_applied_on')->nullable();
            $table->string('tax_policy')->nullable();
            $table->integer('tax')->nullable();
            $table->integer('flat_discount')->nullable();
            $table->integer('min_qty')->nullable();
            $table->integer('max_qty')->nullable();
            $table->string('image')->nullable();
            $table->string('hs_code')->nullable();
            $table->string('locality')->nullable();
            $table->string('origin')->nullable();
            $table->string('color')->nullable();
            $table->string('product_status')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mysql2')->table('subitem', function (Blueprint $table) {
            $table->dropColumns(['sys_no','product_name','product_description','packing','product_barcode','group_id','product_classification_id','product_type_id','product_trend_id','purchase_price',
            'sale_price','mrp_price','is_tax_apply','tax_type_id','tax_applied_on','tax_policy','tax','flat_discount','min_qty','max_qty','image','hs_code','locality','origin','color','product_status']);
        });
    }
}
