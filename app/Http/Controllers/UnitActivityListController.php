<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UnitActivityListController extends Controller
{
    public function show() {

        if(request()->ajax()) {

            $from = request()->from;
            $to = request()->to;
            $transaction_type = request()->transaction_type;
            $warehouse_id = request()->warehouse_id;

            // @if($unit_activity->voucher_type == 2 || $unit_activity->voucher_type == 3 || $unit_activity->voucher_type == 4)
            //             <td>{{ number_format($unit_activity->qty, 0) }}</td>
            //             @php
            //                 $received_qty += $unit_activity->qty;
            //             @endphp
            //         @else
            //             @php
            //                 $received_qty += 0;
            //             @endphp
            //             <td>0</td>
            //         @endif
            //         @if($unit_activity->voucher_type == 1 || $unit_activity->voucher_type == 5 || $unit_activity->voucher_type == 7 || $unit_activity->voucher_type == 50)
            //             @php
            //                 $issued_qty += $unit_activity->qty;
            //             @endphp
            //             <td>{{  number_format($unit_activity->qty, 0) }}</td>
            //         @else
            //             @php
            //                 $issued_qty += 0;
            //             @endphp
            //             <td>0</td>
            //         @endif

           $received_opening_bal = DB::connection("mysql2")
                ->table("stock")
                ->whereIn("voucher_type", [2, 3, 4])
                ->where("stock.qty", ">", 0)
                ->when(isset($from), function ($query) use ($from) {
                    $query->where("stock.voucher_date", "<", $from);
                })
                ->sum("stock.qty");


            $issued_opening_bal = DB::connection("mysql2")
                ->table("stock")
                ->whereIn("voucher_type", [1, 5, 7, 50])
                ->where("stock.qty", ">", 0)
                ->when(isset($from), function ($query) use ($from) {
                    $query->where("stock.voucher_date", "<", $from);
                })
                ->sum("stock.qty");
                                        

            $unit_activities = DB::connection("mysql2")->table("stock")
                        ->select(
                            "stock.sub_item_id",
                            "stock.voucher_date",
                            "stock.voucher_type",
                            "stock.warehouse_id",
                            "stock.username",
                            "stock.voucher_no",
                            "stock.qty",
                            "subitem.product_name",
                            "warehouse.name AS warehouse_name"
                        )
                        ->join("subitem", "subitem.id", "=", "stock.sub_item_id")
                        ->join("warehouse", "warehouse.id", "=", "stock.warehouse_id")
                        ->when(isset($from) && isset($to), function ($query) use ($from, $to) {
                            $query->whereBetween("stock.voucher_date", [$from, $to]);
                        })
                        ->when(isset($transaction_type), function($query) use($transaction_type) {
                            $query->where("voucher_type", $transaction_type);
                        })
                        ->when(isset($warehouse_id), function($query) use($warehouse_id) {
                            $query->where("warehouse_id", $warehouse_id);
                        })
                        ->get();

            return view("Reports.unitLogReport.unitReportAjax", compact("unit_activities", "received_opening_bal", "issued_opening_bal"));
        }

        return view("Reports.unitLogReport.unit_log");
    }
}
