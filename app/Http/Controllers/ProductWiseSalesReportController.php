<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductWiseSalesReportController extends Controller
{
    public function show() {
        if(request()->ajax()) {

            $from = request()->from;
            $to = request()->to;
            $brand_id = request()->brand_id;
            $store_id = request()->store_id;
            $subitem_id = request()->subitem_id;
        
            $items = DB::connection("mysql2")
                        ->table("subitem")
                        ->join("retail_sale_order_details", "retail_sale_order_details.product_id", "=", "subitem.id")
                        ->join("retail_sale_orders", "retail_sale_orders.id", "=", "retail_sale_order_details.retail_sale_order_id")
                        ->select(
                            "retail_sale_orders.sale_order_date",
                            "retail_sale_orders.distributor_id AS buyers_id",
                            "retail_sale_orders.user_id as ba_id",
                            "subitem.sku_code",
                            "subitem.sku_code AS sku",
                            "subitem.product_barcode",
                            "subitem.product_name",
                            "subitem.brand_id",
                            "subitem.purchase_price",
                            "subitem.sale_price",
                            "subitem.date",
                            "subitem.id",
                            DB::raw("SUM(retail_sale_order_details.qty) AS qty"),
                            DB::raw("SUM(subitem.sale_price * retail_sale_order_details.qty) AS amount"),
                            DB::raw("SUM(subitem.mrp_price) AS mrp_price")
                        )
                        ->when(isset($from) && isset($to), function($query) use ($from, $to) {
                            $query->whereBetween("retail_sale_orders.sale_order_date", [$from, $to]);
                        })
                        ->when(isset($brand_id), function($query) use ($brand_id) {
                            $query->where("subitem.brand_id", $brand_id);
                        })
                        ->when(isset($store_id), function($query) use ($store_id) {
                            $query->where('retail_sale_orders.buyers_id', $store_id);
                        })
                        ->when(isset($subitem_id), function($query) use($subitem_id) {
                            $query->where("subitem.id", $subitem_id);
                        })
                        ->groupBy("subitem.id")
                        ->get();

            
            return view("Reports.Product_Wise_Sales_Report.product_wise_sales_report_ajax", compact("items"));
        }

        return view("Reports.Product_Wise_Sales_Report.product_wise_sales_report");
    }
}
