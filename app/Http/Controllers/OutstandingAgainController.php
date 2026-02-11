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
        'sales_tax_invoice.adv_tax AS adv_tax',
        "sales_order.so_no",
        "sod.brand_id",
        "customers.warehouse_from",
        "customers.customer_code",
        "customers.name",
        "customers.address",
        "sales_tax_invoice.gd_date",
        "sales_person",
        "sales_order.branch",

        // Receipt numbers subquery
        DB::raw("(
            SELECT GROUP_CONCAT(DISTINCT nr.rv_no SEPARATOR '\n')
            FROM new_rvs nr
            WHERE FIND_IN_SET(sales_tax_invoice.gi_no, nr.ref_bill_no)
        ) AS rv_numbers"),

        // Sale Return numbers subquery
        DB::raw("(
            SELECT GROUP_CONCAT(DISTINCT cn.cr_no SEPARATOR '\n')
            FROM credit_note cn
            WHERE cn.so_id = sales_order.id
        ) AS cr_numbers"),

        // Pay mode subquery
        DB::raw("(
            SELECT MAX(nr.pay_mode)
            FROM new_rvs nr
            WHERE FIND_IN_SET(sales_tax_invoice.gi_no, nr.ref_bill_no)
        ) AS pay_mode"),

        DB::raw("SUM(sales_tax_invoice_data.amount) AS net_amount"),

        // ✅ Correct receipt amount
        DB::raw("(
            SELECT COALESCE(SUM(nrd.amount), 0)
            FROM new_rv_data nrd
            JOIN new_rvs nr ON nr.id = nrd.master_id
            WHERE FIND_IN_SET(sales_tax_invoice.gi_no, nr.ref_bill_no)
            AND nrd.debit_credit = 0
        ) AS receipt_amount"),

        // ✅ Correct sale return amount
        DB::raw("(
            SELECT COALESCE(SUM(cnd.net_amount), 0)
            FROM credit_note_data cnd
            JOIN credit_note cn ON cn.id = cnd.master_id
            WHERE cn.so_id = sales_order.id
        ) AS sale_return_amount"),

        // ✅ Aging buckets (receipt based subqueries)
        DB::raw("(
            SELECT COALESCE(SUM(nrd.amount), 0)
            FROM new_rv_data nrd
            JOIN new_rvs nr ON nr.id = nrd.master_id
            WHERE FIND_IN_SET(sales_tax_invoice.gi_no, nr.ref_bill_no)
            AND nr.rv_date BETWEEN DATE_SUB(CURDATE(), INTERVAL 45 DAY) AND CURDATE()
            AND nrd.debit_credit = 0
        ) AS one_to_fourty_five_days_due"),

        DB::raw("(
            SELECT COALESCE(SUM(nrd.amount), 0)
            FROM new_rv_data nrd
            JOIN new_rvs nr ON nr.id = nrd.master_id
            WHERE FIND_IN_SET(sales_tax_invoice.gi_no, nr.ref_bill_no)
            AND nr.rv_date BETWEEN DATE_SUB(CURDATE(), INTERVAL 90 DAY) AND DATE_SUB(CURDATE(), INTERVAL 45 DAY)
            AND nrd.debit_credit = 0
        ) AS fourty_five_to_ninety_days_due"),

        DB::raw("(
            SELECT COALESCE(SUM(nrd.amount), 0)
            FROM new_rv_data nrd
            JOIN new_rvs nr ON nr.id = nrd.master_id
            WHERE FIND_IN_SET(sales_tax_invoice.gi_no, nr.ref_bill_no)
            AND nr.rv_date BETWEEN DATE_SUB(CURDATE(), INTERVAL 179 DAY) AND DATE_SUB(CURDATE(), INTERVAL 91 DAY)
            AND nrd.debit_credit = 0
        ) AS ninety_one_to_one_seventy_nine_days_due"),

        DB::raw("(
            SELECT COALESCE(SUM(nrd.amount), 0)
            FROM new_rv_data nrd
            JOIN new_rvs nr ON nr.id = nrd.master_id
            WHERE FIND_IN_SET(sales_tax_invoice.gi_no, nr.ref_bill_no)
            AND nr.rv_date < DATE_SUB(CURDATE(), INTERVAL 180 DAY)
            AND nrd.debit_credit = 0
        ) AS more_than_one_eighty_days_due"),

        // Adjustment doc numbers subquery
        DB::raw("(
            SELECT GROUP_CONCAT(DISTINCT cd.rv_no SEPARATOR '\n')
            FROM received_paymet rp
            JOIN credits_data cd ON cd.id = rp.receipt_id
            WHERE rp.sales_tax_invoice_id = sales_tax_invoice.id
        ) AS adjustment_doc_nos"),

        // Adjustment amount subquery
        DB::raw("(
            SELECT COALESCE(SUM(rp.received_amount), 0)
            FROM received_paymet rp
            JOIN credits_data cd ON cd.id = rp.receipt_id
            WHERE rp.sales_tax_invoice_id = sales_tax_invoice.id
        ) AS adjustment_amount"),

        // Adjustment remarks subquery
        DB::raw("(
            SELECT GROUP_CONCAT(DISTINCT cd.description SEPARATOR ' | ')
            FROM received_paymet rp
            JOIN credits_data cd ON cd.id = rp.receipt_id
            WHERE rp.sales_tax_invoice_id = sales_tax_invoice.id
        ) AS adjustment_remarks"),

        "territories.name AS territory_name"
    )

    ->join("sales_tax_invoice_data", "sales_tax_invoice_data.master_id", "=", "sales_tax_invoice.id")
    ->leftJoin("sales_order", "sales_order.id", "=", "sales_tax_invoice.so_id")
    ->leftJoin(DB::raw("(SELECT master_id, brand_id FROM sales_order_data GROUP BY master_id) AS sod"), "sod.master_id", "=", "sales_order.id")
    ->leftJoin("brands", "sod.brand_id", "=", "brands.id")
    ->join("customers", "sales_order.buyers_id", "=", "customers.id")
    ->leftJoin("territories", "customers.territory_id", "=", "territories.id")

    ->when(isset($si_no), fn ($q) => $q->where("sales_tax_invoice.gi_no", "like", "%$si_no%"))
    ->when(isset($brand_id), fn ($q) => $q->where("sod.brand_id", $brand_id))
    ->when(isset($warehouse_id), fn ($q) => $q->where("customers.warehouse_from", $warehouse_id))

    ->whereBetween("sales_tax_invoice.gd_date", [$from, $to])
    ->groupBy("sales_tax_invoice.gi_no")
    ->get();

    // dd($payments);

            
            
            return view("Reports.Recovery_Report.recovery_report_ajax", compact("payments"));
        }
        return view("Reports.Recovery_Report.recovery_report");
    }
}
