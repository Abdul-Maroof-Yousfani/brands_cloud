<?php

namespace App\Http\Controllers;

use App\Models\Sales_Order_Data;
use DB;
use Illuminate\Http\Request;

class SalesReportsController extends Controller
{
    public function show(Request $request) {
        $m = $request->m;
        \App\Helpers\CommonHelper::companyDatabaseConnection($m);

        if($request->ajax()) {
            $so = $request->so;
            $from = $request->from;
            $to = $request->to;

            $query = DB::connection("mysql2")->table("sales_order_data")
                ->join("subitem", "subitem.id", "=", "sales_order_data.item_id")
                ->join("category", "category.id", "=", "subitem.main_ic_id")
                ->leftJoin("brands", "brands.id", "=", "subitem.brand_id")
                ->when($so, function ($q) use ($so) {
                    $q->where("so_no", "like", "%$so%");
                });

            if ($from && $to) {
                $query->whereBetween("sales_order_data.date", [$from, $to]);
            }

            // New Filters
            if ($request->filled('category_id')) $query->where('subitem.main_ic_id', $request->category_id);
            if ($request->filled('sub_category_id')) $query->where('subitem.sub_category_id', $request->sub_category_id);
            if ($request->filled('brand_id')) $query->where('subitem.brand_id', $request->brand_id);
            if ($request->filled('group_id')) $query->where('subitem.group_id', $request->group_id);
            if ($request->filled('classification_id')) $query->where('subitem.product_classification_id', $request->classification_id);
            if ($request->filled('type_id')) $query->where('subitem.product_type_id', $request->type_id);
            if ($request->filled('trend_id')) $query->where('subitem.product_trend_id', $request->trend_id);

            $sales_order_datas = $query->groupBy("subitem.sku_code")
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
                )
                ->get();

            return view('Reports.Sales_Reports.sales_reports_ajax', compact("sales_order_datas"));
        }

        $categories = DB::connection('mysql2')->table('category')->where('status', 1)->get();
        $sub_categories = DB::connection('mysql2')->table('sub_category')->where('status', 1)->get();
        $brands = DB::connection('mysql2')->table('brands')->where('status', 1)->orderBy('name')->get();
        $principal_groups = DB::connection('mysql2')->table('products_principal_group')->where('status', 1)->get();
        $groups = DB::connection('mysql2')->table('company_groups')->where('status', 1)->get();
        $classifications = DB::connection('mysql2')->table('product_classifications')->where('status', 1)->get();
        $types = DB::connection('mysql2')->table('product_type')->where('status', 1)->get();
        $trends = DB::connection('mysql2')->table('product_trends')->where('status', 1)->get();

        \App\Helpers\CommonHelper::reconnectMasterDatabase();

        return view("Reports.Sales_Reports.sales_reports", compact(
            'categories', 'sub_categories', 'brands', 'principal_groups', 
            'groups', 'classifications', 'types', 'trends'
        ));
    }
}
