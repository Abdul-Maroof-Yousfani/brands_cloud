<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;

class PurchaseTraceabilityReportController extends Controller
{
    public function index() {
        if(request()->ajax()) {
            // $purchase = DB::connection("mysql2")->


            return view("Store.Purchase.purchase_traceability_report_ajax")
        }

        return view("Store.Purchase.purchase_traceability_report");
    }
}
