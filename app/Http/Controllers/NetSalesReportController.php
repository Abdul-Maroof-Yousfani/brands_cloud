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
        $cogs = true;

        if($request->ajax()) {

            $customerSub = DB::connection("mysql2")
                ->table("sales_order")
                ->join("customers", "customers.id", "=", "sales_order.buyers_id")
                ->join("territories", "territories.id", "=", "customers.territory_id")
                ->select(
                    "sales_order.id as sales_order_id",
                    "customers.id as customer_id",
                    "customers.name as customer_name",
                    "customers.customer_code",
                    "territories.id as territory_id",
                    "territories.name as territory_name"
                );


                $returnSub = DB::connection("mysql2")
    ->table("credit_note_data")
    ->select(
        "item",
        DB::raw("SUM(qty) as sales_return_qty"),
        DB::raw("SUM(amount) as gross_return_amount")
    )
    ->groupBy("item");

    $net_sales_reports = DB::connection("mysql2")
    ->table("subitem")
    ->select(
        "subitem.product_name",
        "subitem.sku_code as sku",
        "subitem.product_barcode as barcode",
        "subitem.purchase_price as cog",
        "subitem.hs_code",

        "brands.id as brand_id",
        "brands.name as brand_name",

        "cust.customer_id",
        "cust.customer_name",
        "cust.customer_code",
        "cust.territory_id",
        "cust.territory_name",

        "product_type.type as product_type",
        "sales_order.warehouse_from",

        DB::raw("SUM(sales_order_data.qty) as qty"),
        DB::raw("SUM(sales_order_data.amount) as amount"),
        DB::raw("SUM(sales_order_data.amount) as net_amount"),
        DB::raw("SUM(sales_order_data.amount * (sales_order_data.tax / 100)) as tax_amount"),
        DB::raw("SUM(sales_order_data.amount * (sales_order_data.discount_percent_1 / 100)) as discount_amount"),
        DB::raw("SUM(sales_order_data.foc) as sale_foc"),

        DB::raw("COALESCE(sr.sales_return_qty, 0) as sales_return_qty"),
        DB::raw("COALESCE(sr.gross_return_amount, 0) as gross_return_amount")
    )

    ->join("brands", "subitem.brand_id", "=", "brands.id")
    ->join("product_type", "subitem.product_type_id", "=", "product_type.product_type_id")
    ->join("sales_order_data", "sales_order_data.item_id", "=", "subitem.id")
    ->join("sales_order", "sales_order.id", "=", "sales_order_data.master_id")

    // ✅ Sales return subquery
    ->leftJoinSub($returnSub, "sr", function ($join) {
        $join->on("sr.item", "=", "subitem.id");
    })

    // ✅ Customer subquery
    ->leftJoinSub($customerSub, "cust", function ($join) {
        $join->on("cust.sales_order_id", "=", "sales_order.id");
    })

    ->when(isset($from) && isset($to), function ($q) use ($from, $to) {
        $q->whereBetween("sales_order_data.date", [$from, $to]);
    })
    ->when($sku, fn ($q) => $q->where("subitem.sku_code", "like", "%{$sku}%"))
    ->when($brand_id, fn ($q) => $q->where("brands.id", $brand_id))
    ->when($customer_id, fn ($q) => $q->where("cust.customer_id", $customer_id))
    ->when($region_id, fn ($q) => $q->where("cust.territory_id", $region_id))
    ->when($warehouse_id, fn ($q) => $q->where("sales_order.warehouse_from", $warehouse_id))

    ->groupBy(
        "subitem.id",
        "cust.customer_id"
    )
    ->get();


         $returnSub = DB::connection("mysql2")
                            ->table("credit_note_data")
                            ->select(
                                "item",
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
                                "sales_order.warehouse_from",

                                DB::raw("SUM(sales_order_data.qty) AS qty"),
                                DB::raw("SUM(sales_order_data.amount) AS amount"),
                                DB::raw("SUM(sales_order_data.amount) AS net_amount"),
                                DB::raw("SUM(sales_order_data.amount * (sales_order_data.tax / 100)) AS tax_amount"),
                                DB::raw("SUM(sales_order_data.amount * (sales_order_data.discount_percent_1 / 100)) AS discount_amount"),
                                DB::raw("SUM(sales_order_data.foc) AS sale_foc"),

                                DB::raw("COALESCE(sr.sales_return_qty, 0) AS sales_return_qty"),
                                DB::raw("COALESCE(sr.gross_return_amount, 0) AS gross_return_amount")
                            )
                            ->join("brands", "subitem.brand_id", "=", "brands.id")
                            ->join("product_type", "subitem.product_type_id", "=", "product_type.product_type_id")
                            ->join("sales_order_data", "sales_order_data.item_id", "=", "subitem.id")
                            ->join("sales_order", "sales_order.id", "=", "sales_order_data.master_id")

                            // FIXED: Aggregated return data join
                            ->leftJoin(
                                DB::raw("(" . $returnSub . ") as sr"),
                                "sr.item",
                                "=",
                                "subitem.id"
                            )

                            ->join("customers", "sales_order.buyers_id", "=", "customers.id")
                            ->join("territories", "territories.id", "=", "customers.territory_id")

                            ->when(isset($from) && isset($to), function ($query) use ($from, $to) {
                                $query->whereBetween("sales_order_data.date", [$from, $to]);
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
                                $q->where("sales_order.warehouse_from", $warehouse_id);
                            })

                            ->groupBy(
                                "subitem.id",
                                "sales_order.buyers_id",
                            )
                            ->get();


            return view("Reports.net_sales_report.custom_sales_tax_report_ajax", compact("net_sales_reports", 'cogs'));
        }

        return view("Reports.net_sales_report.custom_sales_tax_report", compact("cogs"));
    }


    public function NetSalesExecutiveReport(Request $request) {
         $sku = $request->sku;
        $from = $request->from;
        $to = $request->to;
        $brand_id = $request->brand_id;
        $customer_id = $request->customer_id;
        $region_id = $request->region_id;
        $warehouse_id = $request->warehouse_id;
        $cogs = false;

        if($request->ajax()) {
         $returnSub = DB::connection("mysql2")
                            ->table("credit_note_data")
                            ->select(
                                "item",
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
                                "sales_order.warehouse_from",

                                DB::raw("SUM(sales_order_data.qty) AS qty"),
                                DB::raw("SUM(sales_order_data.amount) AS amount"),
                                DB::raw("SUM(sales_order_data.amount) AS net_amount"),
                                DB::raw("SUM(sales_order_data.amount * (sales_order_data.tax / 100)) AS tax_amount"),
                                DB::raw("SUM(sales_order_data.amount * (sales_order_data.discount_percent_1 / 100)) AS discount_amount"),
                                DB::raw("SUM(sales_order_data.foc) AS sale_foc"),

                                DB::raw("COALESCE(sr.sales_return_qty, 0) AS sales_return_qty"),
                                DB::raw("COALESCE(sr.gross_return_amount, 0) AS gross_return_amount")
                            )
                            ->join("brands", "subitem.brand_id", "=", "brands.id")
                            ->join("product_type", "subitem.product_type_id", "=", "product_type.product_type_id")
                            ->join("sales_order_data", "sales_order_data.item_id", "=", "subitem.id")
                            ->join("sales_order", "sales_order.id", "=", "sales_order_data.master_id")

                            // FIXED: Aggregated return data join
                            ->leftJoin(
                                DB::raw("(" . $returnSub . ") as sr"),
                                "sr.item",
                                "=",
                                "subitem.id"
                            )

                            ->join("customers", "sales_order.buyers_id", "=", "customers.id")
                            ->join("territories", "territories.id", "=", "customers.territory_id")

                            ->when(isset($from) && isset($to), function ($query) use ($from, $to) {
                                $query->whereBetween("sales_order_data.date", [$from, $to]);
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
                                $q->where("sales_order.warehouse_from", $warehouse_id);
                            })

                            ->groupBy("subitem.id")
                            ->get();


            return view("Reports.net_sales_report.custom_sales_tax_report_ajax", compact("net_sales_reports", 'cogs'));
        }

        return view("Reports.net_sales_report.custom_sales_tax_report", compact("cogs"));
    }
    
}
