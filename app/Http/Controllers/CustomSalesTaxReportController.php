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
                                        ->join("sales_tax_invoice_data", "sales_tax_invoice_data.master_id", "=", "sales_tax_invoice.id")
                                        ->leftJoin("subitem", "subitem.id", "=", "sales_tax_invoice_data.item_id")
                                        ->leftJoin("brands", "brands.id", "=", "subitem.brand_id")
                                        ->leftJoin("customers", "customers.id", "=", "sales_tax_invoice.buyers_id")
                                        ->leftJoin("category", "category.id", "=", "subitem.main_ic_id")
                                        ->leftJoin("territories", "territories.id", "=", "customers.territory_id")
                                        ->leftJoin("sales_order", "sales_order.so_no", "=", "sales_tax_invoice.so_no")
                                        ->leftJoin("sales_order_data", "sales_order_data.id", "=", "sales_tax_invoice_data.so_data_id")
                                        ->select(
                                            "sales_tax_invoice.*",
                                            "sales_tax_invoice_data.qty",
                                            "sales_tax_invoice_data.rate",
                                            "sales_tax_invoice_data.tax",
                                            "sales_tax_invoice_data.tax_amount",
                                            DB::raw("(sales_tax_invoice_data.rate * sales_tax_invoice_data.qty * COALESCE(sales_order_data.discount_percent_1, 0)) / 100 AS discount_amount"),
                                            "sales_tax_invoice_data.amount AS total_amount",
                                            "sales_tax_invoice_data.gi_no",
                                            "sales_tax_invoice_data.date AS item_date",
                                            "subitem.product_name",
                                            "subitem.product_barcode",
                                            "subitem.mrp_price",
                                            "subitem.hs_code",
                                            "brands.name AS brand_name",
                                            "subitem.group_id AS group_id",
                                            "category.main_ic",
                                            "territories.name AS region_name",
                                            "customers.name AS customer_name",
                                            "customers.city",
                                            "customers.warehouse_from",
                                            "sales_order.sale_taxes_amount_rate"
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
                                        ->orderBy("sales_tax_invoice.gi_date", "desc")
                                        ->get();

            return view("Reports.Custom_Sales_Tax_Report.custom_sales_tax_report_ajax", compact("sales_report_data"));
        }

        return view("Reports.Custom_Sales_Tax_Report.custom_sales_tax_report");
    }
}
