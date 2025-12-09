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

            return view("Reports.unitLogReport.unitReportAjax", compact("unit_activities"));
        }

        return view("Reports.unitLogReport.unit_log");
    }
}
