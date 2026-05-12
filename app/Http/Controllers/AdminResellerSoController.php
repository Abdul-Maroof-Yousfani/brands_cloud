<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\ResellerSoRequest;
use App\ResellerSoRequestDetail;
use App\Models\Stock;

class AdminResellerSoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $requests = DB::connection('mysql2')->table('inpl2erp_brands_master_new.reseller_so_requests as r')
            ->join('inpl2erp_brands_master_new.reseller_logins as l', 'r.reseller_id', '=', 'l.id')
            ->join('inpl2erp_brands_new.customers as c', 'l.customer_id', '=', 'c.id')
            ->select('r.*', 'c.name as reseller_name', 'l.email')
            ->orderBy('r.id', 'DESC')
            ->get();

        return view('Sales.reseller_so_requests.index', compact('requests'));
    }

    public function show($id)
    {
        $request = DB::connection('mysql2')->table('inpl2erp_brands_master_new.reseller_so_requests as r')
            ->join('inpl2erp_brands_master_new.reseller_logins as l', 'r.reseller_id', '=', 'l.id')
            ->join('inpl2erp_brands_new.customers as c', 'l.customer_id', '=', 'c.id')
            ->where('r.id', $id)
            ->select('r.*', 'c.name as reseller_name', 'c.id as customer_id', 'l.email')
            ->first();

        if (!$request) {
            abort(404);
        }

        $details = DB::connection('mysql2')->table('inpl2erp_brands_master_new.reseller_so_request_details as d')
            ->join('inpl2erp_brands_new.subitem as s', 'd.product_id', '=', 's.id')
            ->where('d.request_id', $id)
            ->select('d.*', 's.product_name', 's.sku_code')
            ->get();

        return view('Sales.reseller_so_requests.show', compact('request', 'details'));
    }

    public function approve(Request $req, $id)
    {
        $request = ResellerSoRequest::findOrFail($id);
        
        if ($request->status != 0) {
            return redirect()->back()->with('error', 'Request already processed.');
        }

        $details = ResellerSoRequestDetail::where('request_id', $id)->get();
        
        $resellerLogin = DB::table('reseller_logins')->where('id', $request->reseller_id)->first();
        $customerId = $resellerLogin->customer_id;

        // Perform stock transfer logic here
        // 1. Stock Out from Virtual Warehouse (Voucher 50)
        // 2. Create actual SO for Company
        
        // Mark as approved
        $request->status = 1;
        $request->save();

        return redirect()->route('admin.reseller_so.index')->with('success', 'SO Request Approved and processed successfully.');
    }
}
