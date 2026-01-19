<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;

class PurchaseTraceabilityReportController extends Controller
{
    public function index() {
        if(request()->ajax()) {
       
            $purchase = DB::connection('mysql2')
                ->table('purchase_request as pr')
                ->where('pr.status', 1)

                /* ===== Purchase Request Data (aggregated) ===== */
                ->joinSub(
                    DB::connection('mysql2')
                        ->table('purchase_request_data')
                        ->select(
                            'master_id',
                            DB::raw('SUM(qty) as pr_qty'),
                            DB::raw('SUM(amount) as pr_amount')
                        )
                        ->groupBy('master_id'),
                    'prd',
                    function ($join) {
                        $join->on('prd.master_id', '=', 'pr.id');
                    }
                )

                /* ===== Goods Receipt Note ===== */
                ->join('goods_receipt_note as grn', 'grn.po_no', '=', 'pr.purchase_request_no')

                /* ===== GRN Data (aggregated) ===== */
                ->joinSub(
                    DB::connection('mysql2')
                        ->table('grn_data')
                        ->select(
                            'master_id',
                            DB::raw('SUM(qty) as grn_qty'),
                            DB::raw('SUM(amount) as grn_amount')
                        )
                        ->groupBy('master_id'),
                    'grnd',
                    function ($join) {
                        $join->on('grnd.master_id', '=', 'grn.id');
                    }
                )

                /* ===== Purchase Voucher ===== */
                ->join('new_purchase_voucher as pv', 'pv.grn_no', '=', 'grn.grn_no')

                /* ===== Purchase Voucher Data (aggregated) ===== */
                ->joinSub(
                    DB::connection('mysql2')
                        ->table('new_purchase_voucher_data')
                        ->select(
                            'master_id',
                            DB::raw('SUM(amount) as voucher_amount')
                        )
                        ->groupBy('master_id'),
                    'pvd',
                    function ($join) {
                        $join->on('pvd.master_id', '=', 'pv.id');
                    }
                )

                /* ===== Final Select ===== */
                ->select(
                    'pr.id',
                    'pr.purchase_request_no',

                    DB::raw('prd.pr_qty as pr_qty'),
                    DB::raw('prd.pr_amount as pr_amount'),

                    DB::raw('grnd.grn_qty as grn_qty'),
                    DB::raw('grnd.grn_amount as grn_amount'),

                    DB::raw('pvd.voucher_amount as voucher_amount')
                )
                ->get();
            dd($purchase);



            return view("Store.Purchase.purchase_traceability_report_ajax");
        }

        return view("Store.Purchase.purchase_traceability_report");
    }
}
