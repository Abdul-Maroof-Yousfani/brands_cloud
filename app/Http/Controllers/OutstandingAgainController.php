<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;

class OutstandingAgainController extends Controller
{
    public function show() {
        if(request()->ajax()) {
            $si_no = request()->si;
            $brand_id = request()->brand_id;
            $warehouse_id = request()->warehouse_id;
            $to = request()->to;
            $from = request()->from;
         
            $payments = DB::connection("mysql2")
                                    ->table("sales_tax_invoice")
                                    ->select(
                                        "sales_tax_invoice.gi_no",
                                        "new_rvs.rv_no",
                                        "new_rvs.pay_mode",
                                        "sales_order.so_no",
                                        "sales_order_data.brand_id",
                                        "customers.warehouse_from", 
                                        "customers.customer_code", 
                                        "customers.name", 
                                        "customers.address", 
                                        "sales_tax_invoice.gd_date", 
                                        "sales_person", 
                                        "cr_no", 
                                        "sales_order.branch",
                                        DB::raw("SUM(credit_note_data.net_amount) AS sale_return_amount"),
                                        DB::raw("SUM(sales_tax_invoice_data.amount) AS invoice_amount"),
                                        DB::raw("SUM(new_rv_data.amount) AS receipt_amount"),
                                        DB::raw("COALESCE(
                                            SUM(
                                                CASE 
                                                    WHEN new_rvs.rv_date BETWEEN DATE_SUB(CURDATE(), INTERVAL 45 DAY) AND CURDATE()
                                                    AND new_rv_data.debit_credit = 0
                                                    THEN new_rv_data.amount 
                                                    ELSE 0 
                                                END
                                            ), 0
                                        ) AS one_to_fourty_five_days_due"),
                                         DB::raw("COALESCE(
                                            SUM(
                                                CASE 
                                                    WHEN new_rvs.rv_date BETWEEN DATE_SUB(CURDATE(), INTERVAL 90 DAY) 
                                                                            AND DATE_SUB(CURDATE(), INTERVAL 45 DAY)
                                                                            AND new_rv_data.debit_credit = 0
                                                    THEN new_rv_data.amount 
                                                    ELSE 0 
                                                END
                                            ), 0
                                        ) AS fourty_five_to_ninety_days_due"),
                                         DB::raw("COALESCE(
                                            SUM(
                                                CASE 
                                                    WHEN new_rvs.rv_date BETWEEN DATE_SUB(CURDATE(), INTERVAL 179 DAY) 
                                                                            AND DATE_SUB(CURDATE(), INTERVAL 91 DAY)
                                                                            AND new_rv_data.debit_credit = 0
                                                    THEN new_rv_data.amount 
                                                    ELSE 0 
                                                END
                                            ), 0
                                        ) AS ninety_one_to_one_seventy_nine_days_due"),
                                        DB::raw("COALESCE(
                                            SUM(
                                                CASE 
                                                    WHEN new_rvs.rv_date < DATE_SUB(CURDATE(), INTERVAL 180 DAY)
                                                    AND new_rv_data.debit_credit = 0
                                                    THEN new_rv_data.amount 
                                                    ELSE 0 
                                                END
                                            ), 0
                                        ) AS more_than_one_eighty_days_due"),
                                        "territories.name AS territory_name",
                                    )
                                    ->leftJoin("new_rvs", function($join) {
                                        $join->whereRaw("FIND_IN_SET(sales_tax_invoice.gi_no, new_rvs.ref_bill_no)");
                                    })
                                    ->leftJoin("new_rv_data", "new_rv_data.master_id", "=", "new_rvs.id")
                                    ->leftJoin("sales_tax_invoice_data", "sales_tax_invoice_data.master_id", "=", "sales_tax_invoice.id")
                                    ->leftJoin("sales_order", "sales_order.id", "=", "sales_tax_invoice.so_id")
                                    ->leftJoin("sales_order_data", "sales_order_data.master_id", "=", "sales_order.id")
                                    ->leftJoin("brands", "sales_order_data.brand_id", "=", "brands.id")
                                    ->join("customers", "sales_order.buyers_id", "=", "customers.id")
                                    ->leftJoin("credit_note", "credit_note.so_id", "=", "sales_order.id")
                                    ->leftJoin("territories", "customers.territory_id", "=", "territories.id")
                                    ->leftJoin("credit_note_data", "credit_note_data.master_id", "=", "credit_note.id")
                                    ->when(isset($si_no), function($query) use ($si_no) {
                                        $query->where("sales_tax_invoice.gi_no", "like", "%$si_no%");
                                    })
                                    ->when(isset($brand_id), function($query) use ($brand_id) {
                                        $query->where("sales_order_data.brand_id", $brand_id);
                                    })
                                    ->when(isset($warehouse_id), function($query) use ($warehouse_id) {
                                        $query->where("customers.warehouse_from", $warehouse_id);
                                    })
                                    ->whereBetween("sales_tax_invoice.gd_date", [$from, $to])
                                    ->groupBy("sales_tax_invoice.gi_no")
                                    ->get();
            
            
            return view("Reports.Recovery_Report.recovery_report_ajax", compact("payments"));
        }
        return view("Reports.Recovery_Report.recovery_report");
    }
}
