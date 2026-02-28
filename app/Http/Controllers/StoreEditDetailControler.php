<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use Input;
use Auth;
use DB;
use Config;
use Redirect;
use Session;
use App\Helpers\StoreHelper;
use App\Helpers\CommonHelper;
class StoreEditDetailControler extends Controller
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

    public function editStoreChallanVoucherDetail(){
        $m = $_GET['m'];
        CommonHelper::companyDatabaseConnection($_GET['m']);
        $storeChallanNo = Input::get('store_challan_no');
        $slip_no = Input::get('slip_no');
        $store_challan_date = Input::get('store_challan_date');
        $sub_department_id = Input::get('departmentId');
        $pageType = Input::get('pageType');
        $parentCode = Input::get('parentCode');
        $main_description = Input::get('description');
        DB::table('fara')->where('sc_no', $storeChallanNo)->delete();


        $data1['slip_no'] = $slip_no;
        $data1['store_challan_date'] = $store_challan_date;
        $data1['description'] = $main_description;
        $data1['date'] = date("Y-m-d");
        $data1['time'] = date("H:i:s");

        DB::table('store_challan')->where('store_challan_no','=',$storeChallanNo)->update($data1);
        $seletedStoreChallanRow = Input::get('storeChallanDataSection');
        foreach ($seletedStoreChallanRow as $row) {
            $recordId = Input::get('recordId_'.$row.'');
            $demandNo = Input::get('demandNo_'.$row.'');
            $demandDate = Input::get('demandDate_'.$row .'');
            $categoryId = Input::get('categoryId_'.$row.'');
            $subItemId = Input::get('subItemId_'.$row.'');
            $issue_qty = Input::get('issue_qty_'.$row.'');
            $demandAndRemainingQty = Input::get('demandAndRemainingQty_'.$row.'');

            $data2['store_challan_date'] = $store_challan_date;
            $data2['demand_date'] = $demandDate;
            $data2['issue_qty'] = $issue_qty;
            $data2['date'] = date("Y-m-d");
            $data2['time'] = date("H:i:s");

            DB::table('store_challan_data')->where('store_challan_no','=',$storeChallanNo)->where('id','=',$recordId)->update($data2);
            if ($issue_qty == $demandAndRemainingQty) {
                DB::table('demand_data')
                    ->where('category_id', $categoryId)
                    ->where('sub_item_id', $subItemId)
                    ->where('demand_no', $demandNo)
                    ->update(['store_challan_status' => "2"]);

            }

            $data3['sc_no'] = $storeChallanNo;
            $data3['sc_date'] = $store_challan_date;
            $data3['demand_no'] = $demandNo;
            $data3['demand_date'] = $demandDate;
            $data3['main_ic_id'] = $categoryId;
            $data3['sub_ic_id'] = $subItemId;
            $data3['qty'] = $issue_qty;
            $data3['value'] = 0;
            $data3['username'] = Auth::user()->name;
            $data3['date'] = date("Y-m-d");
            $data3['time'] = date("H:i:s");
            $data3['action'] = 2;
            $data3['status'] = 1;
            $data3['company_id'] = $m;
            DB::table('fara')->insert($data3);
        }
        CommonHelper::reconnectMasterDatabase();
        Session::flash('dataEdit','successfully Update.');
        return Redirect::to('store/viewStoreChallanList?pageType='.Input::get('pageType').'&&parentCode='.Input::get('parentCode').'&&m='.$_GET['m'].'#SFR');
    }

    // public function editPurchaseRequestVoucherDetail(){
    //     $m = $_GET['m'];
    //     CommonHelper::companyDatabaseConnection($_GET['m']);
    //     $purchaseRequestNo = Input::get('purchase_request_no');
    //     $slip_no = Input::get('slip_no');
    //     $purchase_request_date = Input::get('purchase_request_date');
    //     $sub_department_id = Input::get('departmentId');
    //     $pageType = Input::get('pageType');
    //     $parentCode = Input::get('parentCode');
    //     $main_description = Input::get('description');
    //     $supplier_id = Input::get('supplier_id');



    //     $data1['slip_no'] = $slip_no;
    //     $data1['purchase_request_date'] = $purchase_request_date;
    //     $data1['description'] = $main_description;
    //     $data1['supplier_id'] = $supplier_id;
    //     $data1['date'] = date("Y-m-d");
    //     $data1['time'] = date("H:i:s");

    //     DB::table('purchase_request')->where('purchase_request_no','=',$purchaseRequestNo)->update($data1);
    //     $seletedPurchaseRequestRow = Input::get('purchaseRequestDataSection');
    //     foreach ($seletedPurchaseRequestRow as $row) {
    //         $recordId = Input::get('recordId_'.$row.'');
    //         $demandNo = Input::get('demandNo_'.$row.'');
    //         $demandDate = Input::get('demandDate_'.$row .'');
    //         $categoryId = Input::get('categoryId_'.$row.'');
    //         $subItemId = Input::get('subItemId_'.$row.'');
    //         $purchase_request_qty = Input::get('purchase_request_qty_'.$row.'');
    //         $purchase_request_rate = Input::get('purchase_request_rate_'.$row.'');
    //         $purchase_request_sub_total = $purchase_request_qty*$purchase_request_rate;


    //         $data2['purchase_request_date'] = $purchase_request_date;
    //         $data2['demand_no'] = $demandNo;
    //         $data2['demand_date'] = $demandDate;
    //         $data2['purchase_request_qty'] = $purchase_request_qty;
    //         $data2['rate'] = $purchase_request_rate;
    //         $data2['sub_total'] = $purchase_request_sub_total;
    //         $data2['date'] = date("Y-m-d");
    //         $data2['time'] = date("H:i:s");

    //         DB::table('purchase_request_data')->where('purchase_request_no','=',$purchaseRequestNo)->where('id','=',$recordId)->update($data2);

    //     }
    //     CommonHelper::reconnectMasterDatabase();
    //     Session::flash('dataEdit','successfully Update.');
    //     return Redirect::to('store/viewPurchaseRequestList?pageType='.Input::get('pageType').'&&parentCode='.Input::get('parentCode').'&&m='.$_GET['m'].'#SFR');
    // }

    public function editPurchaseRequestVoucherDetail(){
        DB::Connection('mysql2')->beginTransaction();
        try {
        $m = $_GET['m'];
        CommonHelper::companyDatabaseConnection($_GET['m']);

        $po_date= Input::get('po_date');
        $po_type=Input::get('po_type');

        $year= substr($po_date,2,2);
        $month= substr($po_date,5,2);

        $purchase_request_date = Input::get('po_date');

        $slip_no = Input::get('slip_no');
        $term_of_del = Input::get('term_of_del');

        $terms_of_paym = Input::get('model_terms_of_payment');

        $supplier_id = Input::get('supplier_id');
        $due_date = Input::get('due_date');
        $supplier_id = explode('@#',$supplier_id);

        $currency_id = Input::get('curren');
        $currency_id=explode(',',$currency_id);
        $currency_id=$currency_id[0];
        $currency_rate = Input::get('curren_rate');


        $trn = Input::get('trn');
        $builty_no = Input::get('builty_no');
        $remarks = Input::get('remarks');
        $main_description = Input::get('main_description');
        $destination = Input::get('destination');
        $department = Input::get('department');


        $sales_tax = Input::get('sales_taxx');
        $sales_tax_amount = Input::get('sales_amount_td');
        $sales_tax_amount=str_replace(",","",$sales_tax_amount);
        $total_amount = Input::get('total_amount');
        $amount_in_words = Input::get('rupeess');

        $pageType = Input::get('pageType');
        $parentCode = Input::get('parentCode');

        $s_order_no = Input::get('s_order_no');
        $purchaseRequestNo = Input::get('po_no');


        $data1['purchase_request_no'] = $purchaseRequestNo;
        $data1['purchase_request_date'] = $purchase_request_date;

        $data1['slip_no'] = $slip_no;
        $data1['term_of_del'] = $term_of_del;
        $data1['po_type'] =  Input::get('po_type');
        $data1['terms_of_paym'] = $terms_of_paym;
        $data1['destination'] = $destination;
        $data1['department'] = $department;

        $data1['supplier_id'] = $supplier_id[0];
        $data1['due_date'] = $due_date;

        $data1['currency_id'] = $currency_id;
        $data1['currency_rate'] = Input::get('currency_rate');
        $data1['trn'] = $trn;
        $data1['builty_no'] = $builty_no;
        $data1['remarks'] = $remarks;
        $data1['description'] = $main_description;
        $data1['sales_tax'] = $sales_tax;
        $data1['sales_tax_amount'] 		 	= $sales_tax_amount;
        $data1['total_amount'] = $total_amount;
        $data1['amount_in_words'] = $amount_in_words;
        $data1['username'] = Auth::user()->name;
        $data1['date'] = date("Y-m-d");
        $data1['time'] = date("H:i:s");
        $data1['purchase_request_status'] = 1;
        $data1['s_order_no'] = $s_order_no;
        // $data1['p_type'] = Input::get('p_type_id');

        DB::table('purchase_request')->where('purchase_request_no','=',$purchaseRequestNo)->update($data1);

        // $master_id=DB::table('purchase_request')->insertGetId($data1);


        $seletedPurchaseRequestRow = Input::get('seletedPurchaseRequestRow');
        $TotAmount = 0;
        foreach ($seletedPurchaseRequestRow as $row) {
            $recordId = Input::get('recordId_'.$row.'');
            $demandNo = Input::get('demandNo_' . $row . '');
            $demandDate = Input::get('demandDate_' . $row . '');
            $subItemId = Input::get('subItemId_' . $row . '');
            $purchase_request_qty = Input::get('purchase_request_qty_' . $row . '');
            $purchase_approve_qty = Input::get('purchase_approve_qty_' . $row . '');
            $purchase_approve_qty=str_replace(",","",$purchase_approve_qty);
            $description = Input::get('description_' . $row . '');
            $purchase_request_rate = Input::get('rate_' . $row . '');
            $purchase_request_rate=str_replace(",","",$purchase_request_rate);
            $description = Input::get('description_' . $row . '');

            $discount_percent = Input::get('discount_percent_' . $row . '');
            $discount_percent=str_replace(",","",$discount_percent);

            $discount_amount = Input::get('discount_amount_' . $row . '');
            $discount_amount=str_replace(",","",$discount_amount);

            $net_amount = Input::get('after_dis_amountt_' . $row . '');
            $net_amount=str_replace(",","",$net_amount);


            $purchase_request_sub_total = $purchase_approve_qty*$purchase_request_rate;
            $demand_data_id=Input::get('demand_data_id' . $row . '');
            $data2['purchase_request_no'] = $purchaseRequestNo;
            $data2['purchase_request_date'] = $purchase_request_date;
            $data2['demand_no'] = $demandNo;
            $data2['demand_date'] = $demandDate;
            $data2['sub_item_id'] = $subItemId;
            $data2['purchase_request_qty'] = $purchase_request_qty;
            $data2['description'] = $description;
            $data2['purchase_approve_qty'] = $purchase_approve_qty;
            $data2['rate'] = $purchase_request_rate;
            $data2['description'] = $description;
            $data2['sub_total'] = $purchase_request_sub_total;
            $data2['demand_data_id'] = $demand_data_id;
            $data2['username'] = Auth::user()->name;
            $data2['date'] = date("Y-m-d");
            $data2['time'] = date("H:i:s");
            $data2['purchase_request_status'] = 1;
            $data2['discount_percent'] = $discount_percent;
            $data2['discount_amount'] = $discount_amount;
            $data2['net_amount'] = $net_amount;
            $TotAmount+=$net_amount;

            DB::table('purchase_request_data')->where('purchase_request_no','=',$purchaseRequestNo)->where('id','=',$recordId)->update($data2);

            // DB::table('purchase_request_data')->insert($data2);
            DB::table('demand_data')->where('id', $demand_data_id)->update(['demand_status' => "3"]);

            DB::table('quotation_data')->where('pr_data_id',$demand_data_id)
            ->where('quotation_status',1)->update(['quotation_status' =>'2']);

        }
        CommonHelper::reconnectMasterDatabase();
        DB::Connection('mysql2')->commit();
        Session::flash('dataEdit','successfully Update.');
        return Redirect::to('store/viewPurchaseRequestList?pageType='.Input::get('pageType').'&&parentCode='.Input::get('parentCode').'&&m='.$_GET['m'].'#SFR');
        }
        catch(\Exception $e)
        {
            DB::Connection('mysql2')->rollback();
            echo "EROOR"; //die();
            dd($e->getMessage());
        }
    }
}

