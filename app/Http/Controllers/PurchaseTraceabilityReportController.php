<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;

class PurchaseTraceabilityReportController extends Controller
{
    public function index(Request $request) {
        $m = $request->m;
        \App\Helpers\CommonHelper::companyDatabaseConnection($m);

        if($request->ajax()) {
       
            $query = DB::connection('mysql2')
                ->table('purchase_request_data as prd')
                ->join('purchase_request as pr', 'pr.id', '=', 'prd.master_id')
                ->leftJoin('subitem as i', 'i.id', '=', 'prd.sub_item_id')
                ->leftJoin('grn_data as grnd', 'grnd.po_data_id', '=', 'prd.id')
                ->leftJoin('goods_receipt_note as grn', 'grn.id', '=', 'grnd.master_id')
                ->leftJoin('warehouse as wh', 'wh.id', '=', 'grn.warehouse_id')
                ->leftJoin('territories as t', 't.id', '=', 'wh.territory_id')
                ->leftJoin('new_purchase_voucher_data as pvd', 'pvd.grn_data_id', '=', 'grnd.id')
                ->leftJoin('new_purchase_voucher as pv', 'pv.id', '=', 'pvd.master_id')
                ->where('pr.status', 1);

            // Filtering
            if ($request->filled('from_date') && $request->filled('to_date')) {
                $query->whereBetween('pr.purchase_request_date', [$request->from_date, $request->to_date]);
            }

            if ($request->filled('suppliers')) {
                $query->whereIn('pr.supplier_id', $request->suppliers);
            }

            if ($request->filled('product_id')) {
                $query->where('prd.sub_item_id', $request->product_id);
            }

            if ($request->filled('brand_ids')) {
                $query->whereIn('prd.brand_id', $request->brand_ids);
            }

            if ($request->filled('warehouse_ids')) {
                $query->whereIn('grn.warehouse_id', $request->warehouse_ids);
            }

            $purchases = $query->select(
                    'pr.id as pr_id',
                    'pr.supplier_id',
                    'pr.purchase_request_no as po_no',
                    'pr.purchase_request_date as pr_date',
                    'wh.name as warehouse_name',
                    't.name as region_name',
                    'i.product_name',
                    'prd.purchase_request_qty as po_qty',
                    'prd.net_amount as po_amount',
                    'grn.grn_no',
                    'grn.grn_date',
                    'grnd.purchase_recived_qty as grn_qty',
                    'grnd.amount as grn_amount',
                    'pv.pv_no as pi_no',
                    'pv.pv_date as pi_date',
                    'pvd.qty as invoice_qty',
                    'pvd.net_amount as invoice_amount'
                )
                ->orderBy('pr.purchase_request_date', 'DESC')
                ->get();

            return view("Store.Purchase.purchase_traceability_report_ajax", compact("purchases"));
        }

        $Suppliers = DB::connection('mysql2')->table('supplier')->where('status', 1)->get();
        $Items = DB::connection('mysql2')->table('subitem')->where('status', 1)->orderBy('product_name')->limit(100)->get();
        $Brands = DB::connection('mysql2')->table('brands')->where('status', 1)->orderBy('name')->get();
        $Warehouses = DB::connection('mysql2')->table('warehouse')->where('status', 1)->orderBy('name')->get();
        $Territories = DB::connection('mysql2')->table('territories')->where('status', 1)->orderBy('name')->get();

        \App\Helpers\CommonHelper::reconnectMasterDatabase();

        return view("Store.Purchase.purchase_traceability_report", compact('Suppliers', 'Items', 'Brands', 'Warehouses', 'Territories'));
    }
}
