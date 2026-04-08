<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class StockTransferReportController extends Controller
{
    public function show(Request $request) {
        $from = $request->from;
        $to = $request->to;
        $sku = $request->sku;

        if($request->ajax()) {
            
            $items = DB::connection('mysql2')->table('stock_transfer_data')
                ->join('stock_transfer', 'stock_transfer.id', '=', 'stock_transfer_data.master_id')
                ->join('subitem', 'subitem.id', '=', 'stock_transfer_data.item_id')
                ->leftJoin('warehouse as w1', 'w1.id', '=', 'stock_transfer_data.warehouse_from')
                ->leftJoin('warehouse as w2', 'w2.id', '=', 'stock_transfer_data.warehouse_to')
                ->select(
                    'stock_transfer.tr_no',
                    'stock_transfer.tr_date as date',
                    'subitem.sku_code',
                    'subitem.product_name',
                    'subitem.product_barcode',
                    'stock_transfer_data.qty',
                    'w1.name as warehouse_from',
                    'w2.name as warehouse_to',
                    'stock_transfer.description',
                    'stock_transfer.username as created_by'
                )
                ->when($from && $to, function($q) use ($from, $to) {
                    $q->whereBetween('stock_transfer.tr_date', [$from, $to]);
                })
                ->when($sku, function($q) use ($sku) {
                    $q->where('subitem.sku_code', 'LIKE', "%{$sku}%")
                      ->orWhere('subitem.product_barcode', 'LIKE', "%{$sku}%");
                })
                ->orderBy('stock_transfer.tr_date', 'desc')
                ->get();
            
            return view('Reports.StockTransferReport.stock_transfer_ajax', compact('items'));
        }

        return view('Reports.StockTransferReport.stock_transfer_report');
    }
}
