<?php

namespace App\Http\Controllers;

use App\Helpers\CommonHelper;
use App\Helpers\SalesHelper;
use App\Models\Sales_Order;
use App\Models\Sales_Order_Data;
use App\Models\Stock;
use App\Models\Subitem;
use App\Models\Warehouse;
use App\Sale_Return_Data;
use App\Sales_Return;
use DB;
use Exception;
use Illuminate\Http\Request;
use App\Helpers\ReuseableCode;
use App\Models\Transactions;
use App\Helpers\FinanceHelper;

class SaleReturnController extends Controller
{
    public $path;

    public function __construct()
    {
        $this->path = 'selling.salereturn.';
    }
    public function create() {
        $warehouses = DB::connection("mysql2")->table("warehouse")->get();
        return view('selling.salereturn.createSaleReturn', compact("warehouses"));
    }
    //  public function store(Request $request)
    // {
    //     DB::Connection('mysql2')->beginTransaction();


    //     // dd($request->all());
    //     try {
    //         $data = $request->product_id;
    //         if (empty($data)) {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'Product Details cannot be null'
    //             ], 422);
    //         }

    //         $byers_id = $request->customer;
    //         $grand_amount = 0;
    //         $sale_tax_mount_total = 0;
    //         $so_no = CommonHelper::generateUniquePosNo('sales_return', 'so_no', 'SR');

    //         $customerDetail = DB::connection('mysql2')
    //             ->table('customers')
    //             ->where('id', $request->customer_name)
    //             ->where('status', 1)
    //             ->select('*')
    //             ->first();

    //         $virtualWarehouseCheck = 0;
    //         if ($customerDetail->CustomerType == '3' || $customerDetail->CustomerType == 3) {
    //             $virtualWarehouseCheck = 1;
    //         }
    //         $sales_return = new Sales_Return();
    //         $sales_return = $sales_return->SetConnection('mysql2');
    //         $sales_return->so_no = $so_no;
    //         $sales_return->so_date = date('Y-m-d');
    //         $sales_return->destination = $request->address;
    //         $sales_return->buyers_id = $request->customer_name;
    //         $sales_return->phone_no = $request->phone_no ?? null;
    //         $sales_return->address = $request->address ?? null;
    //         $sales_return->branch = $request->branch ?? null;
    //         $sales_return->sales_person = $request->saleperson ?? null;
    //         $sales_return->balance_amount = $request->balance_amount ?? 0.0;
    //         $sales_return->credit_limit = $request->credit_limit ?? 0.0;
    //         $sales_return->current_balance_due = $request->balance_amount + $request->total_amount_after_sale_tax ?? 0.0;
    //         $sales_return->virtual_warehouse_check = $virtualWarehouseCheck ?? 0;
    //         $sales_return->total_amount_after_sale_tax = $request->total_amount_after_sale_tax ?? 0;
    //         $sales_return->status = 0;
    //         $sales_return->date = date('Y-m-d');salesreturn.create
    //         $sales_return->total_amount = $request->total_gross_amount ?? 0;
    //         $sales_return->sales_tax_rate = $request->total_sales_tax ?? 0;
    //         $sales_return->sales_tax_group = 13;
    //         $sales_return->total_qty = $request->total_qty ?? 0;
    //         $sales_return->sale_taxes_id = $request->sale_taxes_id ?? 0;
    //         $sales_return->sale_taxes_amount_total = $request->sale_taxes_amount_total ?? 0;
    //         $sales_return->sale_taxes_amount_rate = $request->sale_taxes_amount_rate ?? 0;

    //         // $sales_return->purchase_order_no=$request->purchase_order_no ?? '';
    //         // $sales_return->purchase_order_date=$request->purchase_order_date;
    //         // $sales_return->purchase_order_contract=$request->quotation_id ?? '';

    //         // if(!empty($request->sale_taxt_group))
    //         // {
    //         //     $sale_tax_id = explode(',',$request->sale_taxt_group);
    //         //     $sales_return->sales_tax_group =$sale_tax_id[0];
    //         //     $sales_return->sales_tax_rate =  $request->sale_tax_rate;

    //         // }

    //         // if(!empty($request->further_taxes_group))
    //         // {

    //         //     $further_taxes_group = explode(',',$request->further_taxes_group);
    //         //     $sales_return->further_taxes_group = $further_taxes_group[0];
    //         //     $sales_return->sales_tax_further = $request->sales_tax_further;

    //         // }

    //         // $sales_return->status=1;

    //         $sales_return->remark = $request->remark;
    //         $sales_return->username = auth()->user()->name;
    //         $sales_return->date = date('Y-m-d');
    //         $sales_return->save();

    //         $master_id = $sales_return->id;
    //         $data = $request->product_id;

    //         $count = 1;
    //         foreach ($data as $key => $row):
    //             $sales_return_data = new Sale_Return_Data();
    //             $sales_return_data = $sales_return_data->SetConnection('mysql2');
    //             $sales_return_data->master_id = $master_id;
    //             $sales_return_data->so_no = $so_no;
    //             $sales_return_data->brand_id = $request->brand_id[$key];
    //             $sales_return_data->item_id = $request->product_id[$key];
    //             $sales_return_data->desc = $request->product_id[$key] ?? 0;
    //             $sales_return_data->thickness = 0;
    //             $sales_return_data->diameter = 0;
    //             $sales_return_data->item_description = $request->item_description[$key] ?? null;
    //             $sales_return_data->qty = $request->qty[$key] ?? 0;
    //             $sales_return_data->foc = $request->foc[$key] ?? 0;
    //             $sales_return_data->mrp_price = $request->mrp_price[$key] ?? 0.0;
    //             $sales_return_data->rate = $request->rate[$key] ?? 0;
    //             $sales_return_data->sub_total = $request->gross_amount[$key] ?? 0.0;
    //             $sales_return_data->tax = $request->tax[$key] ?? 0.0;
    //             $sales_return_data->tax_amount = $request->total_tax[$key] ?? 0.0;
    //             $sales_return_data->amount = $request->total_amount[$key] ?? 0.0;
    //             $sales_return_data->printing = $request->printing[$key] ?? null;

    //             $sales_return_data->special_instruction = $request->special_ins[$key] ?? null;
    //             $sales_return_data->delivery_date = $request->delivery_date[$key] ?? null;

    //             $sales_return_data->discount_percent_1 = $request->discount1[$key];
    //             $sales_return_data->discount_amount_1 = $request->discount_amount1[$key];

    //             $sales_return_data->discount_percent_2 = $request->discount2[$key];
    //             $sales_return_data->discount_amount_2 = $request->discount_amount2[$key];
    //             $sales_return_data->warehouse_id = $request->warehouses[$key];

    //             // $sales_return_data->amount = $request->total[$key];
    //             // $sales_return_data->delivery_type=$request->total[$key];   Delivery type
    //             // $sales_return_data->tax = $request->sale_tax_rate;

    //             // $sale_tax_mount =  $request->total[$key]/100*$request->sale_tax_rate;

    //             // $sale_tax_mount_total  += $sale_tax_mount;

    //             // $sales_return_data->tax_amount =   $sale_tax_mount;

    //             // $sales_return_data->sub_total =   $sale_tax_mount+$request->total[$key];
    //             $sales_return_data->status = 1;
    //             $sales_return_data->date = date('Y-m-d');
    //             $sales_return_data->username = auth()->user()->name;
    //             // $sales_return_data->groupby= $count;
    //             $sales_return_data->save();

    //             $grand_amount += $request->total_amount[$key];

    //             $count++;
    //         endforeach;
    //         //  if(!empty($request->quotation_id))
    //         // {
    //         //     $s_qt =  SaleQuotation::find($request->quotation_id);
    //         //     $s_qt->so_status =1;
    //         //     $s_qt->save();
    //         // }

    //         // $sales_order->total_amount = $grand_amount;
    //         // $sales_order->total_amount_after_sale_tax = $request->grand_total_with_tax;
    //         // $sales_order->save();
    //         SalesHelper::sales_activity($so_no, date('Y-m-d'), '0', 1, 'Insert');
    //         $voucher_no = $so_no;
    //         $subject = 'Sales Order Created ' . $so_no;

    //         //  Customer Entry
    //         //    $customer =  Customer::find($byers_id);
    //         //     $transaction=new Transactions();
    //         //     $transaction=$transaction->SetConnection('mysql2');
    //         //     $transaction->voucher_no=$so_no;
    //         //     $transaction->v_date=date('Y-m-d');
    //         //     $transaction->acc_id = $customer->acc_id;
    //         //     $transaction->acc_code=FinanceHelper::getAccountCodeByAccId($customer->acc_id);
    //         //     $transaction->particulars=$subject;
    //         //     $transaction->opening_bal=0;
    //         //     $transaction->debit_credit=1;
    //         //     $transaction->amount= $request->grand_total_with_tax;
    //         //     $transaction->username=Auth::user()->name;
    //         //     $transaction->status=1;
    //         //     $transaction->voucher_type=4;
    //         //     $transaction->save();

    //         //     //  Sale Account Rvene
    //         //    $account_sale =  DB::connection('mysql2')->table('accounts')->where('name','Like','%LOCAL SALES - PIPE%')->first();

    //         //     $transaction=new Transactions();
    //         //     $transaction=$transaction->SetConnection('mysql2');
    //         //     $transaction->voucher_no=$so_no;
    //         //     $transaction->v_date=date('Y-m-d');
    //         //     $transaction->acc_id= $account_sale->id;
    //         //     $transaction->acc_code=FinanceHelper::getAccountCodeByAccId($account_sale->id);
    //         //     $transaction->particulars= $subject;
    //         //     $transaction->opening_bal=0;
    //         //     $transaction->debit_credit=0;
    //         //     $transaction->amount= $grand_amount;
    //         //     $transaction->username=Auth::user()->name;;
    //         //     $transaction->status=1;
    //         //     $transaction->voucher_type=4;
    //         //     $transaction->save();

    //         //     // Sale_ Tax Amount
    //         //     $account_sale_tax =  DB::connection('mysql2')->table('accounts')->where('name','Like','%OUTPUT TAX (SALES)%')->first();

    //         //     $transaction=new Transactions();
    //         //     $transaction=$transaction->SetConnection('mysql2');
    //         //     $transaction->voucher_no=$so_no;
    //         //     $transaction->v_date=date('Y-m-d');
    //         //     $transaction->acc_id=$account_sale_tax->id;
    //         //     $transaction->acc_code=FinanceHelper::getAccountCodeByAccId($account_sale_tax->id);
    //         //     $transaction->particulars= $subject;
    //         //     $transaction->opening_bal=0;
    //         //     $transaction->debit_credit=0;
    //         //     $transaction->amount=$sale_tax_mount_total;
    //         //     $transaction->username=Auth::user()->name;;
    //         //     $transaction->status=1;
    //         //     $transaction->voucher_type=4;
    //         //     $transaction->save();

    //         // exit();
    //         DB::Connection('mysql2')->commit();
    //         // return redirect()->route('createSaleOrder')->with('dataInsert', 'Sale Order Inserted');
    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Sale Order Inserted',
    //             'saleOrderId' => $master_id
    //         ], 200);
    //     } catch (Exception $e) {
    //         DB::Connection('mysql2')->rollBack();
    //         return response()->json([
    //             'success' => false,
    //             'errors' => $e->errors()
    //         ], 422);
    //     }
    // }


public function store(Request $request)
{

    DB::Connection('mysql2')->beginTransaction();


    try {
        $data = $request->product_id;
        if (empty($data)) {
            return response()->json([
                'success' => false,
                'message' => 'Product Details cannot be null'
            ], 422);
        }

        $byers_id = $request->customer_name;
        $grand_amount = 0;
        $sale_tax_mount_total = 0;
        $so_no = CommonHelper::generateUniquePosNo('sales_return', 'so_no', 'SR');

        $customerDetail = DB::connection('mysql2')
            ->table('customers')
            ->where('id', $request->customer_name)
            ->where('status', 1)
            ->select('*')
            ->first();

        if (!$customerDetail) {
            return response()->json([
                'success' => false,
                'message' => 'Customer not found'
            ], 422);
        }

        $virtualWarehouseCheck = 0;
        if ($customerDetail->CustomerType == '3' || $customerDetail->CustomerType == 3) {
            $virtualWarehouseCheck = 1;
        }

        $sales_return = new Sales_Return();
        $sales_return = $sales_return->SetConnection('mysql2');
        $sales_return->so_no = $so_no;
        $sales_return->so_date = date('Y-m-d');
        $sales_return->destination = $request->address;
        $sales_return->buyers_id = $request->customer_name;
        $sales_return->phone_no = $request->phone_no ?? null;
        $sales_return->address = $request->address ?? null;
        $sales_return->branch = $request->branch ?? null;
        $sales_return->sales_person = $request->saleperson ?? null;
        $sales_return->balance_amount = $request->balance_amount ?? 0.0;
        $sales_return->credit_limit = $request->credit_limit ?? 0.0;
        $sales_return->current_balance_due = $request->balance_amount + $request->total_amount_after_sale_tax ?? 0.0;
        $sales_return->virtual_warehouse_check = $virtualWarehouseCheck ?? 0;
        $sales_return->total_amount_after_sale_tax = $request->total_amount_after_sale_tax ?? 0;
        $sales_return->status = 0; // Pending approval status
        $sales_return->date = date('Y-m-d');
        $sales_return->total_amount = $request->total_gross_amount ?? 0;
        $sales_return->sales_tax_rate = $request->total_sales_tax ?? 0;
        $sales_return->sales_tax_group = 13;
        $sales_return->total_qty = $request->total_qty ?? 0;
        $sales_return->sale_taxes_id = $request->sale_taxes_id ?? 0;
        $sales_return->sale_taxes_amount_total = $request->sale_taxes_amount_total ?? 0;
        $sales_return->sale_taxes_amount_rate = $request->sale_taxes_amount_rate ?? 0;
        $sales_return->remark = $request->remark;
        $sales_return->username = auth()->user()->name;
        $sales_return->date = date('Y-m-d');
        $sales_return->save();

        $master_id = $sales_return->id;
        $data = $request->product_id;

        $count = 1;
        foreach ($data as $key => $row):
            $sales_return_data = new Sale_Return_Data();
            $sales_return_data = $sales_return_data->SetConnection('mysql2');
            $sales_return_data->master_id = $master_id;
            $sales_return_data->so_no = $so_no;
            $sales_return_data->brand_id = $request->brand_id[$key];
            $sales_return_data->item_id = $request->product_id[$key];
            $sales_return_data->desc = $request->product_id[$key] ?? 0;
            $sales_return_data->thickness = 0;
            $sales_return_data->diameter = 0;
            $sales_return_data->item_description = $request->item_description[$key] ?? null;
            $sales_return_data->qty = $request->qty[$key] ?? 0;
            $sales_return_data->foc = $request->foc[$key] ?? 0;
            $sales_return_data->mrp_price = $request->mrp_price[$key] ?? 0.0;
            $sales_return_data->rate = $request->rate[$key] ?? 0;
            $sales_return_data->sub_total = $request->gross_amount[$key] ?? 0.0;
            $sales_return_data->tax = $request->tax[$key] ?? 0.0;
            $sales_return_data->tax_amount = $request->total_tax[$key] ?? 0.0;
            $sales_return_data->amount = $request->total_amount[$key] ?? 0.0;
            $sales_return_data->printing = $request->printing[$key] ?? null;
            $sales_return_data->special_instruction = $request->special_ins[$key] ?? null;
            $sales_return_data->delivery_date = $request->delivery_date[$key] ?? null;
            $sales_return_data->discount_percent_1 = $request->discount1[$key];
            $sales_return_data->discount_amount_1 = $request->discount_amount1[$key];
            $sales_return_data->discount_percent_2 = $request->discount2[$key];
            $sales_return_data->discount_amount_2 = $request->discount_amount2[$key];
            $sales_return_data->warehouse_id = $request->warehouses[$key];
            $sales_return_data->status = 1; // Pending approval
            $sales_return_data->date = date('Y-m-d');
            $sales_return_data->username = auth()->user()->name;
            $sales_return_data->save();

            $grand_amount += $request->total_amount[$key];

            // Stock Entry for Sales Return (status = 100)
          // Stock Entry for Sales Return (status = 100)
$item_type = CommonHelper::get_item_type($request->product_id[$key]);



// if ($item_type != 2) {
    // Temporary fix for average cost
    $average_cost = $request->rate[$key]; // Direct rate use karein
    
    $stock_data = [
        'main_id' => $master_id,
        'master_id' => $sales_return_data->id,
        'voucher_no' => $so_no,
        'voucher_date' => date('Y-m-d'),
        'supplier_id' => 0,
        'customer_id' => $request->customer_name,
        'batch_code' => $request->batch_code[$key] ?? 0,
        'voucher_type' => 6,
        'rate' => $request->rate[$key],
        'sub_item_id' => $request->product_id[$key],
        'qty' => $request->qty[$key],
        'discount_percent' => $request->discount1[$key] ?? 0,
        'discount_amount' => $request->discount_amount1[$key] ?? 0,
        'amount' => 0,
        'status' => 100, // Pending approval
        'warehouse_id' => $request->warehouses[$key],
        'username' => auth()->user()->name,
        'opening'       => 0,
        'created_date'  => date('Y-m-d'),
        //  'Territory'     => isset($row[9]) ? $row[9] : null,
       
        
    ];

    DB::Connection('mysql2')->table('stock')->insert($stock_data);


// }

            // Transaction Supply Chain Entry (status = 100)
          // Transaction Supply Chain Entry (status = 100)
$transaction_supply_data = [
    'main_id' => $master_id,
    'master_id' => $sales_return_data->id,
    'voucher_no' => $so_no,
    'item_id' => $request->product_id[$key],
    'qty' => $request->qty[$key],
    'amount' => $request->total_amount[$key],
    'opening' => 0,
    'status' => 100, // Pending approval
    'username' => auth()->user()->name,
    'voucher_type' => 4,
    'voucher_date' => date('Y-m-d'), // Yahan voucher_date use karein
    'ref_no' => $so_no, // Optional: reference number
    'ref_date' => date('Y-m-d') // Optional: reference date
];

DB::Connection('mysql2')->table('transaction_supply_chain')->insert($transaction_supply_data);
            $count++;
        endforeach;

        // Transaction Entries (status = 100)
        $subject = 'Sales Return Created ' . $so_no;

        // Customer Account Entry
        $customer_acc_id = SalesHelper::get_customer_acc_id($byers_id);
        
        $transaction = new Transactions();
        $transaction = $transaction->SetConnection('mysql2');
        $transaction->voucher_no = $so_no;
        $transaction->v_date = date('Y-m-d');
        $transaction->acc_id = $customer_acc_id;
        $transaction->acc_code = FinanceHelper::getAccountCodeByAccId($customer_acc_id);
        $transaction->particulars = $subject;
        $transaction->opening_bal = 0;
        $transaction->debit_credit = 0;
        $transaction->amount = $request->total_amount_after_sale_tax;
        $transaction->username = auth()->user()->name;
        $transaction->status = 100; // Pending approval
        $transaction->voucher_type = 7;
        $transaction->save();

        // Sales Account Entry
        $sales_account_id = DB::Connection('mysql2')->table('accounts')
            ->where('name', 'LIKE', '%LOCAL SALES%')
            ->where('status', 1)
            ->value('id');

        if ($sales_account_id) {
            $transaction = new Transactions();
            $transaction = $transaction->SetConnection('mysql2');
            $transaction->voucher_no = $so_no;
            $transaction->v_date = date('Y-m-d');
            $transaction->acc_id = $sales_account_id;
            $transaction->acc_code = FinanceHelper::getAccountCodeByAccId($sales_account_id);
            $transaction->particulars = $subject;
            $transaction->opening_bal = 0;
            $transaction->debit_credit = 1;
            $transaction->amount = $request->total_gross_amount;
            $transaction->username = auth()->user()->name;
            $transaction->status = 100; // Pending approval
            $transaction->voucher_type = 7;
            $transaction->save();
        }

        // Sales Tax Entry
        if ($request->total_sales_tax > 0) {
            $sales_tax_account_id = DB::Connection('mysql2')->table('accounts')
                ->where('name', 'LIKE', '%OUTPUT TAX%')
                ->where('status', 1)
                ->value('id');

            if ($sales_tax_account_id) {
                $transaction = new Transactions();
                $transaction = $transaction->SetConnection('mysql2');
                $transaction->voucher_no = $so_no;
                $transaction->v_date = date('Y-m-d');
                $transaction->acc_id = $sales_tax_account_id;
                $transaction->acc_code = FinanceHelper::getAccountCodeByAccId($sales_tax_account_id);
                $transaction->particulars = $subject;
                $transaction->opening_bal = 0;
                $transaction->debit_credit = 1;
                $transaction->amount = $request->total_sales_tax;
                $transaction->username = auth()->user()->name;
                $transaction->status = 100; // Pending approval
                $transaction->voucher_type = 7;
                $transaction->save();
            }
        }

        SalesHelper::sales_activity($so_no, date('Y-m-d'), '0', 100, 'Insert');

        DB::Connection('mysql2')->commit();
        
        return response()->json([
            'success' => true,
            'message' => 'Sales Return Created Successfully (Pending Approval)',
            'saleReturnId' => $master_id,
            'so_no' => $so_no
        ], 200);
        
    } catch (Exception $e) {
        DB::Connection('mysql2')->rollBack();
        return response()->json([
            'success' => false,
            'message' => 'Error creating sales return: ' . $e->getMessage()
        ], 500);
    }
}
    

    public function show(Request $request)
    {
        if ($request->ajax()) {
            $sale_orders = DB::Connection('mysql2')
                                    ->table('sales_return')
                                    ->join('customers', 'sales_return.buyers_id', 'customers.id')
                                    ->select('sales_return.*', 'customers.name');
            // ->where('sales_order.status',1)->select('sales_order.*','customers.name');
            // if(!empty($request->to) && !empty($request->from)){
            //     $from = $request->from;
            //     $to = $request->to;
            //     $sale_orders->whereBetween('sales_order.so_date',[$from,$to]);

            // }
            if (!empty($request->Filter)) {
                $sale_orders->where('sales_return.so_no', 'Like', '%' . $request->SoNo . '%');
            }

            $sale_orders = $sale_orders->get();

            return view('selling.salereturn.listSaleReturnAjax', compact('sale_orders'));
        }

        $username= Subitem::select("username")->groupBy("username")->get();
        return view('selling.salereturn.listSaleReturn', compact('username'));
    }

     public function getlistSaleOrder(Request $request)
    {

        if ($request->ajax()) {
            $sale_orders = DB::Connection('mysql2')->table('sales_return')
                            ->join('customers', 'sales_return.buyers_id', 'customers.id')
                            ->join('sales_return_data', 'sales_return_data.master_id', 'sales_return.id')
                            ->join('subitem', 'subitem.id', 'sales_return_data.item_id');


                              $user = auth()->user();
        if ($user && $user->acc_type === 'user') {
            $territory_ids = json_decode($user->territory_id, true);
            if (!is_array($territory_ids)) {
                $territory_ids = [$user->territory_id];
            }

            $sale_orders->whereIn('customers.territory_id', $territory_ids);
        }

            if ($request->has('search') && $request->search != '') {
                $search = strtolower($request->search); 
                $sale_orders->whereRaw('LOWER(customers.name) LIKE ?', ['%' . $search . '%'])
                ->orWhereRaw('LOWER(sales_return.so_no) LIKE ?', ['%' . $search . '%'])
                ->orWhereRaw('LOWER(subitem.product_name) LIKE ?', ['%' . $search . '%'])
                ->orWhereRaw('LOWER(subitem.sys_no) LIKE ?', ['%'. $search .'%'])
                ->orWhereRaw('LOWER(subitem.product_barcode) LIKE ?', ['%'. $search .'%'])
                ->orWhereRaw('LOWER(subitem.sku_code) LIKE ?', ['%'. $search .'%']);
            }

            if($request->has('username') && $request->username !='') {
                $username = $request->username;
                $sale_orders->when($username, function ($query, $username) {
                    $query->whereIn('subitem.username', $username);
                });
            }
            if($request->has('date') && $request->date !=''){
                $date = $request->date;
                $sale_orders->when($date, function ($query, $date) {
                    $query->whereDate('sales_return.so_date', '=', $date);
                });
            }
        //    $sale_orders->where('sales_return.status', 1);


            // $sale_orders->select('sales_order.*', 'customers.name');
            // ->where('sales_order.status',1)->select('sales_order.*','customers.name');
            // if(!empty($request->to) && !empty($request->from)){
            //     $from = $request->from;
            //     $to = $request->to;
            //     $sale_orders->whereBetween('sales_order.so_date',[$from,$to]);

            // }
            if (!empty($request->Filter)) {
                $sale_orders->where('sales_return.so_no', 'Like', '%' . $request->SoNo . '%');
            }

             $sale_orders->select('sales_return.*', 'customers.name')
                    ->groupBy('sales_return.id');

            $sale_orders = $sale_orders->paginate(request('per_page'));

            return view('selling.salereturn.listSaleReturnAjax', compact('sale_orders'));
        }
    }

    public function viewSaleOrdernew(Request $request)
    {
        $sale_order = Sales_Return::where('id', $request->id)->first();

        
        $sale_order_data = Sale_Return_Data::where('master_id', $request->id)->get();

        return view('selling.salereturn.viewSaleReturnnew', compact('sale_order', 'sale_order_data'));
    }
    // public function approveSaleReturn(Request $request) {
    //      try {
            
    //         DB::beginTransaction();
    //         $sale_order = Sales_Return::find($request->id);

    //         $sale_order->status = 1;
    //         $sale_order->save();

            

    //         foreach($sale_order->saleReturnData as $sale_order_data) {
    //             $data = [
    //                 'voucher_type'  => 7,
    //                 'sub_item_id'   => $sale_order_data->id,
    //                 'batch_code'    => 0,
    //                 'qty'           => $sale_order_data->qty,
    //                 'amount'        => $sale_order->amount,
    //                 'warehouse_id'  => !$sale_order_data->warehouse_id ? 1 : $sale_order_data->warehouse_id,
    //                 'opening'       => 1,
    //                 'created_date'  => date('Y-m-d'),
    //                 'username'      => $sale_order_data->username,
    //             ];
    //             try {
    //                Stock::create($data);
    //             } catch(Exception $e) {
    //                 dd($e);
    //             }

    //         }
           
    //     } catch (Exception $ex) {
    //         DB::rollBack();
    //     }
    //     return redirect()->route('listSaleReturn');

    //     // return Redirect::to('sales/viewDeliveryNoteList?pageType='.Input::get('pageType').'&&parentCode='.Input::get('parentCode').'&&m='.$_GET['m'].'#SFR');
   
    // }

    public function approveSaleReturn(Request $request) {
    try {
        DB::Connection('mysql2')->beginTransaction();
        
        $sale_return = Sales_Return::find($request->id);
        if (!$sale_return) {
            return redirect()->back()->with('error', 'Sales Return not found');
        }

        // Update main sales return status to 1
        $sale_return->status = 1;
        $sale_return->save();

        // Update sales return data status to 1
        DB::Connection('mysql2')->table('sale_return_data')
            ->where('master_id', $sale_return->id)
            ->update(['status' => 1]);

        // Update stock entries status to 1
        DB::Connection('mysql2')->table('stock')
            ->where('voucher_no', $sale_return->so_no)
            ->where('status', 100)
            ->update(['status' => 1]);

        // Update transaction supply chain status to 1
        DB::Connection('mysql2')->table('transaction_supply_chain')
            ->where('voucher_no', $sale_return->so_no)
            ->where('status', 100)
            ->update(['status' => 1]);

        // Update transactions status to 1
        DB::Connection('mysql2')->table('transactions')
            ->where('voucher_no', $sale_return->so_no)
            ->where('status', 100)
            ->update(['status' => 1]);

        // Update sales activity status to 1
        SalesHelper::sales_activity($sale_return->so_no, date('Y-m-d'), '0', 1, 'Approved');

        DB::Connection('mysql2')->commit();

        return redirect()->route('listSaleReturn')->with('success', 'Sales Return approved successfully');
   
    } catch (Exception $ex) {
        DB::Connection('mysql2')->rollBack();
        return redirect()->back()->with('error', 'Error approving sales return: ' . $ex->getMessage());
    }
}
    public function edit($id) {
        $warehouses = DB::connection("mysql2")->table("warehouse")->get();
        $sale_orders = DB::Connection('mysql2')->table('sales_return')->find($id);
        // $sales_order_data =    DB::Connection('mysql2')->table('sales_order_data')->where('master_id',$id)->get();

        $sales_order_data = DB::Connection('mysql2')
            ->table('sales_return_data')
            // ->join('subitem','subitem.id','sales_order_data.item_id')
            // ->join('uom','uom.id','subitem.uom')
            // ->select('sales_order_data.id as sale_order_data_id','subitem.*','uom.*','sales_order_data.*')
            ->where('sales_return_data.master_id', $id)
            // ->where('sales_order_data.production_status',0)
            ->where('sales_return_data.status', 1)

            ->get();


            

        // echo "<pre>";

        // print_r($sales_order_data);
        // exit();

        return view($this->path . 'editSaleReturn', compact('sale_orders', 'sales_order_data', 'warehouses'));
    }

    public function update(Request $request, $id)
    {
       
        DB::connection('mysql2')->beginTransaction();
        try {
            $data = $request->product_id;
            if (empty($data)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product Details cannot be null'
                ], 422);
                // return redirect()
                //     ->route('editSaleOrder', ['id' => $id])
                //     ->with('error', 'Product Details cannot be null');
            }

            $grand_amount = 0;
            $sale_tax_amount_total = 0;

            // Find the existing sales order
            $sales_return = Sales_Return::findOrFail($id);
            $sales_return->SetConnection('mysql2');

            $customerDetail = DB::connection('mysql2')
                ->table('customers')
                ->where('id', $request->customer_name)
                ->where('status', 1)
                ->select('*')
                ->first();

            $virtualWarehouseCheck = $customerDetail->CustomerType == '3' || $customerDetail->CustomerType == 3 ? 1 : 0;

           

            // Update the sales order details
            $sales_return->destination = $request->address;
            $sales_return->buyers_id = $request->customer_name;
            $sales_return->phone_no = $request->phone_no ?? null;
            $sales_return->address = $request->address ?? null;
            $sales_return->branch = $request->branch ?? null;
            $sales_return->sales_person = $request->saleperson ?? null;
            $sales_return->balance_amount = $request->balance_amount ?? 0.0;
            $sales_return->credit_limit = $request->credit_limit ?? 0.0;
            $sales_return->current_balance_due = $request->balance_amount + $request->total_amount_after_sale_tax ?? 0.0;
            $sales_return->virtual_warehouse_check = $virtualWarehouseCheck;
            $sales_return->total_amount_after_sale_tax = $request->total_amount_after_sale_tax ?? 0;
            $sales_return->total_amount = $request->total_gross_amount ?? 0;
            $sales_return->sales_tax_rate = $request->total_sales_tax ?? 0;
            $sales_return->total_qty = $request->total_qty ?? 0;
             $sales_return->sale_taxes_id = $request->sale_taxes_id ?? 0;
            $sales_return->sale_taxes_amount_total = $request->sale_taxes_amount_total ?? 0;
            $sales_return->sale_taxes_amount_rate = $request->sale_taxes_amount_rate ?? 0;
            $sales_return->username = auth()->user()->name;
             $sales_return->remark = $request->remark;
            $sales_return->date = date('Y-m-d');
            $sales_return->save();

            // Delete existing sales order data
            Sale_Return_Data::where('master_id', $id)->delete();

            $master_id = $sales_return->id;
            foreach ($data as $key => $row) {
                $sale_return_data = new Sale_Return_Data();
                $sale_return_data->SetConnection('mysql2');
                $sale_return_data->master_id = $master_id;
                $sale_return_data->so_no = $sales_return->so_no;
                $sale_return_data->brand_id = $request->brand_id[$key];
                $sale_return_data->item_id = $request->product_id[$key];
                $sale_return_data->desc = $request->product_id[$key] ?? 0;
                $sale_return_data->item_description = $request->item_description[$key] ?? null;
                $sale_return_data->qty = $request->qty[$key] ?? 0;
                $sale_return_data->foc = $request->foc[$key] ?? 0;
                $sale_return_data->mrp_price = $request->mrp_price[$key] ?? 0.0;
                $sale_return_data->rate = $request->rate[$key] ?? 0;
                $sale_return_data->sub_total = $request->gross_amount[$key] ?? 0.0;
                $sale_return_data->tax = $request->tax[$key] ?? 0.0;
                $sale_return_data->tax_amount = $request->total_tax[$key] ?? 0.0;
                $sale_return_data->amount = $request->total_amount[$key] ?? 0.0;
                $sale_return_data->printing = $request->printing[$key] ?? null;
                $sale_return_data->special_instruction = $request->special_ins[$key] ?? null;
                $sale_return_data->delivery_date = $request->delivery_date[$key] ?? null;
                $sale_return_data->discount_percent_1 = $request->discount1[$key];
                $sale_return_data->discount_amount_1 = $request->discount_amount1[$key];
                $sale_return_data->discount_percent_2 = $request->discount2[$key];
                $sale_return_data->discount_amount_2 = $request->discount_amount2[$key];
                $sale_return_data->status = 1;
                $sale_return_data->date = date('Y-m-d');
                $sale_return_data->username = auth()->user()->name;
                $sale_return_data->warehouse_id = $request->warehouses[$key];
                $sale_return_data->save();

                $grand_amount += $request->total_amount[$key];
            }

            SalesHelper::sales_activity($sales_return->so_no, date('Y-m-d'), '0', 1, 'Update');

            DB::connection('mysql2')->commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Sale Order Updated',
                'saleOrderId' => $master_id
            ], 200);
            // return redirect()->route('listSaleOrder')->with('dataUpdate', 'Sale Order Updated');
        } catch (Exception $e) {
            DB::connection('mysql2')->rollBack();
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
            // return redirect()
            //     ->route('editSaleOrder', ['id' => $id])
            //     ->with('error', $e->getMessage());
        }
    }

      public function destroy($id)
        {
            // Set sale_order.status = 0)
            DB::connection('mysql2')->table('sales_return')
                ->where('id', $id)
                ->update(['status' => 0]);

            // Set related sales_order_data.status = 0
            DB::connection('mysql2')->table('sales_return_data')
                ->where('master_id', $id)
                ->update(['status' => 0]);

            return redirect()->back()->with('success', 'Sale order deleted successfully.');
        }
}
