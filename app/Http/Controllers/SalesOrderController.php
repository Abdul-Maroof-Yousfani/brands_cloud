<?php

namespace App\Http\Controllers;

use App\Helpers\ReuseableCode;
use App\Models\DeliveryNote;
use App\Models\DeliveryNoteData;
use App\Models\Prospect;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Helpers\CommonHelper;
use App\Helpers\FinanceHelper;
use App\Models\Sales_Order;
use App\Models\Sales_Order_Data;
use App\Helpers\SalesHelper;
use App\Models\Contact;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
use Config;
use Redirect;
use Session;
use Exception;
use App\Models\Transactions;
use App\Models\SaleQuotation;
use App\Models\Subitem;

class SalesOrderController extends Controller
{
    public $path;

    public function __construct()
    {
        $this->path = 'selling.saleorder.';
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function getCustomerAssignedWarehouseByWarehouseId(Request $request)
    // {
    //     $warehouse_from = $request->warehouse_id;
    //     $warehouse_to = 0;

    //     $company_warehouse = DB::connection('mysql2')
    //         ->table('warehouse')
    //         ->where('is_virtual', 0)
    //         ->when($warehouse_from != 0, function ($query) use ($warehouse_from) {
    //             // If warehouse_from is not 0, filter by warehouse_from
    //             return $query->where('warehouse.id', $warehouse_from);
    //         })
    //         ->select('warehouse.id', 'warehouse.name')
    //         ->groupBy('warehouse.id', 'warehouse.name')
    //         ->get();

    //     $store_warehouse = DB::connection('mysql2')
    //         ->table('warehouse')
    //         ->where('is_virtual', 1)
    //         ->leftJoin('stock', function ($join) use ($request) {
    //             $join->on('stock.warehouse_id', '=', 'warehouse.id')->where('stock.sub_item_id', $request->item);
    //         })
    //         ->when($warehouse_to, function ($query) use ($warehouse_to) {
    //             // If warehouse_from is not 0, filter by warehouse_from
    //             return $query->where('warehouse.id', $warehouse_to);
    //         })
    //         ->select('warehouse.id', 'warehouse.name', DB::raw('COALESCE(SUM(stock.qty), 0) as total_qty'))
    //         ->groupBy('warehouse.id', 'warehouse.name')
    //         ->get();

    //     $store_warehouses = [];
    //     $company_warehouses = [];
    //     foreach ($store_warehouse as $sw) {
    //         $qty = ReuseableCode::get_stock($request->item, $sw->id);

    //         $store_warehouses[] = [
    //             'id' => $sw->id,
    //             'name' => $sw->name,
    //             'total_qty' => $qty,
    //         ];
    //     }

    //     $store_total_quantity = 0;
    //     foreach ($store_warehouse as $sw) {
    //         $qty = ReuseableCode::get_stock($request->item, $sw->id);
    //         $store_total_quantity += $qty;
    //     }
    //     foreach ($store_warehouse as $sw) {
    //         $qty = ReuseableCode::get_stock($request->item, $sw->id);

    //         $store_warehouses[] = [
    //             'id' => $sw->id,
    //             'name' => $sw->name,
    //             'total_qty' => $qty,
    //         ];
    //     }

    //     $company_total_quantity = 0; // Initialize total quantity variable
    //     foreach ($company_warehouse as $cw) {
    //         $qty = ReuseableCode::get_stock($request->item, $cw->id);
    //         $company_total_quantity += $qty;
    //     }

    //     //        dd($company_total_quantity,$store_total_quantity);

    //     foreach ($company_warehouse as $cw) {
    //         $qty = ReuseableCode::get_stock($request->item, $cw->id);

    //         $company_warehouses[] = [
    //             'id' => $cw->id,
    //             'name' => $cw->name,
    //             'total_qty' => $qty,
    //         ];
    //     }

    //     //        $assinedWiD=  $company_warehouse->pluck('id')->toArray();
    //     //        $stock =  DB::connection('mysql2')->table('stock')->where('sub_item_id',$request->item)->whereIn('warehouse_id',$assinedWiD)->get();
    //     $total_sales_order = ReuseableCode::get_reserved_so($request->item, $request->cusId);
    //     $data = [
    //         "total_so" => $total_sales_order,
    //         'company_warehouse' => $company_warehouses,
    //         'store_warehouse' => $store_warehouses,
    //         'company_total_quantity' => $company_total_quantity,
    //         'store_total_quantity' => $store_total_quantity,
    //     ];

    //     return response()->json($data);
    // }

     public function getCustomerAssignedWarehouseByWarehouseId(Request $request)
    {
        $itemId = $request->item;
        $warehouseFrom = $request->warehouse_id;

        // ✅ Single Query (company + store + stock)
        $warehouses = DB::connection('mysql2')
            ->table('warehouse')
            ->leftJoin('stock', function ($join) use ($itemId) {
                $join->on('stock.warehouse_id', '=', 'warehouse.id')
                    ->where('stock.sub_item_id', $itemId);
            })
            ->select(
                'warehouse.id',
                'warehouse.name',
                'warehouse.is_virtual',
                DB::raw('COALESCE(SUM(stock.qty), 0) AS total_qty')
            )
            ->when($warehouseFrom != 0, function ($query) use ($warehouseFrom) {
                return $query->where('warehouse.id', $warehouseFrom);
            })
            ->groupBy('warehouse.id', 'warehouse.name', 'warehouse.is_virtual')
            ->get();

        // Arrays & Totals
        $company_warehouses = [];
        $store_warehouses   = [];

        $company_total_qty = 0;
        $store_total_qty   = 0;

        foreach ($warehouses as $w) {
            $warehouseData = [
                'id'        => $w->id,
                'name'      => $w->name,
                'total_qty' => $w->total_qty,
            ];

            if ($w->is_virtual == 0) {
                $company_warehouses[] = $warehouseData;
                $company_total_qty += $w->total_qty;
            } else {
                $store_warehouses[] = $warehouseData;
                $store_total_qty += $w->total_qty;
            }
        }

        // Final Response
        return response()->json([
            'total_so'               => ReuseableCode::get_reserved_so($itemId, $request->cusId),
            'company_warehouse'      => $company_warehouses,
            'store_warehouse'        => $store_warehouses,
            'company_total_quantity' => $company_total_qty,
            'store_total_quantity'   => $store_total_qty,
        ]);
    }

    public function getCustomerAssignedWarehouse(Request $request)
    {
        $customer = DB::Connection('mysql2')
            ->table('customers')
            ->where('id', $request->cusId)
            ->first();

        $company_warehouse = DB::connection('mysql2')
            ->table('warehouse')
            ->where('is_virtual', 0)
            ->when($customer->warehouse_from != 0, function ($query) use ($customer) {
                // If warehouse_from is not 0, filter by warehouse_from
                return $query->where('warehouse.id', $customer->warehouse_from);
            })
            ->select('warehouse.id', 'warehouse.name')
            ->groupBy('warehouse.id', 'warehouse.name')
            ->get();

        $store_warehouse = DB::connection('mysql2')
            ->table('warehouse')
            ->where('is_virtual', 1)
            ->leftJoin('stock', function ($join) use ($request) {
                $join->on('stock.warehouse_id', '=', 'warehouse.id')->where('stock.sub_item_id', $request->item);
            })
            ->when($customer->warehouse_to != 0, function ($query) use ($customer) {
                // If warehouse_from is not 0, filter by warehouse_from
                return $query->where('warehouse.id', $customer->warehouse_to);
            })
            ->select('warehouse.id', 'warehouse.name', DB::raw('COALESCE(SUM(stock.qty), 0) as total_qty'))
            ->groupBy('warehouse.id', 'warehouse.name')
            ->get();

        $store_warehouses = [];
        $company_warehouses = [];
        foreach ($store_warehouse as $sw) {
            $qty = ReuseableCode::get_stock($request->item, $sw->id);

            $store_warehouses[] = [
                'id' => $sw->id,
                'name' => $sw->name,
                'total_qty' => $qty,
            ];
        }

        $store_total_quantity = 0;
        foreach ($store_warehouse as $sw) {
            $qty = ReuseableCode::get_stock($request->item, $sw->id);
            $store_total_quantity += $qty;
        }
        foreach ($store_warehouse as $sw) {
            $qty = ReuseableCode::get_stock($request->item, $sw->id);

            $store_warehouses[] = [
                'id' => $sw->id,
                'name' => $sw->name,
                'total_qty' => $qty,
            ];
        }

        $company_total_quantity = 0; // Initialize total quantity variable
        foreach ($company_warehouse as $cw) {
            $qty = ReuseableCode::get_stock($request->item, $cw->id);
            $company_total_quantity += $qty;
        }

        //        dd($company_total_quantity,$store_total_quantity);

        foreach ($company_warehouse as $cw) {
            $qty = ReuseableCode::get_stock($request->item, $cw->id);

            $company_warehouses[] = [
                'id' => $cw->id,
                'name' => $cw->name,
                'total_qty' => $qty,
            ];
        }

        //        $assinedWiD=  $company_warehouse->pluck('id')->toArray();
        //        $stock =  DB::connection('mysql2')->table('stock')->where('sub_item_id',$request->item)->whereIn('warehouse_id',$assinedWiD)->get();
        $total_sales_order = ReuseableCode::get_reserved_so($request->item, $request->cusId);
        $data = [
            "total_so" => $total_sales_order,
            'company_warehouse' => $company_warehouses,
            'store_warehouse' => $store_warehouses,
            'company_total_quantity' => $company_total_quantity,
            'store_total_quantity' => $store_total_quantity,
        ];

        return response()->json($data);
    }
    public function listSaleOrder(Request $request)
    {
        if ($request->ajax()) {
            
            $territory_ids = json_decode(auth()->user()->territory_id); 
            $sale_orders = DB::connection("mysql2")->table("sales_order")->get();
            $sale_orders = DB::Connection('mysql2')
                                ->table('sales_order')
                                ->join('customers', 'sales_order.buyers_id', 'customers.id')
                                
                                ->select('sales_order.*', 'customers.name');
            // ->where('sales_order.status',1)->select('sales_order.*','customers.name');
            // if(!empty($request->to) && !empty($request->from)){
            //     $from = $request->from;
            //     $to = $request->to;
            //     $sale_orders->whereBetween('sales_order.so_date',[$from,$to]);

            // }
            if (!empty($request->Filter)) {
                $sale_orders->where('sales_order.so_no', 'Like', '%' . $request->SoNo . '%');
            }

            $sale_orders = $sale_orders->get();

            return view('selling.saleorder.listSaleOrderAjax', compact('sale_orders'));
        }
        $username= Subitem::select("username")->groupBy("username")->get();
        return view('selling.saleorder.listSaleOrder', compact('username'));
    }
    public function getlistSaleOrder(Request $request)
    {

        if ($request->ajax()) {
            
            $territory_ids = json_decode(auth()->user()->territory_id); 

            $sale_orders = DB::Connection('mysql2')->table('sales_order')
            ->join('customers', 'sales_order.buyers_id', 'customers.id')
            ->join('sales_order_data', 'sales_order_data.master_id', 'sales_order.id')
            ->join('subitem', 'subitem.id', 'sales_order_data.item_id');

            $m = Session::get("run_company");
            if($m == 3) {
                $sale_orders = $sale_orders->whereIn('customers.territory_id', $territory_ids);
            } else {
                $territories = (DB::connection("mysql2")->table("territories")->select("id")->get()->pluck("id"))->toArray();
                dd($territories);
                $sale_orders = $sale_orders->whereIn('customers.territory_id', $territories);
            }


        $user = Auth::user();
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
                ->orWhereRaw('LOWER(sales_order.so_no) LIKE ?', ['%' . $search . '%'])
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
                    $query->whereDate('sales_order.so_date', '=', $date);
                });
            }
           $sale_orders->where('sales_order.status', 1);


            // $sale_orders->select('sales_order.*', 'customers.name');
            // ->where('sales_order.status',1)->select('sales_order.*','customers.name');
            // if(!empty($request->to) && !empty($request->from)){
            //     $from = $request->from;
            //     $to = $request->to;
            //     $sale_orders->whereBetween('sales_order.so_date',[$from,$to]);

            // }
            if (!empty($request->Filter)) {
                $sale_orders->where('sales_order.so_no', 'Like', '%' . $request->SoNo . '%');
            }

             $sale_orders->select('sales_order.*', 'customers.name')
                    ->groupBy('sales_order.id')->orderBy('sales_order.id', 'DESC');

            $sale_orders = $sale_orders->get();
            // $sale_orders = $sale_orders->paginate(request('per_page'));

            return view('selling.saleorder.listSaleOrderAjax', compact('sale_orders'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createSaleOrder()
    {
        return view($this->path . 'createSaleOrder');
    }

    // new code
    public function viewSaleOrdernew(Request $request)
    {
        $sale_order = Sales_Order::where('id', $request->id)->first();

        
        $sale_order_data = Sales_Order_Data::where('master_id', $request->id)->get();

        return view('selling.saleorder.viewSaleOrdernew', compact('sale_order', 'sale_order_data'));
    }

    public function approveSaleOrder(Request $request)
    {
        try {
            
            DB::beginTransaction();
            $sale_order = Sales_Order::find($request->id);

            $sale_order->status = 1;
            $sale_order->save();

            $so_data = $sale_order;
            $deliveryNote = DeliveryNote::create([
                "master_id" => $so_data->id,
                "gd_no" => SalesHelper::get_unique_no_delivery_note(date('y'), date('m')),
                "lot_no" => null,
                "gd_date" => \Carbon\Carbon::now()->format("Y-m-d"),
                "model_terms_of_payment" => $so_data->model_terms_of_payment,
                "so_no" => $so_data->so_no,
                "so_date" => $so_data->so_date,
                "other_refrence" => null,
                "order_no" => $so_data->purchase_order_no,
                "order_date" => $so_data->purchase_order_date,
                "despacth_document_date" => null,
                "despacth_document_no" => null,
                "despacth_through" => null,
                "destination" => $so_data->destination,
                "terms_of_delivery" => "",
                "buyers_id" => $so_data->buyers_id,
                "due_date" => "",
                "description" => "",
                "sales_tax_amount" => CommonHelper::check_str_replace($request->sales_tax_apply),
                "status" => 0,
                "virtual_warehouse_check" => $so_data->virtual_warehouse_check,
                "date" => $so_data->date,
                "username" => $so_data->username,
            ]);
            
            try {
                foreach($sale_order->saleOrderData as $data) {
                    $warehouse_name = CommonHelper::buyers_id_with_warehouse_name($so_data->buyers_id);
                    $warehouse_id = CommonHelper::get_warehouse_id_by_name($warehouse_name);
    
                    $data = DeliveryNoteData::create([
                        "master_id" => $deliveryNote->id,
                        "so_id" => $data->so_no,
                        "so_data_id" => $data->id,
                        "gd_no" => $deliveryNote->gd_no,
                        "gd_date" => $deliveryNote->gd_date,
                        "item_id" => $data->item_id,
                        "desc" => $data->desc,
                        "qty" => $data->qty,
                        "foc" => $data->foc,
                        "rate" => $data->rate,
                        "mrp_price" => $data->mrp_price,
                        "tax" => $data->tax,
                        "tax_amount" => $data->tax_amount,
                        "amount" => $data->amount,
                        "status" => 1,
                        "username" => $data->username,
                        "date" => $data->date,
                        "warehouse_id" => $warehouse_id,
                        "bundles_id" => 0,
                        "groupby" => DeliveryNoteData::count() + 1 ,
                        "batch_code" => 0
                    ]);
                
                }
            } catch(\Exception $e) {
                dd($e);
            }
            DB::commit();

            DB::Connection('mysql2')->commit();
        } catch (Exception $ex) {
            DB::rollBack();
        }
        return redirect()->route('listSaleOrder');

        // return Redirect::to('sales/viewDeliveryNoteList?pageType='.Input::get('pageType').'&&parentCode='.Input::get('parentCode').'&&m='.$_GET['m'].'#SFR');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::Connection('mysql2')->beginTransaction();

        // dd($request->all());
        try {
            $data = $request->product_id;
            if (empty($data)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product Details cannot be null'
                ], 422);
            }

            $byers_id = $request->customer;
            $grand_amount = 0;
            $sale_tax_mount_total = 0;
            $so_no = CommonHelper::generateUniquePosNo('sales_order', 'so_no', 'SO');

            $customerDetail = DB::connection('mysql2')
                ->table('customers')
                ->where('id', $request->customer_name)
                ->where('status', 1)
                ->select('*')
                ->first();

            $virtualWarehouseCheck = 0;
            if ($customerDetail->CustomerType == '3' || $customerDetail->CustomerType == 3) {
                $virtualWarehouseCheck = 1;
            }
            $sales_order = new Sales_Order();
            $sales_order = $sales_order->SetConnection('mysql2');
            $sales_order->so_no = $so_no;
            $sales_order->so_date = $request->sale_order_date;
            $sales_order->destination = $request->address;
            $sales_order->buyers_id = $request->customer_name;
            $sales_order->phone_no = $request->phone_no ?? null;
            $sales_order->address = $request->address ?? null;
            $sales_order->branch = $request->branch ?? null;
            $sales_order->sales_person = $request->saleperson ?? null;
            $sales_order->balance_amount = $request->balance_amount ?? 0.0;
            $sales_order->credit_limit = $request->credit_limit ?? 0.0;
            $sales_order->current_balance_due = $request->balance_amount + $request->total_amount_after_sale_tax ?? 0.0;
            $sales_order->virtual_warehouse_check = $virtualWarehouseCheck ?? 0;
            $sales_order->total_amount_after_sale_tax = $request->total_amount_after_sale_tax ?? 0;
            $sales_order->status = 1;
            $sales_order->date = $request->sale_order_date;
            $sales_order->total_amount = $request->total_gross_amount ?? 0;
            $sales_order->sales_tax_rate = $request->total_sales_tax ?? 0;
            $sales_order->sales_tax_group = 13;
            $sales_order->total_qty = $request->total_qty ?? 0;
            $sales_order->sale_taxes_id = $request->sale_taxes_id ?? 0;
            $sales_order->sale_taxes_amount_total = $request->sale_taxes_amount_total ?? 0;
            $sales_order->sale_taxes_amount_rate = $request->sale_taxes_amount_rate ?? 0;
            $sales_order->warehouse_from = $request->warehouse;
            $sales_order->principal_group_id = $request->principal_group;
            // $sales_order->purchase_order_no=$request->purchase_order_no ?? '';
            // $sales_order->purchase_order_date=$request->purchase_order_date;
            // $sales_order->purchase_order_contract=$request->quotation_id ?? '';

            // if(!empty($request->sale_taxt_group))
            // {
            //     $sale_tax_id = explode(',',$request->sale_taxt_group);
            //     $sales_order->sales_tax_group =$sale_tax_id[0];
            //     $sales_order->sales_tax_rate =  $request->sale_tax_rate;

            // }

            // if(!empty($request->further_taxes_group))
            // {

            //     $further_taxes_group = explode(',',$request->further_taxes_group);
            //     $sales_order->further_taxes_group = $further_taxes_group[0];
            //     $sales_order->sales_tax_further = $request->sales_tax_further;

            // }

            // $sales_order->status=1;

            $sales_order->remark = $request->remark;
            $sales_order->username = Auth::user()->name;
            $sales_order->date = $request->sale_order_date;
            $sales_order->save();

            $master_id = $sales_order->id;
            $data = $request->product_id;

            $count = 1;
            foreach ($data as $key => $row):
                $sales_order_data = new Sales_Order_Data();
                $sales_order_data = $sales_order_data->SetConnection('mysql2');
                $sales_order_data->master_id = $master_id;
                $sales_order_data->so_no = $so_no;
                $sales_order_data->brand_id = $request->brand_id[$key];
                $sales_order_data->item_id = $request->product_id[$key];
                $sales_order_data->desc = $request->product_id[$key] ?? 0;
                $sales_order_data->thickness = 0;
                $sales_order_data->diameter = 0;
                $sales_order_data->item_description = $request->item_description[$key] ?? null;
                $sales_order_data->qty = $request->qty[$key] ?? 0;
                $sales_order_data->foc = $request->foc[$key] ?? 0;
                $sales_order_data->mrp_price = $request->mrp_price[$key] ?? 0.0;
                $sales_order_data->rate = $request->rate[$key] ?? 0;
                $sales_order_data->sub_total = $request->gross_amount[$key] ?? 0.0;
                $sales_order_data->tax = $request->tax[$key] ?? 0.0;
                $sales_order_data->tax_amount = $request->total_tax[$key] ?? 0.0;
                $sales_order_data->amount = $request->total_amount[$key] ?? 0.0;
                $sales_order_data->printing = $request->printing[$key] ?? null;
                $sales_order_data->warehouse_id = $request->warehouse;

                $sales_order_data->special_instruction = $request->special_ins[$key] ?? null;
                $sales_order_data->delivery_date = $request->delivery_date[$key] ?? null;

                $sales_order_data->discount_percent_1 = $request->discount1[$key] ?? null;
                $sales_order_data->discount_amount_1 = $request->discount_amount1[$key] ?? null;

                $sales_order_data->discount_percent_2 = $request->discount2[$key] ?? null;
                $sales_order_data->discount_amount_2 = $request->discount_amount2[$key] ?? null;

                // $sales_order_data->amount = $request->total[$key];
                // $sales_order_data->delivery_type=$request->total[$key];   Delivery type
                // $sales_order_data->tax = $request->sale_tax_rate;

                // $sale_tax_mount =  $request->total[$key]/100*$request->sale_tax_rate;

                // $sale_tax_mount_total  += $sale_tax_mount;

                // $sales_order_data->tax_amount =   $sale_tax_mount;

                // $sales_order_data->sub_total =   $sale_tax_mount+$request->total[$key];
                $sales_order_data->status = 1;
                $sales_order_data->date = $request->sale_order_date;
                $sales_order_data->username = Auth::user()->name;
                // $sales_order_data->groupby= $count;
                $sales_order_data->save();

                $grand_amount += $request->total_amount[$key] ?? null;

                $count++;
            endforeach;
            //  if(!empty($request->quotation_id))
            // {
            //     $s_qt =  SaleQuotation::find($request->quotation_id);
            //     $s_qt->so_status =1;
            //     $s_qt->save();
            // }

            // $sales_order->total_amount = $grand_amount;
            // $sales_order->total_amount_after_sale_tax = $request->grand_total_with_tax;
            // $sales_order->save();
            SalesHelper::sales_activity($so_no, date('Y-m-d'), '0', 1, 'Insert');
            $voucher_no = $so_no;
            $subject = 'Sales Order Created ' . $so_no;

            //  Customer Entry
            //    $customer =  Customer::find($byers_id);
            //     $transaction=new Transactions();
            //     $transaction=$transaction->SetConnection('mysql2');
            //     $transaction->voucher_no=$so_no;
            //     $transaction->v_date=date('Y-m-d');
            //     $transaction->acc_id = $customer->acc_id;
            //     $transaction->acc_code=FinanceHelper::getAccountCodeByAccId($customer->acc_id);
            //     $transaction->particulars=$subject;
            //     $transaction->opening_bal=0;
            //     $transaction->debit_credit=1;
            //     $transaction->amount= $request->grand_total_with_tax;
            //     $transaction->username=Auth::user()->name;
            //     $transaction->status=1;
            //     $transaction->voucher_type=4;
            //     $transaction->save();

            //     //  Sale Account Rvene
            //    $account_sale =  DB::connection('mysql2')->table('accounts')->where('name','Like','%LOCAL SALES - PIPE%')->first();

            //     $transaction=new Transactions();
            //     $transaction=$transaction->SetConnection('mysql2');
            //     $transaction->voucher_no=$so_no;
            //     $transaction->v_date=date('Y-m-d');
            //     $transaction->acc_id= $account_sale->id;
            //     $transaction->acc_code=FinanceHelper::getAccountCodeByAccId($account_sale->id);
            //     $transaction->particulars= $subject;
            //     $transaction->opening_bal=0;
            //     $transaction->debit_credit=0;
            //     $transaction->amount= $grand_amount;
            //     $transaction->username=Auth::user()->name;;
            //     $transaction->status=1;
            //     $transaction->voucher_type=4;
            //     $transaction->save();

            //     // Sale_ Tax Amount
            //     $account_sale_tax =  DB::connection('mysql2')->table('accounts')->where('name','Like','%OUTPUT TAX (SALES)%')->first();

            //     $transaction=new Transactions();
            //     $transaction=$transaction->SetConnection('mysql2');
            //     $transaction->voucher_no=$so_no;
            //     $transaction->v_date=date('Y-m-d');
            //     $transaction->acc_id=$account_sale_tax->id;
            //     $transaction->acc_code=FinanceHelper::getAccountCodeByAccId($account_sale_tax->id);
            //     $transaction->particulars= $subject;
            //     $transaction->opening_bal=0;
            //     $transaction->debit_credit=0;
            //     $transaction->amount=$sale_tax_mount_total;
            //     $transaction->username=Auth::user()->name;;
            //     $transaction->status=1;
            //     $transaction->voucher_type=4;
            //     $transaction->save();

            // exit();
            DB::Connection('mysql2')->commit();
            // return redirect()->route('createSaleOrder')->with('dataInsert', 'Sale Order Inserted');
            return response()->json([
                'success' => true,
                'message' => 'Sale Order Inserted',
                'saleOrderId' => $master_id
            ], 200);
        }catch (\Throwable $e) {
    DB::connection('mysql2')->rollBack();
    return response()->json([
        'success' => false,
        'error' => $e->getMessage(),
        'line' => $e->getLine(),
        'file' => $e->getFile(),
    ], 500);
}

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $sale_orders = DB::Connection('mysql2')->table('sales_order')->find($id);
        return view($this->path . 'sales_order_view', compact('sale_orders'));
    }

    public function getSaleOrderDataCategory(Request $request)
    {
        $html = '<option>Select category</option>';
        $sale_orders = DB::Connection('mysql2')
            ->table('sales_order_data')
            ->join('subitem', 'subitem.id', 'sales_order_data.item_id')
            ->join('sub_category', 'sub_category.id', 'subitem.sub_category_id')
            ->where('sales_order_data.master_id', $request->id)
            ->select('sub_category.id as sub_category_id', 'sub_category.sub_category_name')
            ->where('sales_order_data.status', 1)
            ->where('subitem.status', 1)
            ->groupBy('sub_category.id')
            ->get();
        foreach ($sale_orders as $sale_order) {
            $html .= '<option value="' . $sale_order->sub_category_id . '">' . $sale_order->sub_category_name . '</option>';
        }
        return $html;
    }

    public function getSaleOrderData(Request $request)
    {
        $sale_orders = DB::Connection('mysql2')
            ->table('sales_order_data')
            ->join('subitem', 'subitem.id', 'sales_order_data.item_id')
            ->join('uom', 'uom.id', 'subitem.uom')
            ->select('sales_order_data.id as sale_order_data_id', 'subitem.*', 'uom.*', 'sales_order_data.*')
            ->where('sales_order_data.master_id', $request->so_id)
            //    ->where('sales_order_data.production_status',0)
            ->where('sales_order_data.status', 1)
            ->where('subitem.sub_category_id', $request->category_id)
            ->get();
        return view($this->path . 'getSaleOrderData', compact('sale_orders'));
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
   public function edit($id)
    {
        $sale_orders = DB::Connection('mysql2')->table('sales_order')->find($id);
        // $sales_order_data =    DB::Connection('mysql2')->table('sales_order_data')->where('master_id',$id)->get();
        $brands = DB::Connection('mysql2')->table('brands')->where('principal_group_id', $sale_orders->principal_group_id)->get();
        
        $data = [];
        foreach($brands as $brand) {
            $data[] = [
                'id' => $brand->id,
                'text' => $brand->name
            ];
        }
        $sales_order_data = DB::Connection('mysql2')
            ->table('sales_order_data')
            // ->join('subitem','subitem.id','sales_order_data.item_id')
            // ->join('uom','uom.id','subitem.uom')
            // ->select('sales_order_data.id as sale_order_data_id','subitem.*','uom.*','sales_order_data.*')
            ->where('sales_order_data.master_id', $id)
            // ->where('sales_order_data.production_status',0)
            ->where('sales_order_data.status', 1)

            ->get();


            

        // echo "<pre>";

        // print_r($sales_order_data);
        // exit();

        return view($this->path . 'editSaleOrder', compact('sale_orders', 'sales_order_data', 'data'));
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function update(Request $request, $id)
    // {
    //     DB::Connection('mysql2')->beginTransaction();
    //     try {

    //         $grand_amount = 0;
    //         $sale_tax_mount_total  = 0;

    //         // Assuming you have a Sales_Order model
    //         $sales_order = Sales_Order::findOrFail($id);
    //         $sales_order = $sales_order->SetConnection('mysql2');
    //         // Update Sales_Order information
    //         $sales_order->so_date = $request->sale_order_date;
    //         $sales_order->purchase_order_no = $request->purchase_order_no ?? '';
    //         $sales_order->purchase_order_date = $request->purchase_order_date;
    //         $sales_order->purchase_order_contract = $request->quotation_id ?? '';

    //         if (!empty($request->sale_taxt_group)) {
    //             $sale_tax_id = explode(',', $request->sale_taxt_group);
    //             $sales_order->sales_tax_group = $sale_tax_id[0];
    //             $sales_order->sales_tax_rate = $request->sale_tax_rate;
    //         }
    //         else
    //         {
    //             $sales_order->sales_tax_group = 0;
    //             $sales_order->sales_tax_rate = 0;
    //         }

    //         if(!empty($request->further_taxes_group))
    //         {

    //             $further_taxes_group = explode(',',$request->further_taxes_group);
    //             $sales_order->further_taxes_group = $further_taxes_group[0];
    //             $sales_order->sales_tax_further = $request->sales_tax_further;

    //         }
    //         else
    //         {
    //             $sales_order->further_taxes_group = 0;
    //             $sales_order->sales_tax_further = 0;
    //         }

    //         $sales_order->buyers_id = $request->customer;
    //         $sales_order->save();

    //         // foreach ($request->sale_order_data_id as $key => $value) {
    //         //     # code...
    //         //     db::Connection('mysql2')->table('sales_order_data')->where('id', $value)->update([
    //         //         'status' => 2
    //         //     ]);
    //         // }

    //         $master_id = $id;
    //         $data =  $request->item_id;
    //         $count = 1;
    //         foreach($data as $key=>$row):

    //             $sales_order_data_exists = db::Connection('mysql2')->table('sales_order_data')->where('master_id', $master_id)->where('item_id', $request->item_id[$key])->first();
    //             if($sales_order_data_exists)
    //             {
    //                 if($sales_order_data_exists->production_status == 0)
    //                 {

    //                     db::Connection('mysql2')->table('sales_order_data')->where('master_id', $master_id)->where('item_id', $request->item_id[$key])->update([
    //                         'status' => 2
    //                     ]);

    //                     $sales_order_data = new Sales_Order_Data();
    //                     $sales_order_data = $sales_order_data->SetConnection('mysql2');
    //                     $sales_order_data->master_id=$master_id;
    //                     $sales_order_data->so_no=$request->sale_order_no;
    //                     $sales_order_data->item_id=$request->item_id[$key];
    //                     $sales_order_data->desc = $request->item_id[$key];
    //                     $sales_order_data->thickness= 0;
    //                     $sales_order_data->diameter=0;
    //                     $sales_order_data->item_description=$request->item_description[$key];
    //                     $sales_order_data->qty=$request->qty[$key];
    //                     $sales_order_data->rate=$request->rate[$key];
    //                     $sales_order_data->printing= $request->printing[$key];
    //                     $sales_order_data->special_instruction=$request->special_ins[$key];
    //                     $sales_order_data->delivery_date=$request->delivery_date[$key];
    //                     $sales_order_data->amount = $request->total[$key];
    //                     $sales_order_data->discount_percent = $request->discount_percent[$key];
    //                     $sales_order_data->discount_amount = $request->discount_amount[$key];
    //                     // $sales_order_data->delivery_type=$request->total[$key];   Delivery type
    //                     $sales_order_data->tax = $request->sale_tax_rate;

    //                     $sale_tax_mount =  $request->total[$key]/100*$request->sale_tax_rate;

    //                     $sale_tax_mount_total  += $sale_tax_mount;

    //                     $sales_order_data->tax_amount =   $sale_tax_mount;

    //                     $sales_order_data->sub_total =   $sale_tax_mount+$request->total[$key];
    //                     $sales_order_data->status=1;
    //                     $sales_order_data->date=date('Y-m-d');
    //                     $sales_order_data->username=Auth::user()->name;
    //                     $sales_order_data->groupby= $count;
    //                     $sales_order_data->save();

    //                 }
    //                 // $grand_amount += $request->total[$key];

    //             }
    //             else
    //             {

    //                 $sales_order_data = new Sales_Order_Data();
    //                 $sales_order_data = $sales_order_data->SetConnection('mysql2');
    //                 $sales_order_data->master_id=$master_id;
    //                 $sales_order_data->so_no=$request->sale_order_no;
    //                 $sales_order_data->item_id=$request->item_id[$key];
    //                 $sales_order_data->desc = $request->item_id[$key];
    //                 $sales_order_data->thickness= 0;
    //                 $sales_order_data->diameter=0;
    //                 $sales_order_data->item_description=$request->item_description[$key];
    //                 $sales_order_data->qty=$request->qty[$key];
    //                 $sales_order_data->rate=$request->rate[$key];
    //                 $sales_order_data->printing= $request->printing[$key];
    //                 $sales_order_data->special_instruction=$request->special_ins[$key];
    //                 $sales_order_data->delivery_date=$request->delivery_date[$key];
    //                 $sales_order_data->amount = $request->total[$key];
    //                 // $sales_order_data->delivery_type=$request->total[$key];   Delivery type
    //                 $sales_order_data->tax = $request->sale_tax_rate;

    //                 $sale_tax_mount =  $request->total[$key]/100*$request->sale_tax_rate;

    //                 $sale_tax_mount_total  += $sale_tax_mount;

    //                 $sales_order_data->tax_amount =   $sale_tax_mount;

    //                 $sales_order_data->sub_total =   $sale_tax_mount+$request->total[$key];
    //                 $sales_order_data->status=1;
    //                 $sales_order_data->date=date('Y-m-d');
    //                 $sales_order_data->username=Auth::user()->name;
    //                 $sales_order_data->groupby= $count;
    //                 $sales_order_data->save();

    //             }
    //             $grand_amount += $request->total[$key];
    //             $count ++ ;

    //          endforeach;

    //         if (!empty($request->quotation_id)) {
    //             $s_qt = SaleQuotation::find($request->quotation_id);
    //             $s_qt->so_status = 1;
    //             $s_qt->save();
    //         }

    //         // Update Sales_Order total_amount information
    //         $sales_order->total_amount = array_sum($request->total);
    //         $sales_order->total_amount_after_sale_tax = $request->grand_total_with_tax;
    //         $sales_order->save();

    //         DB::Connection('mysql2')->commit();
    //         return redirect('selling/listSaleOrder')->with('dataInsert', 'Sale Order Updated');
    //     } catch (Exception $e) {
    //         DB::Connection('mysql2')->rollBack();
    //         return redirect()->route('editSaleOrder', ['id' => $id])->with('error', $e->getMessage());
    //     }
    // }
    
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
            $sales_order = Sales_Order::findOrFail($id);
            $sales_order->SetConnection('mysql2');

            $customerDetail = DB::connection('mysql2')
                ->table('customers')
                ->where('id', $request->customer_name)
                ->where('status', 1)
                ->select('*')
                ->first();

            $virtualWarehouseCheck = $customerDetail->CustomerType == '3' || $customerDetail->CustomerType == 3 ? 1 : 0;

           

            // Update the sales order details
            $sales_order->destination = $request->address;
            $sales_order->buyers_id = $request->customer_name;
            $sales_order->phone_no = $request->phone_no ?? null;
            $sales_order->address = $request->address ?? null;
            $sales_order->branch = $request->branch ?? null;
            $sales_order->sales_person = $request->saleperson ?? null;
            $sales_order->balance_amount = $request->balance_amount ?? 0.0;
            $sales_order->principal_group_id = $request->principal_group;
            $sales_order->credit_limit = $request->credit_limit ?? 0.0;
            $sales_order->current_balance_due = $request->balance_amount + $request->total_amount_after_sale_tax ?? 0.0;
            $sales_order->virtual_warehouse_check = $virtualWarehouseCheck;
            $sales_order->total_amount_after_sale_tax = $request->total_amount_after_sale_tax ?? 0;
            $sales_order->total_amount = $request->total_gross_amount ?? 0;
            $sales_order->sales_tax_rate = $request->total_sales_tax ?? 0;
            $sales_order->total_qty = $request->total_qty ?? 0;
            $sales_order->sale_taxes_id = $request->sale_taxes_id ?? 0;
            $sales_order->sale_taxes_amount_total = $request->sale_taxes_amount_total ?? 0;
            $sales_order->sale_taxes_amount_rate = $request->sale_taxes_amount_rate ?? 0;
            $sales_order->status = $sales_order->status;
            $sales_order->username = Auth::user()->name;
            $sales_order->remark = $request->remark;
            $sales_order->so_date = $request->sale_order_date;
            $sales_order->warehouse_from = $request->warehouse;
            $sales_order->save();

            // Delete existing sales order data
            Sales_Order_Data::where('master_id', $id)->delete();

            $master_id = $sales_order->id;
            foreach ($data as $key => $row) {
                $sales_order_data = new Sales_Order_Data();
                $sales_order_data->SetConnection('mysql2');
                $sales_order_data->master_id = $master_id;
                $sales_order_data->so_no = $sales_order->so_no;
                $sales_order_data->brand_id = $request->brand_id[$key];
                $sales_order_data->item_id = $request->product_id[$key];
                $sales_order_data->desc = $request->product_id[$key] ?? 0;
                $sales_order_data->item_description = $request->item_description[$key] ?? null;
                $sales_order_data->qty = $request->qty[$key] ?? 0;
                $sales_order_data->foc = $request->foc[$key] ?? 0;
                $sales_order_data->mrp_price = $request->mrp_price[$key] ?? 0.0;
                $sales_order_data->rate = $request->rate[$key] ?? 0;
                $sales_order_data->sub_total = $request->gross_amount[$key] ?? 0.0;
                $sales_order_data->tax = $request->tax[$key] ?? 0.0;
                $sales_order_data->tax_amount = $request->total_tax[$key] ?? 0.0;
                $sales_order_data->amount = $request->total_amount[$key] ?? 0.0;
                $sales_order_data->printing = $request->printing[$key] ?? null;
                $sales_order_data->special_instruction = $request->special_ins[$key] ?? null;
                $sales_order_data->delivery_date = $request->delivery_date[$key] ?? null;
                $sales_order_data->discount_percent_1 = $request->discount1[$key];
                $sales_order_data->discount_amount_1 = $request->discount_amount1[$key];
                $sales_order_data->discount_percent_2 = $request->discount2[$key];
                $sales_order_data->discount_amount_2 = $request->discount_amount2[$key];
                $sales_order_data->warehouse_id = $request->warehouse;
                $sales_order_data->status = 1;
                $sales_order_data->date = $request->sale_order_date;
                $sales_order_data->username = Auth::user()->name;
                $sales_order_data->save();

                $grand_amount += $request->total_amount[$key];
            }

            SalesHelper::sales_activity($sales_order->so_no, date('Y-m-d'), '0', 1, 'Update');

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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
   public function destroy($id)
{
    // Set sale_order.status = 0
    DB::connection('mysql2')->table('sales_order')
        ->where('id', $id)
        ->update(['status' => 0]);

    // Set related sales_order_data.status = 0
    DB::connection('mysql2')->table('sales_order_data')
        ->where('master_id', $id)
        ->update(['status' => 0]);

    return redirect()->back()->with('success', 'Sale order deleted successfully.');
}


    public function viewSaleOrder($id)
    {
        $sale_orders = DB::Connection('mysql2')->table('sales_order')->join('sales_order_data', 'sales_order.id', 'sales_order_data.master_id')->join('customers', 'customers.id', 'sales_order.buyers_id')->join('subitem', 'subitem.id', 'sales_order_data.item_id')->join('sub_category', 'sub_category.id', 'subitem.sub_category_id')->join('uom', 'uom.id', 'subitem.uom')->select('sales_order_data.id as sale_order_data_id', 'subitem.item_code', 'uom.uom_name', 'sales_order_data.*', 'sales_order.*', 'customers.name AS customer_name', 'sub_category.sub_category_name')->where('sales_order.id', $id)->where('sales_order_data.status', 1)->where('sales_order.status', 1)->get();

        return view('selling.saleorder.viewSaleOrder', compact('sale_orders'));
    }

    public function saleOrderSectionA(Request $request)
    {
        $sale_orders = Sales_Order::find($request->id);
        return view('selling.saleorder.saleOrderSectionA', compact('sale_orders'));
    }
    // public function saleOrderSectionB(Request $request)
    // {
    //     return view('selling.saleorder.viewSaleOrder', compact('sale_orders'));
    // }
}

