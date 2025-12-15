<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;

class SaleReturnJournalReportController extends Controller
{
    public function show(Request $request) {

        if($request->ajax()) {
            $so = $request->so;
            $from = $request->from;
            $to = $request->to;
            $sales_order = null;
            // if(!empty($so)) {
                // $sales_order = Sales_Order::select("id")->where("so_no", $so)->first();
            // }
            // $so_id = $sales_order ? $sales_order->id : "~";
            $sales_report_data = DB::connection('mysql2')->table("subitem")
                                                                ->join("credit_note_data", "credit_note_data.item", "=", "subitem.id")
                                                                ->join("credit_note", "credit_note.id", "=", "credit_note_data.master_id")
                                                                ->join("customers", "customers.id", "credit_note.buyer_id")
                                                                ->join("brands", "subitem.brand_id", "=","brands.id")
                                                                ->join("sales_order_data", "sales_order_data.id", "=", "credit_note_data.so_data_id")
                                                                ->select(
                                                                    DB::raw("SUM(sales_order_data.discount_percent_1) AS discount_percent_1"),
                                                                    DB::raw("SUM(sales_order_data.discount_amount_1) AS discount_amount"),
                                                                    DB::raw("SUM(sales_order_data.discount_amount_2) AS second_discount_amount"),
                                                                    DB::raw("SUM(sales_order_data.tax_amount) AS tax_amount"),
                                                                    DB::raw("SUM(sales_order_data.amount) AS amount"),
                                                                    "credit_note.cr_no",
                                                                    "credit_note_data.qty AS qty",
                                                                    "customers.name AS customer_name",
                                                                    "subitem.product_name AS product_name",
                                                                    "subitem.packing",
                                                                    "subitem.sale_price",
                                                                    "subitem.id",
                                                                    "subitem.hs_code AS hs_code", 
                                                                    "brands.name AS brand_name",
                                                                )
                                                                ->groupBy(
                                                                    "subitem.id",
                                                                    "subitem.product_name",
                                                                    "subitem.packing",
                                                                    "subitem.sale_price",
                                                                    "subitem.hs_code",
                                                                    "brands.name"
                                                                )
                                                                ->get();
               
            // $sales_report_data = DB::connection("mysql2")->table("credit_note_data")
            //     ->join("credit_note", "credit_note.id", "=", "credit_note_data.master_id")
            //     ->join("subitem", "subitem.id", "=", "credit_note_data.item")
            //     ->join("category", "category.id", "=", "subitem.main_ic_id")
            //     ->join("customers", "customers.id", "=", "credit_note.buyer_id")
            //     ->join("brands", "subitem.brand_id", "=","brands.id")
            //     // ->when($so, function ($q) use ($so_id) {
            //     //     $q->where("credit_note.so_id", "like", "%{$so_id}%");
            //     // })
         
            //     ->select(
            //         "category.main_ic",

            //         // In Usage
            //         "credit_note.cr_no",
            //         "credit_note_data.qty AS qty",
            //         "customers.name AS customer_name",
            //         "subitem.product_name AS product_name",
            //         "subitem.packing",
            //         "subitem.sale_price",
            //         "subitem.id",
            //         "subitem.hs_code AS hs_code", 
            //         "brands.name AS brand_name",
            //         // In Usage


            //         "credit_note.sales_tax",
            //         "credit_note.sales_tax_further",
            //         "credit_note_data.voucher_no", 
            //         "subitem.product_barcode", 
            //         'subitem.purchase_price AS cogs',
            //         DB::raw("SUM(credit_note_data.qty) as qty"), 
            //         DB::raw("SUM(credit_note_data.amount) as amount"),
            //         DB::raw("SUM(credit_note_data.net_amount) as net_amount"),
            //         DB::raw("SUM(credit_note_data.discount_amount) as discount_amount"),
            //     )
            //     ->groupBy("subitem.id")
            //     ->get();

       
            return view('Reports.Sales_Return.Journal.sales_return_ajax', compact("sales_report_data"));
        }

        return view("Reports.Sales_Return.Journal.sales_return_journal");
    }
}
