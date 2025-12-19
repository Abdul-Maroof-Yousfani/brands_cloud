<?php

namespace App\Http\Controllers;
//namespace App\Http\Controllers\Auth
//use Auth;
//use App\User;
use App\Http\Requests;
use App\Models\Sales_Order;
use Illuminate\Http\Request;
use App\Helpers\DashboardHelper;

use DB;

class ClientController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $territory_ids = json_decode(auth()->user()->territory_id); 
         
        $sale_orders = DB::connection('mysql2')
        ->table('sales_order')
        ->join('customers','customers.id','sales_order.buyers_id')
        ->whereIn('customers.territory_id', $territory_ids)
        ->where('sales_order.status', 1)
        ->paginate(10);

		return view('dClient.home',compact('sale_orders'));
    }
    public function financeDashboard()
    {
        $sale_orders = DB::connection('mysql2')
        ->table('sales_order')
        ->join('customers','customers.id','sales_order.buyers_id')
        ->where('sales_order.status', 1)
        ->paginate(10);

        $from = date('Y-m-01');
        $to  = date('Y-m-t');
                                //collection
        $collection =   DB::Connection('mysql2')->table('received_paymet as a')
                    ->join('new_rvs as b','a.receipt_no','b.rv_no')
                    ->where('b.status',1)
                    ->whereBetween('b.rv_date',[$from , $to])
                    ->sum('a.received_amount');

                    // total receipt

        $total_receipt =   DB::Connection('mysql2')->table('received_paymet as a')
                    ->join('new_rvs as b','a.receipt_no','b.rv_no')
                    ->where('b.status',1)
                    ->whereBetween('b.rv_date',[$from , $to])
                    ->count('a.id');

                    // total payment


        $total_payment =   DB::Connection('mysql2')->table('new_purchase_voucher_payment as a')
                    ->join('new_pv as b','a.new_pv_no','b.pv_no')
                    ->where('b.status',1)
                    ->whereBetween('b.pv_date',[$from , $to])
                    ->count('a.id');

                    $resultArray = [$total_receipt,$total_payment];
                    $jsonResult = json_encode($resultArray);

		return view('dClient.financeDashboard',compact('sale_orders','collection','total_receipt','total_payment','jsonResult'));
    }
    public function financeDashboardAjax(Request $request)
    {
        $SalesFlowChart = DashboardHelper::SalesFlowChart($request->year);
        return compact('SalesFlowChart');
    }

    public function clientCompanyMenu(){
        return view('dClient.home');
    }

    public function production_dashboard(){
        return view('dClient.production_dashboard');
    }
}
