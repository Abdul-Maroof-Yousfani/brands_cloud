<?php

namespace App\Http\Controllers;

use App\Models\Sales_Order;
use DB;
use Illuminate\Http\Request;

class SalesReturnReportController extends Controller
{



 public function show_ba() {
        if(request()->ajax()) {

            $from = request()->from;
            $to = request()->to;
            $brand_id = request()->brand_id;
            $store_id = request()->store_id;
            $subitem_id = request()->subitem_id;
        
            $items = DB::connection("mysql2")
                        ->table("subitem")
                        ->join("retail_sale_order_return_details", "retail_sale_order_return_details.product_id", "=", "subitem.id")
                        ->join("retail_sale_order_returns", "retail_sale_order_returns.id", "=", "retail_sale_order_return_details.retail_sale_order_return_id")
                        ->select(
                            "retail_sale_order_returns.created_at",
                            "retail_sale_order_returns.distributor_id AS buyers_id",
                            "retail_sale_order_returns.user_id as ba_id",
                            "subitem.sku_code",
                            "subitem.sku_code AS sku",
                            "subitem.product_barcode",
                            "subitem.product_name",
                            "subitem.brand_id",
                            "subitem.purchase_price",
                            "subitem.sale_price",
                            "retail_sale_order_returns.created_at as date",
                            "subitem.id",
                            "subitem.tax as tax_amount",
                            DB::raw("SUM(retail_sale_order_return_details.quantity) AS qty"),
                            DB::raw("SUM(subitem.sale_price * retail_sale_order_return_details.quantity) AS amount"),
                            DB::raw("subitem.mrp_price AS mrp_price")
                        )
                        // ->when(isset($from) && isset($to), function($query) use ($from, $to) {
                        //     $query->whereBetween("retail_sale_order_returns.created_at", [$from, $to]);
                        // })

                        ->when(isset($from) && isset($to), function($query) use ($from, $to) {
                            $query->whereDate("retail_sale_order_returns.created_at", ">=", $from)
                                  ->whereDate("retail_sale_order_returns.created_at", "<=", $to);
                        })
                        ->when(isset($brand_id), function($query) use ($brand_id) {
                            $query->where("subitem.brand_id", $brand_id);
                        })
                        ->when(isset($store_id), function($query) use ($store_id) {
                            $query->where('retail_sale_order_returns.distributor_id', $store_id);
                        })
                        ->when(isset($subitem_id), function($query) use($subitem_id) {
                            $query->where("subitem.id", $subitem_id);
                        })
                        ->groupBy("subitem.id")
                        ->get();

            
            return view("Reports.Sales_Return.ba_sales_return_ajax", compact("items"));
        }

        return view("Reports.Sales_Return.ba_sales_return_report");
    }
    public function show(Request $request) {

        if($request->ajax()) {
            $so = $request->so;
            $from = $request->from;
            $to = $request->to;
            $sales_order = null;
            if(!empty($so)) {
                $sales_order = Sales_Order::select("id")->where("so_no", $so)->first();
            }
            $so_id = $sales_order ? $sales_order->id : "~";
            $sales_report_data = DB::connection("mysql2")->table("credit_note_data")
                ->join("credit_note", "credit_note.id", "=", "credit_note_data.master_id")
                ->join("subitem", "subitem.id", "=", "credit_note_data.item")
                ->join("category", "category.id", "=", "subitem.main_ic_id")
                ->join("brands", "subitem.brand_id", "=","brands.id")
                ->when($so, function ($q) use ($so_id) {
                    $q->where("credit_note.so_id", "like", "%{$so_id}%");
                })
                // ->when(isset($request->from) && isset($request->to), function($query) use ($request) {
                //     $query->whereBetween("credit_note_data.date", [$request->from, $request->to]);
                // })
                // ->whereBetween("credit_note_data.date", [$request->from, $request->to])
                ->groupBy("subitem.product_barcode")
                ->select(
                    "category.main_ic",
                    "credit_note.sales_tax",
                    "credit_note.sales_tax_further",
                    "brands.name",
                    "credit_note_data.voucher_no", 
                    "subitem.product_name", 
                    "subitem.product_barcode", 
                    'subitem.purchase_price AS cogs',
                    DB::raw("SUM(credit_note_data.qty) as qty"), 
                    DB::raw("SUM(credit_note_data.amount) as amount"),
                    DB::raw("SUM(credit_note_data.net_amount) as net_amount"),
                    DB::raw("SUM(credit_note_data.discount_amount) as discount_amount"),
                )
                ->get();

       
            return view('Reports.Sales_Return.sales_return_ajax', compact("sales_report_data"));
        }

        return view("Reports.Sales_Return.sales_return_report");
    }
}
