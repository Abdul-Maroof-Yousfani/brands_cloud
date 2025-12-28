<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InventorySheetController extends Controller
{
     public function show() {
        $brands = DB::connection("mysql2")->table("brands")->get();

        if(request()->ajax()) {

            $from = request()->from;
            $to = request()->to;
            $item_id = request()->item_id;
            $region_id = request()->region_id;

            $stocks = [];

            $items = DB::connection('mysql2')
    ->table('subitem as si')
    ->join('brands as b', 'si.brand_id', '=', 'b.id')                    // Brand is required
    // ->leftJoin('stock as s', "s.sub_item_id", "=", "si.id")
    ->leftJoin('stock as s', function ($join) use ($from, $to) {
        $join->on('s.sub_item_id', '=', 'si.id')
             ->where('s.status', 1);

        // Only filter stock by date if both dates are provided
        if (isset($from) && isset($to)) {
            $join->whereBetween('s.voucher_date', [$from, $to]);
        }
    })
    ->select(
        'b.id as brand_id',
        'b.name as brand_name',
        'si.id as subitem_id',
        "s.voucher_date",
        'si.product_name as name',
        'si.sku_code as sku',
        'si.product_barcode as barcode',

        DB::raw('SUM(CASE WHEN s.voucher_type IN (1,4,6,10,11) AND s.transfer_status != 1 THEN s.qty ELSE 0 END) AS in_stock'),
        DB::raw('SUM(CASE WHEN s.voucher_type IN (2,5,3,9) THEN s.qty ELSE 0 END) AS out_stock'),
        DB::raw('
            COALESCE(SUM(CASE WHEN s.voucher_type IN (1,4,6,10,11) AND s.transfer_status != 1 THEN s.qty ELSE 0 END), 0)
            -
            COALESCE(SUM(CASE WHEN s.voucher_type IN (2,5,3,9) THEN s.qty ELSE 0 END), 0)
            AS net_stock
        ')
    )
    // ->when(isset($from) && isset($to), function ($query) use ($from, $to) {
    //     return $query->where("s.voucher_date", [$from, $to]);
    // })
    ->when(isset($item_id), function($query) use($item_id) {
        $query->where("si.id", $item_id);
    })
    ->groupBy('si.id', 'b.id', 'b.name', 'si.product_name', 'si.sku_code', 'si.product_barcode')
    ->orderBy('brand_name')
    ->orderBy('name')
    ->get();


            foreach($items as $item) {
                $stockQty = (float)$item->in_stock - (float)$item->out_stock;
                $stocks[$item->subitem_id][$item->brand_id] = abs($stockQty);
            }




        
            
            
            return view("Reports.InventorySheet.inventory_sheet_ajax", compact("items", "stocks", "brands"));
        }

        return view("Reports.InventorySheet.inventory_sheet", compact("brands"));
    }
}
