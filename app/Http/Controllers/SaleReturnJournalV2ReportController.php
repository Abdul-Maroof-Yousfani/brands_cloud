<?php

namespace App\Http\Controllers;

use App\Helpers\CommonHelper;
use DB;
use Illuminate\Http\Request;

class SaleReturnJournalV2ReportController extends Controller
{
    public function show(Request $request)
    {
        $m = $request->m;
        CommonHelper::companyDatabaseConnection($m);

        if ($request->ajax()) {
            $from_date = $request->from_date;
            $to_date = $request->to_date;
            $customer_ids = $request->customer_ids;
            $employee_ids = $request->employee_ids;
            $item_ids = $request->item_ids;
            $brand_ids = $request->brand_ids;
            $warehouse_ids = $request->warehouse_ids;
            $zone_ids = $request->zone_ids;
            $type_ids = $request->type_ids;
            $product_type = $request->product_type;

            $query = DB::connection('mysql2')->table("credit_note")
                ->join("credit_note_data", "credit_note.id", "=", "credit_note_data.master_id")
                ->join("subitem", "credit_note_data.item", "=", "subitem.id")
                ->join("brands", "subitem.brand_id", "=", "brands.id")
                ->join("customers", "credit_note.buyer_id", "=", "customers.id")
                ->leftJoin("sales_order_data", "credit_note_data.so_data_id", "=", "sales_order_data.id")
                ->leftJoin("sales_order", "sales_order_data.master_id", "=", "sales_order.id")
                ->leftJoin("warehouse", "credit_note_data.warehouse_id", "=", "warehouse.id")
                ->select(
                    "credit_note.cr_no as bill_no",
                    "credit_note.cr_date as date",
                    "customers.name as customer_name",
                    "credit_note.description as notes",
                    "subitem.product_name as item_name",
                    "brands.name as brand_name",
                    "subitem.hs_code",
                    "credit_note_data.qty",
                    "subitem.packing",
                    "credit_note_data.sale_price as unit_price",
                    "credit_note_data.amount as net_amount",
                    "credit_note_data.discount_amount",
                    "credit_note_data.tax_amount",
                    "credit_note_data.voucher_no as ref_no",
                    "credit_note_data.so_data_id",
                    "credit_note_data.qty as total_pcs",
                    DB::raw("credit_note_data.qty / IFNULL(NULLIF(CAST(subitem.packing AS UNSIGNED), 0), 1) as ctn"),
                    DB::raw("MOD(credit_note_data.qty, IFNULL(NULLIF(CAST(subitem.packing AS UNSIGNED), 0), 1)) as pcs"),
                    "sales_order_data.discount_percent_1",
                    "sales_order_data.discount_amount_1",
                    "sales_order_data.discount_percent_2",
                    "sales_order_data.discount_amount_2"
                );

            if ($from_date && $to_date) {
                $query->whereBetween('credit_note.cr_date', [$from_date, $to_date]);
            }

            if (!empty($customer_ids)) {
                $query->whereIn('credit_note.buyer_id', $customer_ids);
            }

            if (!empty($employee_ids)) {
                $query->whereIn('sales_order.sales_person_id', $employee_ids);
            }

            if (!empty($item_ids)) {
                $query->whereIn('credit_note_data.item', $item_ids);
            }

            if (!empty($brand_ids)) {
                $query->whereIn('subitem.brand_id', $brand_ids);
            }

            if (!empty($warehouse_ids)) {
                $query->whereIn('credit_note_data.warehouse_id', $warehouse_ids);
            }

            if (!empty($product_type)) {
                $query->where('subitem.product_type_id', $product_type);
            }

            if (!empty($zone_ids)) {
                $query->whereIn('customers.territory_id', $zone_ids);
            }

            if (!empty($type_ids)) {
                $query->whereIn('subitem.type_id', $type_ids);
            }

            $sales_report_data = $query->orderBy('credit_note.cr_date', 'desc')->get();

            return view('Reports.Sales_Return.JournalV2.sales_return_journal_v2_ajax', compact("sales_report_data"));
        }

        // Fetch filter data
        $customers = DB::connection('mysql2')->table('customers')->where('status', 1)->orderBy('name')->get();
        // Employee in this context is SubDepartment as seen in SalesOrderController
        $employees = DB::table('sub_department')->where('status', 1)->orderBy('sub_department_name')->get();
        $items = DB::connection('mysql2')->table('subitem')->where('status', 1)->orderBy('product_name')->get();
        $brands = DB::connection('mysql2')->table('brands')->where('status', 1)->orderBy('name')->get();
        $warehouses = DB::connection('mysql2')->table('warehouse')->where('status', 1)->orderBy('name')->get();
        $zones = DB::connection('mysql2')->table('territories')->where('status', 1)->orderBy('name')->get();
        $product_types = DB::connection('mysql2')->table('product_type')->where('status', 1)->get();
        $types = DB::connection('mysql2')->table('type')->where('status', 1)->get();

        return view("Reports.Sales_Return.JournalV2.sales_return_journal_v2", compact(
            'customers', 'employees', 'items', 'brands', 'warehouses', 'zones', 'product_types', 'types'
        ));
    }
}
