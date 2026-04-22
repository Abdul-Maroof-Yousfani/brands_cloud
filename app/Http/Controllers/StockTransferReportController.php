<?php

namespace App\Http\Controllers;
use App\Helpers\CommonHelper;
use Illuminate\Http\Request;
use DB;

class StockTransferReportController extends Controller
{
    public function show(Request $request) {
        $from = $request->from;
        $to = $request->to;
        $sku = $request->sku;

        $status = $request->status;

        if($request->ajax()) {
            CommonHelper::companyDatabaseConnection($request->m);

            $query = DB::connection('mysql2')->table('stock_out_data as sod')
                ->join('stock_out as so', 'so.id', '=', 'sod.master_id')
                ->join('subitem as si', 'si.id', '=', 'sod.item_id')
                ->leftJoin('warehouse as w1', 'w1.id', '=', 'sod.warehouse_from')
                ->leftJoin('warehouse as w2', 'w2.id', '=', 'sod.warehouse_to')
                ->select(
                    'so.so_no as tr_no',
                    'so.so_date as date',
                    'si.sku_code',
                    'si.product_name',
                    'si.product_barcode',
                    'sod.qty',
                    'sod.received_qty',
                    'w1.name as warehouse_from',
                    'w2.name as warehouse_to',
                    'so.description',
                    'so.username as created_by'
                )
                ->where('so.status', 1)
                ->where('sod.status', 1);

            if ($status == 'pending') {
                $query->where('sod.received_qty', 0);
            } elseif ($status == 'partial') {
                $query->where('sod.received_qty', '>', 0)
                      ->whereColumn('sod.received_qty', '<', 'sod.qty');
            } elseif ($status == 'received') {
                $query->whereColumn('sod.received_qty', '>=', 'sod.qty');
            }

            $items = $query->when($from && $to, function($q) use ($from, $to) {
                    $q->whereBetween('so.so_date', [$from, $to]);
                })
                ->when($sku, function($q) use ($sku) {
                    $q->where(function($sub) use ($sku) {
                        $sub->where('si.sku_code', 'LIKE', "%{$sku}%")
                            ->orWhere('si.product_barcode', 'LIKE', "%{$sku}%")
                            ->orWhere('so.so_no', 'LIKE', "%{$sku}%");
                    });
                })
                ->orderBy('so.so_date', 'desc')
                ->get();
            
            return view('Reports.StockTransferReport.stock_transfer_ajax', compact('items'));
        }

        return view('Reports.StockTransferReport.stock_transfer_report');
    }
}
