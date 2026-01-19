<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Helpers\CommonHelper;
use App\Helpers\ProductionHelper;
use Auth;
use DB;
use Config;
use App\Models\Department;
use App\Models\Category;
use App\Models\PurchaseRequest;
use App\Models\PurchaseRequestData;
use App\Models\Subitem;

use Input;
use Validator;
class StoreController extends Controller
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
    public function toDayActivity()
    {
        return view('Store.toDayActivity');
    }

    public  function viewDemandList(){
        return view('Store.viewDemandList');
    }
    public  function scReportPage(){
        return view('Store.scReportPage');
    }
    public  function getDataScReportAjax(Request $request){
        $FromDate = $request->FromDate;
        $ToDate = $request->ToDate;
        $VoucherType = $request->VoucherType;
        return view('Store.getDataScReportAjax',compact('FromDate','ToDate','VoucherType'));
    }



    public  function inventoryActivityPage(){
        return view('Store.inventoryActivityPage');
    }
    public  function inventoryActivityAjax(){
        return view('Store.inventoryActivityAjax');
    }


    public  function stock_transfer_form(){
        return view('Store.stock_transfer_form');
    }
    public  function stock_transfer_list(){
        return view('Store.stock_transfer_list');
    }

    public  function itemWiseOpening(){
        $OpeningItemWise = DB::Connection('mysql2')->table('stock')->where('opening',1)->where('status',1)->where('voucher_type',1)->where('amount','>',0)->where('warehouse_id','!=',0)->get();
        return view('Store.itemWiseOpening',compact('OpeningItemWise'));
    }

    public function editStockTransferForm($id,$Trno){
        $Master = DB::Connection('mysql2')->table('stock_transfer')->where('status',1)->where('id',$id)->first();
        $Detail = DB::Connection('mysql2')->table('stock_transfer_data')->where('status',1)->where('master_id',$id)->get();
        return view('Store.editStockTransferForm',compact('Master','Detail'));
    }

    public  function itemCostClassification(){
        $Subitem= new Subitem();
        $Subitem=$Subitem->SetConnection('mysql2');
        $Subitem=$Subitem->where('status',1)->get();
        
        $item_cost_classification = DB::Connection('mysql2')->table('item_cost_classification')->get();

        return view('Store.itemCostClassification',compact('Subitem','item_cost_classification'));
    }

    public function createStoreChallanForm(){
        $departments = new Department;
        $departments = $departments::where([['company_id', '=', $_GET['m']], ['status', '=', '1'], ])->orderBy('id')->get();
        return view('Store.createStoreChallanForm',compact('departments'));
    }
    public function createIssuanceForm()
    {
        return view('Store.createIssuanceForm');
    }
    public function editIssuanceForm()
    {
        return view('Store.editIssuanceForm');
    }

    public function issuanceList(){
        return view('Store.issuanceList',compact('data'));
    }


    public  function viewStoreChallanList(){
        return view('Store.viewStoreChallanList');
    }

    public  function editStoreChallanVoucherForm(){
        $departments = new Department;
        $departments = $departments::where([['company_id', '=', $_GET['m']], ['status', '=', '1'], ])->orderBy('id')->get();
        return view('Store.AjaxPages.editStoreChallanVoucherForm',compact('departments'));
    }

    public function createPurchaseRequestForm(){
        $departments = new Department;
        $departments = $departments::where([['company_id', '=', $_GET['m']], ['status', '=', '1'], ])->orderBy('id')->get();
        return view('Store.createPurchaseRequestForm',compact('departments'));
    }



    public  function viewPurchaseRequestList(){
        $username = Subitem::select("username")->groupBy("username")->get();
        return view('Store.viewPurchaseRequestList', compact('username'));
    }


    public  function customerStoreList(){
        return view('Store.customerStoreList');
    }


    public  function editPurchaseRequestVoucherForm($id)
    {
        // for department
        $departments = new Department;
        $departments = $departments::where([['company_id', '=', $_GET['m']], ['status', '=', '1'], ])->orderBy('id')->get();

        // for purchase order
        $purchase_order=new PurchaseRequest();
        $purchase_order=$purchase_order->SetConnection('mysql2');
        $purchase_order=$purchase_order->where('id',$id)->first();
        // for purchase order
        $purchase_order_data= new PurchaseRequestData();
        $purchase_order_data=$purchase_order_data->SetConnection('mysql2');
        $purchase_order_data=$purchase_order_data->where('master_id',$id)->get();

        return view('Store.editPurchaseRequestVoucherForm',compact('departments','purchase_order','purchase_order_data','id'));
    }

    public  function editDirectPurchaseRequestVoucherForm($id)
    {
        // for department
        $departments = new Department;
        $departments = $departments::where('status', '=', '1')->select('id','department_name')->orderBy('id')->get();

        // for purchase order
        $purchase_order=new PurchaseRequest();
        $purchase_order=$purchase_order->SetConnection('mysql2');
        $purchase_order=$purchase_order->where('id',$id)->first();

        // for purchase order
        $purchase_order_data= new PurchaseRequestData();
        $purchase_order_data=$purchase_order_data->SetConnection('mysql2');
        $purchase_order_data=$purchase_order_data->where('master_id',$id)->get();
        $supplierList = DB::Connection('mysql2')->table('supplier')->where('status',1)->get();

        return view('Store.editDirectPurchaseRequestVoucherForm',compact('departments','purchase_order','purchase_order_data','supplierList','id'));
    }


    public function createPurchaseRequestSaleForm(){
        $departments = new Department;
        $departments = $departments::where([['company_id', '=', $_GET['m']], ['status', '=', '1'], ])->orderBy('id')->get();
        return view('Store.createPurchaseRequestSaleForm',compact('departments'));
    }

    public  function viewPurchaseRequestSaleList(){
        return view('Store.viewPurchaseRequestSaleList');
    }

    public  function editPurchaseRequestSaleVoucherForm(){
        $departments = new Department;
        $departments = $departments::where([['company_id', '=', $_GET['m']], ['status', '=', '1'], ])->orderBy('id')->get();
        return view('Store.AjaxPages.editPurchaseRequestSaleVoucherForm',compact('departments'));
    }



    public function createStoreChallanReturnForm(){
        $departments = new Department;
        $departments = $departments::where([['company_id', '=', $_GET['m']], ['status', '=', '1'], ])->orderBy('id')->get();
        return view('Store.createStoreChallanReturnForm',compact('departments'));
    }

    public  function viewStoreChallanReturnList(){
        return view('Store.viewStoreChallanReturnList');
    }

    public  function editStoreChallanReturnForm(){
        $departments = new Department;
        $departments = $departments::where([['company_id', '=', $_GET['m']], ['status', '=', '1'], ])->orderBy('id')->get();
        return view('Store.AjaxPages.editStoreChallanReturnForm',compact('departments'));
    }

    public function viewDateWiseStockInventoryReport(){
        CommonHelper::companyDatabaseConnection($_GET['m']);
        $categorys = new Category;
        $categorys = $categorys::where([['company_id', '=', $_GET['m']], ['status', '=', '1'], ])->orderBy('id')->get();
        CommonHelper::reconnectMasterDatabase();
        return view('Store.viewDateWiseStockInventoryReport',compact('categorys'));
    }

    public function stockReportView()
    {

       $category= DB::Connection('mysql2')->table('stock as a')
        ->join('subitem as b','a.sub_item_id','=','b.id')
       ->join('category as c','c.id','=','b.main_ic_id')

        ->select('c.id','c.main_ic')
           ->where('a.status',1)
           ->groupBy('c.id')

       ->get();



        return view('Store.stockReportView',compact('category'));
    }

// public function searchProduct(Request $request)
// {
//     $term = $request->q;

//     $results = DB::connection('mysql2')
//         ->table('subitem')
//         ->where('product_name', 'like', '%' . $term . '%')
//         ->where('status', 1)
//         ->limit(20)
//         ->get(['id', 'product_name']);

//     return response()->json($results);
// }


public function searchProduct(Request $request)
{
    $term = $request->q;

    $results = DB::connection('mysql2')
        ->table('subitem')
        ->where('status', 1)
        ->where(function($query) use ($term) {
            $query->where('product_name', 'like', '%' . $term . '%')
                  ->orWhere('sku_code', 'like', '%' . $term . '%'); // search by SKU
        })
        ->limit(20)
        ->get(['id', 'product_name', 'sku_code']);

    // Format for select2
    $formatted = $results->map(function($item) {
        return [
            'id' => $item->id,
            'text' => $item->product_name . ' (' . $item->sku_code . ')' // show both in dropdown
        ];
    });

    return response()->json($formatted);
}



// public function closingReportView(Request $request)
// {
//     $from_date = $request->from ?? date('Y-m-d');
//     $to_date = $request->to ?? date('Y-m-d');
//     $warehouse_id = $request->warehouse_id ?? null;
//     $product_id = $request->product_id ?? null;
//     $brand_id = $request->brand_id ?? null;

//     $user = Auth::user();
//     $isUser = $user && $user->acc_type == 'user';

//     // Common territory-based logic
//     if ($isUser) {
//         $territory_ids = json_decode($user->territory_id, true);
//         if (!is_array($territory_ids)) {
//             $territory_ids = [$user->territory_id];
//         }

//         $warehouse_ids = DB::connection('mysql2')->table('stock')
//             ->whereIn('territory', $territory_ids)
//             ->where('status', 1)
//             ->pluck('warehouse_id');

//         $warehouses = DB::connection('mysql2')->table('warehouse')
//             ->whereIn('id', $warehouse_ids)
//             ->where('status', 1)
//             ->get();

//         $subitem_ids = DB::connection('mysql2')->table('stock')
//             ->whereIn('territory', $territory_ids)
//             ->where('status', 1)
//             ->pluck('sub_item_id');

//         $products = DB::connection('mysql2')->table('subitem')
//             ->whereIn('id', $subitem_ids)
//             ->where('status', 1)
//             ->get(['id', 'product_name']);

//         $brand_ids = DB::connection('mysql2')->table('subitem')
//             ->whereIn('id', $subitem_ids)
//             ->whereNotNull('brand_id')
//             ->pluck('brand_id');

//         $brands = DB::connection('mysql2')->table('brands')
//             ->whereIn('id', $brand_ids)
//             ->where('status', 1)
//             ->get(['id', 'name']);

//         $territories = DB::connection('mysql2')->table('territories')
//             ->whereIn('id', $territory_ids)
//             ->where('status', 1)
//             ->get(['id', 'name']);
//     } else {
//         $warehouses = DB::connection('mysql2')->table('warehouse')->where('status', 1)->get();
//         $products = DB::connection('mysql2')->table('subitem')->where('status', 1)->get(['id', 'product_name']);
//         $brands = DB::connection('mysql2')->table('brands')->where('status', 1)->get(['id', 'name']);
//         $territories = DB::connection('mysql2')->table('territories')->where('status', 1)->get(['id', 'name']);
//     }

//     // Default product list
//     $defaultProducts = DB::connection('mysql2')
//         ->table('subitem')
//         ->where('status', 1)
//         ->limit(5)
//         ->get(['id', 'product_name']);

//     // AJAX case
//     if ($request->ajax()) {
//         $query = DB::connection('mysql2')->table('stock as s')
//             ->join('subitem as si', 's.sub_item_id', '=', 'si.id')
//             ->leftJoin('category as c', 'si.main_ic_id', '=', 'c.id')
//             ->leftJoin('warehouse as w', 's.warehouse_id', '=', 'w.id')
//             ->leftJoin('brands as b', 'si.brand_id', '=', 'b.id')
//             ->select(
//                 'si.sku_code as sku_code',
//                 'c.main_ic as classification',
//                 'w.name as warehouse_name',
//                 'si.product_name as item_name',
//                 'si.product_barcode as barcode',
//                 'si.type as item_type',
//                 'b.name as brand',
//                 'si.pack_size as packing',
//                 DB::raw('SUM(s.qty) as qty_on_hand'),
//                 DB::raw('0 as stock_in_transit'),
//                 DB::raw('0 as reserve_stock'),
//                 DB::raw('SUM(s.qty) as balance_stock')
//             )
//             ->where('s.status', 1)
//             ->whereBetween('s.created_date', [$from_date, $to_date]);

//         if ($warehouse_id) {
//             $query->where('s.warehouse_id', $warehouse_id);
//         }

//         if ($product_id) {
//             $query->where('s.sub_item_id', $product_id);
//         }

//         if ($brand_id) {
//             $query->where('si.brand_id', $brand_id);
//         }

//         $stocks = $query->groupBy('si.id', 'w.id')->get();

//         return view('Store.closing_report_ajax', compact(
//             'stocks', 'from_date', 'to_date',
//             'warehouses', 'warehouse_id', 'products', 'product_id'
//         ));
//     }

//     return view('Store.closing_report', compact(
//         'warehouses', 'products', 'brands', 'defaultProducts', 'territories'
//     ));
// }


// public function closingReportView(Request $request)
// {
//     $from_date = $request->from ?? date('Y-m-d');
//     $to_date = $request->to ?? date('Y-m-d');
//     $warehouse_ids = (array) $request->input('warehouse_id', []);
//     $product_id = $request->product_id ?? null;
//     $brand_ids = (array) $request->input('brand_id', []);
//     $territory_id = $request->territory_id ?? null;

//     $user = Auth::user();
//     $isUser = $user && $user->acc_type == 'user';

//     if ($isUser) {
//         $territory_ids = json_decode($user->territory_id, true);
//         if (!is_array($territory_ids)) {
//             $territory_ids = [$user->territory_id];
//         }

//         $warehouseList = DB::connection('mysql2')->table('stock')
//             ->whereIn('territory', $territory_ids)
//             ->where('status', 1)
//             ->pluck('warehouse_id');

//         $warehouses = DB::connection('mysql2')->table('warehouse')
//             ->whereIn('id', $warehouseList)
//             ->where('status', 1)
//             ->get();

//         $subitem_ids = DB::connection('mysql2')->table('stock')
//             ->whereIn('territory', $territory_ids)
//             ->where('status', 1)
//             ->pluck('sub_item_id');

//         $products = DB::connection('mysql2')->table('subitem')
//             ->whereIn('id', $subitem_ids)
//             ->where('status', 1)
//             ->get(['id', 'product_name']);

//         $brandList = DB::connection('mysql2')->table('subitem')
//             ->whereIn('id', $subitem_ids)
//             ->whereNotNull('brand_id')
//             ->pluck('brand_id');

//         $brands = DB::connection('mysql2')->table('brands')
//             ->whereIn('id', $brandList)
//             ->where('status', 1)
//             ->get(['id', 'name']);

//         $territories = DB::connection('mysql2')->table('territories')
//             ->whereIn('id', $territory_ids)
//             ->where('status', 1)
//             ->get(['id', 'name']);
//     } else {
//         $warehouses = DB::connection('mysql2')->table('warehouse')->where('status', 1)->get();
//         $products = DB::connection('mysql2')->table('subitem')->where('status', 1)->get(['id', 'product_name']);
//         $brands = DB::connection('mysql2')->table('brands')->where('status', 1)->get(['id', 'name']);
//         $territories = DB::connection('mysql2')->table('territories')->where('status', 1)->get(['id', 'name']);
//     }

//     $defaultProducts = DB::connection('mysql2')
//         ->table('subitem')
//         ->where('status', 1)
//         ->limit(5)
//         ->get(['id', 'product_name']);

//     if ($request->ajax()) {
//         $query = DB::connection('mysql2')->table('stock as s')
//             ->join('subitem as si', 's.sub_item_id', '=', 'si.id')
//             ->leftJoin('category as c', 'si.main_ic_id', '=', 'c.id')
//             ->leftJoin('warehouse as w', 's.warehouse_id', '=', 'w.id')
//             ->leftJoin('brands as b', 'si.brand_id', '=', 'b.id')
//             ->select(
//                 'si.id as product_id',
//                 'si.sku_code',
//                 'si.product_name',
//                 'si.product_barcode as barcode',
//                 'si.type as item_type',
//                 'si.pack_size as packing',
//                 'b.name as brand',
//                 'w.id as warehouse_id',
//                 'w.name as warehouse_name',
               
//                 // درست calculation
//                 DB::raw('SUM(CASE WHEN s.voucher_type IN (1,4,6,10,11) AND s.transfer_status != 1 THEN s.qty ELSE 0 END) AS in_stock'),
//                 DB::raw('SUM(CASE WHEN s.voucher_type IN (2,5,3,9) THEN s.qty ELSE 0 END) AS out_stock'),
//                 DB::raw('SUM(CASE WHEN s.transfer_status = 1 THEN s.qty ELSE 0 END) AS transit_stock')
//             )
//             ->where('s.status', 1)
//             ->whereBetween('s.created_date', [$from_date, $to_date]);

//         if (!empty($warehouse_ids)) {
//             $query->whereIn('s.warehouse_id', $warehouse_ids);
//         } elseif ($territory_id) {
//             $territoryWarehouseIds = DB::connection('mysql2')->table('stock')
//                 ->where('territory', $territory_id)
//                 ->where('status', 1)
//                 ->distinct()
//                 ->pluck('warehouse_id');

//             if ($territoryWarehouseIds->isNotEmpty()) {
//                 $query->whereIn('s.warehouse_id', $territoryWarehouseIds);
//             } else {
//                 $query->whereRaw('0=1');
//             }
//         }

//         if ($product_id) {
//             $query->where('s.sub_item_id', $product_id);
//         }

//         if (!empty($brand_ids)) {
//             $query->whereIn('si.brand_id', $brand_ids);
//         }

//         $rawStock = $query->groupBy('si.id', 'w.id')->get();

//         // Group by product for warehouse-wise quantities
//         $stocks = [];
//         $warehouseMap = [];

//         foreach ($rawStock as $stock) {
//             $key = $stock->product_id;

//             if (!isset($stocks[$key])) {
//                 $stocks[$key] = [
//                     'sku_code' => $stock->sku_code,
//                     'product_name' => $stock->product_name,
//                     'barcode' => $stock->barcode,
//                     'item_type' => $stock->item_type,
//                     'brand' => $stock->brand,
//                     'packing' => $stock->packing,
//                     'transit_stock' => $stock->transit_stock
//                 ];
//             }

//             // درست calculation - transit stock کو الگ رکھیں
//             $stocktest = (float)$stock->in_stock - (float)$stock->out_stock;

//             $stocks[$key][$stock->warehouse_name] = abs($stocktest);
//             $warehouseMap[$stock->warehouse_id] = $stock->warehouse_name;
//         }

     

//         return view('Store.closing_report_ajax', [
//             'stocks' => $stocks,
//             'from_date' => $from_date,
//             'to_date' => $to_date,
//             'warehouses' => $warehouseMap
//         ]);
//     }

//     return view('Store.closing_report', compact(
//         'warehouses', 'products', 'brands', 'defaultProducts', 'territories'
//     ));
// }


public function closingReportView(Request $request)
{

    $m = $request->m ?? null;
    if ((int)$m === 1) {
            // $from_date = $request->from ?? date('Y-m-d');
                $from_date = $request->from ?? date('Y-m-d', strtotime('-2 years'));

                $to_date = $request->to ?? date('Y-m-d');
                $warehouse_ids = (array) $request->input('warehouse_id', []);
                $product_id = $request->product_id ?? null;
                $brand_ids = (array) $request->input('brand_id', []);
                $territory_id = $request->territory_id ?? null;

                $user = Auth::user();
                $isUser = $user && $user->acc_type == 'user';

                if ($isUser) {
                    $territory_ids = json_decode($user->territory_id, true);
                    if (!is_array($territory_ids)) {
                        $territory_ids = [$user->territory_id];
                    }

                    $warehouseList = DB::connection('mysql2')->table('stock')
                        ->whereIn('territory', $territory_ids)
                        ->where('status', 1)
                        ->distinct()
                        ->pluck('warehouse_id');

                    $warehouses = DB::connection('mysql2')->table('warehouse')
                        ->whereIn('id', $warehouseList)
                        ->where('status', 1)
                        ->get();

                    $subitem_ids = DB::connection('mysql2')->table('stock')
                        ->whereIn('territory', $territory_ids)
                        ->where('status', 1)
                        ->distinct()
                        ->pluck('sub_item_id');
                
                    $products = DB::connection('mysql2')->table('subitem')
                        ->whereIn('id', $subitem_ids)
                        ->where('status', 1)
                        ->distinct()
                        ->get(['id', 'product_name']);
               
                    $brandList = DB::connection('mysql2')->table('subitem')
                        ->whereIn('id', $subitem_ids)
                        ->whereNotNull('brand_id')
                        ->pluck('brand_id');

                    $brands = DB::connection('mysql2')->table('brands')
                        ->whereIn('id', $brandList)
                        ->where('status', 1)
                        ->get(['id', 'name']);

                    $territories = DB::connection('mysql2')->table('territories')
                        ->whereIn('id', $territory_ids)
                        ->where('status', 1)
                        ->get(['id', 'name']);
                } else {
                    $warehouses = DB::connection('mysql2')->table('warehouse')->where('status', 1)->get();
                    $products = DB::connection('mysql2')->table('subitem')->where('status', 1)->get(['id', 'product_name']);
                    $brands = DB::connection('mysql2')->table('brands')->where('status', 1)->get(['id', 'name']);
                    $territories = DB::connection('mysql2')->table('territories')->where('status', 1)->get(['id', 'name']);
                }

                $defaultProducts = DB::connection('mysql2')
                    ->table('subitem')
                    ->where('status', 1)
                    ->limit(5)
                    ->get(['id', 'product_name']);


            if ($request->ajax()) {
            $transitSub = DB::connection('mysql2')->table('stock_transfers_transit')
                ->select(
                    'product_id',
                    'warehouse_to_id',
                    DB::raw('SUM(quantity) as transit_stock')
                )
                ->where('tr_status', 1)
                ->groupBy('product_id', 'warehouse_to_id');

            $rawQuery = $transitSub->toSql(); // ye SQL string banega
            $bindings = $transitSub->getBindings(); // bindings

            $query = DB::connection('mysql2')->table('stock as s')
                ->join('subitem as si', 's.sub_item_id', '=', 'si.id')
                ->leftJoin('product_type as pt', 'si.product_type_id', '=', 'pt.product_type_id')

                ->leftJoin('category as c', 'si.main_ic_id', '=', 'c.id')
                ->leftJoin('warehouse as w', 's.warehouse_id', '=', 'w.id')
                ->leftJoin('brands as b', 'si.brand_id', '=', 'b.id')
                ->leftJoin(DB::raw("($rawQuery) as st"), function($join) {
                    $join->on('si.id', '=', 'st.product_id')
                        ->on('w.id', '=', 'st.warehouse_to_id');
                })
                ->addBinding($bindings, 'join')
                ->select(
                    'si.id as product_id',
                    'si.sku_code',
                    'si.product_name',
                    'si.product_barcode as barcode',
                    'pt.type as item_type',
                    // 'si.type as item_type',
                    'si.pack_size as packing',
                    'b.name as brand',
                    'w.id as warehouse_id',
                    'w.name as warehouse_name',
                    DB::raw('SUM(CASE WHEN s.voucher_type IN (1,4,6,10,11) AND s.transfer_status != 1 THEN s.qty ELSE 0 END) AS in_stock'),
                    DB::raw('SUM(CASE WHEN s.voucher_type IN (2,5,3,9) THEN s.qty ELSE 0 END) AS out_stock'),
                    DB::raw('IFNULL(st.transit_stock,0) as transit_stock')
                )
                ->where('s.status', 1)
                ->where("s.voucher_type", "!=", "9")
                ->whereBetween('s.created_date', [$from_date, $to_date])
                ->groupBy('si.id','w.id');


                if (!empty($warehouse_ids)) {
                    $query->whereIn('s.warehouse_id', $warehouse_ids);
                } elseif ($territory_id) {
                    $territoryWarehouseIds = DB::connection('mysql2')->table('stock')
                        ->where('territory', $territory_id)
                        ->where('status', 1)
                        ->distinct()
                        ->pluck('warehouse_id');

                    if ($territoryWarehouseIds->isNotEmpty()) {
                        $query->whereIn('s.warehouse_id', $territoryWarehouseIds);
                    } else {
                        $query->whereRaw('0=1');
                    }
                }

                if ($product_id) {
                    $query->where('s.sub_item_id', $product_id);
                }

                if (!empty($brand_ids)) {
                    $query->whereIn('si.brand_id', $brand_ids);
                }

                // ✅ Group only by product and warehouse (not transit_stock)
                $rawStock = $query->groupBy('si.id', 'w.id')->get();

                $stocks = [];
                $warehouseMap = [];

                foreach ($rawStock as $stock) {
                    $key = $stock->product_id;

                    if (!isset($stocks[$key])) {
                        $stocks[$key] = [
                            'sku_code' => $stock->sku_code,
                            'product_name' => $stock->product_name,
                            'barcode' => $stock->barcode,
                            
                            'item_type' => $stock->item_type,
                            'brand' => $stock->brand,
                            'packing' => $stock->packing,
                            'transit_stock' => $stock->transit_stock
                        ];
                    }

                    $stockQty = (float)$stock->in_stock - (float)$stock->out_stock;
                    $stocks[$key][$stock->warehouse_name] = abs($stockQty);
                    $warehouseMap[$stock->warehouse_id] = $stock->warehouse_name;
                }

                return view('Store.closing_report_ajax', [
                    'stocks' => $stocks,
                    'from_date' => $from_date,
                    'to_date' => $to_date,
                    'warehouses' => $warehouseMap
                ]);
            }

                return view('Store.closing_report', compact(
                    'warehouses', 'products', 'brands', 'defaultProducts', 'territories'
                ));

    }else{

                // $from_date = $request->from ?? date('Y-m-d');
            $from_date = $request->from ?? date('Y-m-d', strtotime('-2 years'));

            $to_date = $request->to ?? date('Y-m-d');
            $warehouse_ids = (array) $request->input('warehouse_id', []);
            $product_id = $request->product_id ?? null;
            $brand_ids = (array) $request->input('brand_id', []);
            $territory_id = $request->territory_id ?? null;

            $user = Auth::user();
            $isUser = $user && $user->acc_type == 'user';

            if ($isUser) {
                $territory_ids = json_decode($user->territory_id, true);
                if (!is_array($territory_ids)) {
                    $territory_ids = [$user->territory_id];
                }

                $warehouseList = DB::connection('mysql2')->table('stock')
                    ->whereIn('territory', $territory_ids)
                    ->where('status', 1)
                    ->distinct()
                    ->pluck('warehouse_id');

                $warehouses = DB::connection('mysql2')->table('warehouse')
                    ->whereIn('id', $warehouseList)
                    ->where('status', 1)
                    ->get();

                $subitem_ids = DB::connection('mysql2')->table('stock')
                    ->whereIn('territory', $territory_ids)
                    ->where('status', 1)
                    ->distinct()
                    ->pluck('sub_item_id');

                $products = DB::connection('mysql2')->table('subitem')
                    ->whereIn('id', $subitem_ids)
                    ->where('status', 1)
                    ->get(['id', 'product_name']);

                $brandList = DB::connection('mysql2')->table('subitem')
                    ->whereIn('id', $subitem_ids)
                    ->whereNotNull('brand_id')
                    ->pluck('brand_id');

                $brands = DB::connection('mysql2')->table('brands')
                    ->whereIn('id', $brandList)
                    ->where('status', 1)
                    ->get(['id', 'name']);

                $territories = DB::connection('mysql2')->table('territories')
                    ->whereIn('id', $territory_ids)
                    ->where('status', 1)
                    ->get(['id', 'name']);
            } else {
                $warehouses = DB::connection('mysql2')->table('warehouse')->where('status', 1)->get();
                $products = DB::connection('mysql2')->table('subitem')->where('status', 1)->get(['id', 'product_name']);
                $brands = DB::connection('mysql2')->table('brands')->where('status', 1)->get(['id', 'name']);
                $territories = DB::connection('mysql2')->table('territories')->where('status', 1)->get(['id', 'name']);
            }

            $defaultProducts = DB::connection('mysql2')
                ->table('subitem')
                ->where('status', 1)
                ->limit(5)
                ->get(['id', 'product_name']);

        if ($request->ajax()) {
        $transitSub = DB::connection('mysql2')->table('stock_transfers_transit')
            ->select(
                'product_id',
                'warehouse_to_id',
                DB::raw('SUM(quantity) as transit_stock')
            )
            ->where('tr_status', 1)
            ->groupBy('product_id', 'warehouse_to_id');

        $rawQuery = $transitSub->toSql(); // ye SQL string banega
        $bindings = $transitSub->getBindings(); // bindings

        $query = DB::connection('mysql2')->table('stock as s')
            ->join('subitem as si', 's.sub_item_id', '=', 'si.id')
            ->leftJoin('product_type as pt', 'si.product_type_id', '=', 'pt.product_type_id')

            ->leftJoin('category as c', 'si.main_ic_id', '=', 'c.id')
            ->leftJoin('warehouse as w', 's.warehouse_id', '=', 'w.id')
            ->leftJoin('brands as b', 'si.brand_id', '=', 'b.id')
            ->leftJoin(DB::raw("($rawQuery) as st"), function($join) {
                $join->on('si.id', '=', 'st.product_id')
                    ->on('w.id', '=', 'st.warehouse_to_id');
            })
            ->addBinding($bindings, 'join')
            ->select(
                'si.id as product_id',
                'si.sku_code',
                'si.product_name',
                'si.purchase_price',
                'si.product_barcode as barcode',
                'pt.type as item_type',
                's.voucher_no as voucher_no',
                's.supplier_id as supplier_id',
                // 'si.type as item_type',
                'si.pack_size as packing',
                'b.name as brand',
                'w.id as warehouse_id',
                'w.name as warehouse_name',
                DB::raw('SUM(CASE WHEN s.voucher_type IN (1,4,6,10,11) AND s.transfer_status != 1 THEN s.qty ELSE 0 END) AS in_stock'),
                DB::raw('SUM(CASE WHEN s.voucher_type IN (2,5,3,9) THEN s.qty ELSE 0 END) AS out_stock'),
                DB::raw('IFNULL(st.transit_stock,0) as transit_stock')
            )
            ->where('s.status', 1)
            ->where("s.voucher_type", "!=", "9")
            ->whereBetween('s.created_date', [$from_date, $to_date])
            ->groupBy('si.id','w.id');


            if (!empty($warehouse_ids)) {
                $query->whereIn('s.warehouse_id', $warehouse_ids);
            } elseif ($territory_id) {
                $territoryWarehouseIds = DB::connection('mysql2')->table('stock')
                    ->where('territory', $territory_id)
                    ->where('status', 1)
                    ->distinct()
                    ->pluck('warehouse_id');

                if ($territoryWarehouseIds->isNotEmpty()) {
                    $query->whereIn('s.warehouse_id', $territoryWarehouseIds);
                } else {
                    $query->whereRaw('0=1');
                }
            }

            if ($product_id) {
                $query->where('s.sub_item_id', $product_id);
            }

            if (!empty($brand_ids)) {
                $query->whereIn('si.brand_id', $brand_ids);
            }

            // ✅ Group only by product and warehouse (not transit_stock)
            $rawStock = $query->groupBy('si.id', 'w.id')->get();

            $stocks = [];
            $warehouseMap = [];

            foreach ($rawStock as $stock) {
                $key = $stock->product_id;

                if (!isset($stocks[$key])) {
                    $stocks[$key] = [
                        'sku_code' => $stock->sku_code,
                        'product_name' => $stock->product_name,
                        'voucher_no' => $stock->voucher_no,
                        'supplier_id' => $stock->supplier_id,
                        'purchase_price' => $stock->purchase_price,
                        'barcode' => $stock->barcode,
                        
                        'item_type' => $stock->item_type,
                        'brand' => $stock->brand,
                        'packing' => $stock->packing,
                        'transit_stock' => $stock->transit_stock
                    ];
                }

                $stockQty = (float)$stock->in_stock - (float)$stock->out_stock;
                $stocks[$key][$stock->warehouse_name] = abs($stockQty);
                $warehouseMap[$stock->warehouse_id] = $stock->warehouse_name;
            }

            return view('Store.closing_report_ajax_fze', [
                'stocks' => $stocks,
                'from_date' => $from_date,
                'to_date' => $to_date,
                'warehouses' => $warehouseMap
            ]);
        }

            return view('Store.closing_report_fze_com', compact(
                'warehouses', 'products', 'brands', 'defaultProducts', 'territories'
            ));

    }

}


public function getWarehousesByTerritory(Request $request)
{
    $territory_id = (int) $request->territory_id;

    $user = Auth::user();
    if (!$user) {
        return response()->json([]); // Not logged in
    }

    // Optional: Restrict if user is a limited-access user
    if (in_array($user->acc_type, ['user'])) {
        $user_territories = json_decode($user->territory_id, true);

        if (!is_array($user_territories)) {
            $user_territories = [$user->territory_id];
        }

        // Check if this territory is allowed for the user
        if (!in_array($territory_id, $user_territories)) {
            return response()->json([]); // Unauthorized access
        }
    }

    // Get warehouse IDs from stock table based on this territory
    $warehouse_ids = DB::connection('mysql2')
        ->table('stock')
        ->where('territory', $territory_id)
        ->where('status', 1)
        ->distinct()
        ->pluck('warehouse_id');

    // Get actual warehouse details
    $warehouses = DB::connection('mysql2')
        ->table('warehouse')
        ->whereIn('id', $warehouse_ids)
        ->where('status', 1)
        ->get(['id', 'name']);

    return response()->json($warehouses);
}


// public function getWarehousesByTerritory_stocktasfer(Request $request)
// {
//     $territory_ids = $request->territory_id;

//     // Agar JSON string me aaraha hai e.g. "[1]" to decode karo
//     if (is_string($territory_ids)) {
//         $decoded = json_decode($territory_ids, true);
//         if (json_last_error() === JSON_ERROR_NONE) {
//             $territory_ids = $decoded;
//         } else {
//             $territory_ids = explode(',', $territory_ids);
//         }
//     }

//     // Ensure array & integers
//     if (!is_array($territory_ids)) {
//         $territory_ids = [$territory_ids];
//     }
//     $territory_ids = array_map('intval', $territory_ids);

//     $user = Auth::user();
//     if (!$user) {
//         return response()->json([]);
//     }

//     // Restrict user territories if user is limited
//     if ($user->acc_type === 'user') {
//         $user_territories = json_decode($user->territory_id, true);
//         if (!is_array($user_territories)) {
//             $user_territories = [$user->territory_id];
//         }
//         $user_territories = array_map('intval', $user_territories);

//         $territory_ids = array_intersect($territory_ids, $user_territories);
//     }

//     if (empty($territory_ids)) {
//         return response()->json([]);
//     }

//     // Get warehouse IDs
//     $warehouse_ids = DB::connection('mysql2')
//         ->table('stock')
//         ->whereIn('territory', $territory_ids)
//         ->where('status', 1)
//         ->distinct()
//         ->pluck('warehouse_id')
//         ->toArray();

//     if (empty($warehouse_ids)) {
//         return response()->json([]);
//     }

//     // Get warehouses
//     $warehouses = DB::connection('mysql2')
//         ->table('warehouse')
//         ->whereIn('id', $warehouse_ids)
//         ->where('status', 1)
//         ->get(['id', 'name']);

//     return response()->json($warehouses);
// }



// public function getWarehousesByOtherTerritories(Request $request)
// {
//     $territory_ids = $request->territory_id;

//     // Agar JSON string me aaraha hai e.g. "[1]" to decode karo
//     if (is_string($territory_ids)) {
//         $decoded = json_decode($territory_ids, true);
//         if (json_last_error() === JSON_ERROR_NONE) {
//             $territory_ids = $decoded;
//         } else {
//             $territory_ids = explode(',', $territory_ids);
//         }
//     }

//     // Ensure array & integers
//     if (!is_array($territory_ids)) {
//         $territory_ids = [$territory_ids];
//     }
//     $territory_ids = array_map('intval', $territory_ids);

//     $user = Auth::user();
//     if (!$user) {
//         return response()->json([]);
//     }

//     // Restrict user territories if user is limited
//     if ($user->acc_type === 'user') {
//         $user_territories = json_decode($user->territory_id, true);
//         if (!is_array($user_territories)) {
//             $user_territories = [$user->territory_id];
//         }
//         $user_territories = array_map('intval', $user_territories);

//         $territory_ids = array_intersect($territory_ids, $user_territories);
//     }

//     if (empty($territory_ids)) {
//         return response()->json([]);
//     }

//     // Get warehouse IDs
//     $warehouse_ids = DB::connection('mysql2')
//         ->table('stock')
//        ->whereNotIn('territory', $territory_ids)
//         ->where('status', 1)
//         ->distinct()
//         ->pluck('warehouse_id')
//         ->toArray();

//     if (empty($warehouse_ids)) {
//         return response()->json([]);
//     }

//     // Get warehouses
//     $warehouses = DB::connection('mysql2')
//         ->table('warehouse')
//         ->whereIn('id', $warehouse_ids)
//         ->where('status', 1)
//         ->get(['id', 'name']);

//     return response()->json($warehouses);
// }


public function getWarehousesByTerritory_stocktasfer(Request $request)
{
    $territory_ids = $request->territory_id;

    // Decode JSON or comma-separated string
    if (is_string($territory_ids)) {
        $decoded = json_decode($territory_ids, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            $territory_ids = $decoded;
        } else {
            $territory_ids = explode(',', $territory_ids);
        }
    }

    if (!is_array($territory_ids)) {
        $territory_ids = [$territory_ids];
    }
    $territory_ids = array_map('intval', $territory_ids);

    $user = Auth::user();
    if (!$user) {
        return response()->json([]);
    }

    // Restrict user territories
    if ($user->acc_type === 'user') {
        $user_territories = json_decode($user->territory_id, true);
        if (!is_array($user_territories)) {
            $user_territories = [$user->territory_id];
        }
        $user_territories = array_map('intval', $user_territories);

        $territory_ids = array_intersect($territory_ids, $user_territories);
    }

    if (empty($territory_ids)) {
        return response()->json([]);
    }

    // Directly filter warehouses by territory_id
    $warehouses = DB::connection('mysql2')
        ->table('warehouse')
        ->whereIn('territory_id', $territory_ids)
        ->where('status', 1)
        ->get(['id', 'name']);

    return response()->json($warehouses);
}


public function getWarehousesByOtherTerritories(Request $request)
{
    $territory_ids = $request->territory_id;

    // Decode JSON or comma-separated string
    if (is_string($territory_ids)) {
        $decoded = json_decode($territory_ids, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            $territory_ids = $decoded;
        } else {
            $territory_ids = explode(',', $territory_ids);
        }
    }

    if (!is_array($territory_ids)) {
        $territory_ids = [$territory_ids];
    }
    $territory_ids = array_map('intval', $territory_ids);

    $user = Auth::user();
    if (!$user) {
        return response()->json([]);
    }

    // Restrict user territories
    if ($user->acc_type === 'user') {
        $user_territories = json_decode($user->territory_id, true);
        if (!is_array($user_territories)) {
            $user_territories = [$user->territory_id];
        }
        $user_territories = array_map('intval', $user_territories);

        $territory_ids = array_intersect($territory_ids, $user_territories);
    }

    if (empty($territory_ids)) {
        return response()->json([]);
    }

    // Get warehouses that belong to OTHER territories
    $warehouses = DB::connection('mysql2')
        ->table('warehouse')
        ->whereNotIn('territory_id', $territory_ids)
        ->where('status', 1)
        ->get(['id', 'name']);

    return response()->json($warehouses);
}




// public function getBrandsByWarehouse(Request $request)
// {
//     $warehouse_id = $request->warehouse_id;

//     $brand_ids = DB::connection('mysql2')->table('stock as s')
//         ->join('subitem as si', 's.sub_item_id', '=', 'si.id')
//         ->where('s.warehouse_id', $warehouse_id)
//         ->where('s.status', 1)
//         ->distinct()
//         ->pluck('si.brand_id');

//     $brands = DB::connection('mysql2')->table('brands')
//         ->whereIn('id', $brand_ids)
//         ->where('status', 1)
//         ->get(['id', 'name']);

//     return response()->json($brands);
// }

// public function getBrandsByWarehouse(Request $request)
// {
//     $warehouse_id = (int) $request->warehouse_id;

//     $user = Auth::user();
//     if (!$user) {
//         return response()->json([]);
//     }

//     // Optional: if user is restricted to certain territories or warehouses
//     if (in_array($user->acc_type, ['user'])) {
//         $user_territories = json_decode($user->territory_id, true);
//         if (!is_array($user_territories)) {
//             $user_territories = [$user->territory_id];
//         }

//         // Check if the warehouse belongs to allowed territory
//         $validWarehouse = DB::connection('mysql2')
//             ->table('stock')
//             ->where('warehouse_id', $warehouse_id)
//             ->whereIn('territory', $user_territories)
//             ->exists();

//         if (!$validWarehouse) {
//             return response()->json([]); // Unauthorized access
//         }
//     }

//     // Step 1: Get brand_ids from subitem via stock
//     $brand_ids = DB::connection('mysql2')->table('stock as s')
//         ->join('subitem as si', 's.sub_item_id', '=', 'si.id')
//         ->where('s.warehouse_id', $warehouse_id)
//         ->where('s.status', 1)
//         ->whereNotNull('si.brand_id')
//         ->distinct()
//         ->pluck('si.brand_id');

//     if ($brand_ids->isEmpty()) {
//         return response()->json([]);
//     }

//     // Step 2: Get brand details
//     $brands = DB::connection('mysql2')->table('brands')
//         ->whereIn('id', $brand_ids)
//         ->where('status', 1)
//         ->get(['id', 'name']);

//     return response()->json($brands);
// }


public function getBrandsByWarehouse(Request $request)
{
    $warehouse_ids = (array) $request->warehouse_id; // Accept multiple warehouse IDs

    $user = Auth::user();
    if (!$user) {
        return response()->json([]);
    }

    // Optional: if user is restricted to certain territories or warehouses
    if (in_array($user->acc_type, ['user'])) {
        $user_territories = json_decode($user->territory_id, true);
        if (!is_array($user_territories)) {
            $user_territories = [$user->territory_id];
        }

        // Check if all selected warehouses belong to allowed territory
        $validWarehouses = DB::connection('mysql2')
            ->table('stock')
            ->whereIn('warehouse_id', $warehouse_ids)
            ->whereIn('territory', $user_territories)
            ->distinct()
            ->pluck('warehouse_id')
            ->toArray();

        $diff = array_diff($warehouse_ids, $validWarehouses);
        if (!empty($diff)) {
            return response()->json([]); // Unauthorized access
        }
    }

    // Step 1: Get brand_ids from subitem via stock for multiple warehouses
    $brand_ids = DB::connection('mysql2')->table('stock as s')
        ->join('subitem as si', 's.sub_item_id', '=', 'si.id')
        ->whereIn('s.warehouse_id', $warehouse_ids)
        ->where('s.status', 1)
        ->whereNotNull('si.brand_id')
        ->distinct()
        ->pluck('si.brand_id');

    if ($brand_ids->isEmpty()) {
        return response()->json([]);
    }

    // Step 2: Get brand details
    $brands = DB::connection('mysql2')->table('brands')
        ->whereIn('id', $brand_ids)
        ->where('status', 1)
        ->get(['id', 'name']);

    return response()->json($brands);
}


    public function stockReportBatchWiseView()
    {

        $category= DB::Connection('mysql2')->table('stock as a')
            ->join('subitem as b','a.sub_item_id','=','b.id')
            ->join('category as c','c.id','=','b.main_ic_id')

            ->select('c.id','c.main_ic')
            ->where('a.status',1)
            ->groupBy('c.id')

            ->get();

        $batch_code= DB::Connection('mysql2')->table('stock as a')
            ->select('a.batch_code')
            ->where('a.status',1)
            ->groupBy('a.batch_code')
            ->get();

        return view('Store.stockReportBatchWiseView',compact('category','batch_code'));
    }


    public function fullstockReportView()
    {
        return view('Store.fullstockReportView');
    }
    public function fullstockReportViewBatch()
    {
        return view('Store.fullstockReportViewBatch');
    }


    public function StockOpeningValuesUpdate()
    {
        $Subitem= new Subitem();
        $Subitem=$Subitem->SetConnection('mysql2');
        $Subitem=$Subitem->where('status',1)->get();
        return view('Store.StockOpeningValuesUpdate',compact('Subitem'));
    }

    public function stockDetailReport()
    {
        return view('Store.stockDetailReport');
    }

    public function InventoryStockReport()
    {
        return view('Store.InventoryStockReport');
    }
    public function checkPurchasingPage()
    {
        $SubItem = DB::Connection('mysql2')->select('select * from subitem where status = 1');
        return view('Store.checkPurchasingPage',compact('SubItem'));
    }
    public function getCheckPurchasingDataAjax()
    {
        $SubItemId = Input::get('SubItemId');
        $StockData = DB::Connection('mysql2')->select('select * from stock where status = 1 AND sub_item_id = '.$SubItemId.' and voucher_type in(1) and transfer = 0 ORDER BY voucher_date asc');
        return view('Store.AjaxPages.getCheckPurchasingDataAjax', compact('StockData'));
    }


    public function rateAndAmountupdate()
    {
        return view('Store.rateAndAmountupdate');
    }

    public function InventoryStockReportAjax()
    {
        $from_date  = $_GET['from_date'];
        $to_date    = $_GET['to_date'];
     //   $stock = DB::Connection('mysql2')->select('SELECT s.*, gd.po_data_id as po_id FROM stock s
                                               //   INNER JOIN grn_data gd ON gd.grn_no = s.voucher_no
                                                //  WHERE s.status=1 AND s.voucher_type=1 AND s.voucher_date BETWEEN "'.$from_date.'" AND "'.$to_date.'" ');

        $stock = DB::Connection('mysql2')->select('select b.sub_item_id,a.supplier_id,a.grn_no,a.grn_date,a.type,b.region,b.region_to,b.purchase_recived_qty,b.purchase_recived_qty,b.amount,b.rate
        from goods_receipt_note a
         inner join grn_data b
         ON
         a.id=b.master_id
         where a.grn_date BETWEEN  "'.$from_date.'" and "'.$to_date.'"
         and a.status=1
         and a.grn_status in (2,3)');


        $issuence=DB::Connection('mysql2')->select('select a.iss_no,a.issuance_type,a.iss_date,a.region,b.id,b.rate,b.amount,b.sub_item_id,b.qty,a.description from issuance a
        inner join
        issuance_data b
        ON
        a.id=b.master_id
        where a.iss_date BETWEEN "'.$from_date.'" and "'.$to_date.'"
        and a.status=1
        and a.issuance_status=2
        Order by b.sub_item_id
        ');



        $return=DB::Connection('mysql2')->select('select a.issuance_no as iss_no,a.issuance_type,a.issuance_date as iss_date,a.region,b.subitem as sub_item_id,b.rate,b.amount,b.stock_return_data_id,b.qty,a.description from stock_return a
        inner join
        stock_return_data b
        ON
        a.stock_return_id=b.stock_return_id
        where a.issuance_date BETWEEN "'.$from_date.'" and "'.$to_date.'"
        and a.status=1
        and a.return_status=2');

        return view('Store.AjaxPages.InventoryStockReportAjax', compact('stock','from_date','to_date','issuence','return'));


    }

    public function rateAndAmountupdateAjax()
    {
        $from_date  = $_GET['from_date'];
        //$to_date    = $_GET['to_date'];
        $dateArray = explode('-',$from_date);
        $d=cal_days_in_month(CAL_GREGORIAN,$dateArray[1],$dateArray[0]);
        $From = $from_date.'-01';
        $To = $from_date.'-'.$d;
        $to_date = $To;


        //   $stock = DB::Connection('mysql2')->select('SELECT s.*, gd.po_data_id as po_id FROM stock s
        //   INNER JOIN grn_data gd ON gd.grn_no = s.voucher_no
        //  WHERE s.status=1 AND s.voucher_type=1 AND s.voucher_date BETWEEN "'.$from_date.'" AND "'.$to_date.'" ');

        $stock = DB::Connection('mysql2')->select('select b.sub_item_id,a.supplier_id,a.grn_no,a.grn_date,a.type,b.region,b.region_to,b.purchase_recived_qty,b.id,b.rate,b.amount
        ,a.grn_date
        from goods_receipt_note a
         inner join grn_data b
         ON
         a.id=b.master_id
         
         where a.grn_date BETWEEN "'.$From.'" and "'.$To.'"
         and a.status=1
         and a.grn_status in (2,3)');


        $issuence=DB::Connection('mysql2')->select('select a.iss_no,a.issuance_type,a.iss_date,a.region,b.id,b.rate,b.amount,b.sub_item_id,b.qty,a.description,a.iss_date,a.region from issuance a
        inner join
        issuance_data b
        ON
        a.id=b.master_id
        where a.iss_date BETWEEN "'.$From.'" and "'.$To.'"
        and a.status=1
        and a.issuance_status=2
        Order by b.sub_item_id
        ');



        $return=DB::Connection('mysql2')->select('select a.issuance_no as iss_no,a.issuance_type,a.issuance_date as iss_date,a.region,b.subitem as sub_item_id,b.rate,b.amount,b.stock_return_data_id,b.qty,a.description,a.region from stock_return a
        inner join
        stock_return_data b
        ON
        a.stock_return_id=b.stock_return_id
        where a.issuance_date BETWEEN "'.$From.'" and "'.$To.'"
        and a.status=1
        and a.return_status=2');

        return view('Store.AjaxPages.rateAndAmountupdateAjax', compact('stock','from_date','to_date','issuence','return'));


    }



    function UpdateRateAmount()
    {
        $Id = Input::get('Id');
        $Rate = Input::get('Rate');
        $Amount = Input::get('Amount');
        $UpdateData['rate'] = $Rate;
        $UpdateData['amount'] = $Amount;
        //Grn Data And Stock update
        DB::connection('mysql2')->table('issuance_data')->where('id',$Id)->update($UpdateData);
        DB::connection('mysql2')->table('stock')->where('master_id',$Id)->where('voucher_type',2)->update($UpdateData);
    }

    function UpdateRateAmountGrn()
    {
        $Id = Input::get('Id');
        $Rate = Input::get('Rate');
        $Amount = Input::get('Amount');
        $UpdateData['rate'] = $Rate;
        $UpdateData['amount'] = $Amount;
        //Issuance Data And Stock update
        DB::connection('mysql2')->table('grn_data')->where('id',$Id)->update($UpdateData);
        DB::connection('mysql2')->table('stock')->where('master_id',$Id)->where('voucher_type',1)->update($UpdateData);
    }
    function UpdateRateAmountReturn()
    {
        $Id = Input::get('Id');
        $Rate = Input::get('Rate'); 
        $Amount = Input::get('Amount');
        $UpdateData['rate'] = $Rate;
        $UpdateData['amount'] = $Amount;
        //Return Stock Data And Stock update
        DB::connection('mysql2')->table('stock_return_data')->where('stock_return_data_id',$Id)->update($UpdateData);
        DB::connection('mysql2')->table('stock')->where('master_id',$Id)->where('voucher_type',3)->update($UpdateData);
    }

    function stockReportItemWisePage()
    {
        return view('Store.stockReportItemWisePage');
    }


    function stockReportItemWiseAjax(Request $request)
    {
        $from=$request->from;
        $to=$request->to_date;

        $data= DB::Connection('mysql2')->select('SELECT a.qty,a.rate,a.amount,b.sub_ic,b.id,b.id as sub_ic_id from stock a
        INNER JOIN
        subitem b
        ON
        a.sub_item_id=b.id
        where a.status=1
        and qty>0
        

        and a.voucher_date BETWEEN "'.$from.'" and "'.$to.'"  group by a.sub_item_id');
//        $data=DB::Connection('mysql2')->select('SELECT a.qty,a.rate,a.amount,b.sub_ic,c.region_name from stock
//        where voucher_date BETWEEN  "2020-07-01" and "2020-07-31" and status=1
//        group by sub_item_id,sub_item_id');
        return view('Store.AjaxPages.stockReportItemWiseAjax',compact('data'));
    }

     public function item_detaild_supplier_wise(Request $request)
     {
          $item=$request->sub_item_id;

       $data= DB::Connection('mysql2')->table('stock')->where('status',1)
            ->where('sub_item_id',$item)->where('voucher_type',1)->where('opening',0)->get();
         return view('Store.AjaxPages.item_detaild_supplier_wise',compact('data','item'));
     }

    public function add_opening(Request $request)
    {
  
        return view('Store.add_opening');
    }

    public function add_opening_import(Request $request)
    {
  
        return view('Store.add_opening_import');
    }

    public function BAclosingReportViewClone(Request $request)
    {
        // Exact copy of BAclosingReportView method
        // $from_date = $request->from ?? date('Y-m-d');
            $from_date = $request->from ?? date('Y-m-d', strtotime('-2 years'));

            $to_date = $request->to ?? date('Y-m-d');
            $warehouse_ids = (array) $request->input('warehouse_id', []);
            $product_id = $request->product_id ?? null;
            $brand_ids = (array) $request->input('brand_id', []);
            $territory_id = $request->territory_id ?? null;

            $user = Auth::user();
            $isUser = $user && $user->acc_type == 'user';
            if ($isUser) {
                $territory_ids = json_decode($user->territory_id, true);
                if (!is_array($territory_ids)) {
                    $territory_ids = [$user->territory_id];
                }

                $warehouseList = DB::connection('mysql2')->table('stock')
                    ->whereIn('territory', $territory_ids)
                    ->where('status', 1)
                    ->distinct()
                    ->pluck('warehouse_id');

                $customers = \Illuminate\Support\Facades\DB::connection("mysql2")->table("customers")
                    ->where("status", "active")
                    ->get();

                $warehouses = DB::connection('mysql2')->table('warehouse')
                    ->whereIn('id', $warehouseList)
                    ->where("is_virtual", 1)
                    ->where('status', 1)
                    ->get();

                $subitem_ids = DB::connection('mysql2')->table('stock')
                    ->whereIn('territory', $territory_ids)
                    ->where('status', 1)
                    ->distinct()
                    ->pluck('sub_item_id');

                $products = DB::connection('mysql2')->table('subitem')
                    ->whereIn('id', $subitem_ids)
                    ->where('status', 1)
                    ->get(['id', 'product_name']);

                $brandList = DB::connection('mysql2')->table('subitem')
                    ->whereIn('id', $subitem_ids)
                    ->whereNotNull('brand_id')
                    ->pluck('brand_id');

                $brands = DB::connection('mysql2')->table('brands')
                    ->whereIn('id', $brandList)
                    ->where('status', 1)
                    ->get(['id', 'name']);

                $territories = DB::connection('mysql2')->table('territories')
                    ->whereIn('id', $territory_ids)
                    ->where('status', 1)
                    ->get(['id', 'name']);
            } else {
                $warehouses = DB::connection('mysql2')->table('warehouse')
                                                            ->where("is_virtual", 1)
                                                            ->where('status', 1)
                                                            ->get();
                $customers = \Illuminate\Support\Facades\DB::connection("mysql2")->table("customers")
                    ->where("status", "active")
                    ->get();

                $products = DB::connection('mysql2')->table('subitem')->where('status', 1)->get(['id', 'product_name']);
                $brands = DB::connection('mysql2')->table('brands')->where('status', 1)->get(['id', 'name']);
                $territories = DB::connection('mysql2')->table('territories')->where('status', 1)->get(['id', 'name']);
            }

            $defaultProducts = DB::connection('mysql2')
                ->table('subitem')
                ->where('status', 1)
                ->limit(5)
                ->get(['id', 'product_name']);


        if ($request->ajax()) {
        $transitSub = DB::connection('mysql2')->table('stock_transfers_transit')
            ->select(
                'product_id',
                'warehouse_to_id',
                DB::raw('SUM(quantity) as transit_stock')
            )
            ->where('tr_status', 1)
            ->groupBy('product_id', 'warehouse_to_id');

        $rawQuery = $transitSub->toSql(); // ye SQL string banega
        $bindings = $transitSub->getBindings(); // bindings

        $query = DB::connection('mysql2')->table('ba_stock as s')
            ->join('subitem as si', 's.sub_item_id', '=', 'si.id')
            ->leftJoin('product_type as pt', 'si.product_type_id', '=', 'pt.product_type_id')
            ->leftJoin("customers as cus", "cus.id", "=", "s.customer_id")
            ->leftJoin('category as c', 'si.main_ic_id', '=', 'c.id')
            ->leftJoin('warehouse as w', 's.warehouse_id', '=', 'w.id')
            ->leftJoin('brands as b', 'si.brand_id', '=', 'b.id')
            ->leftJoin(DB::raw("($rawQuery) as st"), function($join) {
                $join->on('si.id', '=', 'st.product_id')
                    ->on('w.id', '=', 'st.warehouse_to_id');
            })
            ->addBinding($bindings, 'join')
            ->select(
                'si.id as product_id',
                'si.sku_code',
                'si.product_name',
                'si.product_barcode as barcode',
                'pt.type as item_type',
                // 'si.type as item_type',
                'si.pack_size as packing',
                'b.name as brand',
                'w.id as warehouse_id',
                'w.name as warehouse_name',
                'cus.name as customer_name',
                'cus.id as customer_id',
                DB::raw('SUM(CASE WHEN s.voucher_type IN (51,1,9) AND s.transfer_status != 1 THEN s.qty ELSE 0 END) AS in_stock'),
                DB::raw('SUM(CASE WHEN s.voucher_type IN (50) THEN s.qty ELSE 0 END) AS out_stock'),
                DB::raw('IFNULL(st.transit_stock,0) as transit_stock')
            )
            ->where('s.status', 1)

            ->whereBetween('s.created_date', [$from_date, $to_date])
            ->groupBy('si.id','cus.id');


            if (!empty($warehouse_ids)) {
                $query->whereIn('s.warehouse_id', $warehouse_ids);
            } elseif ($territory_id) {
                $territoryWarehouseIds = DB::connection('mysql2')->table('stock')
                    ->where('territory', $territory_id)
                    ->where('status', 1)
                    ->distinct()
                    ->pluck('warehouse_id');

                if ($territoryWarehouseIds->isNotEmpty()) {
                    $query->whereIn('s.warehouse_id', $territoryWarehouseIds);
                } else {
                    $query->whereRaw('0=1');
                }
            }

            if ($product_id) {
                $query->where('s.sub_item_id', $product_id);
            }

            if (!empty($brand_ids)) {
                $query->whereIn('si.brand_id', $brand_ids);
            }

            // ✅ Group only by product and warehouse (not transit_stock)
            $rawStock = $query->groupBy('si.id', 'w.id')->get();

            $stocks = [];
            $customersMap = [];

            foreach ($rawStock as $stock) {
                $key = $stock->product_id;

                if (!isset($stocks[$key])) {
                    $stocks[$key] = [
                        'sku_code' => $stock->sku_code,
                        'product_name' => $stock->product_name,
                        'barcode' => $stock->barcode,
                        'item_type' => $stock->item_type,
                        'brand' => $stock->brand,
                        'packing' => $stock->packing,
                        'transit_stock' => $stock->transit_stock
                    ];
                }

                $stockQty = (float)$stock->in_stock - (float)$stock->out_stock;
                $stocks[$key][$stock->customer_name] = abs($stockQty);
                $customersMap[$stock->customer_id] = $stock->customer_name;
            }

            return view('Store.ba_closing_report_clone_ajax', [
                'stocks' => $stocks,
                'from_date' => $from_date,
                'to_date' => $to_date,
                'customersMap' => $customersMap,
                'customers' => $customers
            ]);
        }


        return view('Store.ba_closing_report_clone', compact(
                        'warehouses', 'products', 'brands', 'defaultProducts', 'territories', 'customers'
                    ));

    }

        public function BAclosingReportView(Request $request)
        {


  
    
            // $from_date = $request->from ?? date('Y-m-d');
                $from_date = $request->from ?? date('Y-m-d', strtotime('-2 years'));

                $to_date = $request->to ?? date('Y-m-d');
                $warehouse_ids = (array) $request->input('warehouse_id', []);
                $product_id = $request->product_id ?? null;
                $brand_ids = (array) $request->input('brand_id', []);
                $territory_id = $request->territory_id ?? null;

                $user = Auth::user();
                $isUser = $user && $user->acc_type == 'user';
                if ($isUser) {
                    $territory_ids = json_decode($user->territory_id, true);
                    if (!is_array($territory_ids)) {
                        $territory_ids = [$user->territory_id];
                    }

                    $warehouseList = DB::connection('mysql2')->table('stock')
                        ->whereIn('territory', $territory_ids)
                        ->where('status', 1)
                        ->distinct()
                        ->pluck('warehouse_id');
                 
                    $warehouses = DB::connection('mysql2')->table('warehouse')
                        ->whereIn('id', $warehouseList)
                        ->where("is_virtual", 1)
                        ->where('status', 1)
                        ->get();

                    $subitem_ids = DB::connection('mysql2')->table('stock')
                        ->whereIn('territory', $territory_ids)
                        ->where('status', 1)
                        ->distinct()
                        ->pluck('sub_item_id');

                    $products = DB::connection('mysql2')->table('subitem')
                        ->whereIn('id', $subitem_ids)
                        ->where('status', 1)
                        ->get(['id', 'product_name']);

                    $brandList = DB::connection('mysql2')->table('subitem')
                        ->whereIn('id', $subitem_ids)
                        ->whereNotNull('brand_id')
                        ->pluck('brand_id');

                    $brands = DB::connection('mysql2')->table('brands')
                        ->whereIn('id', $brandList)
                        ->where('status', 1)
                        ->get(['id', 'name']);

                    $territories = DB::connection('mysql2')->table('territories')
                        ->whereIn('id', $territory_ids)
                        ->where('status', 1)
                        ->get(['id', 'name']);
                } else {
                    $warehouses = DB::connection('mysql2')->table('warehouse')
                                                                ->where("is_virtual", 1)
                                                                ->where('status', 1)
                                                                ->get();
                    $products = DB::connection('mysql2')->table('subitem')->where('status', 1)->get(['id', 'product_name']);
                    $brands = DB::connection('mysql2')->table('brands')->where('status', 1)->get(['id', 'name']);
                    $territories = DB::connection('mysql2')->table('territories')->where('status', 1)->get(['id', 'name']);
                }

                $defaultProducts = DB::connection('mysql2')
                    ->table('subitem')
                    ->where('status', 1)
                    ->limit(5)
                    ->get(['id', 'product_name']);


            if ($request->ajax()) {
            $transitSub = DB::connection('mysql2')->table('stock_transfers_transit')
                ->select(
                    'product_id',
                    'warehouse_to_id',
                    DB::raw('SUM(quantity) as transit_stock')
                )
                ->where('tr_status', 1)
                ->groupBy('product_id', 'warehouse_to_id');

            $rawQuery = $transitSub->toSql(); // ye SQL string banega
            $bindings = $transitSub->getBindings(); // bindings

            $query = DB::connection('mysql2')->table('ba_stock as s')
                ->join('subitem as si', 's.sub_item_id', '=', 'si.id')
                ->leftJoin('product_type as pt', 'si.product_type_id', '=', 'pt.product_type_id')

                ->leftJoin('category as c', 'si.main_ic_id', '=', 'c.id')
                ->leftJoin('warehouse as w', 's.warehouse_id', '=', 'w.id')
                ->leftJoin('brands as b', 'si.brand_id', '=', 'b.id')
                ->leftJoin(DB::raw("($rawQuery) as st"), function($join) {
                    $join->on('si.id', '=', 'st.product_id')
                        ->on('w.id', '=', 'st.warehouse_to_id');
                })
                ->addBinding($bindings, 'join')
                ->select(
                    'si.id as product_id',
                    'si.sku_code',
                    'si.product_name',
                    'si.product_barcode as barcode',
                    'pt.type as item_type',
                    // 'si.type as item_type',
                    'si.pack_size as packing',
                    'b.name as brand',
                    'w.id as warehouse_id',
                    'w.name as warehouse_name',
                    DB::raw('SUM(CASE WHEN s.voucher_type IN (51,1,9) AND s.transfer_status != 1 THEN s.qty ELSE 0 END) AS in_stock'),
                    DB::raw('SUM(CASE WHEN s.voucher_type IN (50) THEN s.qty ELSE 0 END) AS out_stock'),
                    DB::raw('IFNULL(st.transit_stock,0) as transit_stock')
                )
                ->where('s.status', 1)
                
                ->whereBetween('s.created_date', [$from_date, $to_date])
                ->groupBy('si.id','w.id');


                if (!empty($warehouse_ids)) {
                    $query->whereIn('s.warehouse_id', $warehouse_ids);
                } elseif ($territory_id) {
                    $territoryWarehouseIds = DB::connection('mysql2')->table('stock')
                        ->where('territory', $territory_id)
                        ->where('status', 1)
                        ->distinct()
                        ->pluck('warehouse_id');

                    if ($territoryWarehouseIds->isNotEmpty()) {
                        $query->whereIn('s.warehouse_id', $territoryWarehouseIds);
                    } else {
                        $query->whereRaw('0=1');
                    }
                }

                if ($product_id) {
                    $query->where('s.sub_item_id', $product_id);
                }

                if (!empty($brand_ids)) {
                    $query->whereIn('si.brand_id', $brand_ids);
                }

                // ✅ Group only by product and warehouse (not transit_stock)
                $rawStock = $query->groupBy('si.id', 'w.id')->get();

                $stocks = [];
                $warehouseMap = [];

                foreach ($rawStock as $stock) {
                    $key = $stock->product_id;

                    if (!isset($stocks[$key])) {
                        $stocks[$key] = [
                            'sku_code' => $stock->sku_code,
                            'product_name' => $stock->product_name,
                            'barcode' => $stock->barcode,
                            
                            'item_type' => $stock->item_type,
                            'brand' => $stock->brand,
                            'packing' => $stock->packing,
                            'transit_stock' => $stock->transit_stock
                        ];
                    }
                       
                    $stockQty = (float)$stock->in_stock - (float)$stock->out_stock;
                    $stocks[$key][$stock->warehouse_name] = abs($stockQty);
                    $warehouseMap[$stock->warehouse_id] = $stock->warehouse_name;
                }

                return view('Store.ba_closing_report_ajax', [
                    'stocks' => $stocks,
                    'from_date' => $from_date,
                    'to_date' => $to_date,
                    'warehouses' => $warehouseMap
                ]);
            }

              
            return view('Store.ba_closing_report', compact(
                            'warehouses', 'products', 'brands', 'defaultProducts', 'territories'
                        ));

        }
//  public function add_opening_import_post(Request $request)
// {

//     ini_set('max_execution_time', 300); 
//     $validator = Validator::make($request->all(), [
//         'file' => 'required|mimes:csv,txt',
//     ], [
//         'file.required' => 'Please select a file to upload.',
//         'file.mimes'    => 'Only .csv files are allowed.',
//     ]);

//     if ($validator->fails()) {
//         return response()->json(['errors' => $validator->errors()], 422);
//     }

    
//     $file = $request->file('file')->getRealPath();
//     $missingProducts = [];

//     if (($handle = fopen($file, 'r')) !== false) {
//         $header = fgetcsv($handle, 1000, ",");

//         DB::beginTransaction();

//         try {
//             while (($row = fgetcsv($handle, 1000, ",")) !== false) {
               
//                 if (count($row) > 20) continue;

              

//                 $product_name        = strtolower(trim($row[2]));
//                 $product_mrp         = trim($row[5]);
//                 $product_sale_price  = trim($row[6]);

//                 $warehouse_quantities = [
//                     17 => trim($row[9]),  // Make Up City North
//                     18 => trim($row[10]), // Liquidation Stock RWL
//                     19 => trim($row[11]), // Tariq Trader Warehouse PSH
//                     15 => trim($row[12]), // FOC Warehouse Rawalpindi
//                 //     20 => trim($row[14]), // Tester Warehouse Rawalpindi
//                 //    21 => trim($row[14]), // Damage Expire Warehouse Rawalpindi
//                 //     22 => trim($row[15]), // Commercial Fresh Stock
//                 ];

//                 //   $warehouse_quantities = [
//                 //     16 => trim($row[8]),  // Make Up City North
//                 //     24 => trim($row[9]), // Liquidation Stock RWL
//                 //     25 => trim($row[10]), // Tariq Trader Warehouse PSH
//                 //     26 => trim($row[11]), // FOC Warehouse Rawalpindi
//                 //     27 => trim($row[12]), // Tester Warehouse Rawalpindi
                   
//                 // ];

                
// // dd($row);
//                 if ($product_name !== '') {
//                     $subitem = Subitem::whereRaw('LOWER(product_name) = ?', [$product_name])->first();

//                     if ($subitem) {
//                         // $subitem->mrp_price  = $product_mrp;
//                         // $subitem->sale_price = $product_sale_price;
//                         // $subitem->save();

//                         foreach ($warehouse_quantities as $warehouse_id => $qty) {
//                            if (is_numeric($qty)) {
//                                 $data = [
//                                     'voucher_type'  => 1,
//                                     'sub_item_id'   => $subitem->id,
//                                     'batch_code'    => $subitem->batch_code,
//                                     'qty'           => $qty,
//                                     'amount'        => 0,
//                                     'warehouse_id'  => $warehouse_id,
//                                     'opening'       => 1,
//                                     'created_date'  => date('Y-m-d'),
//                                     'username'      => 'Amir Murshad',
//                                     'status'        => 1,
//                                     'Territory' => isset($row[13]) ? $row[13] : null,
//                                     // 'Territory' => isset($row[13]) ? $row[13] : null,
//                                 ];

//                                 DB::connection('mysql2')->table('stock')->insert($data);
//                             }
//                         }

//                     } else {
//                         $missingProducts[] = $product_name;
//                     }
//                 }
//             }

//             DB::commit();
//         } catch (\Exception $e) {
//             DB::rollBack();
//             fclose($handle);
//             return response()->json(['message' => 'Data import failed: ' . $e->getMessage()], 500);
//         }

//         fclose($handle);

//         $message = '✅ Data imported successfully.';
//         if (count($missingProducts)) {
//             $message .= ' ⚠️ Missing Products: ' . implode(', ', array_unique($missingProducts));
//         }

//         return response()->json(['message' => $message]);
//     } else {
//         return response()->json(['message' => '❌ Failed to open the file.'], 500);
//     }
// }


public function add_opening_import_post(Request $request)
{
    ini_set('max_execution_time', 300); 

    $validator = Validator::make($request->all(), [
        'file' => 'required|mimes:csv,txt',
    ], [
        'file.required' => 'Please select a file to upload.',
        'file.mimes'    => 'Only .csv files are allowed.',
    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }

    $file = $request->file('file')->getRealPath();
    $missingProducts = [];

    if (($handle = fopen($file, 'r')) !== false) {
        $header = fgetcsv($handle, 1000, ",");

        DB::beginTransaction();

        try {
            while (($row = fgetcsv($handle, 1000, ",")) !== false) {
                if (count($row) > 20) continue;

                $product_name       = strtolower(trim($row[2]));
                $sku_code           = strtolower(trim($row[1]));
                $product_mrp        = trim($row[5]);
                $product_sale_price = trim($row[6]);


                //  $warehouse_quantities = [
                //     10 => trim($row[9]),  // Make Up City North
                  
                // ];

                // $warehouse_quantities = [
                //     17 => trim($row[8]),  // Make Up City North
                //     18 => trim($row[9]), // Liquidation Stock RWL
                //     19 => trim($row[10]), // Tariq Trader Warehouse PSH
                //     15 => trim($row[11]), // FOC Warehouse Rawalpindi
                // ];



                $warehouse_quantities = [
                    26 => trim($row[8]),  // Make Up City North
                    // 22 => trim($row[9]), // Liquidation Stock RWL
                  
                   
                ];


                //     $warehouse_quantities = [
                //     10 => trim($row[8]),  // Make Up City North
                   
                // ];

                if ($product_name !== '' && $sku_code !== '') {
                    $subitem = Subitem::whereRaw('LOWER(product_name) = ?', [$product_name])
                                      ->whereRaw('LOWER(sku_code) = ?', [$sku_code])
                                      ->first();

                    if ($subitem) {
                        foreach ($warehouse_quantities as $warehouse_id => $qty) {
                            if (is_numeric($qty)) {
                                $data = [
                                    'voucher_type'  => 1,
                                    'sub_item_id'   => $subitem->id,
                                    'batch_code'    => $subitem->batch_code,
                                    'qty'           => $qty,
                                    'amount'        => 0,
                                    'warehouse_id'  => $warehouse_id,
                                    'opening'       => 1,
                                    'created_date'  => date('Y-m-d'),
                                    'username'      => 'Amir Murshad',
                                    'status'        => 1,
                                    'Territory'     => isset($row[9]) ? $row[9] : null,
                                ];


                                DB::connection('mysql2')->table('stock')->insert($data);
                            }
                        }
                    } else {
                        $missingProducts[] = $product_name . ' (SKU: ' . $sku_code . ')';
                    }
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            fclose($handle);
            return response()->json(['message' => 'Data import failed: ' . $e->getMessage()], 500);
        }

        fclose($handle);

        $message = '✅ Data imported successfully.';
        if (count($missingProducts)) {
            $message .= ' ⚠️ Missing Products: ' . implode(', ', array_unique($missingProducts));
        }

        return response()->json(['message' => $message]);
    } else {
        return response()->json(['message' => '❌ Failed to open the file.'], 500);
    }
}



    public function average_cost(Request $request)
    {
        $m=$request->m;
        return view('Store.average_cost',compact('m'));
    }

    public function inventory_movement()
    {


        $SubItem = DB::Connection('mysql2')->select('select a.id,a.sub_ic from subitem a
                                          INNER JOIN stock b ON b.sub_item_id = a.id
                                          WHERE a.status = 1
                                          GROUP BY b.sub_item_id');

        return view('Store.inventory_movement',compact('SubItem'));
    }

    public function inventory_movement_test()
    {


        $SubItem = DB::Connection('mysql2')->select('select a.id,a.sub_ic from subitem a
                                          INNER JOIN stock b ON b.sub_item_id = a.id
                                          WHERE a.status = 1
                                          GROUP BY b.sub_item_id');

        return view('Store.inventory_movement_test',compact('SubItem'));
    }


    public function stock_movemnet(Request $request)
    {
        ini_set('memory_limit', '-1');
        $ReportType=$request->ReportType;
        $from=$request->from_date;
        $brand_id = $request->brand_id;
        $to=$request->to_date;
        $accyeafrom=$request->accyearfrom;
        $ItemId=$request->ItemId;
        $purchase=$request->purchase;
        $sales=$request->sales;

        if($ItemId == 'all'):

        $data=DB::Connection('mysql2')->table('stock as a')
            ->join('subitem as b','a.sub_item_id','=','b.id')
            ->join("brands", "brands.id", "=", "b.brand_id")
            ->when(isset($brand_id), function($query) use ($brand_id) {
                $query->where("brands.id", $brand_id);
            })
            ->where('a.status',1)
            ->where('amount','>',0)
            ->whereBetween("a.created_date", [$from, $to])
            ->select('a.*','b.sub_ic', 'b.product_name')
            ->groupby('a.sub_item_id')

            ->get();

        else:
        $data=DB::Connection('mysql2')->table('stock as a')
            ->join('subitem as b','a.sub_item_id','=','b.id')
            ->join("brands", "brands.id", "=", "b.brand_id")
            ->when(isset($brand_id), function($query) use ($brand_id) {
                $query->where("brands.id", $brand_id);
            })
            ->where('a.status',1)
            ->where('a.sub_item_id',$ItemId)
            ->whereBetween("a.created_date", [$from, $to])
            ->select('a.*','b.sub_ic', 'b.product_name')
            ->groupby('a.sub_item_id')
            ->get();
        endif;
        if($ReportType == 1):
            if ($purchase==0 && $sales==0):
     
           return view('Store.AjaxPages.stock_movemnet',compact('from','to','accyeafrom','data'));
            elseif($purchase==1):
                return view('Store.AjaxPages.stock_movement_in',compact('from','to','accyeafrom','data'));

            elseif($sales==1):
                return view('Store.AjaxPages.stock_movement_out',compact('from','to','accyeafrom','data'));
            endif;

        else:

            if($ItemId == 'all'):
                $data=DB::Connection('mysql2')->table('transaction_supply_chain as a')
                    ->join('subitem as b','a.item_id','=','b.id')
                    ->where('a.status',1)
                    ->select('a.*','b.sub_ic')
                    
                    ->groupby('a.item_id')
                    ->get();
            else:
                $data=DB::Connection('mysql2')->table('transaction_supply_chain as a')
                    ->join('subitem as b','a.item_id','=','b.id')
                    ->where('a.status',1)
                    ->where('a.item_id',$ItemId)
                    ->select('a.*','b.sub_ic')
                    ->groupby('a.item_id')
                    ->get();
            endif;
            return view('Store.AjaxPages.stock_movemnet_finance',compact('from','to','accyeafrom','data'));
        endif;

    }


    public function stock_movemnet_test(Request $request)
    {
        ini_set('memory_limit', '-1');
        $ReportType=$request->ReportType;
        $from=$request->from_date;
        $to=$request->to_date;
        $accyeafrom=$request->accyearfrom;
        $ItemId=$request->ItemId;

        if($ItemId == 'all'):
            $RecCount=DB::Connection('mysql2')->table('stock as a')
                ->join('subitem as b','a.sub_item_id','=','b.id')
                ->where('a.status',1)
                ->where('a.amount','>',0)
                ->select('a.*','b.sub_ic')
                ->groupby('a.sub_item_id')->get();

            $RecCount = $RecCount->count();

            $data = DB::Connection('mysql2')->select('select a.*,b.sub_ic from stock a
            INNER JOIN subitem b on b.id = a.sub_item_id
            WHERE a.status = 1
            and a.amount > 0
            group by a.sub_item_id limit 0,1000');


        else:
            $data=DB::Connection('mysql2')->table('stock as a')
                ->join('subitem as b','a.sub_item_id','=','b.id')
                ->where('a.status',1)
                ->where('a.sub_item_id',$ItemId)
                ->select('a.*','b.sub_ic')
                ->groupby('a.sub_item_id')->get();

        endif;

        return view('Store.AjaxPages.stock_movemnet_test',compact('from','to','accyeafrom','data','RecCount'));
    }
    public function stock_movemnetAjaxMoreData(Request $request)
    {

        $from=$request->from_date;
        $to=$request->to_date;
        $accyeafrom=$request->accyearfrom;
        $RCount=$request->RCount;
        $LmFrom=$request->QCounter;

        $data = DB::Connection('mysql2')->select('select a.*,b.sub_ic from stock a INNER JOIN subitem b on b.id = a.sub_item_id WHERE a.status = 1 and a.amount > 0 group by a.sub_item_id limit '.$LmFrom.',1000');
        
        return view('Store.AjaxPages.stock_movemnetAjaxMoreData',compact('from','to','accyeafrom','data','LmFrom'));

    }







    public function issuence_against_product(Request $request)
    {
         $id=$request->id;
        $data=DB::Connection('mysql2')->table('product_creation_data')->where('master_id',$id)->where('status',1)->get();

        $check=DB::Connection('mysql2')->table('product_creation_data')->where('status',1)->where('master_id',$id)->where('pi_no','=',null)->count();

        if ($check==0):
            echo 'Access Denied';
            die;
        endif;
        return view('Store.issuence_against_product',compact('data'));
    }

    public function inventory_movement_fi()
    {


        $SubItem = DB::Connection('mysql2')->select('select a.id,a.sub_ic from subitem a
                                          INNER JOIN transaction_supply_chain b
                                          ON b.item_id = a.id
                                          WHERE a.status = 1
                                          GROUP BY b.item_id');

        return view('Store.inventory_movement_fi',compact('SubItem'));
    }

    public function add_internal_consumtion()
    {

        return view('Store.add_internal_consumtion');
    }

    public function internal_consumtion_list()
    {

        return view('Store.internal_consumtion_list');
    }

    public function add_finish(Request $request)
    {
         $data=  $request->item;
        foreach($data as $row):
          $item_data=explode(',',$row);
          $dataa['finish_good']=$item_data[0];
          DB::Connection('mysql2')->table('subitem')->where('id',$item_data[1])->update($dataa);
            endforeach;
    }

    public function add_bom()
    {
        try {
      $data=  DB::Connection('mysql2')->table('bom_data')->get();

        foreach ($data as $row):

       if ($row->finish_good_id!='Finish Good'):
      $finish_good=DB::Connection('mysql2')->table('subitem')->where('item_code',$row->finish_good_id)->first()->id;
       $data1=array
       (
            'finish_goods'=>$finish_good,
            'description'=>$row->finish_good_id,
           'date'=>date('Y-m-d'),
           'status'=>1,
           'username'=>Auth::user()->name,
       );

  //     $id= DB::Connection('mysql2')->table('production_bom')->insertGetId($data1);


            $finish_good=DB::Connection('mysql2')->table('subitem')->where('item_code',$row->direct_material)->first()->id;
            $data2=array
            (
                'master_id'=>$id,
                'item_id'=>$finish_good,
                'qty_mm'=>$row->d_qty,
                'qty_ft'=>$row->d_qty / 304.8,
                'date'=>date('Y-m-d'),
                'status'=>1,
                'username'=>Auth::user()->name,
            );

          //  DB::Connection('mysql2')->table('production_bom_data_direct_material')->insertGetId($data2);

            if ($row->indirect_material!=''):
            $finish_good= DB::Connection('mysql2')->table('subitem')->where('item_code',$row->indirect_material)->first()->id;
            $data3=array
            (
                'main_id'=>$id,
                'item_id'=>$finish_good,
                'qty'=>$row->in_qty,
                'status'=>1,
                'username'=>Auth::user()->name,
            );

         //   DB::Connection('mysql2')->table('production_bom_data_indirect_material')->insertGetId($data3);
                endif;
            endif;
        endforeach;
            DB::Connection('mysql2')->commit();
        }
        catch(\Exception $e)
        {
            DB::Connection('mysql2')->rollback();
            dd($e->getMessage());
        }
    }


    function add_operation_data()
    {
        try {

          $data=  DB::Connection('mysql2')->table('production_bom')->where('status',1)->get();

            foreach ($data as $row):


                $production_work_order=array
                (
                    'finish_good_id'=>$row->finish_goods,
                    'status'=>1,
                    'username'=>Auth::user()->name,
                    'date'=>date('Y-m-d'),
                );

                $id= DB::Connection('mysql2')->table('production_work_order')->insertGetId($production_work_order);

            $data1=  DB::Connection('mysql2')->table('production_machine_data')->where('status',1)->where('finish_good',$row->finish_goods)->get();


            foreach ($data1 as $row1):

                $production_work_order_data=array
                (
                    'master_id'=>$id,
                    'machine_id'=>$row1->master_id,
                    'capacity'=>70,
                    'labour_category_id'=>'',
                    'wait_time'=>'00:00:12',
                    'move_time'=>'00:00:06',
                    'que_time'=>0,
                    'status'=>1,
                    'date'=>date('Y-m-d'),
                    'username'=>Auth::user()->name,
                );
                 DB::Connection('mysql2')->table('production_work_order_data')->insert($production_work_order_data);
            endforeach;

            endforeach;


            DB::Connection('mysql2')->commit();
        }
        catch(\Exception $e)
        {
            DB::Connection('mysql2')->rollback();
            dd($e->getMessage());
        }
    }

    public function Create_routing()
    {
        $data = DB::Connection('mysql2')->table('production_work_order')->where('status',1)->get();



        foreach ($data as $row):

            $data1=array
            (
                'finish_goods'=>$row->finish_good_id,
                'voucher_no'=>ProductionHelper::get_unique_code_for_routing(),
                'operation_id'=>$row->id,
                'status'=>1,
                'username'=>Auth::user()->name,
                'date'=>date('Y-m-d'),

            );
            $id= DB::Connection('mysql2')->table('production_route')->insertGetId($data1);
            $data2 = DB::Connection('mysql2')->table('production_work_order_data')->where('status',1)->where('master_id',$row->id)->get();

          $count=1;
            foreach ($data2 as $row1):
                $orderby=0;
                if ($row1->machine_id==28 || $row1->machine_id==29):
                    $orderby=0;
                    else:
                    $orderby=$count++;
                    endif;

                $data2=array
                (
                    'master_id'=>$id,
                    'operation_data_id'=>$row1->id,
                    'machine_id'=>$row1->machine_id,
                    'orderby'=>$orderby,
                    'status'=>1,

                );

                DB::Connection('mysql2')->table('production_route_data')->insert($data2);
                endforeach;
            endforeach;

    }

}
