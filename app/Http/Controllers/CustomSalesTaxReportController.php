<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;

class CustomSalesTaxReportController extends Controller
{
    public function show(Request $request) {
        $so = $request->so;
        $from = $request->from;
        $to = $request->to;
        $brand_id = $request->brand_id;
        $customer_id = $request->customer_id;
        $region_id = $request->region_id;
        $warehouse_id = $request->warehouse_id;

        if($request->ajax()) {
            $sales_report_data = DB::connection("mysql2")->table("sales_tax_invoice")
                                        
                                        ->leftJoin("sales_tax_invoice_data", "sales_tax_invoice_data.master_id", "=", "sales_tax_invoice.id")
                                        ->leftJoin("subitem", "subitem.id", "=", "sales_tax_invoice_data.item_id")
                                        ->leftJoin("brands", "brands.id", "=", "subitem.brand_id")
                                        ->leftJoin("customers", "customers.id", "=", "sales_tax_invoice.buyers_id")
                                        ->leftJoin("category", "category.id", "=", "subitem.main_ic_id")
                                        ->leftJoin("gst", "sales_tax_invoice.acc_id", "=", "gst.acc_id")
                                        ->join("territories", "territories.id", "=", "customers.territory_id")
                                        ->join(DB::raw("
                                            (SELECT so_no, SUM(amount) as amount, SUM(amount * (tax / 100)) AS tax_amount, SUM(amount) AS net_amount, SUM(amount * (discount_percent_1 / 100)) as discount_amount, SUM(sales_order_data.mrp_price) AS retail_value
                                            FROM sales_order_data
                                            GROUP BY so_no
                                            ) as sod
                                        "), "sod.so_no", "=", "sales_tax_invoice.so_no")
                                        ->groupBy("sales_tax_invoice.gi_no")
                                        ->select("*",
                                            DB::raw("SUM(sales_tax_invoice_data.qty) as qty"), 
                                            DB::raw("SUM(sales_tax_invoice.sales_tax_further) as sales_tax_further"),
                                            "brands.name AS brand_name",
                                            "subitem.group_id AS group_id"
                                        )
                                        ->when(isset($from) && isset($to), function($query) use($from, $to) {
                                            $query->whereBetween("sales_tax_invoice_data.date", [$from, $to]);
                                        })
                                         ->when($so, function ($q) use ($so) {
                                            $q->where("sales_tax_invoice.gi_no", "like", "%{$so}%");
                                        })
                                        ->when($brand_id, function($q) use ($brand_id) {
                                            $q->where("brands.id", $brand_id);
                                        })
                                        ->when($customer_id, function($q) use($customer_id) {
                                            $q->where("customers.id", $customer_id);
                                        })
                                        ->when($region_id, function($q) use($region_id) {
                                            $q->where("territories.id", $region_id);
                                        })
                                        ->when($warehouse_id, function($q) use($warehouse_id) {
                                            $q->where("customers.warehouse_from", $warehouse_id);
                                        })
                                        ->get();

            return view("Reports.Custom_Sales_Tax_Report.custom_sales_tax_report_ajax", compact("sales_report_data"));
        }

        return view("Reports.Custom_Sales_Tax_Report.custom_sales_tax_report");
    }
}
