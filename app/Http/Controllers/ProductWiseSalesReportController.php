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
            $brand_ids = request()->brand_id;
            $store_ids = request()->store_id;
            $ba_ids = request()->ba_id;
            $subitem_id = request()->subitem_id;
        
            $items = DB::connection("mysql2")
                        ->table("subitem")
                        ->leftJoin("retail_sale_order_details", "retail_sale_order_details.product_id", "=", "subitem.id")
                        ->leftJoin("retail_sale_orders", "retail_sale_orders.id", "=", "retail_sale_order_details.retail_sale_order_id")
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
                            "retail_sale_orders.created_at as date",
                            "subitem.id",
                            "subitem.tax as tax_amount",
                            DB::raw("SUM(retail_sale_order_details.qty) AS qty"),
                            DB::raw("SUM(subitem.sale_price * retail_sale_order_details.qty) AS amount"),
                            DB::raw("subitem.mrp_price AS mrp_price")
                        )
                        ->when(isset($from) && isset($to), function($query) use ($from, $to) {
                            $query->whereDate("retail_sale_orders.sale_order_date", ">=", $from)
                                  ->whereDate("retail_sale_orders.sale_order_date", "<=", $to);
                        })
                        ->when(!empty($brand_ids), function($query) use ($brand_ids) {
                            if (is_array($brand_ids)) {
                                $query->whereIn("subitem.brand_id", $brand_ids);
                            } else {
                                $query->where("subitem.brand_id", $brand_ids);
                            }
                        })
                        ->when(!empty($store_ids), function($query) use ($store_ids) {
                            if (is_array($store_ids)) {
                                $query->whereIn('retail_sale_orders.distributor_id', $store_ids);
                            } else {
                                $query->where('retail_sale_orders.distributor_id', $store_ids);
                            }
                        })
                        ->when(!empty($ba_ids), function($query) use ($ba_ids) {
                            if (is_array($ba_ids)) {
                                $query->whereIn('retail_sale_orders.user_id', $ba_ids);
                            } else {
                                $query->where('retail_sale_orders.user_id', $ba_ids);
                            }
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
