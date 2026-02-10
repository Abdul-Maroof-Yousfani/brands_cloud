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

        'sales_tax_invoice.total AS invoice_amount',
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

      

        // ✅ Correct receipt amount
        DB::raw("(
            SELECT COALESCE(SUM(nrd.amount), 0)
            FROM new_rv_data nrd
            JOIN new_rvs nr ON nr.id = nrd.master_id
            WHERE FIND_IN_SET(sales_tax_invoice.gi_no, nr.ref_bill_no)
        ) AS receipt_amount"),

        // ✅ Correct sale return amount
        DB::raw("(
            SELECT COALESCE(SUM(cnd.net_amount), 0)
            FROM credit_note_data cnd
            JOIN credit_note cn ON cn.id = cnd.master_id
            WHERE cn.so_id = sales_order.id
        ) AS sale_return_amount"),

        // ✅ Aging buckets (receipt based)
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

        "territories.name AS territory_name"
    )

    ->leftJoin("new_rvs", function ($join) {
        $join->whereRaw("FIND_IN_SET(sales_tax_invoice.gi_no, new_rvs.ref_bill_no)");
    })
    ->leftJoin("new_rv_data", "new_rv_data.master_id", "=", "new_rvs.id")
    ->leftJoin("sales_order", "sales_order.id", "=", "sales_tax_invoice.so_id")
    ->leftJoin("sales_order_data", "sales_order_data.master_id", "=", "sales_order.id")
    ->leftJoin("brands", "sales_order_data.brand_id", "=", "brands.id")
    ->join("customers", "sales_order.buyers_id", "=", "customers.id")
    ->leftJoin("credit_note", "credit_note.so_id", "=", "sales_order.id")
    ->leftJoin("territories", "customers.territory_id", "=", "territories.id")

    ->when(isset($si_no), fn ($q) => $q->where("sales_tax_invoice.gi_no", "like", "%$si_no%"))
    ->when(isset($brand_id), fn ($q) => $q->where("sales_order_data.brand_id", $brand_id))
    ->when(isset($warehouse_id), fn ($q) => $q->where("customers.warehouse_from", $warehouse_id))

    ->whereBetween("sales_tax_invoice.gd_date", [$from, $to])
    ->groupBy("sales_tax_invoice.gi_no")
    ->get();

            
            
            return view("Reports.Recovery_Report.recovery_report_ajax", compact("payments"));
        }
        return view("Reports.Recovery_Report.recovery_report");
    }
}
