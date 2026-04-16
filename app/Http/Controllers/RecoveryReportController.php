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
            $customer_id = $request->customer_id;
            $internal_bank_id = $request->internal_bank_id;
            $company_bank_id = $request->company_bank_id;
            $principal_group_id = $request->principal_group_id;
            $bank_customer_id = $request->bank_customer_id;
            $bank_customer_id = $request->bank_customer_id;

            $outstandings = DB::connection("mysql2")
                                ->table("new_rvs")
                                ->join("new_rv_data", "new_rv_data.master_id", "=", "new_rvs.id")
                                ->leftJoin("accounts", "accounts.id", "=", "new_rv_data.acc_id")
                                ->leftJoin("customers", "customers.id", "=", "new_rvs.buyer_id")
                                ->select(
                                    "new_rvs.rv_no",
                                    "new_rvs.rv_date",
                                    "new_rv_data.description",
                                    "new_rvs.brand_id",
                                    "new_rvs.territory_id",
                                    "customers.territory_id as customer_territory",
                                    "new_rvs.pay_mode",
                                    "new_rvs.cheque_no",
                                    "new_rvs.bank as company_bank_id",
                                    "new_rvs.bank_customer_id as bank_customer_id",
                                    "new_rvs.cheque_date",
                                    "accounts.name as internal_bank_name",
                                    "new_rvs.principal_group_id",
                                    DB::raw("SUM(new_rv_data.amount) AS amount")
                                )
                                ->where("new_rv_data.debit_credit", "=", 1)
                                ->where("new_rvs.sales", "=", 1)
                                ->whereBetween("new_rvs.rv_date", [$request->from, $request->to])
                                ->when($v_no, function ($q) use ($v_no) {
                                    $q->where("new_rvs.rv_no", "like", "%{$v_no}%");
                                })
                                ->when($customer_id, function($q) use ($customer_id) {
                                    $q->where("new_rvs.buyer_id", $customer_id);
                                })
                                ->when($internal_bank_id, function($q) use ($internal_bank_id) {
                                    $q->where("new_rv_data.acc_id", $internal_bank_id);
                                })
                                ->when($company_bank_id, function($q) use ($company_bank_id) {
                                    $q->where("new_rvs.bank", $company_bank_id);
                                })
                                ->when($bank_customer_id, function($q) use ($bank_customer_id) {
                                    $q->where("new_rvs.bank_customer_id", $bank_customer_id);
                                })
                                ->when($principal_group_id, function($q) use ($principal_group_id) {
                                    $q->where("new_rvs.principal_group_id", $principal_group_id);
                                })
                                ->groupBy("new_rvs.rv_no")
                                ->get();
            
            
            return view("Reports.outstanding-again-report.outstanding_again_report_ajax", compact("outstandings"));
        }
        return view("Reports.outstanding-again-report.outstanding_again_report");
    }
}
