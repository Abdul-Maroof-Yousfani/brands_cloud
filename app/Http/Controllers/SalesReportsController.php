<?php

namespace App\Http\Controllers;

use App\Models\Sales_Order_Data;
use DB;
use Illuminate\Http\Request;

class SalesReportsController extends Controller
{
    public function show(Request $request) {
        if($request->ajax()) {
            $so = $request->so;
            $from = $request->from;
            $to = $request->to;
            $sales_order_datas = DB::connection("mysql2")->table("sales_order_data")
                ->join("subitem", "subitem.id", "=", "sales_order_data.item_id")
                ->join("category", "category.id", "=", "subitem.main_ic_id")
                ->join("brands", "brands.id", "=", "sales_order_data.brand_id")
                ->when($so, function ($q) use ($so) {
                    $q->where("so_no", "like", "%$so%");
                })
                ->whereBetween("sales_order_data.date", [$request->from, $request->to])
                ->groupBy("subitem.sku_code")
                ->select(
                    "category.main_ic",
                    "subitem.sku_code", 
                    "subitem.product_name", 
                    "brands.name", 
                    "subitem.product_barcode", 
                    "subitem.purchase_price as cogs",
                    "subitem.group_id",
                    DB::raw("SUM(sales_order_data.qty) as qty"), 
                    DB::raw("SUM(sales_order_data.sub_total) as sub_total"),
                    DB::raw("SUM(sales_order_data.discount_amount_2) as discount_amount_2"),
                    DB::raw("SUM(sales_order_data.discount_amount_1) as discount_amount_1"),
                    DB::raw("SUM(sales_order_data.amount) as amount"),
                    "sales_order_data.tax",
                    DB::raw("SUM(sales_order_data.tax_amount) as tax_amount"),
                ) // example to avoid MySQL strict mode error
                ->get();

            return view('Reports.Sales_Reports.sales_reports_ajax', compact("sales_order_datas"));
        }
        return view("Reports.Sales_Reports.sales_reports");
    }
}
