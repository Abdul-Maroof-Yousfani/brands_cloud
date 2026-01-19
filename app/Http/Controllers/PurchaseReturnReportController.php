<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseReturnReportController extends Controller
{
    public function index() {
        if(request()->ajax()) {
            $purchase_return = DB::table("purchase_return")
                                    ->join("purchase_return_data", "purchase_return_data.master_id", "=", "purchase_return.id")
                                    ->select(
                                        "purchase_return_data.sub_item_id",
                                        DB::raw("purchase_return_data.return_qty as qty"),
                                        DB::raw("purchase_return_data.return_qty as ctn"),
                                        DB::raw("purchase_return_data.amount as gross_amount"),
                                        DB::raw("purchase_return_data.discount_amount as discount_amount"),
                                        DB::raw("SUM(purchase_return_data.net_amount) as net_amount")
                                    )
                                    ->groupBy("purchase_return_data.sub_item_id")
                                    ->get();

            return view("Store.Purchase.purchase_return_report_ajax", compact("purchase_return"));
        }

        return view("Store.Purchase.purchase_return_report");
    }
}
