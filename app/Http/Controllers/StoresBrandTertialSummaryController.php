<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StoresBrandTertialSummaryController extends Controller
{
    public function show() {
        $brands = DB::connection("mysql2")->table("brands")->get();

        if(request()->ajax()) {

            $from = request()->from;
            $to = request()->to;
            $customer_id = request()->customer_id;
            $region_id = request()->region_id;

            $stocks = [];

            $stores = DB::connection('mysql2')->table('customers')
    ->leftJoin('stock as s', function ($join) {
        $join->on('s.customer_id', '=', 'customers.id')
             ->where('s.status', 1);
        // If you want to filter by date range, add it here:
        // ->whereBetween('s.created_date', [$from_date, $to_date]);
    })
    ->leftJoin('subitem as si', 's.sub_item_id', '=', 'si.id')
    ->leftJoin('brands as b', 'si.brand_id', '=', 'b.id')
    ->select(
        "s.voucher_date",
        'b.id as brand_id', // Note: this will be NULL if no stock
        'customers.id as customer_id',
        'customers.territory_id',
        'customers.customer_code',
        'customers.name',
        DB::raw('SUM(CASE WHEN s.voucher_type IN (1,4,6,10,11) AND s.transfer_status != 1 THEN s.qty ELSE 0 END) AS in_stock'),
        DB::raw('SUM(CASE WHEN s.voucher_type IN (2,5,3,9) THEN s.qty ELSE 0 END) AS out_stock')
    )
    ->when(isset($from) && isset($to), function($query) use ($from, $to) {
        $query->whereBetween("s.voucher_date", [$from, $to]);;
    }) 
    ->when(isset($customer_id), function($query) use ($customer_id) {
        $query->where("customers.id", $customer_id);
    })
    ->when(isset($region_id), function($query)use ($region_id) {
        $query->where("customers.territory_id", "=", $region_id);
    })
    ->groupBy(
        'customers.id',// Needed because it's in select
    )
    ->get();


            foreach($stores as $store) {
                $stockQty = (float)$store->in_stock - (float)$store->out_stock;
                $stocks[$store->customer_id][$store->brand_id] = abs($stockQty);
            }



            // dd($stores);

            // $stores = DB::connection("mysql2")
            //                 ->table("customers")
            //                 ->join("stock", "stock.customer_id", "=", "customers.id")
            //                 ->groupBy("customers.id")
            //                 ->get();
        
            
            
            return view("Reports.StoresBrandTertiary.stores_brand_tertiary_ajax", compact("stores", "stocks", "brands"));
        }

        return view("Reports.StoresBrandTertiary.stores_brand_tertiary", compact("brands"));
    }
}
