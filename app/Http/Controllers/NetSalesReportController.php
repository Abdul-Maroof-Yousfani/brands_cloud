<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NetSalesReportController extends Controller
{
    public function show(Request $request) {
        $sku = $request->sku;
        $from = $request->from;
        $to = $request->to;
        $brand_id = $request->brand_id;
        $customer_id = $request->customer_id;
        $region_id = $request->region_id;
        $warehouse_id = $request->warehouse_id;
        
        $category_id = $request->category_id;
        $sub_category_id = $request->sub_category_id;
        $principal_group_id = $request->principal_group_id;
        $group_id = $request->group_id;
        $classification_id = $request->classification_id;
        $type_id = $request->type_id;
        $trend_id = $request->trend_id;

        $cogs = true;

        if($request->ajax()) {
         $returnSub = DB::connection("mysql2")
                            ->table("credit_note_data")
                            ->join("credit_note", "credit_note.id", "=", "credit_note_data.master_id")
                            ->select(
                                "item",
                                "credit_note.buyer_id",
                                DB::raw("SUM(qty) as sales_return_qty"),
                                DB::raw("SUM(amount) as gross_return_amount")
                            )
                            ->groupBy("item")
                            ->toSql();

                        $net_sales_reports = DB::connection("mysql2")
                            ->table("subitem")
                            ->select(
                                "subitem.product_name AS product_name",
                                "brands.name AS brand_name",
                                "brands.id",
                                "subitem.sku_code AS sku",
                                "subitem.product_barcode AS barcode",
                                "subitem.purchase_price AS cog",
                                "subitem.hs_code",
                                "customers.name AS customer_name",
                                "customers.customer_code AS customer_code",
                                "customers.id",
                                "product_type.type AS product_type",
                                "territories.name AS territory_name",
                                "territories.id",
                                'sales_tax_invoice.buyers_id',
                                "sales_tax_invoice.gi_no as invoice_no",
                                "sales_tax_invoice.gi_date as invoice_date",
                                DB::raw("DATE_FORMAT(sales_tax_invoice.gi_date, '%M') as month_name"),

                                DB::raw("SUM(sales_tax_invoice_data.qty) AS qty"),
                                DB::raw("SUM(sales_tax_invoice_data.rate * sales_tax_invoice_data.qty) AS gross_sales_amount"),
                                DB::raw("AVG(sales_tax_invoice_data.rate) AS rate"),
                                DB::raw("AVG(sales_tax_invoice_data.tax) AS tax_percent"),
                                DB::raw("SUM(sales_tax_invoice_data.amount) AS amount"),
                                DB::raw("SUM(sales_tax_invoice_data.amount) AS net_amount"),
                                DB::raw("SUM(sales_tax_invoice_data.tax_amount) AS tax_amount"),
                                DB::raw("SUM((sales_tax_invoice_data.rate * sales_tax_invoice_data.qty * COALESCE(sales_order_data.discount_percent_1, 0)) / 100) AS discount_amount"),
                                DB::raw("MAX(subitem.mrp_price) AS mrp_price"),

                                DB::raw("COALESCE(sr.sales_return_qty, 0) AS sales_return_qty"),
                                DB::raw("COALESCE(sr.gross_return_amount, 0) AS gross_return_amount")
                            )
                            ->join("brands", "subitem.brand_id", "=", "brands.id")
                            ->join("product_type", "subitem.product_type_id", "=", "product_type.product_type_id")
                            ->join("sales_tax_invoice_data", "sales_tax_invoice_data.item_id", "=", "subitem.id")
                            ->join("sales_tax_invoice", "sales_tax_invoice.id", "=", "sales_tax_invoice_data.master_id")
                            ->leftJoin("sales_order_data", "sales_order_data.id", "=", "sales_tax_invoice_data.so_data_id")

                            // FIXED: Aggregated return data join
                            ->leftJoin(
                                DB::raw("(" . $returnSub . ") as sr"),
                                function ($join)  {
                                    $join->on("sr.item", "=", "subitem.id")
                                        ->on('sr.buyer_id', "=", "sales_tax_invoice.buyers_id");
                                }
                            )

                            ->join("customers", "sales_tax_invoice.buyers_id", "=", "customers.id")
                            ->join("territories", "territories.id", "=", "customers.territory_id")

                            ->when(isset($from) && isset($to), function ($query) use ($from, $to) {
                                $query->whereBetween("sales_tax_invoice_data.date", [$from, $to]);
                            })
                            ->when($sku, function ($q) use ($sku) {
                                $q->where("subitem.sku_code", "like", "%{$sku}%");
                            })
                            ->when($brand_id, function ($q) use ($brand_id) {
                                $q->where("brands.id", $brand_id);
                            })
                            ->when($customer_id, function ($q) use ($customer_id) {
                                $q->where("customers.id", $customer_id);
                            })
                            ->when($region_id, function ($q) use ($region_id) {
                                $q->where("territories.id", $region_id);
                            })
                            ->when($warehouse_id, function ($q) use ($warehouse_id) {
                                $q->where("sales_tax_invoice_data.warehouse_id", $warehouse_id);
                            })
                            
                            ->when($category_id, function ($q) use ($category_id) {
                                $q->where("subitem.main_ic_id", $category_id);
                            })
                            ->when($sub_category_id, function ($q) use ($sub_category_id) {
                                $q->where("subitem.sub_category_id", $sub_category_id);
                            })
                            ->when($principal_group_id, function ($q) use ($principal_group_id) {
                                $q->where("subitem.principal_group_id", $principal_group_id);
                            })
                            ->when($group_id, function ($q) use ($group_id) {
                                $q->where("subitem.group_id", $group_id);
                            })
                            ->when($classification_id, function ($q) use ($classification_id) {
                                $q->where("subitem.product_classification_id", $classification_id);
                            })
                            ->when($type_id, function ($q) use ($type_id) {
                                $q->where("subitem.product_type_id", $type_id);
                            })
                            ->when($trend_id, function ($q) use ($trend_id) {
                                $q->where("subitem.product_trend_id", $trend_id);
                            })

                            ->groupBy(
                                "subitem.id",
                                "sales_tax_invoice.buyers_id",
                                "sales_tax_invoice.gi_no"
                            )
                            ->get();


            return view("Reports.net_sales_report.custom_sales_tax_report_ajax", compact("net_sales_reports", 'cogs'));
        }

        $categories = DB::connection('mysql2')->table('category')->where('status', 1)->get();
        $sub_categories = DB::connection('mysql2')->table('sub_category')->where('status', 1)->get();
        $brands = DB::connection('mysql2')->table('brands')->where('status', 1)->orderBy('name')->get();
        $principal_groups = DB::connection('mysql2')->table('products_principal_group')->where('status', 1)->get();
        $groups = DB::connection('mysql2')->table('company_groups')->where('status', 1)->get();
        $classifications = DB::connection('mysql2')->table('product_classifications')->where('status', 1)->get();
        $types = DB::connection('mysql2')->table('product_type')->where('status', 1)->get();
        $trends = DB::connection('mysql2')->table('product_trends')->where('status', 1)->get();

        return view("Reports.net_sales_report.custom_sales_tax_report", compact('cogs', 'categories', 'sub_categories', 'brands', 'principal_groups', 'groups', 'classifications', 'types', 'trends'));
    }


    public function NetSalesExecutiveReport(Request $request) {
        $sku = $request->sku;
        $from = $request->from;
        $to = $request->to;
        $brand_id = $request->brand_id;
        $customer_id = $request->customer_id;
        $region_id = $request->region_id;
        $warehouse_id = $request->warehouse_id;

        $category_id = $request->category_id;
        $sub_category_id = $request->sub_category_id;
        $principal_group_id = $request->principal_group_id;
        $group_id = $request->group_id;
        $classification_id = $request->classification_id;
        $type_id = $request->type_id;
        $trend_id = $request->trend_id;

        $cogs = true;

        if($request->ajax()) {
         $returnSub = DB::connection("mysql2")
                            ->table("credit_note_data")
                            ->join("credit_note", "credit_note.id", "=", "credit_note_data.master_id")
                            ->select(
                                "item",
                                "credit_note.buyer_id",
                                DB::raw("SUM(qty) as sales_return_qty"),
                                DB::raw("SUM(amount) as gross_return_amount")
                            )
                            ->groupBy("item")
                            ->toSql();

                        $net_sales_reports = DB::connection("mysql2")
                            ->table("subitem")
                            ->select(
                                "subitem.product_name AS product_name",
                                "brands.name AS brand_name",
                                "brands.id",
                                "subitem.sku_code AS sku",
                                "subitem.product_barcode AS barcode",
                                "subitem.purchase_price AS cog",
                                "subitem.hs_code",
                                "customers.name AS customer_name",
                                "customers.customer_code AS customer_code",
                                "customers.id",
                                "product_type.type AS product_type",
                                "territories.name AS territory_name",
                                "territories.id",
                                'sales_tax_invoice.buyers_id',
                                "sales_tax_invoice.gi_no as invoice_no",
                                "sales_tax_invoice.gi_date as invoice_date",
                                DB::raw("DATE_FORMAT(sales_tax_invoice.gi_date, '%M') as month_name"),

                                DB::raw("SUM(sales_tax_invoice_data.qty) AS qty"),
                                DB::raw("SUM(sales_tax_invoice_data.rate * sales_tax_invoice_data.qty) AS gross_sales_amount"),
                                DB::raw("AVG(sales_tax_invoice_data.rate) AS rate"),
                                DB::raw("AVG(sales_tax_invoice_data.tax) AS tax_percent"),
                                DB::raw("SUM(sales_tax_invoice_data.amount) AS amount"),
                                DB::raw("SUM(sales_tax_invoice_data.amount) AS net_amount"),
                                DB::raw("SUM(sales_tax_invoice_data.amount * (sales_tax_invoice_data.tax / 100)) AS tax_amount"),
                                DB::raw("SUM((sales_tax_invoice_data.rate * sales_tax_invoice_data.qty * COALESCE(sales_order_data.discount_percent_1, 0)) / 100) AS discount_amount"),
                                DB::raw("MAX(subitem.mrp_price) AS mrp_price"),

                                DB::raw("COALESCE(sr.sales_return_qty, 0) AS sales_return_qty"),
                                DB::raw("COALESCE(sr.gross_return_amount, 0) AS gross_return_amount")
                            )
                            ->join("brands", "subitem.brand_id", "=", "brands.id")
                            ->join("product_type", "subitem.product_type_id", "=", "product_type.product_type_id")
                            ->join("sales_tax_invoice_data", "sales_tax_invoice_data.item_id", "=", "subitem.id")
                            ->join("sales_tax_invoice", "sales_tax_invoice.id", "=", "sales_tax_invoice_data.master_id")
                            ->leftJoin("sales_order_data", "sales_order_data.id", "=", "sales_tax_invoice_data.so_data_id")

                            // FIXED: Aggregated return data join
                            ->leftJoin(
                                DB::raw("(" . $returnSub . ") as sr"),
                                function ($join)  {
                                    $join->on("sr.item", "=", "subitem.id")
                                        ->on('sr.buyer_id', "=", "sales_tax_invoice.buyers_id");
                                }
                            )

                            ->join("customers", "sales_tax_invoice.buyers_id", "=", "customers.id")
                            ->join("territories", "territories.id", "=", "customers.territory_id")

                            ->when(isset($from) && isset($to), function ($query) use ($from, $to) {
                                $query->whereBetween("sales_tax_invoice_data.date", [$from, $to]);
                            })
                            ->when($sku, function ($q) use ($sku) {
                                $q->where("subitem.sku_code", "like", "%{$sku}%");
                            })
                            ->when($brand_id, function ($q) use ($brand_id) {
                                $q->where("brands.id", $brand_id);
                            })
                            ->when($customer_id, function ($q) use ($customer_id) {
                                $q->where("customers.id", $customer_id);
                            })
                            ->when($region_id, function ($q) use ($region_id) {
                                $q->where("territories.id", $region_id);
                            })
                            ->when($warehouse_id, function ($q) use ($warehouse_id) {
                                $q->where("sales_tax_invoice_data.warehouse_id", $warehouse_id);
                            })

                            ->when($category_id, function ($q) use ($category_id) {
                                $q->where("subitem.main_ic_id", $category_id);
                            })
                            ->when($sub_category_id, function ($q) use ($sub_category_id) {
                                $q->where("subitem.sub_category_id", $sub_category_id);
                            })
                            ->when($principal_group_id, function ($q) use ($principal_group_id) {
                                $q->where("subitem.principal_group_id", $principal_group_id);
                            })
                            ->when($group_id, function ($q) use ($group_id) {
                                $q->where("subitem.group_id", $group_id);
                            })
                            ->when($classification_id, function ($q) use ($classification_id) {
                                $q->where("subitem.product_classification_id", $classification_id);
                            })
                            ->when($type_id, function ($q) use ($type_id) {
                                $q->where("subitem.product_type_id", $type_id);
                            })
                            ->when($trend_id, function ($q) use ($trend_id) {
                                $q->where("subitem.product_trend_id", $trend_id);
                            })

                            ->groupBy(
                                "subitem.id",
                                "sales_tax_invoice.buyers_id",
                                "sales_tax_invoice.gi_no"
                            )
                            ->get();


            return view("Reports.net_sales_report.custom_sales_tax_report_ajax_1", compact("net_sales_reports", 'cogs'));
        }

        $categories = DB::connection('mysql2')->table('category')->where('status', 1)->get();
        $sub_categories = DB::connection('mysql2')->table('sub_category')->where('status', 1)->get();
        $brands = DB::connection('mysql2')->table('brands')->where('status', 1)->orderBy('name')->get();
        $principal_groups = DB::connection('mysql2')->table('products_principal_group')->where('status', 1)->get();
        $groups = DB::connection('mysql2')->table('company_groups')->where('status', 1)->get();
        $classifications = DB::connection('mysql2')->table('product_classifications')->where('status', 1)->get();
        $types = DB::connection('mysql2')->table('product_type')->where('status', 1)->get();
        $trends = DB::connection('mysql2')->table('product_trends')->where('status', 1)->get();

        return view("Reports.net_sales_report.custom_sales_tax_report_1", compact('cogs', 'categories', 'sub_categories', 'brands', 'principal_groups', 'groups', 'classifications', 'types', 'trends'));
    }

    
}
