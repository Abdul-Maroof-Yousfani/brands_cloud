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
                        ->leftJoin("sales_order_data", "sales_order_data.item_id", "=", "subitem.id")
                        ->leftJoin("sales_order", "sales_order.id", "=", "sales_order_data.master_id")
                        ->select(
                            "sales_order_data.date",
                            "sales_order.buyers_id",
                            "subitem.sku_code",
                            "subitem.sku_code AS sku",
                            "subitem.product_barcode",
                            "subitem.product_name",
                            "subitem.brand_id",
                            "subitem.purchase_price",
                            "subitem.date",
                            "subitem.id",
                            DB::raw("SUM(sales_order_data.qty) AS qty"),
                            DB::raw("SUM(sales_order_data.amount) AS amount"),
                            DB::raw("SUM(sales_order_data.mrp_price) AS mrp_price")
                        )
                        ->when(isset($from) && isset($to), function($query) use ($from, $to) {
                            $query->whereBetween("sales_order.date", [$from, $to]);
                        })
                        ->when(isset($brand_id), function($query) use ($brand_id) {
                            $query->where("subitem.brand_id", $brand_id);
                        })
                        ->when(isset($store_id), function($query) use ($store_id) {
                            $query->where('sales_order.buyers_id', $store_id);
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
