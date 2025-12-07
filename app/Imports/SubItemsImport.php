<?php
namespace App\Imports;

use App\Models\Subitem;
use App\Models\Brand;
use App\Models\CompanyGroup;
use App\Models\ProductType;
use App\Models\ProductTrend;
use App\Models\ProductClassification;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Helpers\CommonHelper;
use DB;

class SubItemsImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        if (empty($row['sku_code']) && empty($row['product_name']) && empty($row['uom'])) {
           
            return null;
        }
        $code = CommonHelper::generateUniquePosNo('subitem', 'sys_no', 'ITEM');
        return new Subitem([
            'sub_category_id' => 1,  
            'item_code' => $code ,
            'sys_no' => $code ,
            'main_ic_id' => 1,  
            'sub_ic' => $row['sub_ic_id'] ?? "hello",  
            'sku_code' => $row['sku_code'] ?? "habcja",
            'uom' => $row['uom'] ? $this->getUomId($row['uom']) : 1,
            'hs_code_id' => $row['hs_code_id'] ?? null,
            'brand_id' => isset($row['brand_name']) ? $this->getBrandId($row['brand_name']) :  null,
            'product_name' => $row['product_name'] ?? null,
            'product_description' => $row['product_description'] ?? null,
            'packing' => $row['packing'] ?? null,
            'product_barcode' => $row['product_barcode'] ?? null,
            'group_id' => isset($row['group']) ? $this->getGroupId($row['group']) : null,
            'product_classification_id' => $row['product_classification'] ? $this->getProductClassificationId($row['product_classification']) : null, 
            'product_type_id' => $row['product_type'] ? $this->getProductTypeId($row['product_type']) : null, 
            'product_trend_id' => $row['product_trend'] ? $this->getProductTrendId($row['product_trend']) : null, 
            'purchase_price' => $row['purchase_price'] ?? null,
            'sale_price' => $row['sale_price'] ?? null,
            'mrp_price' => $row['mrp_price'] ?? null,
            'is_tax_apply' => isset($row['is_tax_apply']) ? 1 : 0,
            'tax_type_id' => $row['tax_type_id'] ?? null, 
            'tax_applied_on' => $row['tax_applied_on'] ?? null, 
            'tax_policy' => $row['tax_policy'] ?? null, 
            'tax' => $row['tax'] ?? null, 
            'flat_discount' => $row['flat_discount'] ?? null,
            'min_qty' => $row['min_qty'] ?? null,
            'max_qty' => $row['max_qty'] ?? null,
            'locality' => $row['locality'] ?? null,
            'origin' => $row['origin'] ?? null,
            'color' => $row['color'] ?? null,
            'product_status' => $row['product_status'] ?? null,
            'username' => auth()->user()->name ?? null,
            'date' => date('Y-m-d'),
        ]);
    }

    private function getBrandId($brandName)
    {
        $brand = Brand::where('name', $brandName)->first();
        return $brand ? $brand->id : null;
    }

    private function getGroupId($groupName)
    {
        $group = CompanyGroup::where('name', $groupName)->first();
        return $group ? $group->id : null;
    }
    private function getProductTypeId($productTypeName)
    {
        $productType = ProductType::where('type', $productTypeName)->first();
        return $productType ? $productType->product_type_id : null;
    }
    private function getProductTrendId($productTrendName)
    {
        $productTrend = ProductTrend::where('name', $productTrendName)->first();
        return $productTrend ? $productTrend->id : null;
    }
    private function getProductClassificationId($productClassificationName)
    {
        $product_classification = ProductClassification::where('name', $productClassificationName)->first();
        return $product_classification ? $product_classification->id : null;
    }

    private static function getUomId($uom)
    {
      $uom_id= DB::table('uom')->where('uom_name',$uom)->first();
      return $uom_id ? $uom_id->id : 1;
    }
}
