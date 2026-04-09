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
        $category_id = $request->category_id;
        $sub_category_id = $request->sub_category_id;
        $principal_group_id = $request->principal_group_id;
        $brand_id = $request->brand_id;
        $group_id = $request->group_id;
        $classification_id = $request->classification_id;
        $type_id = $request->type_id;
        $trend_id = $request->trend_id;

        if($request->ajax()) {
            $so = $request->so;
            $customer_id = $request->customer_id;
            $from = $request->from;
            $to = $request->to;
            $sales_order = null;
            if(!empty($so)) {
                $sales_order = Sales_Order::select("id")->where("so_no", $so)->first();
            }
            $so_id = $sales_order ? $sales_order->id : "~";
            $sales_report_data = DB::connection("mysql2")->table("credit_note_data")
                ->join("credit_note", "credit_note.id", "=", "credit_note_data.master_id")
                ->join("customers", "customers.id", "=", "credit_note.buyer_id")
                ->join("subitem", "subitem.id", "=", "credit_note_data.item")
                ->join("category", "category.id", "=", "subitem.main_ic_id")
                ->join("brands", "subitem.brand_id", "=","brands.id")
                ->when($so, function ($q) use ($so_id) {
                    $q->where("credit_note.so_id", "like", "%{$so_id}%");
                })
                ->when($customer_id, function ($q) use ($customer_id) {
                    $q->where("credit_note.buyer_id", $customer_id);
                })
                ->when($from && $to, function ($q) use ($from, $to) {
                    $q->whereBetween("credit_note_data.date", [$from, $to]);
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
                ->when($brand_id, function ($q) use ($brand_id) {
                    $q->where("subitem.brand_id", $brand_id);
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
                ->groupBy("subitem.product_barcode", "customers.name")
                ->select(
                    "customers.name as customer_name",
                    "category.main_ic",
                    "credit_note.sales_tax",
                    "credit_note.sales_tax_further",
                    "brands.name",
                    "credit_note_data.voucher_no", 
                    "subitem.product_name", 
                    "subitem.product_barcode", 
                    "subitem.sku_code", 
                    'subitem.purchase_price AS cogs',
                    DB::raw("SUM(credit_note_data.qty) as qty"), 
                    DB::raw("SUM(credit_note_data.amount) as amount"),
                    DB::raw("SUM(credit_note_data.net_amount) as net_amount"),
                    DB::raw("SUM(credit_note_data.discount_amount) as discount_amount"),
                )
                ->get();

       
            return view('Reports.Sales_Return.sales_return_ajax', compact("sales_report_data"));
        }

        $categories = DB::connection('mysql2')->table('category')->where('status', 1)->get();
        $sub_categories = DB::connection('mysql2')->table('sub_category')->where('status', 1)->get();
        $brands = DB::connection('mysql2')->table('brands')->where('status', 1)->orderBy('name')->get();
        $principal_groups = DB::connection('mysql2')->table('products_principal_group')->where('status', 1)->get();
        $groups = DB::connection('mysql2')->table('company_groups')->where('status', 1)->get();
        $classifications = DB::connection('mysql2')->table('product_classifications')->where('status', 1)->get();
        $types = DB::connection('mysql2')->table('product_type')->where('status', 1)->get();
        $trends = DB::connection('mysql2')->table('product_trends')->where('status', 1)->get();
        $customers = DB::connection("mysql2")->table("customers")->where('status', 1)->orderBy('name')->get();

        return view("Reports.Sales_Return.sales_return_report", compact(
            'customers', 'categories', 'sub_categories', 'brands', 'principal_groups', 
            'groups', 'classifications', 'types', 'trends'
        ));
    }
}
