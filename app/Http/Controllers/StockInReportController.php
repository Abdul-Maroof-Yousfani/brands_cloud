<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StockInReportController extends Controller
{
     public function index(Request $request) {



  $from_date = $request->from ?? date('Y-m-d');
    $to_date = $request->to ?? date('Y-m-d');
    $warehouse_ids = (array) $request->input('warehouse_id', []);
    $product_id = $request->product_id ?? null;
    $brand_ids = (array) $request->input('brand_id', []);
    $territory_id = $request->territory_id ?? null;

    $user = Auth::user();
    $isUser = $user && $user->acc_type == 'user';

    if ($isUser) {
        $territory_ids = json_decode($user->territory_id, true);
        if (!is_array($territory_ids)) {
            $territory_ids = [$user->territory_id];
        }

        $warehouseList = DB::connection('mysql2')->table('stock')
            ->whereIn('territory', $territory_ids)
            ->where('status', 1)
            ->pluck('warehouse_id');

        $warehouses = DB::connection('mysql2')->table('warehouse')
            ->whereIn('id', $warehouseList)
            ->where('status', 1)
            ->get();

        $subitem_ids = DB::connection('mysql2')->table('stock')
            ->whereIn('territory', $territory_ids)
            ->where('status', 1)
            ->pluck('sub_item_id');

        $products = DB::connection('mysql2')->table('subitem')
            ->whereIn('id', $subitem_ids)
            ->where('status', 1)
            ->get(['id', 'product_name']);

        $brandList = DB::connection('mysql2')->table('subitem')
            ->whereIn('id', $subitem_ids)
            ->whereNotNull('brand_id')
            ->pluck('brand_id');

        $brands = DB::connection('mysql2')->table('brands')
            ->whereIn('id', $brandList)
            ->where('status', 1)
            ->get(['id', 'name']);

        $territories = DB::connection('mysql2')->table('territories')
            ->whereIn('id', $territory_ids)
            ->where('status', 1)
            ->get(['id', 'name']);
    } else {
        $warehouses = DB::connection('mysql2')->table('warehouse')->where('status', 1)->get();
        $products = DB::connection('mysql2')->table('subitem')->where('status', 1)->get(['id', 'product_name']);
        $brands = DB::connection('mysql2')->table('brands')->where('status', 1)->get(['id', 'name']);
        $territories = DB::connection('mysql2')->table('territories')->where('status', 1)->get(['id', 'name']);
    }

    $defaultProducts = DB::connection('mysql2')
        ->table('subitem')
        ->where('status', 1)
        ->limit(5)
        ->get(['id', 'product_name']);

   if ($request->ajax()) {
    
  $transitSub = DB::connection('mysql2')->table('stock_transfers_transit')
    ->select(
        'product_id',
        'warehouse_to_id',
        DB::raw('SUM(quantity) as transit_stock')
    )
    ->where('tr_status', 1)
    ->groupBy('product_id', 'warehouse_to_id');

$rawQuery = $transitSub->toSql(); // ye SQL string banega
$bindings = $transitSub->getBindings(); // bindings

$query = DB::connection('mysql2')->table('stock as s')
    ->join('subitem as si', 's.sub_item_id', '=', 'si.id')
    ->leftJoin('category as c', 'si.main_ic_id', '=', 'c.id')
    ->leftJoin('warehouse as w', 's.warehouse_id', '=', 'w.id')
    ->leftJoin('brands as b', 'si.brand_id', '=', 'b.id')
    ->leftJoin(DB::raw("($rawQuery) as st"), function($join) {
        $join->on('si.id', '=', 'st.product_id')
             ->on('w.id', '=', 'st.warehouse_to_id');
    })
    ->addBinding($bindings, 'join')
    ->select(
        'si.id as product_id',
        'si.sku_code',
        'si.product_name',
        'si.product_barcode as barcode',
        'si.type as item_type',
        'si.pack_size as packing',
        'b.name as brand',
        'w.id as warehouse_id',
        'w.name as warehouse_name',
        DB::raw('SUM(CASE WHEN s.voucher_type IN (1,4,6,10,11) AND s.transfer_status != 1 THEN s.qty ELSE 0 END) AS in_stock'),
        DB::raw('SUM(CASE WHEN s.voucher_type IN (2,5,3,9) THEN s.qty ELSE 0 END) AS out_stock'),
        DB::raw('IFNULL(st.transit_stock,0) as transit_stock')
    )
    ->where('s.status', 1)
    ->whereBetween('s.created_date', [$from_date, $to_date])
    ->groupBy('si.id','w.id');


    if (!empty($warehouse_ids)) {
        $query->whereIn('s.warehouse_id', $warehouse_ids);
    } elseif ($territory_id) {
        $territoryWarehouseIds = DB::connection('mysql2')->table('stock')
            ->where('territory', $territory_id)
            ->where('status', 1)
            ->distinct()
            ->pluck('warehouse_id');

        if ($territoryWarehouseIds->isNotEmpty()) {
            $query->whereIn('s.warehouse_id', $territoryWarehouseIds);
        } else {
            $query->whereRaw('0=1');
        }
    }

    if ($product_id) {
        $query->where('s.sub_item_id', $product_id);
    }

    if (!empty($brand_ids)) {
        $query->whereIn('si.brand_id', $brand_ids);
    }

    // ✅ Group only by product and warehouse (not transit_stock)
    $rawStock = $query->groupBy('si.id', 'w.id')->get();

    $stocks = [];
    $warehouseMap = [];

    foreach ($rawStock as $stock) {
        $key = $stock->product_id;

        if (!isset($stocks[$key])) {
            $stocks[$key] = [
                'sku_code' => $stock->sku_code,
                'product_name' => $stock->product_name,
                'barcode' => $stock->barcode,
                'item_type' => $stock->item_type,
                'brand' => $stock->brand,
                'packing' => $stock->packing,
                'transit_stock' => $stock->transit_stock
            ];
        }

        $stockQty = (float)$stock->in_stock;
        $stocks[$key][$stock->warehouse_name] = abs($stockQty);
        $warehouseMap[$stock->warehouse_id] = $stock->warehouse_name;
    }

    return view('Reports.Stock_Report.stock_report_ajax', [
        'stocks' => $stocks,
        'from_date' => $from_date,
        'to_date' => $to_date,
        'warehouses' => $warehouseMap
    ]);
}

    return view('Reports.Stock_In_Report', compact(
        'warehouses', 'products', 'brands', 'defaultProducts', 'territories'
    ));



    }
}
