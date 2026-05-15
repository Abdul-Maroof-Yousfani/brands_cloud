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
        $db1 = config('database.connections.mysql.database');
        $db2 = config('database.connections.mysql2.database');

        $requests = DB::connection('mysql2')->table($db1 . '.reseller_so_requests as r')
            ->join($db1 . '.reseller_logins as l', 'r.reseller_id', '=', 'l.id')
            ->join($db2 . '.customers as c', 'l.customer_id', '=', 'c.id')
            ->select('r.*', 'c.name as reseller_name', 'l.email', 'r.customer_name')
            ->orderBy('r.id', 'DESC')
            ->get();

        return view('Sales.reseller_so_requests.index', compact('requests'));
    }

    public function show($id)
    {
        $db1 = config('database.connections.mysql.database');
        $db2 = config('database.connections.mysql2.database');

        $request = DB::connection('mysql2')->table($db1 . '.reseller_so_requests as r')
            ->join($db1 . '.reseller_logins as l', 'r.reseller_id', '=', 'l.id')
            ->join($db2 . '.customers as c', 'l.customer_id', '=', 'c.id')
            ->where('r.id', $id)
            ->select('r.*', 'c.name as reseller_name', 'c.id as customer_id', 'l.email', 'r.customer_name')
            ->first();

        if (!$request) {
            abort(404);
        }

        $details = DB::connection('mysql2')->table($db1 . '.reseller_so_request_details as d')
            ->join($db2 . '.subitem as s', 'd.product_id', '=', 's.id')
            ->where('d.request_id', $id)
            ->select('d.*', 's.product_name', 's.sku_code')
            ->get();

        $warehouses = DB::connection('mysql2')->table($db2 . '.warehouse')->where('status', 1)->get();

        return view('Sales.reseller_so_requests.show', compact('request', 'details', 'warehouses'));
    }

    public function approve(Request $req, $id)
    {
        $req->validate([
            'warehouse_id' => 'required'
        ]);

        $request = ResellerSoRequest::findOrFail($id);
        
        if ($request->status != 0) {
            return redirect()->back()->with('error', 'Request already processed.');
        }

        $details = ResellerSoRequestDetail::where('request_id', $id)->get();
        
        $resellerLogin = DB::table('reseller_logins')->where('id', $request->reseller_id)->first();
        $customerId = $resellerLogin->customer_id;

        $db2 = config('database.connections.mysql2.database');

        // Insert Stock IN (voucher_type 1) for the Company warehouse
        foreach ($details as $detail) {
            DB::connection('mysql2')->table($db2 . '.stock')->insert([
                'sub_item_id' => $detail->product_id,
                'qty' => $detail->qty,
                'voucher_type' => 1, // 1 = Stock IN
                'warehouse_id' => $req->warehouse_id,
                'status' => 1,
                'opening' => 0,
                'transfer' => 0,
                'created_date' => date('Y-m-d'),
                'description' => 'SO Request Approved: ' . $request->id
            ]);
        }
        
        // Mark as approved
        $request->status = 1;
        $request->save();

        return redirect()->route('admin.reseller_so.index')->with('success', 'SO Request Approved and Stock IN processed successfully.');
    }
}
