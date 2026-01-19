<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;

class PurchaseTraceabilityReportController extends Controller
{
    public function index() {
        if(request()->ajax()) {
       
            $purchases = DB::connection('mysql2')
    ->table('purchase_request as pr')
    ->where('pr.status', 1)

    /* ===== Purchase Request Data (aggregated) ===== */
    ->join(DB::raw('(SELECT master_id, SUM(purchase_request_qty) as pr_qty, SUM(amount) as pr_amount 
                     FROM purchase_request_data 
                     GROUP BY master_id) as prd'), 
           'prd.master_id', '=', 'pr.id')

    /* ===== Goods Receipt Note ===== */
    ->join('goods_receipt_note as grn', 'grn.po_no', '=', 'pr.purchase_request_no')

    /* ===== GRN Data (aggregated) ===== */
    ->join(DB::raw('(SELECT master_id, SUM(purchase_recived_qty) as grn_qty, SUM(amount) as grn_amount 
                     FROM grn_data 
                     GROUP BY master_id) as grnd'), 
           'grnd.master_id', '=', 'grn.id')

    /* ===== Purchase Voucher ===== */
    ->join('new_purchase_voucher as pv', 'pv.grn_no', '=', 'grn.grn_no')

    /* ===== Purchase Voucher Data (aggregated) ===== */
    ->join(DB::raw('(SELECT master_id, SUM(amount) as voucher_amount, qty 
                     FROM new_purchase_voucher_data 
                     GROUP BY master_id) as pvd'), 
           'pvd.master_id', '=', 'pv.id')

    /* ===== Final Select ===== */
    ->select(
        'pr.id',
        'pr.purchase_request_no',
        "pr.supplier_id",
        'pr.purchase_request_no as po_no',
        "pr.purchase_request_date as pr_date",
        "grn.grn_no",
        'grn.grn_date',
        DB::raw('SUM(prd.pr_qty) as po_qty'),
        DB::raw('SUM(prd.pr_amount) as po_amount'),
        DB::raw('SUM(grnd.grn_qty) as grn_qty'),
        DB::raw('SUM(grnd.grn_amount) as grn_amount'),
        DB::raw('pv.pv_no as pi_no'),
        DB::raw('pv.pv_date as pi_date'),
        DB::raw('SUM(pvd.voucher_amount) AS invoice_amount'),
        DB::raw('SUM(pvd.qty) AS pv_qty')
    )
    ->groupBy("purchase_request_no", "pi_no", "po_no")
    ->get();





            return view("Store.Purchase.purchase_traceability_report_ajax", compact("purchases"));
        }

        return view("Store.Purchase.purchase_traceability_report");
    }
}
