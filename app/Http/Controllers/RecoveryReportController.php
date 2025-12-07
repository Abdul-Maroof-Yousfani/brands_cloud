<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RecoveryReportController extends Controller
{
    public function show(Request $request) {
        if(request()->ajax()) {
            $so_no = $request->so;
            $v_no = $request->v_no;

            $outstandings = DB::connection("mysql2")
                                ->table("new_rvs")
                                ->select(
                                    "new_rvs.rv_no",
                                    "new_rvs.rv_date",
                                    "new_rv_data.description",
                                    "new_rvs.brand_id",
                                    "new_rvs.territory_id",
                                    "new_rvs.pay_mode",
                                    "new_rvs.cheque_no",
                                    "new_rvs.bank",
                                    "new_rvs.cheque_date",
                                    DB::raw("SUM(new_rv_data.amount) AS amount")
                                )
                                ->where("new_rv_data.debit_credit", "=", 1)
                                ->where("new_rvs.sales", "=", 1)
                                ->whereBetween("new_rvs.rv_date", [$request->from, $request->to])
                                ->join("new_rv_data", "new_rv_data.master_id", "=", "new_rvs.id")
                                ->when($v_no, function ($q) use ($v_no) {
                                    $q->where("new_rvs.rv_no", "like", "%{$v_no}%");
                                })
                                ->groupBy("new_rvs.rv_no")
                                ->get();
            
            
            return view("Reports.outstanding-again-report.outstanding_again_report_ajax", compact("outstandings"));
        }
        return view("Reports.outstanding-again-report.outstanding_again_report");
    }
}
