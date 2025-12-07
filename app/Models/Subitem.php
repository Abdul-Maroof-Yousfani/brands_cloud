<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subitem extends Model{
	protected $connection = 'mysql2';
	protected $table = 'subitem';

	protected $fillable = [
        'main_ic_id','sub_category_id','item_code','brand_id','sub_ic','description','uom','acc_id','rate','pack_size','uom2', 
        'hs_code_id','reorder_level','time','date', 'brand_id','action','username','status','type','stockType','delete_username','open_qty','open_val','sku_code','itemType','sys_no', 'product_name', 'product_description', 'packing', 
        'product_barcode', 'group_id', 'product_classification_id', 'product_type_id', 
        'product_trend_id', 'purchase_price', 'sale_price', 'mrp_price', 'is_tax_apply', 
        'tax_type_id', 'tax_applied_on', 'tax_policy'.'tax', 'flat_discount', 'min_qty', 
        'max_qty','image','hs_code', 'locality', 'origin', 'color', 'product_status', 'is_barcode_scanning','hs_code'
    ];
	// protected $fillable = ['supplier_id','sub_ic','main_ic_id','acc_id','department_id','pack_size','kit_amount','tax_able','sales_tax_rate','time','date','action','username','status','trail_id','branch_id','type','no_test','uom','saleOutUnitQuantityPrice','allowDiscountUnitQuantity','completeBoxPrice','completeBoxDiscount','allowTestingQuantity','inventoryStockEveryTime','totalQuantityinOnePack','stockType','itemType'];
	protected $primaryKey = 'id';
	public $timestamps = false;

	public function uomData()
	{
		return $this->belongsTo(UOM::class,'uom','id');
	}

	public function scopeSelected($query)
    {
        return $query->select('id','product_name', 'product_barcode', 'sku_code','sys_no', 'sale_price', 'mrp_price');
    }

	public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
    public function stock() {
        return $this->hasOne(Stock::class,'sub_item_id');
    }
}