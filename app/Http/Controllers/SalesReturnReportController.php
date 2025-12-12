<?php

namespace App\Http\Controllers;

use App\Models\Sales_Order;
use DB;
use Illuminate\Http\Request;

class SalesReturnReportController extends Controller
{

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
