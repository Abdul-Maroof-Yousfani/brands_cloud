<?php

namespace App\Http\Controllers;

use App\Models\Transactions;
use App\Models\Warehouse;
use Illuminate\Database\DatabaseManager;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Helpers\CommonHelper;
use App\Helpers\FinanceHelper;
use App\Models\Sales_Order;
use App\Models\Sales_Order_Data;
use App\Models\CreditNote;
use App\Models\CreditNoteData;
use App\Models\Type;
use App\Models\Conditions;
use App\Models\SurveryBy;
use App\Models\Client;
use App\Models\Branch;
use App\Models\NewPv;
use App\Models\NewPvData;
use App\Models\ProductTrend;
use App\Models\Customer;
use App\Models\ProductClassification;
use App\Helpers\NotificationHelper;
use Illuminate\Support\Facades\Validator;

use App\Models\ProductType;
use App\Models\ResourceAssigned;
use App\Models\Quotation;
use App\Models\Quotation_Data;
use App\Models\Complaint;
use App\Models\ComplaintProduct;
use App\Models\InvDesc;
use App\Helpers\SalesHelper;
use App\Helpers\ReuseableCode;
use App\Models\Invoice_totals;


use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Config;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use App\Models\DeliveryNote;
use App\Models\DeliveryNoteData;
use App\Models\SalesTaxInvoice;
use App\Models\SalesTaxInvoiceData;
use App\Models\Invoice;
use App\Models\InvoiceData;
use App\Models\Survey;
use App\Models\ClientJob;
use App\Models\ComplaintDocument;
use App\Models\Stock;
use App\Models\Subitem;
use Maatwebsite\Excel\Facades\Excel;

class SalesAddDetailControler extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        date_default_timezone_set("Asia/Karachi");
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    public function import_customer_opening(Request $request)
    {
        $this->validate($request, [
            'select_file'  => 'required|mimes:xls,xlsx'
        ]);

        //$path = $request->file('select_file')->getRealPath();

        $data = Excel::toArray([], $request->file('select_file'));
        //print_r($data->toArray());
        //die;

        if (count($data) > 0) {
            foreach ($data as $key => $value) {
                foreach ($value as $row) {
                    $checkCustomerAccount = DB::Connection('mysql2')->table('customers')->where(DB::raw('lower(name)'), strtolower($row[1]))->first();
                    if (!empty($checkCustomerAccount)) {
                        if ($checkCustomerAccount->acc_id != 0) {
                            $checkOpeningBalance = DB::Connection('mysql2')->table('transactions')->where('opening_bal', '=', 1)->where('acc_id', '=', $checkCustomerAccount->acc_id)->first();
                            if ($row[5] < 0) {
                                $debit_credit = 0;
                            } else {
                                $debit_credit = 1;
                            }
                            if (empty($checkOpeningBalance)) {
                                $getAccountDetail = DB::Connection('mysql2')->table('accounts')->where('id', '=', $checkCustomerAccount->acc_id)->first();
                                $accCode = $getAccountDetail->code;
                            } else {
                                if ($checkOpeningBalance->acc_code == '') {
                                    $getAccountDetail = DB::Connection('mysql2')->table('accounts')->where('id', '=', $checkCustomerAccount->acc_id)->first();
                                    $accCode = $getAccountDetail->code;
                                } else {
                                    $accCode = $checkOpeningBalance->acc_code;
                                }
                            }
                            $insertData = array(
                                'acc_id'   => $checkCustomerAccount->acc_id,
                                'acc_code' => $accCode,
                                'opening_bal'   => 1,
                                'amount'   => trim($row[5], '-'),
                                'debit_credit' => $debit_credit,
                                'v_date'  => date("Y-m-d"),
                                'date'   => date("Y-m-d"),
                                'time'   => date("H:i:s"),
                                'action'   => 'create',
                                'username'   => Auth::user()->name . ' - Upload',
                                'status'   => 1,

                            );
                            if (!empty($checkOpeningBalance)) {
                                DB::Connection('mysql2')->table('transactions')->where('opening_bal', '=', 1)->where('acc_id', '=', $checkCustomerAccount->acc_id)->update($insertData);
                            } else {
                                DB::Connection('mysql2')->table('transactions')->insert($insertData);
                            }
                        }
                    } else {
                        $notInsertData[] = array(
                            'customer_name' => $row[1],
                            'amount' => $row[5],
                        );
                    }
                }
            }
        }
        return back()->with('success', 'Excel Data Imported successfully.')->with('submit_errors', $notInsertData);
    }

    public function addSaleReceiptVoucherDetailAgainstSQ(Request $request)
    {
        $receiptType = $request->receiptType;
        $rvStr = DB::Connection('mysql2')->selectOne("select max(convert(substr(`rv_no`,4,length(substr(`rv_no`,4))-4),signed integer)) rvReg from `new_rvs` where substr(`rv_no`,-4,2) = " . date('m') . " and substr(`rv_no`,-2,2) = " . date('y') . "")->rvReg;
        if ($receiptType == 1) {
            $rv_no = 'BRV' . ($rvStr + 1) . date('my');
        } else {
            $rv_no = 'CRV' . ($rvStr + 1) . date('my');
        }
        $pageType = $request->pageType;
        $parentCode = $request->parentCode;
        $m = $request->m;
        $soNo = $request->soNo;
        $soDate = $request->soDate;
        $soId = $request->soId;
        $customerId = $request->customerId;
        $customerAccId = $request->customerAccId;
        $slip_no = $request->slip_no;
        $rv_date = $request->rv_date;
        $credit_amount = $request->credit_amount;
        $cheque_no = $request->cheque_no;
        $cheque_date = $request->cheque_date;
        $main_description = $request->main_description;
        $seletedSaleOrderRow = $request->seletedSaleOrderRow;


        $totalCreditAmount = 0;

        $data1['rv_no']           = $rv_no;
        $data1['rv_date']       = $rv_date;
        $data1['so_id'] = $soId;
        if ($receiptType == 1) {
            $data1['cheque_no']     = $cheque_no;
            $data1['cheque_date']   = $cheque_date;
        }
        $data1['rv_type']     = $receiptType;
        $data1['description']   = $main_description;
        $data1['rv_status']      = 1;
        $data1['date']             = date('Y-m-d');
        $data1['status']             = 1;
        $data1['username']         = Auth::user()->name;
        $data1['sales']     = 1;
        $data1['pay_mode']     = 2;
        $data1['buyer_id']     = $customerId;
        $data1['buyer_acc_id']     = $customerAccId;

        $master_id = DB::Connection('mysql2')->table('new_rvs')->insertGetId($data1);

        foreach ($seletedSaleOrderRow as $row) {
            $account_id = Input::get('account_id_' . $row . '');
            $debit = Input::get('debit_' . $row . '');
            $totalCreditAmount += $debit;

            $data2['master_id'] = $master_id;
            $data2['rv_no'] = $rv_no;
            $data2['acc_id'] = $account_id;
            $data2['paid_to_id'] = $customerId;
            $data2['paid_to_type'] = 3;
            $data2['debit_credit'] = 1;
            $data2['amount'] = $debit;
            $data2['description'] = $main_description;
            $data2['status']             = 1;
            $data2['rv_status'] = 1;

            DB::Connection('mysql2')->table('new_rv_data')->insert($data2);
        }

        $data3['master_id'] = $master_id;
        $data3['rv_no'] = $rv_no;
        $data3['acc_id'] = $customerAccId;
        $data3['paid_to_id'] = $customerId;
        $data3['paid_to_type'] = 3;
        $data3['debit_credit'] = 0;
        $data3['amount'] = $totalCreditAmount;
        $data3['description'] = $main_description;
        $data3['status']             = 1;
        $data3['rv_status'] = 1;

        DB::Connection('mysql2')->table('new_rv_data')->insert($data3);

        if ($credit_amount == $totalCreditAmount) {
            DB::Connection('mysql2')->table('sales_order')->where('so_no', $soNo)->update(['amount_received_status' => 2]);
        } else {
            DB::Connection('mysql2')->table('sales_order')->where('so_no', $soNo)->update(['advanced_received_amount' => 2]);
        }
        return Redirect::to('sales/addReceiptVoucherAgainstSOForm?pageType=' . $pageType . '&&parentCode=' . $parentCode . '&&m=' . $m . '#SFR');
    }

    public function addCashCustomerDetail()
    {
        CommonHelper::companyDatabaseConnection($_GET['m']);

        $account_head = Input::get('account_head');
        $customer_name = Input::get('customer_name');
        $country = Input::get('country');
        $state = Input::get('state');
        $city = Input::get('city');
        $contact_no = Input::get('contact_no');
        $email = Input::get('email');
        $o_blnc_trans = Input::get('o_blnc_trans');
        $o_blnc = Input::get('o_blnc');
        $ntn = Input::get('ntn');
        $strn = Input::get('strn');
        $address = Input::get('address');
        $operational = '1';
        $customer_type = '2';

        $sent_code = $account_head;

        $max_id = DB::selectOne('SELECT max(`id`) as id  FROM `accounts` WHERE `parent_code` LIKE \'' . $account_head . '\'')->id;
        if ($max_id == '') {
            $code = $sent_code . '-1';
        } else {
            $max_code2 = DB::selectOne('SELECT `code`  FROM `accounts` WHERE `id` LIKE \'' . $max_id . '\'')->code;
            $max_code2;
            $max = explode('-', $max_code2);
            $code = $sent_code . '-' . (end($max) + 1);
        }

        $level_array = explode('-', $code);
        $counter = 1;
        foreach ($level_array as $level):
            $data1['level' . $counter] = $level;
            $counter++;
        endforeach;
        $data1['code'] = $code;
        $data1['name'] = $customer_name;
        $data1['parent_code'] = $account_head;
        $data1['username']              = Auth::user()->name;
        $data1['date']               = date("Y-m-d");
        $data1['time']               = date("H:i:s");
        $data1['action']               = 'create';
        $data1['operational']        = $operational;


        $acc_id = DB::table('accounts')->insertGetId($data1);


        $data2['acc_id']             = $acc_id;
        $data2['name']                = $customer_name;
        $data2['country']             = $country;
        $data2['province']            = $state;
        $data2['city']                = $city;
        $data2['contact']               = $contact_no;
        $data2['email']                 = $email;
        $data2['cnic_ntn']                 = $ntn;
        $data2['strn']                 = $strn;

        $data2['address']                 = $address;
        $data2['username']            = Auth::user()->name;
        $data2['date']                = date("Y-m-d");
        $data2['time']                = date("H:i:s");
        $data2['action']              = 'create';
        $data2['customer_type']     = $customer_type;

        DB::table('customers')->insert($data2);

        $data3['acc_id'] =    $acc_id;
        $data3['acc_code'] =    $code;
        $data3['debit_credit'] =    $o_blnc_trans;
        $data3['amount']       =     $o_blnc;
        $data3['opening_bal']       =     1;
        $data3['username']              = Auth::user()->name;
        $data3['date']               = date("Y-m-d");
        $data3['v_date']             = date("Y-m-d");
        $data3['time']               = date("H:i:s");
        $data3['action']               = 'create';
        DB::table('transactions')->insert($data3);

        CommonHelper::reconnectMasterDatabase();
        return Redirect::to('sales/viewCashCustomerList?pageType=' . Input::get('pageType') . '&&parentCode=' . Input::get('parentCode') . '&&m=' . $_GET['m'] . '#SFR');
    }


    public function addCreditCustomerDetail(Request $request)
    {
        $rules = [
            'customer_name' => 'required|unique:mysql2.customers,name',
            'customer_code' => 'required',
            'email' => 'nullable|email',
            'phone_1' => 'required',
            'country' => 'required',
            'state' => 'required',
            'city' => 'required',
            'address' => 'required',
            'tax_filer' => 'required',
            'ntn' => 'required',
            'strn' => 'required',
            'display_note_invoice' => 'required',
            'credit_days' => 'required',
            'credit_amount' => 'required',
            'locality' => 'required',
            // 'account_title' => 'required',
            // 'bank' => 'required',
            // 'branch_code' => 'required',
            'customer_type' => 'required',
            'ba_mapping' => 'required_if:customer_type,3',
            'warehouse_to' => 'required_if:customer_type,3',
        ];
    
        $validator = Validator::make($request->all(), $rules, [
            'ba_mapping.required_if' => 'BA Mapping is required for Reseller/Distributor.',
            'warehouse_to.required_if' => 'Warehouse To is required for Reseller/Distributor.',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        CommonHelper::companyDatabaseConnection($_GET['m']);

        $account_head = Input::get('account_head');
        $customer_code = SalesHelper::generateCustomerCode();
        $customer_name = Input::get('customer_name');
        $country = Input::get('country');
        $state = Input::get('state');
        $city = Input::get('city');

        $contact_person = Input::get('contact_person');
        $contact_person_email = Input::get('contact_person_email');
        $phone_1 = Input::get('phone_1');
        $phone_2 = Input::get('phone_2');
        $fax = Input::get('fax');
        $address = Input::get('address');
        $email = Input::get('email');
        $o_blnc_trans = Input::get('o_blnc_trans');
        $o_blnc = Input::get('o_blnc');
        $operational = '1';
        $customer_type = Input::get('customer_type');
        $ntn = Input::get('ntn');
        $strn = Input::get('strn');

        $account_head = 'Trade Payable';
        $sent_code = '1-2-2'; //'Trade Receivables';
        // $sent_code = $account_head;

        $max_id = DB::Connection('mysql2')->selectOne('SELECT max(`id`) as id  FROM `accounts` WHERE `parent_code` LIKE \'' . $sent_code . '\'')->id;

        if ($max_id == '') {
            $code = $sent_code . '-1';
        } else {
            $max_code2 = DB::Connection('mysql2')->selectOne('SELECT `code`  FROM `accounts` WHERE `id` LIKE \'' . $max_id . '\'')->code;
            $max_code2;
            $max = explode('-', $max_code2);
            $code = $sent_code . '-' . (end($max) + 1);
        }

        $level_array = explode('-', $code);
        $counter = 1;
        foreach ($level_array as $level):
            $data1['level' . $counter] = strip_tags($level);
            $counter++;
        endforeach;

        $data1['code'] = strip_tags($code);
        $data1['name'] = $customer_name;
        $data1['parent_code'] = strip_tags($sent_code);
        $data1['username'] = Auth::user()->name;
        $data1['date'] = date("Y-m-d");
        $data1['time'] = date("H:i:s");
        $data1['action'] = 'create';
        $data1['type'] = 1;
        $data1['operational'] = 1;
        $acc_id = DB::Connection('mysql2')->table('accounts')->insertGetId($data1);

        $data2['acc_id']             = $acc_id;
        $data2['name']                = $customer_name;
        $data2['store_code']   = $customer_code;
        $data2['customer_code']   = $request->customer_code;
        $data2['email']                 = $email ?? '';
        $data2['phone_1']               = $phone_1 ?? 0;
        $data2['phone_2']               = $phone_2 ?? NULL;
        $data2['address']                 = $address ?? '';
        $data2['country']             = $country ?? 0;
        $data2['province']            = $state ?? 0;
        $data2['city']                = $city ?? 0;
        $data2['zip']                 = $request->zip ?? '';
        $data2['title']                 = $request->title ?? '';
        $data2['contact_person']               = $contact_person ?? '';
        $data2['contact_person_email']               = $contact_person_email ?? '';
        $data2['company_shipping_type']               = $request->company_shipping_type ?? '';
        $data2['shipping_city']               = $request->shipping_city ?? NULL;
        $data2['shipping_state']               = $request->shipping_state ?? NULL;
        $data2['shipping_country']               = $request->shipping_country ?? NULL;
        $data2['opening_balance']               = $request->opening_balance ?? '';
        $data2['opening_balance_date']               = $request->opening_balance_date ?? '';
        $data2['regd_in_income_tax']               = $request->tax_filer ?? '';
        $data2['cnic_ntn']                 = $ntn ?? '';
        $data2['strn']                 = $strn ?? '';
        $data2['strn_term']                 = $request->strn_term ?? '';
        $data2['display_note_invoice']                 = $request->display_note_invoice ?? 'no';
        $data2['wh_tax']                 = $request->wh_tax ?? '';
        $data2['adv_tax']                 = $request->adv_tax ?? 0;
        $data2['creditDaysLimit']     = Input::get('credit_days') ?? 0;
        $data2['creditLimit']     = Input::get('credit_amount') ?? 0;
        $data2['locality']     = Input::get('locality') ?? 0;
        $data2['store_category']     = Input::get('store_category') ?? 0;
        $data2['territory_id']     = Input::get('territory_id') ?? 0;
        $data2['SaleRep']     = Input::get('sale_person') ?? 0;
        $data2['customer_group_id']     = Input::get('customer_group_id') ?? NULL;
        $data2['accept_cheque']     = Input::get('accept_cheque') ?? "no";
        $data2['display_pending_payment_invoice']     = Input::get('display_pending_payment_invoice') ?? 0;
        

        $data2['CustomerType']     = Input::get('customer_type') ?? 1;
        // $data2['status']     = Input::get('status') ?? "Active";
        $data2['status']     = Input::get("status") ?? 1;
        $data2['ba_mapping']     = Input::get('ba_mapping') ?? "no";
        $data2['employee_id']     = Input::get('employee_id') ?? NULL;
        $data2['special_price_mapped']     = Input::get('special_price_mapped') ?? "no";
        $data2['warehouse_from']     = implode(",", Input::get('warehouse_from') ?? 0);
        $data2['warehouse_to']     = Input::get('warehouse_to') ?? NULL;
        // $data2['fax']   		    = $fax ?? '';

        $data2['username']            = Auth::user()->name;
        $data2['date']                = date("Y-m-d");
        $data2['time']                = date("H:i:s");
        $data2['action']              = 'create';
        $data2['terms_of_payment']     = Input::get('term') ?? 0;
        $data2["region_id"] = Input::get("region_id") ?? 0;
        // $data2['discount_percent']     = Input::get('discount_percent') ?? 0;

        $CustId = DB::table('customers')->insertGetId($data2);

        $data3['acc_id'] =    $acc_id;
        $data3['acc_code'] =    $code;
        $data3['debit_credit'] = 1;
        $data3['amount']       = 0.00;
        $data3['opening_bal']       =     1;
        $data3['username']              = Auth::user()->name;
        $data3['date']               = date("Y-m-d");
        $data3['v_date']             = '2023-07-01';
        $data3['time']               = date("H:i:s");
        $data3['action']               = 'create';
        DB::table('transactions')->insert($data3);

        $type = "Customer " . $customer_name;
        \App\Helpers\CommonHelper::createNotification(
            $type . " is created by " . auth()->user()->name, 
            "Customer"
        );

        if (Input::get('accept_cheque') == "yes") {
            // bank fields
            $data4['bank_name']     = Input::get('bank') ?? "-";
            $data4['username']              = Auth::user()->name;
            $data4['status']              = 1;
            $data4['date']               = date("Y-m-d");
            $data4['acc_id'] =    $acc_id;
            $data4['account_title']     = Input::get('account_title') ?? '-';
            $data4['account_no']     = Input::get('account_no') ?? '-';
            $data4['swift_code']     = Input::get('branch_code') ?? '-';
            $data4['bank_address']     = "Karachi Pakistan";
            DB::table('bank_detail')->insert($data4);
        }



        // $contact_person_more = Input::get('contact_person_more');
        // $contact_no_more  = Input::get('contact_no_more');
        // $fax_more  = Input::get('fax_more');
        // $address_more  = Input::get('address_more');
        // if(isset($contact_person_more)):
        // foreach($contact_person_more as $key => $row)
        // {
        //     if($contact_person_more[$key] != "" || $contact_no_more[$key] !="" || $fax_more[$key] !="" || $address_more[$key] !="")
        //     {
        //         $InfoData['cust_id'] = $CustId;
        //         $InfoData['contact_person'] = $contact_person_more[$key] ?? '';
        //         $InfoData['contact_no'] = $contact_no_more[$key] ?? 0;
        //         $InfoData['fax'] = $fax_more[$key] ?? '';
        //         $InfoData['address'] = $address_more[$key] ?? '';
        //         DB::Connection('mysql2')->table('customer_info')->insert($InfoData);
        //     }
        // }
        //     endif;
        CommonHelper::reconnectMasterDatabase();
        return Redirect::to('sales/viewCreditCustomerList?pageType=' . Input::get('pageType') . '&&parentCode=' . Input::get('parentCode') . '&&m=' . $_GET['m'] . '#SFR');
    }

    // public function updateCreditCustomerDetail(){

    //     CommonHelper::companyDatabaseConnection($_GET['m']);

    //     //$account_head = Input::get('account_head');
    //     $EditId = Input::get('EditId');
    //     $AccId = Input::get('AccId');
    //     $customer_code = Input::get('customer_code');
    //     $customer_name = Input::get('customer_name');
    //     $country = Input::get('country');
    //     $state = Input::get('state');
    //     $city = Input::get('city');

    //     $contact_person = Input::get('contact_person');
    //     $contact_no = Input::get('contact_no');
    //     $fax = Input::get('fax');
    //     $address = Input::get('address');
    //     $email = Input::get('email');
    //     //$o_blnc_trans = Input::get('o_blnc_trans');
    //     //$o_blnc = Input::get('o_blnc');
    //     //$operational = '1';
    //     $customer_type = '3';
    //     $ntn = Input::get('ntn');
    //     $strn = Input::get('strn');

    //     //$sent_code = $account_head;

    //     $data2['name']     		   = $customer_name;
    //     //$data2['customer_code']    = $customer_code;
    //     $data2['country']     		= $country ?? 0;
    //     $data2['province']     	   = $state ?? 0;
    //     $data2['city']     	       = $city ?? 0;
    //     $data2['cnic_ntn']   		      = $ntn ?? '';
    //     $data2['strn']   		      = $strn ?? '';
    //     $data2['contact_person']   		    = $contact_person ?? 0;
    //     $data2['contact']   		    = $contact_no ?? 0;
    //     $data2['fax']   		    = $fax ?? '';
    //     $data2['address']   		      = $address ?? '';

    //     $data2['email']   		      = $email ?? '';
    //     $data2['username']	 	   = Auth::user()->name;
    //     $data2['date']     		   = date("Y-m-d");
    //     $data2['time']     		   = date("H:i:s");
    //     $data2['status']     		   = 1;
    //     $data2['action']     		 = 'update';
    //     $data2['customer_type']     = $customer_type;
    //     $data2['terms_of_payment']     = Input::get('term') ?? 0;
    //     $data2['credit_days']     = Input::get('credit_days') ?? 0;
    //     // $data2['discount_percent']     = Input::get('discount_percent') ?? 0;
    //     $data2['category_id']     = json_encode(Input::get('category'));
    //     $data2['discount']     = json_encode(Input::get('discount_percent'));
    //     DB::table('customers')->where('id',$EditId)->update($data2);

    //     $AccUpdate['name']     		   = $customer_name;
    //     $AccUpdate['type'] = 1;
    //     $AccUpdate['username']	 	   = Auth::user()->name;
    //     $AccUpdate['action'] = 'update';
    //     $AccUpdate['status'] = 1;
    //     DB::table('accounts')->where('id',$AccId)->update($AccUpdate);


    //     DB::table('customer_info')->where('cust_id',$EditId)->delete();

    //     $contact_person_more = Input::get('contact_person_more');
    //     $contact_no_more  = Input::get('contact_no_more');
    //     $fax_more  = Input::get('fax_more');
    //     $address_more  = Input::get('address_more');
    //     if(isset($contact_person_more)):
    //         foreach($contact_person_more as $key => $row)
    //         {
    //             if($contact_person_more[$key] != "" || $contact_no_more[$key] !="" || $fax_more[$key] !="" || $address_more[$key] !="")
    //             {
    //                 $InfoData['cust_id'] = $EditId;
    //                 $InfoData['contact_person'] = $contact_person_more[$key];
    //                 $InfoData['contact_no'] = $contact_no_more[$key];
    //                 $InfoData['fax'] = $fax_more[$key];
    //                 $InfoData['address'] = $address_more[$key];
    //                 DB::Connection('mysql2')->table('customer_info')->insert($InfoData);
    //             }
    //         }
    //     endif;
    //     CommonHelper::reconnectMasterDatabase();
    //     return Redirect::to('sales/viewCreditCustomerList?pageType='.Input::get('pageType').'&&parentCode='.Input::get('parentCode').'&&m='.$_GET['m'].'#SFR');
    // }

    public function updateCreditCustomerDetail(Request $request)
    {
      
        $validator = Validator::make($request->all(), [
            'customer_name' => 'required',
            'customer_code' => 'required',
            'email' => 'nullable|email',
            'phone_1' => 'required',
            'country' => 'required',
            'state' => 'required',
            'city' => 'required',
            'address' => 'required',
            'tax_filer' => 'required',
            'ntn' => 'required',
            'strn' => 'required',
            'display_note_invoice' => 'required',
            'credit_days' => 'required',
            'credit_amount' => 'required',
            'locality' => 'required',
            // 'account_title' => 'required',
            // 'bank' => 'required',
            // 'branch_code' => 'required',
            'customer_type' => 'required',
            'ba_mapping' => 'required_if:customer_type,3',
            'warehouse_to' => 'required_if:customer_type,3',
        ], [
            'ba_mapping.required_if' => 'BA Mapping is required for Reseller/Distributor.',
            'warehouse_to.required_if' => 'Warehouse To is required for Reseller/Distributor.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with(["error" => implode('<br>', $validator->errors()->all()), "status" => 404]);
            // return response()->json(['errors' => $validator->errors()], 422);
        }

        try {

            CommonHelper::companyDatabaseConnection($_GET['m']);

            $customerId = $request->EditId;

            $customer = DB::table('customers')->where('id', $customerId)->first();

            if (!$customer) {
                return response()->json(['success' => false, 'message' => 'Customer not found.'], 404);
            }

            // Update 'accounts' table
            $accountHead = 'Trade Payable';
            $sentCode = '1-2-2';

            $data1 = [
                'name' => $request->input('customer_name', $customer->name),
                'username' => Auth::user()->name,
                'date' => date("Y-m-d"),
                'time' => date("H:i:s"),
                'action' => 'update',
                'type' => 1,
                'operational' => 1,
            ];

            DB::connection('mysql2')->table('accounts')->where('id', $customer->acc_id)->update($data1);

            // Update 'customers' table
            $data2 = [
                'name' => $request->input('customer_name', $customer->name),
                'email' => $request->input('email', $customer->email),
                'phone_1' => $request->input('phone_1', $customer->phone_1),
                'phone_2' => $request->input('phone_2', $customer->phone_2),
                'address' => $request->input('address', $customer->address),
                'country' => $request->input('country', $customer->country),
                'region_id' => $request->input("region_id"),
                'province' => $request->input('state', $customer->province),
                'city' => $request->input('city', $customer->city),
                'zip' => $request->input('zip', $customer->zip),
                'title' => $request->input('title', $customer->title),
                'contact_person' => $request->input('contact_person', $customer->contact_person),
                'contact_person_email' => $request->input('contact_person_email', $customer->contact_person_email),
                'company_shipping_type' => $request->input('company_shipping_type', $customer->company_shipping_type),
                'shipping_city' => $request->input('shipping_city', $customer->shipping_city),
                'shipping_state' => $request->input('shipping_state', $customer->shipping_state),
                'shipping_country' => $request->input('shipping_country', $customer->shipping_country),
                'opening_balance' => $request->input('opening_balance', $customer->opening_balance),
                'opening_balance_date' => $request->input('opening_balance_date', $customer->opening_balance_date),
                'regd_in_income_tax' => $request->input('tax_filer', $customer->regd_in_income_tax),
                'cnic_ntn' => $request->input('ntn', $customer->cnic_ntn),
                'strn' => $request->input('strn', $customer->strn),
                'strn_term' => $request->input('strn_term', $customer->strn_term),
                'display_note_invoice' => $request->input('display_note_invoice', $customer->display_note_invoice),
                'wh_tax' => $request->input('wh_tax', $customer->wh_tax),
                'adv_tax' => $request->input('adv_tax', $customer->adv_tax),
                'creditDaysLimit' => $request->input('credit_days', $customer->creditDaysLimit),
                'creditLimit' => $request->input('credit_amount', $customer->creditLimit),
                'locality' => $request->input('locality', $customer->locality),
                'store_category' => $request->input('store_category', $customer->store_category),
                'territory_id' => $request->input('territory_id', $customer->territory_id),
                'SaleRep' => $request->input('sale_person', $customer->SaleRep),
                'customer_group_id' => $request->input('customer_group_id', $customer->customer_group_id),
                'accept_cheque' => $request->input('accept_cheque', $customer->accept_cheque),
                'display_pending_payment_invoice' => $request->input('display_pending_payment_invoice', $customer->display_pending_payment_invoice),
                'CustomerType' => $request->input('customer_type', $customer->CustomerType),
                // 'status' => $request->input('status', $customer->status),
                'ba_mapping' => $request->input('ba_mapping', $customer->ba_mapping),
                'employee_id' => $request->input('employee_id', $customer->employee_id),
                'special_price_mapped' => $request->input('special_price_mapped', $customer->special_price_mapped),
                // 'warehouse_from' => $request->input('warehouse_from', $customer->warehouse_from),
                'warehouse_from' => is_array($request->warehouse_from)
    ? implode(',', $request->warehouse_from)
    : ($request->warehouse_from ?? $customer->warehouse_from),

                
                'warehouse_to' => $request->input('warehouse_to', $customer->warehouse_to),
                'username' => Auth::user()->name,
                'date' => date("Y-m-d"),
                'time' => date("H:i:s"),
                'action' => 'update',
                'terms_of_payment' => $request->input('term', $customer->terms_of_payment),
                'status' => Input::get("status") ?? 1
            ];

            DB::table('customers')->where('id', $customerId)->update($data2);

            // Update 'transactions' table
            $data3 = [
                'debit_credit' => 1,
                'amount' => 0.00,
                'opening_bal' => 1,
                'username' => Auth::user()->name,
                'date' => date("Y-m-d"),
                'v_date' => '2023-07-01',
                'time' => date("H:i:s"),
                'action' => 'update',
            ];

            DB::table('transactions')->where('acc_id', $customer->acc_id)->update($data3);
            $bankDetail =  DB::Connection('mysql2')->table('bank_detail')->where('acc_id', $customer->acc_id)->first();

            if ($request->input('accept_cheque') == "yes") {
                $searchCriteria = [
                    'acc_id' => $customer->acc_id,
                ];

                $dataToUpdateOrCreate = [
                    'bank_name' => $request->input('bank', optional($bankDetail)->bank_name) ?? "-",
                    'account_title' => $request->input('account_title', optional($bankDetail)->account_title) ?? "-",
                    'account_no' => $request->input('account_no', optional($bankDetail)->account_no) ?? "-",
                    'swift_code' => $request->input('branch_code', optional($bankDetail)->swift_code) ?? "-",
                    'bank_address' => "Karachi Pakistan",
                    'username' => Auth::user()->name,
                    'status' => 1,
                    'date' => date("Y-m-d"),
                ];

                // Update if exists, otherwise create a new record
                $bankDetail = DB::connection('mysql2')->table('bank_detail')->updateOrInsert($searchCriteria, $dataToUpdateOrCreate);
            }

               $type = "Customer " . $customer->name;
                \App\Helpers\CommonHelper::createNotification(
                    $type . " is edited by " . auth()->user()->name,
                    "Customer"
                );
            // Reconnect to the master database
            CommonHelper::reconnectMasterDatabase();

            return Redirect::to('sales/viewCreditCustomerList?pageType=' . $request->input('pageType') . '&&parentCode=' . $request->input('parentCode') . '&&m=' . $_GET['m'] . '#SFR');
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function addCreditSaleVoucherDetail()
    {
        $m = $_GET['m'];
        CommonHelper::companyDatabaseConnection($_GET['m']);
        $creditSaleSection = Input::get('creditSaleSection');
        foreach ($creditSaleSection as $row) {
            $invoice_date = strip_tags(Input::get('invoice_date_' . $row . ''));
            $dc_no = strip_tags(Input::get('dc_no_' . $row . ''));
            $vehicle_no = strip_tags(Input::get('vehicle_no_' . $row . ''));
            $customer_name_id = strip_tags(Input::get('customer_name_id_' . $row . ''));
            $credit_acc_id = strip_tags(Input::get('credit_acc_id_' . $row . ''));
            $invoice_against_discount = strip_tags(Input::get('invoice_against_discount_' . $row . ''));
            $main_description = strip_tags(Input::get('main_description_' . $row . ''));
            $creditSaleDataSection = Input::get('creditSaleDataSection_' . $row . '');

            $str = DB::selectOne("select max(convert(substr(`inv_no`,4,length(substr(`inv_no`,4))-4),signed integer)) reg from `invoice` where substr(`inv_no`,-4,2) = " . date('m') . " and substr(`inv_no`,-2,2) = " . date('y') . "")->reg;
            $inv_no = 'cre' . ($str + 1) . date('my');

            $data1['inv_no']         = $inv_no;
            $data1['dc_no']           = $dc_no;
            $data1['vehicle_no']       = $vehicle_no;
            $data1['invoiceType']       = '3';
            $data1['inv_against_discount'] = $invoice_against_discount;
            $data1['inv_date']         = $invoice_date;
            $data1['consignee']           = $customer_name_id;
            $data1['main_description']       = $main_description;
            $data1['credit_acc_id']       = $credit_acc_id;
            $data1['date']                   = date("Y-m-d");
            $data1['time']                   = date("H:i:s");
            $data1['username']          = Auth::user()->name;
            $data1['status']            = 1;
            $data1['inv_status']     = 1;

            DB::table('invoice')->insert($data1);
            foreach ($creditSaleDataSection as $row2) {
                $category_id = strip_tags(Input::get('category_id_' . $row . '_' . $row2 . ''));
                $sub_item_id = strip_tags(Input::get('sub_item_id_' . $row . '_' . $row2 . ''));
                $description = strip_tags(Input::get('description_' . $row . '_' . $row2 . ''));
                $price = strip_tags(Input::get('price_' . $row . '_' . $row2 . ''));
                $qty = strip_tags(Input::get('qty_' . $row . '_' . $row2 . ''));
                $amount = strip_tags(Input::get('amount_' . $row . '_' . $row2 . ''));

                $data2['inv_no']         = $inv_no;
                $data2['inv_date']       = $invoice_date;
                $data2['category_id']       = $category_id;
                $data2['sub_item_id']       = $sub_item_id;
                $data2['description']       = $description;
                $data2['price']               = $price;
                $data2['qty']               = $qty;
                $data2['amount']               = $amount;
                $data2['date']                   = date("Y-m-d");
                $data2['time']                   = date("H:i:s");
                $data2['username']          = Auth::user()->name;
                $data2['status']            = 1;
                $data2['inv_status']     = 1;

                DB::table('inv_data')->insert($data2);
            }
        }
        CommonHelper::reconnectMasterDatabase();
        return Redirect::to('sales/viewCreditSaleVouchersList?pageType=' . Input::get('pageType') . '&&parentCode=' . Input::get('parentCode') . '&&m=' . $_GET['m'] . '#SFR');
    }

    public function addCashSaleVoucherDetail()
    {
        $m = $_GET['m'];
        CommonHelper::companyDatabaseConnection($_GET['m']);
        $cashSaleSection = Input::get('cashSaleSection');
        foreach ($cashSaleSection as $row) {
            $invoice_date = strip_tags(Input::get('invoice_date_' . $row . ''));
            $dc_no = strip_tags(Input::get('dc_no_' . $row . ''));
            $vehicle_no = strip_tags(Input::get('vehicle_no_' . $row . ''));
            $customer_name_id = strip_tags(Input::get('customer_name_id_' . $row . ''));
            $credit_acc_id = strip_tags(Input::get('credit_acc_id_' . $row . ''));
            $debit_acc_id = strip_tags(Input::get('debit_acc_id_' . $row . ''));
            $invoice_against_discount = strip_tags(Input::get('invoice_against_discount_' . $row . ''));
            $main_description = strip_tags(Input::get('main_description_' . $row . ''));
            $cashSaleDataSection = Input::get('cashSaleDataSection_' . $row . '');
            $totalAmount = 0;

            $str_inv = DB::selectOne("select max(convert(substr(`inv_no`,4,length(substr(`inv_no`,4))-4),signed integer)) reg from `invoice` where substr(`inv_no`,-4,2) = " . date('m') . " and substr(`inv_no`,-2,2) = " . date('y') . "")->reg;
            $inv_no = 'cas' . ($str_inv + 1) . date('my');

            $data1['inv_no']         = $inv_no;
            $data1['dc_no']           = $dc_no;
            $data1['vehicle_no']       = $vehicle_no;
            $data1['invoiceType']       = '2';
            $data1['inv_against_discount'] = $invoice_against_discount;
            $data1['inv_date']         = $invoice_date;
            $data1['consignee']           = $customer_name_id;
            $data1['main_description']       = $main_description;
            $data1['credit_acc_id']       = $credit_acc_id;
            $data1['debit_acc_id']       = $debit_acc_id;
            $data1['date']                   = date("Y-m-d");
            $data1['time']                   = date("H:i:s");
            $data1['username']          = Auth::user()->name;
            $data1['approve_username']          = Auth::user()->name;
            $data1['status']            = 1;
            $data1['inv_status']     = 2;

            $lastId = DB::table('invoice')->insertGetId($data1);
            foreach ($cashSaleDataSection as $row2) {
                $category_id = strip_tags(Input::get('category_id_' . $row . '_' . $row2 . ''));
                $sub_item_id = strip_tags(Input::get('sub_item_id_' . $row . '_' . $row2 . ''));
                $description = strip_tags(Input::get('description_' . $row . '_' . $row2 . ''));
                $price = strip_tags(Input::get('price_' . $row . '_' . $row2 . ''));
                $qty = strip_tags(Input::get('qty_' . $row . '_' . $row2 . ''));
                $amount = strip_tags(Input::get('amount_' . $row . '_' . $row2 . ''));
                $totalAmount += $amount;

                $data2['inv_no']         = $inv_no;
                $data2['inv_date']       = $invoice_date;
                $data2['category_id']       = $category_id;
                $data2['sub_item_id']       = $sub_item_id;
                $data2['description']       = $description;
                $data2['price']               = $price;
                $data2['qty']               = $qty;
                $data2['amount']               = $amount;
                $data2['date']                   = date("Y-m-d");
                $data2['time']                   = date("H:i:s");
                $data2['username']          = Auth::user()->name;
                $data2['approve_username']          = Auth::user()->name;
                $data2['status']            = 1;
                $data2['inv_status']     = 2;

                DB::table('inv_data')->insert($data2);

                $data3['inv_no']         = $inv_no;
                $data3['inv_date']       = $invoice_date;
                $data3['main_ic_id']       = $category_id;
                $data3['sub_ic_id']       = $sub_item_id;
                $data3['customer_id'] = $customer_name_id;
                $data3['inv_against_discount'] = $invoice_against_discount;
                $data3['qty']               = $qty;
                $data3['price']               = $price;
                $data3['value']               = $amount;
                $data3['action']               = '5';
                $data3['date']                   = date("Y-m-d");
                $data3['time']                   = date("H:i:s");
                $data3['username']          = Auth::user()->name;
                $data3['status']            = 1;
                $data3['company_id']     = $m;

                DB::table('fara')->insert($data3);
            }

            $calculatedTotalDiscount = $totalAmount * $invoice_against_discount / 100;

            $str_jv = DB::selectOne("select max(convert(substr(`jv_no`,3,length(substr(`jv_no`,3))-4),signed integer)) reg from `jvs` where substr(`jv_no`,-4,2) = " . date('m') . " and substr(`jv_no`,-2,2) = " . date('y') . "")->reg;
            $jv_no = 'jv' . ($str_jv + 1) . date('my');

            $data_jvs['jv_no']      = $jv_no;
            $data_jvs['jv_date']   = $invoice_date;
            $data_jvs['inv_no']      = $inv_no;
            $data_jvs['inv_date']   = $invoice_date;
            $data_jvs['slip_no'] = $dc_no;
            $data_jvs['description']      = '(' . $main_description . ') * ( Invoice No  => ' . $inv_no . ' ) * ( Invoice Date  => ' . $invoice_date . ' ) * ( Slip No => ' . $dc_no . ' )';
            $data_jvs['jv_status'] = 2;
            $data_jvs['voucherType']       = 3;
            $data_jvs['status']       = 1;
            $data_jvs['username']  = Auth::user()->name;
            $data_jvs['date']       = date('Y-m-d');
            $data_jvs['time']       = date('H:i:s');
            $data_jvs['approve_username']   = Auth::user()->name;

            DB::table('jvs')->insert($data_jvs);

            CommonHelper::reconnectMasterDatabase();
            $congsinee_acc = CommonHelper::getAccountIdByMasterTable($m, $customer_name_id, 'customers');
            CommonHelper::companyDatabaseConnection($_GET['m']);
            $data_jvdebit['acc_id']         = $congsinee_acc;
            $data_jvdebit['amount']         = $totalAmount - $calculatedTotalDiscount;
            $data_jvdebit['debit_credit']  = '1';
            $data_jvdebit['jv_no']          = $jv_no;
            $data_jvdebit['jv_date']          = $invoice_date;
            $data_jvdebit['description'] = '(' . $main_description . ') * ( Invoice No  => ' . $inv_no . ' ) * ( Invoice Date  => ' . $invoice_date . ' ) * ( Slip No => ' . $dc_no . ' )';
            $data_jvdebit['username']  = Auth::user()->name;
            $data_jvdebit['date']       = date('Y-m-d');
            $data_jvdebit['time']       = date('H:i:s');
            $data_jvdebit['approve_username']   = Auth::user()->name;
            $data_jvdebit['jv_status'] = 2;


            $data_jvcredit['acc_id']        = $credit_acc_id;
            $data_jvcredit['amount']         = $totalAmount - $calculatedTotalDiscount;
            $data_jvcredit['debit_credit']  = '0';
            $data_jvcredit['jv_no']          = $jv_no;
            $data_jvcredit['jv_date']          = $invoice_date;
            $data_jvcredit['description'] = '(' . $main_description . ') * ( Invoice No  => ' . $inv_no . ' ) * ( Invoice Date  => ' . $invoice_date . ' ) * ( Slip No => ' . $dc_no . ' )';
            $data_jvcredit['username']  = Auth::user()->name;
            $data_jvcredit['date']       = date('Y-m-d');
            $data_jvcredit['time']       = date('H:i:s');
            $data_jvcredit['approve_username']   = Auth::user()->name;
            $data_jvcredit['jv_status'] = 2;

            DB::table('jv_data')->insert($data_jvdebit);
            DB::table('jv_data')->insert($data_jvcredit);

            $jvsDataDetail = DB::table('jv_data')
                ->where('jv_no', $jv_no)
                ->where('jv_status', '2')->get();
            foreach ($jvsDataDetail as $jvRow) {
                $jvdata['acc_id'] = $jvRow->acc_id;
                CommonHelper::reconnectMasterDatabase();
                $jvdata['acc_code'] = FinanceHelper::getAccountCodeByAccId($jvRow->acc_id, $m);
                CommonHelper::companyDatabaseConnection($_GET['m']);
                $jvdata['particulars'] = $jvRow->description;
                $jvdata['opening_bal'] = '0';
                $jvdata['debit_credit'] = $jvRow->debit_credit;
                $jvdata['amount'] = $jvRow->amount;
                $jvdata['voucher_no'] = $jvRow->jv_no;
                $jvdata['voucher_type'] = 1;
                $jvdata['v_date'] = $jvRow->jv_date;
                $jvdata['date'] = date("Y-m-d");
                $jvdata['time'] = date("H:i:s");
                $jvdata['username'] = Auth::user()->name;

                DB::table('transactions')->insert($jvdata);
            }


            $str_rv = DB::selectOne("select max(convert(substr(`rv_no`,4,length(substr(`rv_no`,4))-4),signed integer)) reg from `rvs` where substr(`rv_no`,-4,2) = " . date('m') . " and substr(`rv_no`,-2,2) = " . date('y') . "")->reg;
            $rv_no = 'crv' . ($str_rv + 1) . date('my');

            $data_rvs['rv_no']      = $rv_no;
            $data_rvs['rv_date']   = $invoice_date;
            $data_rvs['inv_no']      = $inv_no;
            $data_rvs['inv_date']   = $invoice_date;
            $data_rvs['slip_no'] = $dc_no;
            $data_rvs['description']      = '(' . $main_description . ') * ( Invoice No  => ' . $inv_no . ' ) * ( Invoice Date  => ' . $invoice_date . ' ) * ( Slip No => ' . $dc_no . ' )';
            $data_rvs['rv_status'] = 2;
            $data_rvs['voucherType']       = 3;
            $data_rvs['sale_receipt_type']       = 1;
            $data_rvs['status']       = 1;
            $data_rvs['username']  = Auth::user()->name;
            $data_rvs['date']       = date('Y-m-d');
            $data_rvs['time']       = date('H:i:s');
            $data_rvs['approve_username']   = Auth::user()->name;

            DB::table('rvs')->insert($data_rvs);

            $data_rvdebit['acc_id']         = $congsinee_acc;
            $data_rvdebit['amount']         = $totalAmount - $calculatedTotalDiscount;
            $data_rvdebit['debit_credit']  = '0';
            $data_rvdebit['rv_no']          = $rv_no;
            $data_rvdebit['description'] = '(' . $main_description . ') * ( Invoice No  => ' . $inv_no . ' ) * ( Invoice Date  => ' . $invoice_date . ' ) * ( Slip No => ' . $dc_no . ' )';
            $data_rvdebit['rv_date']      = $invoice_date;
            $data_rvdebit['rv_status'] = 2;
            $data_rvdebit['status']       = 1;
            $data_rvdebit['username']  = Auth::user()->name;
            $data_rvdebit['date']       = date('Y-m-d');
            $data_rvdebit['time']       = date('H:i:s');
            $data_rvdebit['approve_username']   = Auth::user()->name;

            DB::table('rv_data')->insert($data_rvdebit);

            $data_rvcredit['acc_id']        = $debit_acc_id;
            $data_rvcredit['amount']        = $totalAmount - $calculatedTotalDiscount;
            $data_rvcredit['debit_credit'] = '1';
            $data_rvcredit['rv_no']          = $rv_no;
            $data_rvcredit['rv_date']      = $invoice_date;
            $data_rvcredit['description'] = '(' . $main_description . ') * ( Invoice No  => ' . $inv_no . ' ) * ( Invoice Date  => ' . $invoice_date . ' ) * ( Slip No => ' . $dc_no . ' )';
            $data_rvcredit['rv_status'] = 2;
            $data_rvcredit['status']       = 1;
            $data_rvcredit['username']  = Auth::user()->name;
            $data_rvcredit['date']       = date('Y-m-d');
            $data_rvcredit['time']       = date('H:i:s');
            $data_rvcredit['approve_username']   = Auth::user()->name;

            DB::table('rv_data')->insert($data_rvcredit);

            $rvsDataDetail = DB::table('rv_data')
                ->where('rv_no', $rv_no)
                ->where('rv_status', '2')->get();
            foreach ($rvsDataDetail as $rvRow) {
                $rvdata['acc_id'] = $rvRow->acc_id;
                CommonHelper::reconnectMasterDatabase();
                $rvdata['acc_code'] = FinanceHelper::getAccountCodeByAccId($rvRow->acc_id, $m);
                CommonHelper::companyDatabaseConnection($_GET['m']);
                $rvdata['particulars'] = $rvRow->description;
                $rvdata['opening_bal'] = '0';
                $rvdata['debit_credit'] = $rvRow->debit_credit;
                $rvdata['amount'] = $rvRow->amount;
                $rvdata['voucher_no'] = $rvRow->rv_no;
                $rvdata['voucher_type'] = 3;
                $rvdata['v_date'] = $rvRow->rv_date;
                $rvdata['date'] = date("Y-m-d");
                $rvdata['time'] = date("H:i:s");
                $rvdata['username'] = Auth::user()->name;

                DB::table('transactions')->insert($rvdata);
            }
        }
        CommonHelper::reconnectMasterDatabase();
        return Redirect::to('sales/viewCashSaleVouchersList?pageType=' . Input::get('pageType') . '&&parentCode=' . Input::get('parentCode') . '&&m=' . $_GET['m'] . '#SFR');
    }


    function createSalesOrder(Request $request)
    {


        DB::Connection('mysql2')->beginTransaction();
        try {

            $byers_id = $request->buyers_id;
            $byers_id = explode('*', $byers_id);
            $byers_id = $byers_id[0];

            $sales_order = new Sales_Order();
            $sales_order = $sales_order->SetConnection('mysql2');


            $master_id = $request->sales_order_id;
            if ($master_id != ''):

                $sales_order = $sales_order->find($master_id);
                $so_no = $sales_order->so_no;
            else:
                $so_no = SalesHelper::get_unique_no(date('y'), date('m'));
                $sales_order->so_no = $so_no;
            endif;


            // $sales_order->buyers_unit=$request->buyers_unit;
            $sales_order->so_date = $request->so_date;
            $sales_order->model_terms_of_payment = $request->model_terms_of_payment;
            $sales_order->order_no = $request->order_no ?? '';
            $sales_order->order_date = $request->order_date;
            $sales_order->other_refrence = $request->other_refrence ?? '';
            $sales_order->desptch_through = $request->desptch_through ?? '';
            $sales_order->destination = $request->destination;;
            $sales_order->terms_of_delivery = $request->terms_of_delivery ?? '';
            $sales_order->due_date = $request->due_date ?? '';
            $sales_order->status = 1;
            $sales_order->username = Auth::user()->name;
            $sales_order->amount_in_words = $request->rupeess;;
            $sales_order->date = date('Y-m-d');
            $sales_order->buyers_id = $byers_id;
            $sales_order->description = $request->description;
            $sales_order->department = $request->department;
            $sales_order->commission = $request->commission;
            $sales_order->p_type = $request->v_type ?? 0;
            $sales_order->verified = $request->verified;
            $sales_tax = CommonHelper::check_str_replace($request->sales_tax);
            $sales_tax_further = CommonHelper::check_str_replace($request->sales_tax_further);


            $sales_order->sales_tax = $sales_tax;
            $sales_order->sales_tax_further = $sales_tax_further;
            $sales_order->total_amount = $request->total_after_sales_tax;
            $sales_order->save();
            if ($master_id == ''):
                $master_id = $sales_order->id;
            endif;
            $data = $request->sub_ic_des;



            $count = 1;
            $total_amount = 0;
            foreach ($data as $key => $row):
                $item = $request->input('sub_ic_des')[$key];
                $item = explode(',', $item);
                $item = $item[0];
                $sales_order = new Sales_Order_Data();
                $sales_order = $sales_order->SetConnection('mysql2');
                $sales_order->master_id = $master_id;
                $sales_order->so_no = $so_no;
                $sales_order->desc = $item;
                $sales_order->item_id = $item;
                $sales_order->qty = $request->input('actual_qty')[$key];
                $sales_order->no_of_carton = $request->input('no_of_carton')[$key];
                $sales_order->rate = $request->input('rate')[$key];
                $sales_order->amount = CommonHelper::check_str_replace($request->input('amount')[$key]);
                $sales_order->sub_total = $request->input('amount')[$key];
                $percent = $request->input('tax')[$key];
                $percent = explode(',', $percent);
                $percent = $percent[1];
                $sales_order->tax = $percent;
                $sales_order->tax_amount = CommonHelper::check_str_replace(str_replace(',', '', $request->input('tax_amount')[$key]));
                $sales_order->amount = CommonHelper::check_str_replace($request->input('after_dis_amount')[$key]);
                $total_amount += CommonHelper::check_str_replace($request->input('after_dis_amount')[$key]);
                $sales_order->status = 1;
                $sales_order->date = date('Y-m-d');
                $sales_order->username = Auth::user()->name;
                $sales_order->groupby = $count;
                $sales_order->save();
                $count++;
            endforeach;
            $Loop = Input::get('account_id');

            //Abdul Code
            if ($Loop != "") {
                $Counta = 0;
                foreach ($Loop as $LoopFil) {
                    $ExpData['voucher_no'] = $so_no;
                    $ExpData['main_id'] = $master_id;
                    $ExpData['acc_id'] = Input::get('account_id')[$Counta];
                    $ExpData['amount'] = Input::get('expense_amount')[$Counta];
                    $total_amount += Input::get('expense_amount')[$Counta];
                    $ExpData['created_date'] = date('Y-m-d');
                    $ExpData['username'] = Auth::user()->name;
                    $Counta++;
                    DB::Connection('mysql2')->table('addional_expense_sales_order')->insert($ExpData);
                }
            }
            //Abdul Code
            SalesHelper::sales_activity($so_no, $request->so_date, $total_amount + $sales_tax, 1, 'Insert');



            $voucher_no = $so_no;
            $subject = 'Sales Order Created ' . $so_no;
            //  NotificationHelper::send_email('Sales Order','Create',$request->department,$voucher_no,$subject,$request->v_type);
            DB::Connection('mysql2')->commit();
        } catch (Exception $ex) {


            DB::rollBack();
        }
        CommonHelper::reconnectMasterDatabase();
        return Redirect::to('sales/viewSalesOrderList?pageType=' . Input::get('pageType') . '&&parentCode=' . Input::get('parentCode') . '&&m=' . $_GET['m'] . '#Murtaza');
    }



    function updateSalesOrder(Request $request)
    {
        $EditId = $request->EditId;
        CommonHelper::companyDatabaseConnection($_GET['m']);
        DB::Connection('mysql2')->beginTransaction();
        try {

            $byers_id = $request->buyers_id;
            $byers_id = explode('*', $byers_id);
            $byers_id = $byers_id[0];

            //            $sales_order=new Sales_Order();
            //            $sales_order=$sales_order->SetConnection('mysql2');
            $sales_order['so_no'] = $request->so_no;
            $sales_order['so_date'] = $request->so_date;
            $sales_order['model_terms_of_payment'] = $request->model_terms_of_payment;
            $sales_order['order_no'] = $request->order_no;
            $sales_order['order_date'] = $request->order_date;
            $sales_order['other_refrence'] = $request->other_refrence;
            $sales_order['desptch_through'] = $request->desptch_through;
            $sales_order['destination'] = $request->destination;
            $sales_order['terms_of_delivery'] = $request->terms_of_delivery;;
            $sales_order['due_date'] = $request->due_date;
            $sales_order['status'] = 1;
            $sales_order['username'] = Auth::user()->name;
            $sales_order['amount_in_words'] = $request->rupeess;
            $sales_order['date'] = $request->sale_order_date;
            $sales_order['buyers_id'] = $byers_id;
            $sales_order['description'] = $request->description;

            $sales_tax = CommonHelper::check_str_replace($request->sales_tax);
            $sales_tax_further = CommonHelper::check_str_replace($request->sales_tax_further);

            $sales_order['sales_tax'] = $sales_tax;
            $sales_order['sales_tax_further'] = $sales_tax_further;

            DB::table('sales_order')->where('id', '=', $EditId)->update($sales_order);
            DB::table('sales_order_data')->where('master_id', $EditId)->delete();
            //$sales_order->save();
            //$id=$sales_order->id;
            $count = $request->count;
            for ($i = 1; $i <= $count; $i++):
                $sales_order_data = new Sales_Order_Data();
                $sales_order_data = $sales_order_data->SetConnection('mysql2');
                $sales_order_data->master_id = $EditId;
                $sales_order_data->so_no = $request->so_no;
                $sales_order_data->item_id = $request->input('item_id_' . $i);
                $sales_order_data->batch_id = $request->input('batch_id_' . $i);
                $sales_order_data->description = $request->input('description_' . $i);
                $qty = CommonHelper::check_str_replace($request->input('qty_' . $i));
                $per_pcs_item = CommonHelper::check_str_replace($request->input('per_pcs_item_' . $i));
                $rate = CommonHelper::check_str_replace($request->input('rate_' . $i));
                $amount = CommonHelper::check_str_replace($request->input('amount_' . $i));
                $sales_order_data->qty = $qty;
                $sales_order_data->per_pcs_item = $per_pcs_item;
                $sales_order_data->rate = $rate;
                $sales_order_data->discount = $request->input('discount_percent_' . $i);
                $sales_order_data->discount_amount = $request->input('discount_amount_' . $i);
                $sales_order_data->amount = $amount;
                $sales_order_data->status = 1;
                $sales_order_data->date = $request->sale_order_date;
                $sales_order_data->username = Auth::user()->name;
                $sales_order_data->save();
            endfor;

            DB::Connection('mysql2')->commit();
        } catch (Exception $ex) {


            DB::rollBack();
        }
        return Redirect::to('sales/viewSalesOrderList?pageType=&&parentCode=&&m=' . $_GET['m'] . '#SFR');
    }

     function addeDeliveryNote(Request $request)
    {
        //    ReuseableCode::get_stock(17295,1,700,0);die;
        
      

        DB::Connection('mysql2')->beginTransaction();
        try {

            $request->master_id;

            $byers_id = $request->buyers_id;
            $byers_id = explode('*', $byers_id);
            $byers_id = $byers_id[0];

            $delivery_note = new DeliveryNote();
            $delivery_note = $delivery_note->SetConnection('mysql2');
            $delivery_note->master_id = $request->master_id;
            $gd_no = SalesHelper::get_unique_no_delivery_note(date('y'), date('m'));
            $delivery_note->gd_no = $gd_no;
            $delivery_note->gd_date = $request->gd_date;
            $delivery_note->model_terms_of_payment = $request->model_terms_of_payment;
            $delivery_note->so_no = $request->so_no;
            $delivery_note->so_date = $request->so_date;
            $delivery_note->other_refrence = $request->other_refrence;
            $delivery_note->order_no = $request->order_no ?? '';;
            $delivery_note->order_date = $request->order_date;;
            $delivery_note->despacth_document_no = $request->despacth_document_no;
            $delivery_note->despacth_document_date = $request->despacth_document_date;
            $delivery_note->despacth_through = $request->despacth_through ?? '';
            $delivery_note->destination = $request->destination ?? '';
            $delivery_note->terms_of_delivery = $request->terms_of_delivery ?? '';
            $delivery_note->buyers_id = $request->buyers_id;
            $delivery_note->due_date = $request->due_date;
            $delivery_note->sales_tax_amount = CommonHelper::check_str_replace($request->sales_tax_apply);
            $SalesTaxAmount = CommonHelper::check_str_replace($request->sales_tax_apply);
            $delivery_note->description = $request->description;
            $delivery_note->status = 0;
            $delivery_note->virtual_warehouse_check = $request->virtual_warehouse_check;
            $delivery_note->date = date('Y-m-d');
            $delivery_note->username = Auth::user()->name;
            $delivery_note->save();
            $id = $delivery_note->id;


            $count = $request->count;

            $actual_qty = 0;
            $total_send_qty = 0;
            $total_amount = 0;
            $countOfGroupBy = 1;
            $error_messages = [];
            for ($i = 1; $i <= $count; $i++):
                $delivery_note_data = new DeliveryNoteData();


                $delivery_note_data = $delivery_note_data->SetConnection('mysql2');
                $delivery_note_data->master_id = $id;
                $delivery_note_data->so_id = $request->master_id;
                $delivery_note_data->desc = $request->input('desc' . $i);
                $delivery_note_data->so_data_id = $request->input('data_id' . $i);
                $delivery_note_data->gd_no = $gd_no;
                $delivery_note_data->gd_date = $request->input('gd_date');
                $delivery_note_data->item_id = $request->input('item_id' . $i);

                $batch_code = $request->input('batch_code' . $i);
                if ($batch_code == ''):
                    $batch_code = 0;
                endif;

                $delivery_note_data->batch_code = $batch_code;


                $qty = CommonHelper::check_str_replace($request->input('qty' . $i));
                $actual_qty += $qty;
                $send_qty = CommonHelper::check_str_replace($request->input('send_qty' . $i));
                $total_send_qty += $send_qty;


                $rate = CommonHelper::check_str_replace($request->input('send_rate' . $i));
                $amount = CommonHelper::check_str_replace($request->input('send_amount' . $i));

                $delivery_note_data->qty = $send_qty;
                $delivery_note_data->foc = $request->foc_qty;

                $delivery_note_data->rate = $rate;
                $delivery_note_data->mrp_price = $request->input('mrp_price' . $i);
                $delivery_note_data->tax = $request->input('send_discount' . $i);
                $delivery_note_data->tax_amount = $request->input('send_discount_amount' . $i);
                $delivery_note_data->amount = $amount;
                $total_amount += $amount;

                $delivery_note_data->warehouse_id = $request->input('warehouse' . $i);
                $delivery_note_data->warehouse_to_id = $request->input('warehouse_to' . $i);
                $delivery_note_data->groupby = $countOfGroupBy; //$request->input('groupby' . $i);
                $delivery_note_data->bundles_id = $request->input('bundles_id' . $i);
                $delivery_note_data->status = 1;
                $delivery_note_data->date = date('Y-m-d');
                $delivery_note_data->username = Auth::user()->name;
                $delivery_note_data->save();
                $master_data_id = $delivery_note_data->id;
                $type =  CommonHelper::get_item_type($request->input('item_id' . $i));

                $average_cost = ReuseableCode::average_cost_sales($request->input('item_id' . $i), $request->input('warehouse' . $i), $request->input('batch_code' . $i));

                // edit by maroof just for checking


                // echo $request->input('item_id' . $i);
                // echo "<br>";
                // echo $request->input('warehouse' . $i);
                // exit;
              
                $item_name = CommonHelper::get_product_name($request->input('item_id' . $i));
                $item_qty = $request->input('qty' . $i);
                 $qty = ReuseableCode::get_stock_new($request->input('item_id' . $i), $request->input('warehouse' . $i));
                 $qty = (float) $qty;
                // $qty = number_format($qty, 2);
                
               


                if ($qty < (float)$send_qty):
                    
                    $qty =  $qty < 0 ? 0 : $qty;
                    $delivery_note_dataa = new DeliveryNoteData();

                    $delivery_note_dataa = $delivery_note_data->SetConnection('mysql2');
                    $delivery_note_dataa = $delivery_note_data->where('item_id', $request->input('item_id' . $i))
                        ->where('master_id', $id)
                        ->get();
                    //                $total_qty=0;
                    $total_amount = 0;



                    $total_qty = 0;

                    $error_messages[] = "Insufficient stock for {$item_name}. Requested quantity: {$item_qty}, but only {$qty} available.";

                //




                //                foreach ($delivery_note_dataa as $row):
                //                    echo $row->item_id.' =>'.CommonHelper::get_item_name($request->input('item_id' . $i))
                //                        .'=>'.$row->qty.'</br>';
                //                    $total_qty+=$row->qty;
                //                    endforeach;



                //  echo  'Stock Not Available For =>'.CommonHelper::get_item_name($request->input('item_id' . $i)).' =>'.$total_qty.' =>'.number_format($qty,2).' =>item_id';

                endif;

                // $stock=array
                // (
                //     'main_id'=>$id,   //  delivery note id
                //     'master_id'=>$master_data_id, // delievry note data id
                //     'voucher_no'=>$gd_no,      //DN gd_no
                //     'voucher_date'=>$request->gd_date,  // DN
                //     'supplier_id'=>0,
                //     'customer_id'=>$request->buyers_id,   //DN
                //     'voucher_type'=>5,
                //     'rate'=>$rate,    //DNA
                //     'sub_item_id'=>$request->input('item_id' . $i), // DNA
                //     'batch_code'=>$batch_code,  //DNA
                //     'qty'=>$send_qty,     //DNA
                //     'discount_percent'=> $request->input('send_discount' . $i),    //DNA
                //     'discount_amount'=> 0 , // $request->input('send_discount_amount' . $i),    //DNA
                //     'amount'=>$send_qty*$average_cost,  //DNA
                //     'status'=>1,
                //     'warehouse_id'=>$request->input('warehouse' . $i),   //DNA
                //     'username'=>Auth::user()->username,
                //     'created_date'=>date('Y-m-d'),
                //     'created_date'=>date('Y-m-d'),
                //     'opening'=>0,
                //     'so_data_id'=>$request->input('data_id' . $i)   //DNA
                // );
                $amount = $send_qty * $average_cost;
                $total_amount = 0;
                //    DB::Connection('mysql2')->table('stock')->insert($stock);

                $countOfGroupBy++;
            endfor;

            if (!empty($error_messages)) {
                DB::rollBack();
                $error_message = implode('<br>', $error_messages);
                return redirect()->back()->with('stockerror', $error_message);
            }

            if ($total_send_qty == $actual_qty):


                $sale_order = new Sales_Order();
                $sale_order = $sale_order->SetConnection('mysql2');
                $sale_order = $sale_order->find($request->master_id);
                $sale_order->delivery_note_status = 1;
                $sale_order->save();
            endif;



            // Control account DN
            //    $this->dn_insert($id,$gd_no);


            SalesHelper::sales_activity($gd_no, $request->gd_date, $total_amount + $SalesTaxAmount, 2, 'Insert');

            $voucher_no = $gd_no;
            // $dept_and_type = NotificationHelper::get_dept_id('sales_order', 'id', $request->master_id)->select('department', 'p_type')->first();
            // $dept_id = $dept_and_type->department;
            // $p_type = $dept_and_type->p_type;
            // $subject = 'Delivery Note For ' . $request->so_no;
            // NotificationHelper::send_email('Delivery Note', 'Create', $dept_id, $voucher_no, $subject, $p_type);

            DB::Connection('mysql2')->commit();
        } catch (Exception $ex) {


            DB::rollBack();
        }

        //   echo $actual_qty.' '.$total_send_qty;

        return Redirect::to('sales/viewDeliveryNoteList?pageType=' . Input::get('pageType') . '&&parentCode=' . Input::get('parentCode') . '&&m=' . $_GET['m'] . '#SFR');
    }

    public function approveDeliveryNote(Request $request)
    {

        try {
            $delivery_note = DeliveryNote::find($request->m);
            $getSo = Sales_Order::find($delivery_note->master_id);
            $getDeliveryNoteDetail = DeliveryNoteData::where('master_id', $delivery_note->id)->get();
            foreach ($getDeliveryNoteDetail as $key => $value) {
                $average_cost = ReuseableCode::average_cost_sales($value->item_id, $value->warehouse, $value->batch_code);

                $stock = array(
                    'main_id' => $delivery_note->id,   //  delivery note id
                    'master_id' => $value->master_id, // delievry note data id
                    'voucher_no' => $delivery_note->gd_no,      //DN gd_no
                    'voucher_date' => $delivery_note->gd_date,  // DN
                    'supplier_id' => 0,
                    'customer_id' => $delivery_note->buyers_id,   //DN
                    'voucher_type' => 5,
                    'rate' => $value->rate,    //DNA
                    'sub_item_id' => $value->item_id, // DNA
                    'batch_code' => $value->batch_code ?? "",  //DNA
                    'qty' => $value->qty,     //DNA
                    'discount_percent' => 0,    //DNA
                    'discount_amount' => 0, // $request->input('send_discount_amount' . $i),    //DNA
                    'amount' => $value->qty * $average_cost,  //DNA
                    'status' => 1,
                    'warehouse_id' => $value->warehouse_id,   //DNA
                    'username' => Auth::user()->username,
                    'created_date' => date('Y-m-d'),
                    'opening' => 0,
                    'so_data_id' => $value->so_data_id   //DNA
                );
                DB::Connection('mysql2')->table('stock')->insert($stock);

                $customer = DB::connection("mysql2")->table("customers")
                                        ->select("id", "CustomerType")
                                        ->where("id", $delivery_note->buyers_id)
                                        ->first();
             
                // Customer is distributes/reseller
                if($customer->CustomerType == 3 && isset($value->warehouse_to_id)) {
                    $virtualWarehouseStock = array(
                        'main_id' => $delivery_note->id,   //  delivery note id
                        'master_id' => $value->master_id, // delievry note data id
                        'voucher_no' => $delivery_note->gd_no,      //DN gd_no
                        'voucher_date' => $delivery_note->gd_date,  // DN
                        'supplier_id' => 0,
                        'customer_id' => $delivery_note->buyers_id,   //DN
                        'voucher_type' => 9,
                        'rate' => $value->rate,    //DNA
                        'sub_item_id' => $value->item_id, // DNA
                        'batch_code' => $value->batch_code ?? "",  //DNA
                        'qty' => $value->qty,     //DNA
                        'discount_percent' => 0,    //DNA
                        'discount_amount' => 0, // $request->input('send_discount_amount' . $i),    //DNA
                        'amount' => $value->qty * $average_cost,  //DNA
                        'status' => 1,
                        'warehouse_id' => $value->warehouse_to_id,   //DNA
                        'warehouse_id_to' => $value->warehouse_to_id,   //DNA
                        'username' => Auth::user()->username,
                        'created_date' => date('Y-m-d'),
                        'opening' => 0,
                        'so_data_id' => $value->so_data_id   //DNA
                    );
                    DB::Connection('mysql2')->table('ba_stock')->insert($virtualWarehouseStock);
                }


                if (isset($value->warehouse_to_id)) {
                    $virtualWarehouseStock = array(
                        'main_id' => $delivery_note->id,   //  delivery note id
                        'master_id' => $value->master_id, // delievry note data id
                        'voucher_no' => $delivery_note->gd_no,      //DN gd_no
                        'voucher_date' => $delivery_note->gd_date,  // DN
                        'supplier_id' => 0,
                        'customer_id' => $delivery_note->buyers_id,   //DN
                        'voucher_type' => 1,
                        'rate' => $value->rate,    //DNA
                        'sub_item_id' => $value->item_id, // DNA
                        'batch_code' => $value->batch_code ?? "",  //DNA
                        'qty' => $value->qty,     //DNA
                        'discount_percent' => 0,    //DNA
                        'discount_amount' => 0, // $request->input('send_discount_amount' . $i),    //DNA
                        'amount' => $value->qty * $average_cost,  //DNA
                        'status' => 1,
                        'warehouse_id' => $value->warehouse_to_id,   //DNA
                        'warehouse_id_to' => $value->warehouse_to_id,   //DNA
                        'username' => Auth::user()->username,
                        'created_date' => date('Y-m-d'),
                        'created_date' => date('Y-m-d'),
                        'opening' => 0,
                        'so_data_id' => $value->so_data_id   //DNA
                    );
                    DB::Connection('mysql2')->table('stock')->insert($virtualWarehouseStock);
                }
            }
            $delivery_note->status = 1;
            $delivery_note->save();

            $getSo->dn_approve = 1;
            $getSo->save();
            DB::Connection('mysql2')->commit();
        } catch (Exception $ex) {
            DB::rollBack();
        }
        return Redirect::to('sales/viewDeliveryNoteList?pageType=' . Input::get('pageType') . '&&parentCode=' . Input::get('parentCode') . '&&m=' . $_GET['m'] . '#SFR');
    }

    function addeDeliveryChallan(Request $request)
    {
        //    echo "<pre>";
        //    print_r($request->all());
        //    exit();
        //    ReuseableCode::get_stock(17295,1,700,0);die;

        DB::Connection('mysql2')->beginTransaction();
        try {

            $request->master_id;

            $byers_id = $request->buyers_id;
            $byers_id = explode('*', $byers_id);
            $byers_id = $byers_id[0];

            $delivery_note = new DeliveryNote();
            $delivery_note = $delivery_note->SetConnection('mysql2');
            $delivery_note->master_id = $request->master_id;
            $gd_no = $request->gd_no; // SalesHelper::get_unique_no_delivery_note(date('y'),date('m'));
            $delivery_note->gd_no = $gd_no;
            $delivery_note->lot_no = $request->lot_no;
            $delivery_note->gd_date = $request->gd_date;
            $delivery_note->model_terms_of_payment = $request->model_terms_of_payment;
            $delivery_note->so_no = $request->so_no;
            $delivery_note->so_date = $request->so_date;
            $delivery_note->other_refrence = $request->other_refrence;
            $delivery_note->order_no = $request->order_no ?? '';;
            $delivery_note->order_date = $request->order_date;;
            $delivery_note->despacth_document_no = $request->despacth_document_no;
            $delivery_note->despacth_document_date = $request->despacth_document_date;
            $delivery_note->despacth_through = $request->despacth_through ?? '';
            $delivery_note->destination = $request->destination ?? '';
            $delivery_note->terms_of_delivery = $request->terms_of_delivery ?? '';
            $delivery_note->contact_person = $request->contact_person ?? '';
            $delivery_note->phone_no = $request->phone_no ?? '';
            $delivery_note->packing_list_id = $request->packing_id ?? '';
            $delivery_note->qc_packing_id = $request->qc_packing_id ?? '';
            $delivery_note->buyers_id = $request->buyers_id;
            $delivery_note->due_date = $request->due_date;
            $delivery_note->sales_tax_amount = CommonHelper::check_str_replace($request->sales_tax_apply);
            $SalesTaxAmount = CommonHelper::check_str_replace($request->sales_tax_apply);
            $delivery_note->description = $request->description;
            $delivery_note->status = 1;
            $delivery_note->date = date('Y-m-d');
            $delivery_note->username = Auth::user()->name;
            $delivery_note->save();
            $id = $delivery_note->id;


            $count = $request->count;

            $actual_qty = 0;
            $total_send_qty = 0;
            $total_amount = 0;
            $countOfGroupBy = 1;
            for ($i = 1; $i <= $count; $i++):
                $delivery_note_data = new DeliveryNoteData();
                $delivery_note_data = $delivery_note_data->SetConnection('mysql2');
                $delivery_note_data->master_id = $id;
                $delivery_note_data->so_id = $request->master_id;
                $delivery_note_data->desc = $request->input('desc' . $i);
                $delivery_note_data->so_data_id = $request->input('data_id' . $i);
                $delivery_note_data->gd_no = $gd_no;
                $delivery_note_data->gd_date = $request->input('gd_date');
                $delivery_note_data->item_id = $request->input('item_id' . $i);

                $batch_code = $request->input('batch_code' . $i);
                if ($batch_code == ''):
                    $batch_code = 0;
                endif;

                $delivery_note_data->batch_code = $batch_code;


                $qty = CommonHelper::check_str_replace($request->input('qty' . $i));
                $actual_qty += $qty;
                $send_qty = CommonHelper::check_str_replace($request->input('send_qty' . $i));
                $total_send_qty += $send_qty;


                $rate = CommonHelper::check_str_replace($request->input('send_rate' . $i));
                $amount = CommonHelper::check_str_replace($request->input('send_amount' . $i));


                $delivery_note_data->qty = $send_qty;

                $delivery_note_data->rate = $rate;
                $delivery_note_data->tax = $request->input('send_discount' . $i);
                $delivery_note_data->tax_amount = $request->input('send_discount_amount' . $i);
                $delivery_note_data->amount = $amount;
                $total_amount += $amount;


                $delivery_note_data->warehouse_id = $request->input('warehouse' . $i);
                $delivery_note_data->groupby = $countOfGroupBy; //$request->input('groupby' . $i);
                $delivery_note_data->bundles_id = $request->input('bundles_id' . $i);
                $delivery_note_data->status = 1;
                $delivery_note_data->date = date('Y-m-d');
                $delivery_note_data->username = Auth::user()->name;
                $delivery_note_data->save();
                $master_data_id = $delivery_note_data->id;
                $type =  CommonHelper::get_item_type($request->input('item_id' . $i));



                $average_cost = ReuseableCode::average_cost_sales($request->input('item_id' . $i), $request->input('warehouse' . $i), $request->input('batch_code' . $i));


                $qty = ReuseableCode::get_stock($request->input('item_id' . $i), $request->input('warehouse' . $i), $send_qty, $request->input('batch_code' . $i));
                $qty = number_format($qty, 2);
                if ($qty < 0):
                    $delivery_note_dataa = new DeliveryNoteData();
                    $delivery_note_dataa = $delivery_note_data->SetConnection('mysql2');
                    $delivery_note_dataa = $delivery_note_data->where('item_id', $request->input('item_id' . $i))
                        ->where('master_id', $id)
                        ->get();
                    $total_qty = 0;
                    $total_amount = 0;
                    foreach ($delivery_note_dataa as $row):
                        echo $row->item_id . ' =>' . CommonHelper::get_item_name($request->input('item_id' . $i))
                            . '=>' . $row->qty . '</br>';
                        $total_qty += $row->qty;
                    endforeach;


                    DB::rollBack();
                    //  echo  'Stock Not Available For =>'.CommonHelper::get_item_name($request->input('item_id' . $i)).' =>'.$total_qty.' =>'.number_format($qty,2).' =>item_id';
                    die;

                endif;

                $stock = array(
                    'main_id' => $id,
                    'master_id' => $master_data_id,
                    'voucher_no' => $gd_no,
                    'voucher_date' => $request->gd_date,
                    'supplier_id' => 0,
                    'customer_id' => $request->buyers_id,
                    'voucher_type' => 5,
                    'rate' => $rate,
                    'sub_item_id' => $request->input('item_id' . $i),
                    'batch_code' => $batch_code,
                    'qty' => $send_qty,
                    'discount_percent' => $request->input('send_discount' . $i),
                    'discount_amount' => 0, // $request->input('send_discount_amount' . $i),
                    'amount' => $send_qty * $average_cost,
                    'status' => 1,
                    'warehouse_id' => $request->input('warehouse' . $i),
                    'username' => Auth::user()->username,
                    'created_date' => date('Y-m-d'),
                    'created_date' => date('Y-m-d'),
                    'opening' => 0,
                    'so_data_id' => $request->input('data_id' . $i)
                );
                $amount = $send_qty * $average_cost;
                $total_amount = 0;
                DB::Connection('mysql2')->table('stock')->insert($stock);

                $countOfGroupBy++;
            endfor;

            //     if ($total_send_qty==$actual_qty):


            // $sale_order= new Sales_Order();
            // $sale_order=$sale_order->SetConnection('mysql2');
            // $sale_order=$sale_order->find($request->master_id);
            // $sale_order->delivery_note_status=1;
            // $sale_order->save();
            //         endif;

            db::connection('mysql2')->table('qc_packings')
                ->where('packing_list_id', $request->packing_id)
                ->where('id', $request->qc_packing_id)
                ->update([
                    'dc_status' => 2
                ]);


            // Control account DN
            //    $this->dn_insert($id,$gd_no);


            SalesHelper::sales_activity($gd_no, $request->gd_date, $total_amount + $SalesTaxAmount, 2, 'Insert');

            $voucher_no = $gd_no;
            $dept_and_type = NotificationHelper::get_dept_id('sales_order', 'id', $request->master_id)->select('department', 'p_type')->first();
            $dept_id = $dept_and_type->department;
            $p_type = $dept_and_type->p_type;
            $subject = 'Delivery Note For ' . $request->so_no;
            NotificationHelper::send_email('Delivery Note', 'Create', $dept_id, $voucher_no, $subject, $p_type);
            DB::Connection('mysql2')->commit();
        } catch (Exception $ex) {


            DB::rollBack();
        }

        //   echo $actual_qty.' '.$total_send_qty;

        return Redirect::to('sales/viewDeliveryChallanList?pageType=' . Input::get('pageType') . '&&parentCode=' . Input::get('parentCode') . '&&m=' . $_GET['m'] . '#SFR');
    }

    function dn_insert($id, $gd_no)
    {
        $data =   DB::Connection('mysql2')->select(
            'select sum(a.amount) as amount, a.voucher_no,a.voucher_date,d.acc_id,a.description from stock as a
                inner join
                subitem as c
                on
                c.id=a.sub_item_id
                inner join
                category as d
                on
                c.main_ic_id=d.id
                where a.status=1
                and c.type!=2
                and a.voucher_no="' . $gd_no . '"
                group by d.id'

        );


        $total_amount = 0;
        foreach ($data as $row):
            $transaction = new Transactions();
            $transaction = $transaction->SetConnection('mysql2');
            $transaction->voucher_no = $row->voucher_no;
            $transaction->v_date = $row->voucher_date;
            $transaction->acc_id = $row->acc_id;
            $transaction->acc_code = FinanceHelper::getAccountCodeByAccId($row->acc_id);
            $transaction->particulars = $row->description;
            $transaction->opening_bal = 0;
            $transaction->debit_credit = 0;
            $transaction->amount = $row->amount;
            $transaction->username = Auth::user()->name;;
            $transaction->status = 1;
            $transaction->voucher_type = 7;
            $transaction->save();
            $total_amount += $row->amount;
        endforeach;

        if ($total_amount > 0):

            $transaction = new Transactions();
            $transaction = $transaction->SetConnection('mysql2');
            $transaction->voucher_no = $row->voucher_no;
            $transaction->v_date = $row->voucher_date;
            $transaction->acc_id = 314;
            $transaction->acc_code = FinanceHelper::getAccountCodeByAccId(314);
            $transaction->particulars = $row->description;
            $transaction->opening_bal = 0;
            $transaction->debit_credit = 1;
            $transaction->amount = $total_amount;
            $transaction->username = Auth::user()->name;;
            $transaction->status = 1;
            $transaction->voucher_type = 7;
            $transaction->save();
        endif;

        $data =  DB::Connection('mysql2')->table('delivery_note as a')
            ->join('delivery_note_data as b', 'a.id', '=', 'b.master_id')
            ->join('subitem as c', 'c.id', '=', 'b.item_id')
            ->join('category as d', 'd.id', '=', 'c.main_ic_id')
            ->where('a.status', 1)
            ->where('a.gd_no', $gd_no)
            ->where('c.type', 2)
            ->select(DB::raw('SUM(b.qty*b.rate) as amount'), 'a.gd_no', 'a.gd_date', 'a.description', 'd.acc_id')
            ->groupBy('d.id')
            ->get();

        $total_amount = 0;
        foreach ($data as $row):
            $transaction = new Transactions();
            $transaction = $transaction->SetConnection('mysql2');
            $transaction->voucher_no = $row->gd_no;
            $transaction->v_date = $row->gd_date;
            $transaction->acc_id = $row->acc_id;
            $transaction->acc_code = FinanceHelper::getAccountCodeByAccId($row->acc_id);
            $transaction->particulars = $row->description;
            $transaction->opening_bal = 0;
            $transaction->debit_credit = 0;
            $transaction->amount = $row->amount;
            $transaction->username = Auth::user()->name;;
            $transaction->status = 1;
            $transaction->voucher_type = 7;
            $transaction->save();
            $total_amount += $row->amount;
        endforeach;

        if ($total_amount > 0):

            $transaction = new Transactions();
            $transaction = $transaction->SetConnection('mysql2');
            $transaction->voucher_no = $row->gd_no;
            $transaction->v_date = $row->gd_date;
            $transaction->acc_id = 314;
            $transaction->acc_code = FinanceHelper::getAccountCodeByAccId(314);
            $transaction->particulars = $row->description;
            $transaction->opening_bal = 0;
            $transaction->debit_credit = 1;
            $transaction->amount = $total_amount;
            $transaction->username = Auth::user()->name;;
            $transaction->status = 1;
            $transaction->voucher_type = 7;
            $transaction->save();
        endif;
    }
    function addeDirectSalesTaxInvoice(Request $request)
    {
        // dd($request->all());

        DB::Connection('mysql2')->beginTransaction();
        try {
            $byers_id = $request->buyers_id;
            $byers_id = explode('*', $byers_id);
            $byers_id = $byers_id[0];

            foreach ($request->sub_ic_des as $key => $value) {

                $stock = array(
                    'main_id' => '',
                    'master_id' => '',
                    'voucher_no' => $request->gi_no,
                    'voucher_date' => $request->gi_date,
                    'supplier_id' => 0,
                    'customer_id' => $byers_id,
                    'voucher_type' => 5,
                    'rate' => $request->rate[$key],
                    'sub_item_id' => $request->sub_ic_des[$key],
                    'batch_code' => $request->batch_code[$key] ?? '0',
                    'qty' => $request->actual_qty[$key],
                    'discount_percent' => '',
                    'discount_amount' => '',
                    'amount' => $request->actual_qty[$key] * $request->rate[$key],
                    'status' => 1,
                    'warehouse_id' => $request->warehouse[$key],
                    'username' => Auth::user()->username,
                    'created_date' => date('Y-m-d'),
                    'created_date' => date('Y-m-d'),
                    'opening' => 0,
                    'so_data_id' => '',
                );
                //  DB::Connection('mysql2')->table('stock')->insert($stock);
            }


            $sales_tax_invoice = new SalesTaxInvoice();
            $sales_tax_invoice = $sales_tax_invoice->SetConnection('mysql2');

            // for invoice no and invoice date
            $gi_no = $request->gi_no;
            $sales_tax_invoice->gi_no = $request->gi_no;
            $sales_tax_invoice->gi_date = $request->gi_date;
            $sales_tax_invoice->model_terms_of_payment = $request->model_terms_of_payment ?? '';
            $sales_tax_invoice->order_date = $request->order_date ?? '';
            $sales_tax_invoice->other_refrence = $request->other_refrence;
            $sales_tax_invoice->despacth_document_no = $request->despacth_document_no;
            $sales_tax_invoice->despacth_document_date = $request->despacth_document_date;
            $sales_tax_invoice->despacth_through = $request->despacth_through;
            $sales_tax_invoice->destination = $request->destination;;
            $sales_tax_invoice->terms_of_delivery = $request->terms_of_delivery;;
            $sales_tax_invoice->due_date = $request->due_date ?? '';
            $sales_tax_invoice->status = 1;
            $sales_tax_invoice->username = Auth::user()->name;
            $sales_tax_invoice->amount_in_words = $request->amount_in_words;
            $sales_tax_invoice->order_no = $request->order_no ?? '';
            $sales_tax_invoice->date = date('Y-m-d');
            $sales_tax_invoice->buyers_id = $byers_id;
            $sales_tax_invoice->description = $request->description;

            //    $sales_tax_data = SalesHelper::get_sales_tax_by_sales_order_id($request->sales_order_id);
            $sales_tax_invoice->sales_tax =  CommonHelper::check_str_replace($request->sales_tax);
            $sales_tax_invoice->sales_tax_further = CommonHelper::check_str_replace($request->sales_tax_further);
            $sales_tax_invoice->acc_id = $request->acc_id;
            $sales_tax_invoice->currency = $request->curren;
            $sales_tax_invoice->currency_rate = $request->currency_rate;
            $sales_tax_invoice->save();
            $id = $sales_tax_invoice->id;


            $total_amount = 0;
            foreach ($request->sub_ic_des as $key => $value) {
                # code...

                $sales_tax_invoice_data = new SalesTaxInvoiceData();
                $sales_tax_invoice_data = $sales_tax_invoice_data->SetConnection('mysql2');
                $sales_tax_invoice_data->master_id = $id;
                $sales_tax_invoice_data->groupby =  1;
                $sales_tax_invoice_data->gi_no = $gi_no;
                $sales_tax_invoice_data->item_id = $request->sub_ic_des[$key];
                $sales_tax_invoice_data->description = '';
                $qty =  $request->actual_qty[$key];
                $rate = $request->rate[$key];
                $amount = $request->amount[$key];
                $sales_tax_invoice_data->qty = $qty;
                $sales_tax_invoice_data->rate = $rate;
                $sales_tax_invoice_data->tax = $request->tax[$key];
                $sales_tax_invoice_data->tax_amount = $request->tax_amount[$key];
                $sales_tax_invoice_data->amount = $amount;
                $sales_tax_invoice_data->warehouse_id = $request->warehouse[$key];
                //    $sales_tax_invoice_data->bundles_id = $request->input('bundles_id' . $i);
                $sales_tax_invoice_data->status = 1;
                $sales_tax_invoice_data->date = date('Y-m-d');
                $sales_tax_invoice_data->username = Auth::user()->name;
                $sales_tax_invoice_data->save();
                $total_amount += $qty * $rate;
            }

            $t_data = DB::Connection('mysql2')->table('sales_tax_invoice as a')
                ->join('sales_tax_invoice_data as b', 'a.id', '=', 'b.master_id')
                ->join('subitem as c', 'b.item_id', '=', 'c.id')
                ->join('category as d', 'd.id', '=', 'c.main_ic_id')
                ->select(DB::raw('SUM(b.rate*b.qty) as amount'), 'b.item_id', 'a.gi_date', 'd.revenue_acc_id')
                ->where('a.gi_no', $gi_no)
                ->where('a.status', 1)
                ->groupBy('d.id')
                ->get();

            foreach ($t_data as $revenue):
                $transaction = new Transactions();
                $transaction = $transaction->SetConnection('mysql2');
                $transaction->voucher_no = $gi_no;
                $transaction->v_date = $request->gi_date;
                $transaction->acc_id = $revenue->revenue_acc_id;
                $transaction->acc_code = FinanceHelper::getAccountCodeByAccId($request->acc_id);
                $transaction->particulars = $request->description;
                $transaction->opening_bal = 0;
                $transaction->debit_credit = 0;
                $transaction->amount = $revenue->amount;
                $transaction->username = Auth::user()->name;;
                $transaction->status = 100;
                $transaction->voucher_type = 6;
                $transaction->save();
            endforeach;

            $sales_tax = DB::Connection('mysql2')->table('sales_tax_invoice_data')
                ->where('status', 1)
                ->where('master_id', $id)
                ->sum('tax_amount');

            if ($sales_tax > 0):


                $sales_tac_acc_id = DB::Connection('mysql2')->table('accounts')->where('status', 1)->where('name', 'like', '%' . 'Sales Tax Output FBR' . '%')->select('id')->first();

                $transaction = new Transactions();
                $transaction = $transaction->SetConnection('mysql2');
                $transaction->voucher_no = $gi_no;
                $transaction->v_date = $request->gi_date;
                $transaction->acc_id = 188;
                $transaction->acc_code = FinanceHelper::getAccountCodeByAccId(152);
                $transaction->particulars = $request->description;
                $transaction->opening_bal = 0;
                $transaction->debit_credit = 0;
                $transaction->amount = $sales_tax;
                $transaction->username = Auth::user()->name;
                $transaction->status = 100;
                $transaction->voucher_type = 6;
                $transaction->save();
                $total_amount += $sales_tax;

            endif;

            $sales_tax_further = CommonHelper::check_str_replace($request->sales_tax_further);

            if ($sales_tax_further > 0):


                $sales_tac_acc_id_further = DB::Connection('mysql2')->table('accounts')->where('status', 1)->where('name', 'Additional Sales Tax Receivable (3%)')->select('id')->first();
                $transaction = new Transactions();
                $transaction = $transaction->SetConnection('mysql2');
                $transaction->voucher_no = $gi_no;
                $transaction->v_date = $request->gi_date;
                $transaction->acc_id = $sales_tac_acc_id_further;
                $transaction->acc_code = FinanceHelper::getAccountCodeByAccId($sales_tac_acc_id_further);
                $transaction->particulars = $request->description;
                $transaction->opening_bal = 0;
                $transaction->debit_credit = 0;
                $transaction->amount = $sales_tax_further;
                $transaction->username = Auth::user()->name;;
                $transaction->status = 100;
                $transaction->voucher_type = 6;
                $transaction->save();
                $total_amount += $sales_tax_further;

            endif;
            $Loop = Input::get('account_id');
            if ($Loop != "") {
                $Counta = 0;
                foreach ($Loop as $LoopFil) {
                    $ExpData['voucher_no'] = $request->gi_no;
                    $ExpData['main_id'] = $id;
                    $ExpData['acc_id'] = Input::get('account_id')[$Counta];
                    $ExpData['amount'] = Input::get('expense_amount')[$Counta];
                    $ExpData['created_date'] = date('Y-m-d');
                    $ExpData['username'] = Auth::user()->name;

                    DB::Connection('mysql2')->table('addional_expense_sales_tax_invoice')->insert($ExpData);
                    $transaction = new Transactions();
                    $transaction = $transaction->SetConnection('mysql2');
                    $transaction->voucher_no = $gi_no;
                    $transaction->v_date = $request->gi_date;
                    $transaction->acc_id = Input::get('account_id')[$Counta];
                    $transaction->acc_code = FinanceHelper::getAccountCodeByAccId(Input::get('account_id')[$Counta]);
                    $transaction->particulars = $request->description;
                    $transaction->opening_bal = 0;
                    $transaction->debit_credit = 0;
                    $transaction->amount = Input::get('expense_amount')[$Counta];
                    $transaction->username = Auth::user()->name;;
                    $transaction->status = 100;
                    $transaction->voucher_type = 6;
                    $transaction->save();
                    $total_amount += Input::get('expense_amount')[$Counta];
                    $Counta++;
                }
            }

            $customer_acc_id = SalesHelper::get_customer_acc_id($byers_id);;

            $transaction = new Transactions();
            $transaction = $transaction->SetConnection('mysql2');
            $transaction->voucher_no = $gi_no;
            $transaction->v_date = $request->gi_date;
            $transaction->acc_id = $customer_acc_id;
            $transaction->acc_code = FinanceHelper::getAccountCodeByAccId($customer_acc_id);
            $transaction->particulars = $request->description;
            $transaction->opening_bal = 0;
            $transaction->debit_credit = 1;
            $transaction->amount = $total_amount;
            $transaction->username = Auth::user()->name;;
            $transaction->status = 100;
            $transaction->voucher_type = 6;
            $transaction->save();
            $data['sales_tax_invoice_id'] = $id;
            $data['sales_tax_invoice'] = 1;

            SalesHelper::sales_activity($gi_no, $request->gi_date, $total_amount, 3, 'Insert');

            $voucher_no = $gi_no;

            $subject = 'Sales Tax Invoice For ' . $request->so_no;
            //  NotificationHelper::send_email('Sales tax Invoice','Create', $dept_id,$voucher_no,$subject,$p_type);

            DB::Connection('mysql2')->commit();
        } catch (Exception $ex) {

            DB::rollBack();
            echo $ex->getLine();
        }
        $SavePrintVal = Input::get('SavePrintVal');




        if ($SavePrintVal == 1) {
            $Url = url('sales/PrintSalesTaxInvoice?id=' . $id . 'pageType=' . Input::get('pageType') . '&&parentCode=' . Input::get('parentCode') . '&&m=' . $_GET['m'] . '#SFR');
            //echo "<script type='text/javascript'>window.open('".$Url."', '_blank')</script>";
            return Redirect::to($Url);
            return Redirect::to('sales/CreateSalesTaxInvoiceList?pageType=' . Input::get('pageType') . '&&parentCode=' . Input::get('parentCode') . '&&m=' . $_GET['m'] . '#SFR');
        } else {
            return Redirect::to('sales/viewSalesTaxInvoiceList?pageType=' . Input::get('pageType') . '&&parentCode=' . Input::get('parentCode') . '&&m=' . $_GET['m'] . '#SFR');
        }
    }


    function updateDirectSalesTaxInvoice(Request $request, $id)
    {
        // dd($request->all(),$id);

        DB::Connection('mysql2')->beginTransaction();
        try {
            $byers_id = $request->buyers_id;
            $byers_id = explode('*', $byers_id);
            $byers_id = $byers_id[0];




            $sales_tax_invoice = new SalesTaxInvoice();
            $sales_tax_invoice = $sales_tax_invoice->SetConnection('mysql2')->find($id);

            // for invoice no and invoice date

            //    $sales_tax_invoice->gi_no = $request->gi_no;
            $gi_no = $sales_tax_invoice->gi_no;
            $sales_tax_invoice->gi_date = $request->gi_date;
            $sales_tax_invoice->model_terms_of_payment = $request->model_terms_of_payment;
            $sales_tax_invoice->order_date = $request->order_date;
            $sales_tax_invoice->other_refrence = $request->other_refrence;
            $sales_tax_invoice->despacth_document_no = $request->despacth_document_no;
            $sales_tax_invoice->despacth_document_date = $request->despacth_document_date;
            $sales_tax_invoice->despacth_through = $request->despacth_through;
            $sales_tax_invoice->destination = $request->destination;;
            $sales_tax_invoice->terms_of_delivery = $request->terms_of_delivery;;
            $sales_tax_invoice->due_date = $request->due_date;
            $sales_tax_invoice->status = 1;
            $sales_tax_invoice->username = Auth::user()->name;
            $sales_tax_invoice->amount_in_words = $request->amount_in_words;
            $sales_tax_invoice->order_no = $request->order_no;
            $sales_tax_invoice->date = date('Y-m-d');
            $sales_tax_invoice->buyers_id = $byers_id;
            $sales_tax_invoice->description = $request->description;

            //    $sales_tax_data = SalesHelper::get_sales_tax_by_sales_order_id($request->sales_order_id);
            $sales_tax_invoice->sales_tax =  CommonHelper::check_str_replace($request->sales_tax);
            $sales_tax_invoice->sales_tax_further = CommonHelper::check_str_replace($request->sales_tax_further);
            $sales_tax_invoice->acc_id = $request->acc_id;
            $sales_tax_invoice->currency = $request->curren;
            $sales_tax_invoice->currency_rate = $request->currency_rate;
            $sales_tax_invoice->save();
            $id = $sales_tax_invoice->id;

            $sales_tax_invoice_data_delete = new SalesTaxInvoiceData();
            $sales_tax_invoice_data_delete = $sales_tax_invoice_data_delete->SetConnection('mysql2')->where('master_id', $id)->delete();



            $total_amount = 0;
            foreach ($request->sub_ic_des as $key => $value) {
                # code...

                $sales_tax_invoice_data = new SalesTaxInvoiceData();
                $sales_tax_invoice_data = $sales_tax_invoice_data->SetConnection('mysql2');
                $sales_tax_invoice_data->master_id = $id;
                $sales_tax_invoice_data->groupby =  1;
                $sales_tax_invoice_data->gi_no = $gi_no;
                $sales_tax_invoice_data->item_id = $request->sub_ic_des[$key];
                $sales_tax_invoice_data->description = $request->uom_id[$key] ?? '0';
                $qty =  $request->actual_qty[$key];
                $rate = $request->rate[$key];
                $amount = $request->amount[$key];
                $sales_tax_invoice_data->qty = $qty;
                $sales_tax_invoice_data->rate = $rate;
                $sales_tax_invoice_data->tax = $request->tax[$key];
                $sales_tax_invoice_data->tax_amount = $request->tax_amount[$key];
                $sales_tax_invoice_data->amount = $amount;
                $sales_tax_invoice_data->warehouse_id = $request->warehouse[$key];
                //    $sales_tax_invoice_data->bundles_id = $request->input('bundles_id' . $i);
                $sales_tax_invoice_data->status = 1;
                $sales_tax_invoice_data->date = date('Y-m-d');
                $sales_tax_invoice_data->username = Auth::user()->name;
                $sales_tax_invoice_data->save();
                $total_amount += $qty * $rate;
            }

            $transactionfordelete = new Transactions();
            $transactionfordelete = $transactionfordelete->SetConnection('mysql2')
                ->where('voucher_no', $gi_no)->delete();

            $t_data = DB::Connection('mysql2')->table('sales_tax_invoice as a')
                ->join('sales_tax_invoice_data as b', 'a.id', '=', 'b.master_id')
                ->join('subitem as c', 'b.item_id', '=', 'c.id')
                ->join('category as d', 'd.id', '=', 'c.main_ic_id')
                ->select(DB::raw('SUM(b.rate*b.qty) as amount'), 'b.item_id', 'a.gi_date', 'd.revenue_acc_id')
                ->where('a.gi_no', $gi_no)
                ->where('a.status', 1)
                ->groupBy('d.id')
                ->get();

            foreach ($t_data as $revenue):
                $transaction = new Transactions();
                $transaction = $transaction->SetConnection('mysql2');
                $transaction->voucher_no = $gi_no;
                $transaction->v_date = $request->gi_date;
                $transaction->acc_id = $request->acc_id;
                $transaction->acc_code = FinanceHelper::getAccountCodeByAccId($request->acc_id);
                $transaction->particulars = $request->description;
                $transaction->opening_bal = 0;
                $transaction->debit_credit = 0;
                $transaction->amount = $revenue->amount;
                $transaction->username = Auth::user()->name;;
                $transaction->status = 100;
                $transaction->voucher_type = 6;
                $transaction->save();
            endforeach;

            $sales_tax = DB::Connection('mysql2')->table('sales_tax_invoice_data')
                ->where('status', 1)
                ->where('master_id', $id)
                ->sum('tax_amount');

            if ($sales_tax > 0):


                $sales_tac_acc_id = DB::Connection('mysql2')->table('accounts')->where('status', 1)->where('name', 'like', '%' . 'Sales Tax Output FBR' . '%')->select('id')->first();

                $transaction = new Transactions();
                $transaction = $transaction->SetConnection('mysql2');
                $transaction->voucher_no = $gi_no;
                $transaction->v_date = $request->gi_date;
                $transaction->acc_id = 152;
                $transaction->acc_code = FinanceHelper::getAccountCodeByAccId(152);
                $transaction->particulars = $request->description;
                $transaction->opening_bal = 0;
                $transaction->debit_credit = 0;
                $transaction->amount = $sales_tax;
                $transaction->username = Auth::user()->name;
                $transaction->status = 100;
                $transaction->voucher_type = 6;
                $transaction->save();
                $total_amount += $sales_tax;

            endif;

            $sales_tax_further = CommonHelper::check_str_replace($request->sales_tax_further);

            if ($sales_tax_further > 0):


                $sales_tac_acc_id_further = DB::Connection('mysql2')->table('accounts')->where('status', 1)->where('name', 'Additional Sales Tax Receivable (3%)')->select('id')->first();
                $transaction = new Transactions();
                $transaction = $transaction->SetConnection('mysql2');
                $transaction->voucher_no = $gi_no;
                $transaction->v_date = $request->gi_date;
                $transaction->acc_id = $sales_tac_acc_id_further;
                $transaction->acc_code = FinanceHelper::getAccountCodeByAccId($sales_tac_acc_id_further);
                $transaction->particulars = $request->description;
                $transaction->opening_bal = 0;
                $transaction->debit_credit = 0;
                $transaction->amount = $sales_tax_further;
                $transaction->username = Auth::user()->name;;
                $transaction->status = 100;
                $transaction->voucher_type = 6;
                $transaction->save();
                $total_amount += $sales_tax_further;

            endif;
            $Loop = Input::get('account_id');
            if ($Loop != "") {
                $Counta = 0;
                foreach ($Loop as $LoopFil) {
                    $ExpData['voucher_no'] = $request->gi_no;
                    $ExpData['main_id'] = $id;
                    $ExpData['acc_id'] = Input::get('account_id')[$Counta];
                    $ExpData['amount'] = Input::get('expense_amount')[$Counta];
                    $ExpData['created_date'] = date('Y-m-d');
                    $ExpData['username'] = Auth::user()->name;

                    DB::Connection('mysql2')->table('addional_expense_sales_tax_invoice')->insert($ExpData);
                    $transaction = new Transactions();
                    $transaction = $transaction->SetConnection('mysql2');
                    $transaction->voucher_no = $gi_no;
                    $transaction->v_date = $request->gi_date;
                    $transaction->acc_id = Input::get('account_id')[$Counta];
                    $transaction->acc_code = FinanceHelper::getAccountCodeByAccId(Input::get('account_id')[$Counta]);
                    $transaction->particulars = $request->description;
                    $transaction->opening_bal = 0;
                    $transaction->debit_credit = 0;
                    $transaction->amount = Input::get('expense_amount')[$Counta];
                    $transaction->username = Auth::user()->name;;
                    $transaction->status = 100;
                    $transaction->voucher_type = 6;
                    $transaction->save();
                    $total_amount += Input::get('expense_amount')[$Counta];
                    $Counta++;
                }
            }

            $customer_acc_id = SalesHelper::get_customer_acc_id($byers_id);;

            $transaction = new Transactions();
            $transaction = $transaction->SetConnection('mysql2');
            $transaction->voucher_no = $gi_no;
            $transaction->v_date = $request->gi_date;
            $transaction->acc_id = $customer_acc_id;
            $transaction->acc_code = FinanceHelper::getAccountCodeByAccId($customer_acc_id);
            $transaction->particulars = $request->description;
            $transaction->opening_bal = 0;
            $transaction->debit_credit = 1;
            $transaction->amount = $total_amount;
            $transaction->username = Auth::user()->name;;
            $transaction->status = 100;
            $transaction->voucher_type = 6;
            $transaction->save();
            $data['sales_tax_invoice_id'] = $id;
            $data['sales_tax_invoice'] = 1;

            SalesHelper::sales_activity($gi_no, $request->gi_date, $total_amount, 3, 'Insert');

            $voucher_no = $gi_no;

            $subject = 'Sales Tax Invoice For ' . $request->so_no;
            //  NotificationHelper::send_email('Sales tax Invoice','Create', $dept_id,$voucher_no,$subject,$p_type);

            DB::Connection('mysql2')->commit();
        } catch (Exception $ex) {

            DB::rollBack();
            echo $ex->getLine();
        }
        $SavePrintVal = Input::get('SavePrintVal');



        $m = Session::get('company_run');
        if ($SavePrintVal == 1) {
            $Url = url('sales/PrintSalesTaxInvoice?id=' . $id . 'pageType=' . Input::get('pageType') . '&&parentCode=' . Input::get('parentCode') . '&&m=' . $m . '#SFR');
            //echo "<script type='text/javascript'>window.open('".$Url."', '_blank')</script>";
            return Redirect::to($Url);
            return Redirect::to('sales/CreateSalesTaxInvoiceList?pageType=' . Input::get('pageType') . '&&parentCode=' . Input::get('parentCode') . '&&m=' . $m . '#SFR');
        } else {
            return Redirect::to('sales/CreateSalesTaxInvoiceList?pageType=' . Input::get('pageType') . '&&parentCode=' . Input::get('parentCode') . '&&m=' . $m . '#SFR');
        }
    }
    function addeSalesTaxInvoice(Request $request)
    {
        $SavePrintVal = Input::get('SavePrintVal');
        
        // dd($request->all());
        
        $update_id = explode(',', $request->input('dn_ids'));
        
        
        $count = $request->count;
        DB::Connection('mysql2')->beginTransaction();
        try {
            // dd($request->all());
            
            // $delivery_note= new DeliveryNote();
            //  $delivery_note=$delivery_note->SetConnection('mysql2');
            //  $delivery_note=$delivery_note->find($request->delivery_note_id);
            //   $delivery_note->sales_tax_invoice=1;
            //   $delivery_note->save();
            
            
            //  echo $request->model_terms_of_payment ;// SalesHelper::get_sales_tax_by_sales_order_id($request->sales_order_id);
            //  exit();
            
            $customer = Customer::find($request->buyers_id);
            // Add validation for customer and account
            if (!$customer || (!$customer->acc_id && $customer->acc_id != 0)) {
                DB::Connection('mysql2')->rollBack();
                return back()->with('error', 'Customer account not found or not configured properly');
            }
            // dd( $customer);
            
            $sales_tax_invoice = new SalesTaxInvoice();
            $sales_tax_invoice = $sales_tax_invoice->SetConnection('mysql2');

            // for invoice no and invoice date
            $gi_no = SalesHelper::get_unique_no_sales_tax_invoice(date('y'), date('m'));
            $sales_tax_invoice->gi_no = $gi_no;
            $sales_tax_invoice->gi_date = $request->gi_date;
            $sales_tax_invoice->gd_no = $request->despacth_document_no;
            $sales_tax_invoice->gd_date = $request->despacth_document_date;

            // for Delivery Note No and date


            // for Sales Order No and Date

            $sales_tax_invoice->so_no = $request->so_no;
            $sales_tax_invoice->so_date = $request->so_date;

            // For Sales Order Id
            $sales_tax_invoice->so_id = $request->sales_order_id;


            // For Delivery Note Id
            //  $sales_tax_invoice->gd_id = $request->delivery_note_id;
            // End
            $sales_tax_invoice->model_terms_of_payment = $request->terms_of_delivery ?? 0;
            // $sales_tax_invoice->description = $request->description;
            $sales_tax_invoice->order_date = $request->order_date ?? NULL;
            $sales_tax_invoice->other_refrence = $request->other_refrence ?? NULL;
            $sales_tax_invoice->despacth_document_no = $request->despacth_document_no ?? NULL;
            $sales_tax_invoice->despacth_document_date = $request->despacth_document_date ?? NULL;
            $sales_tax_invoice->despacth_through = $request->despacth_through ?? '';
            $sales_tax_invoice->destination = $request->destination ?? '';
            $sales_tax_invoice->terms_of_delivery = $request->terms_of_delivery ?? '';
            $sales_tax_invoice->due_date = $request->due_date ?? NULL;
            $sales_tax_invoice->status = 1;
            $sales_tax_invoice->username = Auth::user()->name;
            $sales_tax_invoice->amount_in_words = $request->amount_in_words ?? NULL;
            $sales_tax_invoice->order_no = $request->order_no ?? '';
            $sales_tax_invoice->date = date('Y-m-d');
            $sales_tax_invoice->buyers_id = $request->buyers_id;
            $sales_tax_invoice->description = $request->description ?? NULL;
            $sales_tax_invoice->wh_tax = $request->wh_tax ?? 0;
            $sales_tax_invoice->adv_tax = $request->adv_tax ?? 0;
            $sales_tax_invoice->total = $request->total_amount_after_sale_tax ?? 0;
            $sales_tax_data = SalesHelper::get_sales_tax_by_sales_order_id($request->sales_order_id);
            $sales_tax_invoice->sales_tax =  CommonHelper::check_str_replace($request->sales_tax);
            $sales_tax_invoice->sales_tax_further = CommonHelper::check_str_replace($request->sales_tax_further);
            $sales_tax_invoice->acc_id = $customer->acc_id ?? 0;
            // model_terms_of_payment


            $sales_tax_invoice->save();



            $id = $sales_tax_invoice->id;

            $count = $request->count;

            $total_amount = 0;
            for ($i = 1; $i <= $count; $i++):
                $sales_tax_invoice_data = new SalesTaxInvoiceData();
                $sales_tax_invoice_data = $sales_tax_invoice_data->SetConnection('mysql2');
                $sales_tax_invoice_data->master_id = $id;
                $sales_tax_invoice_data->so_id = $request->sales_order_id;

                $sales_tax_invoice_data->dn_data_ids = $request->input('dn_ids');
                $sales_tax_invoice_data->so_data_id = $request->input('so_data_id' . $i);

                $sales_tax_invoice_data->groupby =  $request->input('groupby' . $i);
                $sales_tax_invoice_data->description =  $request->description;
                // $sales_tax_invoice_data->gd_id = $request->delivery_note_id;
                $sales_tax_invoice_data->gi_no = $gi_no;
                $sales_tax_invoice_data->so_no = $request->so_no;
                $sales_tax_invoice_data->gd_no = $request->despacth_document_no;


                $sales_tax_invoice_data->item_id = $request->input('item_id' . $i);

                // $sales_tax_invoice_data->description = $request->input('item_desc' . $i);

                $ordered_qty = CommonHelper::check_str_replace($request->input('ordered_qty' . $i));
                $dn_qty = CommonHelper::check_str_replace($request->input('dn_qty' . $i));
                $qty = CommonHelper::check_str_replace($request->input('qty' . $i));
                $rate = CommonHelper::check_str_replace($request->input('rate' . $i));
                $amount = CommonHelper::check_str_replace($request->input('net_amount' . $i));

                 $tax_amount = CommonHelper::check_str_replace($request->input('tax_amount' . $i));

                //   dd($tax_amount);
                $sales_tax_invoice_data->ordered_qty = $ordered_qty;
                $sales_tax_invoice_data->dn_qty = $dn_qty;
                $sales_tax_invoice_data->qty = $qty;

                $sales_tax_invoice_data->rate = $rate;
                $sales_tax_invoice_data->tax = $tax = $request->input('tax' . $i);
                $taxAmount = $tax / 100 * $amount;
                // $amount += $amount;
               $sales_tax_invoice_data->tax_amount = $tax_amount;
                $sales_tax_invoice_data->amount = $amount;
                $sales_tax_invoice_data->warehouse_id = $request->input('warehouse_id' . $i);
                $sales_tax_invoice_data->bundles_id = $request->input('bundles_id' . $i);
                $sales_tax_invoice_data->status = 1;
                $sales_tax_invoice_data->date = date('Y-m-d');
                $sales_tax_invoice_data->username = Auth::user()->name;
                $sales_tax_invoice_data->save();
                // $total_amount+=$qty*$rate;
                $total_amount += $amount;
            endfor;

            $supply_chain_finance = DB::Connection('mysql2')->table('stock')->whereIn('main_id', $update_id)->get();
            foreach ($supply_chain_finance as $row) {

                $InsertData['main_id'] = $row->main_id;
                $InsertData['master_id'] = $row->master_id;
                $InsertData['voucher_no'] = $gi_no;
                $InsertData['voucher_date'] = $request->gi_date;
                $InsertData['item_id'] = $row->sub_item_id;
                $InsertData['qty'] = $row->qty;
                $InsertData['amount'] = $row->amount;
                $InsertData['opening'] = 0;
                $InsertData['status'] = 1;
                $InsertData['username'] = Auth::user()->name;
                $InsertData['voucher_type'] = 3;
                //    DB::Connection('mysql2')->table('transaction_supply_chain')->insert($InsertData);

            }




            $t_data = DB::Connection('mysql2')->table('sales_tax_invoice as a')
                ->join('sales_tax_invoice_data as b', 'a.id', '=', 'b.master_id')
                ->join('subitem as c', 'b.item_id', '=', 'c.id')
                ->join('category as d', 'd.id', '=', 'c.main_ic_id')
                ->select(DB::raw('SUM(b.rate*b.qty) as amount'), 'b.item_id', 'a.gi_date', 'd.revenue_acc_id')
                ->where('a.gi_no', $gi_no)
                ->where('a.status', 1)
                ->groupBy('d.id')
                ->get();

            foreach ($t_data as $revenue):


                $transaction = new Transactions();
                $transaction = $transaction->SetConnection('mysql2');
                $transaction->voucher_no = $gi_no;
                $transaction->v_date = $request->gi_date;
                $transaction->acc_id = $customer->acc_id;
                $transaction->acc_code = FinanceHelper::getAccountCodeByAccId($customer->acc_id);
                $transaction->particulars = $request->description;
                $transaction->opening_bal = 0;
                $transaction->debit_credit = 0;
                $transaction->amount = $revenue->amount;
                $transaction->username = Auth::user()->name;
                $transaction->status = 100;
                $transaction->voucher_type = 6;
                $transaction->save();
            endforeach;






            $sales_tax = DB::Connection('mysql2')->table('sales_tax_invoice_data')
                ->where('status', 1)
                ->where('master_id', $id)
                ->sum('tax_amount');

            if ($sales_tax > 0):



                // $acc_id = DB::Connection('mysql2')->table('sales_order as so')
                // ->join('gst as g', 'so.sales_tax_group', '=', 'g.id')
                // ->where('g.status', 1)
                // ->where('so.status', 1)
                // ->where('so.id', $request->sales_order_id)
                // ->select('g.acc_id')
                // ->first()->acc_id;

                $sales_tac_acc_id = DB::Connection('mysql2')->table('accounts')->where('status', 1)->where('id', $customer->acc_id) //where('name','like','%' .'Sales Tax Payable (18%)'. '%')
                    ->select('id')->value('id');



                $transaction = new Transactions();
                $transaction = $transaction->SetConnection('mysql2');
                $transaction->voucher_no = $gi_no;
                $transaction->v_date = $request->gi_date;
                $transaction->acc_id = $sales_tac_acc_id;
                $transaction->acc_code = FinanceHelper::getAccountCodeByAccId($sales_tac_acc_id);
                $transaction->particulars = $request->description;
                $transaction->opening_bal = 0;
                $transaction->debit_credit = 0;
                $transaction->amount = $sales_tax;
                $transaction->username = Auth::user()->name;
                $transaction->status = 100;
                $transaction->voucher_type = 6;
                $transaction->save();
                $total_amount += $sales_tax;

            endif;





            $sales_tax_further = CommonHelper::check_str_replace($request->sales_tax_further);

            if ($sales_tax_further > 0):

                $acc_id = DB::Connection('mysql2')->table('sales_order as so')
                    ->join('further_taxes as ft', 'so.further_taxes_group', '=', 'ft.id')
                    ->where('ft.status', 1)
                    ->where('so.status', 1)
                    ->where('so.id', $request->sales_order_id)
                    ->select('ft.acc_id')
                    ->first()->acc_id;

                $sales_tac_acc_id_further = DB::Connection('mysql2')->table('accounts')->where('status', 1)->where('id', $customer->acc_id) //where('name','Additional Sales Tax Receivable (3%)')
                    ->select('id')->first()->id;

                $transaction = new Transactions();
                $transaction = $transaction->SetConnection('mysql2');
                $transaction->voucher_no = $gi_no;
                $transaction->v_date = $request->gi_date;
                $transaction->acc_id = $sales_tac_acc_id_further;
                $transaction->acc_code = FinanceHelper::getAccountCodeByAccId($sales_tac_acc_id_further);
                $transaction->particulars = $request->description;
                $transaction->opening_bal = 0;
                $transaction->debit_credit = 0;
                $transaction->amount = $sales_tax_further;
                $transaction->username = Auth::user()->name;;
                $transaction->status = 100;
                $transaction->voucher_type = 6;
                $transaction->save();
                $total_amount += $sales_tax_further;

            endif;


            $Loop = Input::get('account_id');

            if ($Loop != "") {
                $Counta = 0;
                foreach ($Loop as $LoopFil) {
                    $ExpData['voucher_no'] = $request->gi_no;
                    $ExpData['main_id'] = $id;
                    $ExpData['acc_id'] = Input::get('account_id')[$Counta];
                    $ExpData['amount'] = Input::get('expense_amount')[$Counta];
                    $ExpData['created_date'] = date('Y-m-d');
                    $ExpData['username'] = Auth::user()->name;

                    DB::Connection('mysql2')->table('addional_expense_sales_tax_invoice')->insert($ExpData);



                    $transaction = new Transactions();
                    $transaction = $transaction->SetConnection('mysql2');
                    $transaction->voucher_no = $gi_no;
                    $transaction->v_date = $request->gi_date;
                    $transaction->acc_id = Input::get('account_id')[$Counta];
                    $transaction->acc_code = FinanceHelper::getAccountCodeByAccId(Input::get('account_id')[$Counta]);
                    $transaction->particulars = $request->description;
                    $transaction->opening_bal = 0;
                    $transaction->debit_credit = 0;
                    $transaction->amount = Input::get('expense_amount')[$Counta];
                    $transaction->username = Auth::user()->name;;
                    $transaction->status = 100;
                    $transaction->voucher_type = 6;
                    $transaction->save();
                    $total_amount += Input::get('expense_amount')[$Counta];
                    $Counta++;
                }
            }

            $customer_acc_id = SalesHelper::get_customer_acc_id($request->buyers_id);;

            $transaction = new Transactions();
            $transaction = $transaction->SetConnection('mysql2');
            $transaction->voucher_no = $gi_no;
            $transaction->v_date = $request->gi_date;
            $transaction->acc_id = $customer->acc_id;
            $transaction->acc_code = FinanceHelper::getAccountCodeByAccId($customer_acc_id);
            $transaction->particulars = $request->description;
            $transaction->opening_bal = 0;
            $transaction->debit_credit = 1;
            $transaction->amount = $total_amount;
            $transaction->username = Auth::user()->name;
            $transaction->status = 100;
            $transaction->voucher_type = 6;
            $transaction->save();


            $data['sales_tax_invoice_id'] = $id;
            $data['sales_tax_invoice'] = 1;
            DB::Connection('mysql2')->table('delivery_note')->whereIn('id', $update_id)->update($data);



            $cogs = DB::Connection('mysql2')->table('stock as a')
                ->join('subitem as b', 'a.sub_item_id', '=', 'b.id')
                ->join('category as c', 'c.id', 'b.main_ic_id')
                ->where('a.status', 1)
                ->whereIn('a.main_id', $update_id)
                ->where('a.voucher_type', 5)
                ->where('b.type', '!=', 2)
                ->select(DB::raw('sum(a.amount) as amount'), 'c.cogs_acc_id', 'c.acc_id')
                ->groupBy('c.id')
                ->get();
            $cogs_total = 0;
            foreach ($cogs as $row):

                $transaction = new Transactions();
                $transaction = $transaction->SetConnection('mysql2');
                $transaction->voucher_no = $gi_no;
                $transaction->v_date = $request->gi_date;
                $transaction->acc_id = $row->cogs_acc_id;
                $transaction->acc_code = FinanceHelper::getAccountCodeByAccId($row->cogs_acc_id);
                $transaction->particulars = $request->description;
                $transaction->opening_bal = 0;
                $transaction->debit_credit = 1;
                $transaction->amount = $row->amount;
                $transaction->username = Auth::user()->name;
                $transaction->status = 100;
                $transaction->voucher_type = 8;
                $transaction->save();
                $cogs_total += $row->amount;


                if ($cogs_total > 0):
                    $transaction = new Transactions();
                    $transaction = $transaction->SetConnection('mysql2');
                    $transaction->voucher_no = $gi_no;
                    $transaction->v_date = $request->gi_date;
                    $transaction->acc_id = $row->acc_id;
                    $transaction->acc_code = FinanceHelper::getAccountCodeByAccId($row->acc_id);
                    $transaction->particulars = $request->description;
                    $transaction->opening_bal = 0;
                    $transaction->debit_credit = 0;
                    $transaction->amount = $cogs_total;
                    $transaction->username = Auth::user()->name;;
                    $transaction->status = 100;
                    $transaction->voucher_type = 8;
                    $transaction->save();
                endif;
            endforeach;



            //DB::Connection('mysql2')->table('new_rvs')->where('so_id',$request->sales_order_id)->update(['ref_bill_no'=>$request->so_no]);
            //echo "SELECT a.id,a.rv_no,a.rv_date,a.buyer_id,SUM(b.amount) as totalAmount FROM `new_rvs` as a INNER JOIN new_rv_data as b on b.rv_no = a.rv_no WHERE a.ref_bill_no = '' and a.so_id = $request->sales_order_id GROUP BY a.rv_no";
            //die;
            $getPaidRvsList = DB::Connection('mysql2')->select("SELECT a.id,a.rv_no,a.rv_date,a.buyer_id,SUM(b.amount) as totalAmount FROM `new_rvs` as a INNER JOIN new_rv_data as b on b.rv_no = a.rv_no WHERE a.ref_bill_no = '' and a.so_id = $request->sales_order_id and debit_credit = 0 GROUP BY a.rv_no");

            //->table('new_rvs')->where('so_id',$request->sales_order_id)->where('ref_bill_no','=','')->get();
            foreach ($getPaidRvsList as $gprlRow) {
                $bData['si_id'] = $id;
                $bData['so_id'] = $request->sales_order_id;
                $bData['pos_id'] = 0;
                $bData['rv_id'] = $gprlRow->id;
                $bData['rv_no'] = $gprlRow->rv_no;
                $bData['received_amount'] = $gprlRow->totalAmount;
                $bData['tax_percent'] = 0;
                $bData['tax_amount'] = 0;
                $bData['discount_amount'] = 0;
                $bData['net_amount'] = $gprlRow->totalAmount;
                $bData['status'] = 1;
                $bData['type'] = 0;
                $bridgeTableId = DB::Connection('mysql2')->table('brige_table_sales_receipt')->insertGetId($bData);
                DB::Connection('mysql2')->table('new_rvs')->where('id', $gprlRow->id)->update(['ref_bill_no' => $gi_no]);

                DB::Connection('mysql2')->table('received_paymet')->insert(['sales_tax_invoice_id' => $id, 'receipt_id' => $gprlRow->id, 'receipt_no' => $gprlRow->rv_no, 'received_amount' => $gprlRow->totalAmount, 'slip_no' => '-']);
            }
            /*
        old code commit on 02-06-200
            $data= DB::Connection('mysql2')->select('select a.gi_no,a.description,a.gi_date,c.voucher_no,

                                                sum(c.amount)as amount
                                                from sales_tax_invoice as a
                                                inner join
                                                sales_tax_invoice_data as b
                                                on
                                                a.id=b.master_id
                                                inner join
                                                stock c
                                                on
                                                c.so_data_id=b.so_data_id
                                                where a.so_type=0
                                                and a.status=1
                                                and c.status=1
                                                and a.id="'.$id.'"
                                                group by c.voucher_no

                                                ');
        */
            // main Code

            //     $data=  DB::Connection('mysql2')->select('select a.id as main_id, a.gi_date,a.gi_no,b.*
            //         from sales_tax_invoice a
            //         INNER JOIN sales_tax_invoice_data b
            //         ON
            //         b.master_id = a.id
            //         WHERE  a.status = 1
            //         and a.so_type=0
            //         and a.id="'.$id.'"
            //         group by b.dn_data_ids');

            //     foreach($data as $row):


            //         $data=ReuseableCode::get_dn_no($row->dn_data_ids);
            //         $total_return=0;
            //         foreach($data as $row):
            //       $dataa=DB::Connection('mysql2')->table('delivery_note')->where('gd_no',$row->gd_no)->first();

            //       $return=  DB::Connection('mysql2')->selectOne('select a.cr_no,a.type
            //         from credit_note a
            //         inner join
            //         credit_note_data b
            //         on
            //         a.id=b.master_id

            //         where a.status=1
            //         and b.voucher_no="'.$row->gd_no.'" ');

            //             if (!empty($return->cr_no)):
            //                 echo 'Issue '.$return->cr_no.' '.$return->type;
            //                 $total_return+=    DB::Connection('mysql2')->table('stock')->where('status',1)->where('voucher_no',$return->cr_no)->sum('amount');
            //             endif;

            //      $dn_nos[]=$row->gd_no;
            //      endforeach;


            //         $on_dn=ReuseableCode::get_stock_amount_of_dn($dn_nos);
            //         $dn_actual=$on_dn - $total_return;
            //         $data1 =array
            //         (
            //             'acc_id'=>768,
            //             'v_date'=>$request->gi_date,
            //             'voucher_no'=>$gi_no,
            //             'voucher_type'=>8,
            //             'acc_code'=>'6-1',
            //             'particulars'=> $request->description,
            //             'opening_bal'=>0,
            //             'debit_credit'=>1,
            //             'amount'=>$dn_actual,
            //             'username'=>Auth::user()->name,
            //             'status'=>1,
            //             'date'=>date('Y-m-d'),
            //         );
            //            DB::Connection('mysql2')->table('transactions')->insert($data1);

            //         $data =array
            //         (
            //             'acc_id'=>97,
            //             'v_date'=>$request->gi_date,
            //             'voucher_no'=>$gi_no,
            //             'voucher_type'=>8,
            //             'acc_code'=>'1-2-1-1',
            //             'particulars'=> $request->description,
            //             'opening_bal'=>0,
            //             'debit_credit'=>0,
            //             'amount'=>$dn_actual,
            //             'username'=>Auth::user()->name,
            //             'status'=>1,
            //             'date'=>date('Y-m-d'),
            //         );
            //             DB::Connection('mysql2')->table('transactions')->insert($data);

            //     endforeach;


            // $data = DB::Connection('mysql2')->select('
            // select
            // b.dn_data_ids, b.gi_no,a.gi_date
            // FROM sales_tax_invoice_data b
            // INNER JOIN  sales_tax_invoice a ON b.master_id = a.id
            // WHERE   a.status = 1
            // and a.id="'.$id.'"
            // AND b.item_id != 0
            // group by b.dn_data_ids');

            //     $total_amountttt=0;
            //     foreach($data as $row):


            //         $data1 = DB::Connection('mysql2')->select('select * from stock where status = 1 and voucher_type = 5 and main_id in('.$row->dn_data_ids.')');
            //         $total_amount=0;
            //         foreach($data1 as $row1):
            //             $InsertData['main_id'] = $row1->main_id;
            //             $InsertData['master_id'] = 0;
            //             $InsertData['voucher_no'] = $row->gi_no;
            //             $InsertData['item_id'] = $row1->sub_item_id;
            //             $InsertData['qty'] = $row1->qty;
            //             $InsertData['amount'] = $row1->amount;
            //             $InsertData['opening'] = 0;
            //             $InsertData['status'] = 1;
            //             $InsertData['username'] = 'software';
            //             $InsertData['voucher_type'] = 3;
            //             $InsertData['voucher_date'] = $row->gi_date;
            //             $InsertData['ref_no'] = $row1->voucher_no;
            //             $InsertData['ref_date'] = $row1->voucher_date;
            //          DB::Connection('mysql2')->table('transaction_supply_chain')->insert($InsertData);
            //             $total_amountttt+=$row1->amount;
            //         endforeach;

            //             endforeach;




            SalesHelper::sales_activity($gi_no, $request->gi_date, $total_amount, 3, 'Insert');

            $voucher_no = $gi_no;
            // $dept_and_type = NotificationHelper::get_dept_id('sales_order', 'id', $request->sales_order_id)->select('department', 'p_type')->first();
            // $dept_id = $dept_and_type->department;
            // $p_type = $dept_and_type->p_type;;
            $subject = 'Sales Tax Invoice For ' . $request->so_no;
            // NotificationHelper::send_email('Sales tax Invoice', 'Create', $dept_id, $voucher_no, $subject, $p_type);

            DB::Connection('mysql2')->commit();
        } catch (Exception $ex) {

            DB::rollBack();
            dd($ex->getLine());
        }





        if ($SavePrintVal == 1) {
            $Url = url('sales/PrintSalesTaxInvoice?id=' . $id . 'pageType=' . Input::get('pageType') . '&&parentCode=' . Input::get('parentCode') . '&&m=' . $_GET['m'] . '#SFR');
            //echo "<script type='text/javascript'>window.open('".$Url."', '_blank')</script>";
            return Redirect::to($Url);
            return Redirect::to('sales/CreateSalesTaxInvoiceList?pageType=' . Input::get('pageType') . '&&parentCode=' . Input::get('parentCode') . '&&m=' . $_GET['m'] . '#SFR');
        } else {
            return Redirect::to('sales/CreateSalesTaxInvoiceList?pageType=' . Input::get('pageType') . '&&parentCode=' . Input::get('parentCode') . '&&m=' . $_GET['m'] . '#SFR');
        }
    }

    function updateSalesTaxInvoice(Request $request)
    {
        $sales_tax_invoice = new SalesTaxInvoice();
        $sales_tax_invoice = $sales_tax_invoice->SetConnection('mysql2');
        $sales_tax_invoice = $sales_tax_invoice->find($request->id);
        $sales_tax_invoice->gi_date = $request->gi_date;
        $sales_tax_invoice->acc_id = $request->acc_id;
        $sales_tax_invoice->description = $request->description;
        $sales_tax_invoice->save();

        
        return Redirect::to('sales/viewSalesTaxInvoiceList?pageType=' . Input::get('pageType') . '&&parentCode=' . Input::get('parentCode') . '&&m=' . $_GET['m'] . '#SFR');
    }

    function sales_tax_delete(Request $request)
    {
        CommonHelper::companyDatabaseConnection($_GET['m']);
        if ($request->id != ""):
            DB::table('sales_tax_invoice')
                ->where('id', $request->id)
                ->update(['status' => 0]);

            DB::table('sales_tax_invoice_data')
                ->where('master_id', $request->id)
                ->update(['status' => 0]);

            $dn_data_ids = DB::table('sales_tax_invoice_data')->where('master_id', $request->id)->select('dn_data_ids')->first();
            $dn_data_ids = explode(',', $dn_data_ids->dn_data_ids);

            $data['sales_tax_invoice'] = 0;
            $data['sales_tax_invoice_id'] = 0;
            $DeleteData['status'] = 0;
            DB::table('delivery_note')->whereIn('id', $dn_data_ids)->update($data);
            DB::Connection('mysql2')->table('transaction_supply_chain')->where('main_id', $request->id)->update($DeleteData);




            $gi = DB::table('sales_tax_invoice')
                ->where('id', $request->id)
                ->select('gi_no')->first()->gi_no;



            DB::table('transactions')
                ->where('voucher_no', $gi)
                ->update(['status' => 0]);

            DB::table('stock')
                ->where('voucher_no', $gi)
                ->update(['status' => 0]);
        endif;

        $type = "Sales Tax Invoice";
        SalesHelper::sales_activity($gi, date('Y-m-d'), 0, 3, 'Delete');
        \App\Helpers\CommonHelper::createNotification(
            $type . " with " . $gi . " is deleted by " . auth()->user()->username, 
            $type . ""
        );
        CommonHelper::reconnectMasterDatabase();
    }

    function delivery_note_delete(Request $request)
    {
        CommonHelper::companyDatabaseConnection($_GET['m']);
        if ($request->id != ""):
            DB::table('delivery_note')
                ->where('id', $request->id)
                ->update(['status' => 0]);

            DB::table('delivery_note_data')
                ->where('master_id', $request->id)
                ->update(['status' => 0]);
        endif;

        CommonHelper::reconnectMasterDatabase();

        return Redirect::to('sales/viewDeliveryNoteList?pageType=' . Input::get('pageType') . '&&parentCode=' . Input::get('parentCode') . '&&m=' . $_GET['m'] . '#SFR');
    }

    function sale_order_delete(Request $request)
    {
        CommonHelper::companyDatabaseConnection($_GET['m']);


        if ($request->id != ""):
            $count =  DB::table('delivery_note')->where('status', 1)->where('master_id', $request->id)->count();


            if ($count == 0):
                DB::table('sales_order')
                    ->where('id', $request->id)
                    ->update(['status' => 0]);

                DB::table('sales_order_data')
                    ->where('master_id', $request->id)
                    ->update(['status' => 0]);
                $So = DB::table('sales_order')->where('id', $request->id)->select('so_no', 'so_date')->first();
                SalesHelper::sales_activity($So->so_no, $So->so_date, '0', 1, 'Delete');

            else:
                echo '0';
            endif;

        endif;

        CommonHelper::reconnectMasterDatabase();

        //   return Redirect::to('sales/viewSalesOrderList?pageType='.Input::get('pageType').'&&parentCode='.Input::get('parentCode').'&&m='.$_GET['m'].'#SFR');
    }

    function delivery_not_delete(Request $request)
    {
        CommonHelper::companyDatabaseConnection($_GET['m']);


        if ($request->id != ""):
            $MasterId = DB::table('delivery_note')->where('id', $request->id)->select('master_id')->first()->master_id;
            $stid =  DB::table('sales_tax_invoice_data')->where('status', 1)
                ->where('so_id', $MasterId)->groupBy('dn_data_ids')->get();
            $Array = "";
            foreach ($stid as $fil):
                //$Array = explode(',',$fil->dn_data_ids);
                $Array .= $fil->dn_data_ids . ',';
            endforeach;
            $Array = rtrim($Array, ',');
            $Array = explode(',', $Array);

            if (in_array($request->id, $Array)) {
                echo '0';
            } else {
                DB::table('delivery_note')
                    ->where('id', $request->id)
                    ->update(['status' => 0]);

                DB::table('delivery_note_data')
                    ->where('master_id', $request->id)
                    ->update(['status' => 0]);

                DB::table('stock')
                    ->where('main_id', $request->id)
                    ->where('voucher_type', 5)
                    ->update(['status' => 0]);

                DB::table('sales_order')
                    ->where('id', $MasterId)
                    ->update(['delivery_note_status' => 0, 'status' => 2]);

                $Dn = DB::table('delivery_note')->where('id', $request->id)->select('gd_no', 'gd_date')->first();
                SalesHelper::sales_activity($Dn->gd_no, $Dn->gd_date, '0', 2, 'Delete');
            }
        //            die();
        //
        //            if ($count==0):
        //
        //            echo "yes";
        //
        //            else:
        //                echo "0";
        //            endif;

        endif;

        CommonHelper::reconnectMasterDatabase();

        //   return Redirect::to('sales/viewSalesOrderList?pageType='.Input::get('pageType').'&&parentCode='.Input::get('parentCode').'&&m='.$_GET['m'].'#SFR');
    }

    function addCreditNote(Request $request)
    {
        // dd($request->all());

        DB::Connection('mysql2')->beginTransaction();
        try {
            $cr_no = SalesHelper::generateCreditNotNo(date('y'), date('m'));
            $credit_note = new CreditNote();
            $credit_note = $credit_note->SetConnection('mysql2');
            $sale_order = Sales_Order::where("so_no", $request->so_id)->first();
            $credit_note->so_id = $sale_order->id;
            $credit_note->cr_no = $request->credit_not_no;
            $credit_note->cr_date = $request->credit_date;
            $credit_note->buyer_id = $request->byer_id;
            $credit_note->description = $request->description_1;
            $credit_note->sales_tax = CommonHelper::check_str_replace($request->sales_tax);
            $credit_note->sales_tax_further = CommonHelper::check_str_replace($request->sales_tax_further);
            $credit_note->create_date = date('Y-m-d');
            $credit_note->status = 1;
            $credit_note->type = $request->type;
            $credit_note->username = Auth::user()->name;
            $credit_note->save();
            $id = $credit_note->id;

            $sales_tax = CommonHelper::check_str_replace($request->sales_tax);
            if ($sales_tax == ''):
                $sales_tax = 0;
            endif;

            $sales_tax_further = $request->sales_tax_further;
            if ($sales_tax_further == ''):
                $sales_tax_further = 0;
            endif;
            $count = count($request->count);
            $total_amout = 0;




            for ($i = 1; $i <= $count; $i++):
                $credit_note_data = new CreditNoteData();
                $credit_note_data = $credit_note_data->SetConnection('mysql2');
                $credit_note_data->master_id = $id;
                $credit_note_data->voucher_data_id = $request->input('invoice_data_id' . $i);
                if ($request->type == 1):
                    $credit_note_data->so_data_id = $request->input('so_data_id' . $i);
                endif;
                $credit_note_data->so_data_id = $request->input('so_data_id' . $i);

                $credit_note_data->voucher_no = $request->input('gi_no' . $i);;
                $credit_note_data->voucher_date = $request->input('gi_date' . $i);;
                $credit_note_data->item = $request->input('item_id' . $i);;
                $credit_note_data->qty = CommonHelper::check_str_replace($request->input('qty' . $i));
                $credit_note_data->rate = CommonHelper::check_str_replace($request->input('rate' . $i));



                $credit_note_data->amount = CommonHelper::check_str_replace($request->input('amount' . $i));



                $credit_note_data->discount_percent = CommonHelper::check_str_replace($request->input('discount_percent' . $i));
                $credit_note_data->discount_amount = CommonHelper::check_str_replace($request->input('discount_amount' . $i));
                $credit_note_data->actual_discount_percent = $request->input('actual_discount_percent' . $i);
                $credit_note_data->actual_discount_amount = $request->input('actual_discount_amount' . $i); 
                $credit_note_data->net_amount = CommonHelper::check_str_replace($request->input('net_amount' . $i));
                $credit_note_data->batch_code = $request->input('batch_code' . $i);

                $credit_note_data->date = date("Y-m-d");
                $credit_note_data->type = $request->type;
                $credit_note_data->status = 1;
              $credit_note_data->warehouse_id= $request->input("warehouse" . $i);
              
                $credit_note_data->username = Auth::user()->name;
                $credit_note_data->save();
                $master_data_id = $credit_note_data->id;

                $amount = CommonHelper::check_str_replace($request->input('net_amount' . $i));
                $type =  CommonHelper::get_item_type($request->input('item_id' . $i));



                if ($type != 2):

                    if ($request->type == 1):
                        $amount_data = DB::Connection('mysql2')->table('stock')->where('status', 1)
                            ->where('voucher_no', $request->input('gi_no' . $i))
                            ->where('master_id', $request->input('invoice_data_id' . $i))
                            ->where('voucher_type', 5)
                            ->select('amount', 'qty')
                            ->first('amount');
                        
                        $rate = 0;
                        if($amount_data) {
                            $rate = ($amount_data->amount) / $amount_data->qty;
                        }
                    endif;


                    if ($request->type == 2):


                        $dn_data =   DB::Connection('mysql2')->table('delivery_note_data')->where('status', 1)->where('so_data_id', $request->input('so_data_id' . $i))->first();




                        $voucher_no = $dn_data->gd_no ?? '';

                        if ($request->input('so_data_id' . $i) == 0):

                            $dn_data =   DB::Connection('mysql2')->table('sales_tax_invoice_data')->where('status', 1)->where('id', $request->input('si_data_id' . $i))->first();
                            $voucher_no = $dn_data->gi_no;
                        endif;

            // dd($dn_data->so_data_id);

                        $amount_data = DB::Connection('mysql2')->table('stock')->where('status', 1)
                            ->where('voucher_no', $voucher_no)
                            ->where('so_data_id', $dn_data->so_data_id)
                            // ->where('master_id', $dn_data->id)
                            ->where('voucher_type', 5)
                            ->select('amount', 'qty','rate')
                            ->first('amount');


        

                        if (!$amount_data) {

                            dd("amouint is empty");
                        }

                        
                        $rate = $amount_data->rate;

                       
                    endif;
                    $stock = array(
                        'main_id' => $id,
                        'master_id' => $master_data_id,
                        'voucher_no' => $request->input('credit_not_no'),
                        'voucher_date' => $request->input('credit_date'),
                        'supplier_id' => 0,
                        'customer_id' => $request->buyers_id,
                        'batch_code' => $request->input('batch_code' . $i),
                        'voucher_type' => 6,
                        'rate' => CommonHelper::check_str_replace($request->input('rate' . $i)),
                        'sub_item_id' => $request->input('item_id' . $i),
                        'qty' => CommonHelper::check_str_replace($request->input('qty' . $i)),
                        'discount_percent' => CommonHelper::check_str_replace($request->input('discount_percent' . $i)),
                        'discount_amount' => CommonHelper::check_str_replace($request->input('discount_amount' . $i)),
                        'amount' => $rate * CommonHelper::check_str_replace($request->input('qty' . $i)),
                        'status' => 1,
                        'warehouse_id' => $request->input('warehouse' . $i),
                        'username' => Auth::user()->username,
                        'created_date' => date('Y-m-d'),
                        'created_date' => date('Y-m-d'),
                        'opening' => 0,
                    );

                    $total_amout += $amount;
                    DB::Connection('mysql2')->table('stock')->insert($stock);
                endif;
            endfor;

            $SRInsertedData = DB::Connection('mysql2')->select('select a.cr_no,b.* from credit_note a
                                            inner join credit_note_data b on b.master_id = a.id
                                            where a.id = ' . $id . '
                                            and a.type = 2');

            foreach ($SRInsertedData as $SRFil) {

                $InsertData['main_id'] = $SRFil->master_id;
                $InsertData['master_id'] = $SRFil->id;
                $InsertData['voucher_no'] = $SRFil->cr_no;
                $InsertData['item_id'] = $SRFil->item;
                $InsertData['qty'] = $SRFil->qty;
                $InsertData['amount'] = $SRFil->net_amount;
                $InsertData['opening'] = 0;
                $InsertData['status'] = 1;
                $InsertData['username'] = Auth::user()->name;
                $InsertData['voucher_type'] = 4;
                //      DB::Connection('mysql2')->table('transaction_supply_chain')->insert($InsertData);

            }



            if ($request->type == 2):
                // DB::rollBack();
                // dd('in');
                $data =   DB::Connection('mysql2')->select(
                    'select sum(a.amount) as amount,d.acc_id from credit_note_data as a
                    inner join
                    subitem as c
                    on
                    c.id=a.item
                    inner join
                    category as d
                    on
                    c.main_ic_id=d.id
                    where a.status=1

                    and a.master_id="' . $id . '"
                    group by d.id'

                );
                $total_amount = 0;

                
                foreach ($data as $row):
                    $transaction = new Transactions();
                    $transaction = $transaction->SetConnection('mysql2');
                    $transaction->voucher_no = $request->input('credit_not_no');
                    $transaction->v_date = $request->input('credit_date');
                    $transaction->acc_id = $row->acc_id;
                    $transaction->acc_code = FinanceHelper::getAccountCodeByAccId($row->acc_id);
                    $transaction->particulars = $request->description_1;
                    $transaction->opening_bal = 0;
                    $transaction->debit_credit = 1;
                    $transaction->amount = $row->amount;
                    $transaction->username = Auth::user()->name;;
                    $transaction->status = 1;
                    $transaction->voucher_type = 7;
                    $transaction->save();
                    $total_amount += $row->amount;
                endforeach;

                $sales_tax = DB::Connection('mysql2')->table('credit_note_data')->where('status', 1)->where('master_id', $id);
                $sales_tax_amount = $sales_tax->sum('discount_amount');


                if ($sales_tax_amount > 0):

                    $sales_tac_acc_id = ReuseableCode::sales_tax_acc_id($sales_tax->value("discount_percent"));
                    $transaction = new Transactions();
                    $transaction = $transaction->SetConnection('mysql2');
                    $transaction->voucher_no = $request->input('credit_not_no');
                    $transaction->v_date = $request->input('credit_date');
                    $transaction->acc_id = $sales_tac_acc_id->acc_id;
                    $transaction->acc_code = FinanceHelper::getAccountCodeByAccId($sales_tac_acc_id->acc_id);
                    $transaction->particulars = $request->description_1;
                    $transaction->opening_bal = 0;
                    $transaction->debit_credit = 1;
                    $transaction->amount = $sales_tax_amount;
                    $transaction->username = Auth::user()->name;;
                    $transaction->status = 1;
                    $transaction->voucher_type = 7;
                    $transaction->save();
                    $total_amount += $sales_tax_amount;

                endif;





                $sales_tax_further = CommonHelper::check_str_replace($request->sales_tax_further);

                if ($sales_tax_further > 0):


                    $sales_tac_acc_id_further = DB::Connection('mysql2')->table('accounts')->where('status', 1)->where('name', '3% Additional Sales Tax')->select('id')->first()->id;

                    $transaction = new Transactions();
                    $transaction = $transaction->SetConnection('mysql2');
                    $transaction->voucher_no = $request->input('credit_not_no');
                    $transaction->v_date = $request->input('credit_date');
                    $transaction->acc_id = $sales_tac_acc_id_further;
                    $transaction->acc_code = FinanceHelper::getAccountCodeByAccId($sales_tac_acc_id_further);
                    $transaction->particulars = $request->description_1;
                    $transaction->opening_bal = 0;
                    $transaction->debit_credit = 0;
                    $transaction->amount = $request->sales_tax_further;
                    $transaction->username = Auth::user()->name;;
                    $transaction->status = 1;
                    $transaction->voucher_type = 7;
                    $transaction->save();
                //  $total_amout+=$request->sales_tax_further;

                endif;
                $customer_acc_id = SalesHelper::get_customer_acc_id($request->byer_id);;
                $transaction = new Transactions();
                $transaction = $transaction->SetConnection('mysql2');
                $transaction->voucher_no = $request->input('credit_not_no');
                $transaction->v_date = $request->input('credit_date');
                $transaction->acc_id = $customer_acc_id;
                $transaction->acc_code = FinanceHelper::getAccountCodeByAccId($customer_acc_id);
                $transaction->particulars = $request->description_1;
                $transaction->opening_bal = 0;
                $transaction->debit_credit = 0;
                $transaction->amount = $total_amount;
                $transaction->username = Auth::user()->name;;
                $transaction->status = 1;
                $transaction->voucher_type = 7;
                $transaction->save();


            endif;
            // DB::rollBack();
            // dd('out');

            if ($request->type == 2):
               
                $data_collection = DB::Connection('mysql2')->table('stock')->where('voucher_no', $request->input('credit_not_no'))->where('status', 1);
                $data = $data_collection->first();
                $amount = $data_collection->sum('amount');

                $transaction = new Transactions();
                $transaction = $transaction->SetConnection('mysql2');
                $transaction->voucher_no = $cr_no;
                $transaction->v_date = $request->input('credit_date');
                $transaction->acc_id = 97;
                $transaction->acc_code = '1-2-1-1';
                $transaction->particulars = $request->description_1;
                $transaction->opening_bal = 0;
                $transaction->debit_credit = 1;
                $transaction->amount = $amount;
                $transaction->username = Auth::user()->name;;
                $transaction->status = 1;
                $transaction->voucher_type = 9;
                //    $transaction->save();


                $transaction = new Transactions();
                $transaction = $transaction->SetConnection('mysql2');
                $transaction->voucher_no = $cr_no;
                $transaction->v_date = $request->input('credit_date');
                $transaction->acc_id = 768;
                $transaction->acc_code = '6-1';
                $transaction->particulars = $request->description_1;
                $transaction->opening_bal = 0;
                $transaction->debit_credit = 0;
                $transaction->amount = $amount;
                $transaction->username = Auth::user()->name;;
                $transaction->status = 1;
                $transaction->voucher_type = 9;
            //    $transaction->save();


            endif;


            $type = "Sale Return";
            \App\Helpers\CommonHelper::createNotification(
                $type . " with Sale Order of " . $sale_order->so_no . " is created by " . auth()->user()->name, 
                $type . ""
            );

            DB::Connection('mysql2')->commit();
        } catch (Exception $ex) {

            DB::rollBack();
        }

        return Redirect::to('sales/viewCustomerCreditNoteList?pageType=view&&parentCode=000&&m=' . $_GET['m'] . '#SFR');
    }

    public function updateCreditNote(Request $request)
    {

        $cr_no = $request->cr_no;
        // update credit note table start
        $credit_note = new CreditNote();
        $credit_note = $credit_note->setConnection('mysql2');
        $credit_note = $credit_note->where('cr_no', $cr_no)->first();
        $credit_note->cr_date = $request->credit_date;
        $credit_note->description = $request->description_1;
        $credit_note->save();
        // update credit note table end
        $id = $credit_note->id;


        // update stock and credit note data start
        foreach ($request->cr_data_ids as $key => $item) {
            $key++;
            $credit_note_data = new CreditNoteData();
            $credit_note_data = $credit_note_data->setConnection('mysql2');
            $credit_note_data = $credit_note_data->find($item);
            $credit_note_data->qty = $request->input("qty" . $key);
            $credit_note_data->amount = $request->input("amount" . $key);
            $credit_note_data->discount_amount = $request->input("discount_amount" . $key);
            $credit_note_data->net_amount = $request->input("net_amount" . $key);
            $credit_note_data->save();
            // updating credit note data amount and qty in stock table
            $stockTable = new Stock();
            $stockTable = $stockTable->setConnection('mysql2');
            $stockTable = $stockTable->where('main_id', $id)->where('master_id', $credit_note_data->id)->where('voucher_no', $cr_no)->first();
            $stockTable->qty = $request->input("qty" . $key);
            $stockTable->amount = $request->input("net_amount" . $key);
            $stockTable->save();
        }


        // update stock and credit note data end
        if ($request->type == 2):

            // delete old transactions
            $transaction = new Transactions();
            $transaction = $transaction->SetConnection('mysql2')
                ->where('voucher_no', $cr_no)
                ->delete();
            $data =   DB::Connection('mysql2')->select(
                'select sum(a.amount) as amount,d.acc_id from credit_note_data as a
                inner join
                subitem as c
                on
                c.id=a.item
                inner join
                category as d
                on
                c.main_ic_id=d.id
                where a.status=1

                and a.master_id="' . $id . '"
                group by d.id'

            );
            // adding new transaction for type 2 -- Sales taxt invoice si*******
            $total_amount = 0;
            foreach ($data as $row):



                $transaction = new Transactions();
                $transaction = $transaction->SetConnection('mysql2');
                $transaction->voucher_no = $cr_no;
                $transaction->v_date = $request->input('credit_date');
                $transaction->acc_id = $row->acc_id;
                $transaction->acc_code = FinanceHelper::getAccountCodeByAccId($row->acc_id);
                $transaction->particulars = $request->description_1;
                $transaction->opening_bal = 0;
                $transaction->debit_credit = 1;
                $transaction->amount = $row->amount;
                $transaction->username = Auth::user()->name;;
                $transaction->status = 1;
                $transaction->voucher_type = 7;
                $transaction->save();
                $total_amount += $row->amount;
            endforeach;



            $sales_tax = DB::Connection('mysql2')->table('credit_note_data')->where('status', 1)->where('master_id', $id);
            $sales_tax_amount = $sales_tax->sum('discount_amount');


            // if ($sales_tax_amount > 0):

            //     $sales_tac_acc_id = ReuseableCode::invoice_tax_acc_id($sales_tax->value('discount_percent'));
            //     // if (!$sales_tac_acc_id) {
            //     //     dd("issue with sales_tac_acc_id is empty!");
            //     // }
            //     $transaction = new Transactions();
            //     $transaction = $transaction->SetConnection('mysql2');
            //     $transaction->voucher_no = $cr_no;
            //     $transaction->v_date = $request->input('credit_date');
            //     $transaction->acc_id = $sales_tac_acc_id;
            //     $transaction->acc_code = FinanceHelper::getAccountCodeByAccId($sales_tac_acc_id);
            //     $transaction->particulars = $request->description_1;
            //     $transaction->opening_bal = 0;
            //     $transaction->debit_credit = 1;
            //     $transaction->amount = $sales_tax_amount;
            //     $transaction->username = Auth::user()->name;;
            //     $transaction->status = 1;
            //     $transaction->voucher_type = 7;
            //     $transaction->save();
            //     $total_amount += $sales_tax_amount;

            // endif;

            $sales_tax_further = CommonHelper::check_str_replace($request->sales_tax_further);

            if ($sales_tax_further > 0):


                $sales_tac_acc_id_further = DB::Connection('mysql2')->table('accounts')->where('status', 1)->where('name', '3% Additional Sales Tax')->select('id')->first()->id;

                $transaction = new Transactions();
                $transaction = $transaction->SetConnection('mysql2');
                $transaction->voucher_no = $cr_no;
                $transaction->v_date = $request->input('credit_date');
                $transaction->acc_id = $sales_tac_acc_id_further;
                $transaction->acc_code = FinanceHelper::getAccountCodeByAccId($sales_tac_acc_id_further);
                $transaction->particulars = $request->description_1;
                $transaction->opening_bal = 0;
                $transaction->debit_credit = 0;
                $transaction->amount = $request->sales_tax_further;
                $transaction->username = Auth::user()->name;;
                $transaction->status = 1;
                $transaction->voucher_type = 7;
                $transaction->save();
            //  $total_amout+=$request->sales_tax_further;

            endif;
            $customer_acc_id = SalesHelper::get_customer_acc_id($request->buyer_id);;
            $transaction = new Transactions();
            $transaction = $transaction->SetConnection('mysql2');
            $transaction->voucher_no = $cr_no;
            $transaction->v_date = $request->input('credit_date');
            $transaction->acc_id = $customer_acc_id;
            $transaction->acc_code = FinanceHelper::getAccountCodeByAccId($customer_acc_id);
            $transaction->particulars = $request->description_1;
            $transaction->opening_bal = 0;
            $transaction->debit_credit = 0;
            $transaction->amount = $total_amount;
            $transaction->username = Auth::user()->name;;
            $transaction->status = 1;
            $transaction->voucher_type = 7;
            $transaction->save();


        endif;
        $type = "Sale Return";
        \App\Helpers\CommonHelper::createNotification(
            $type . " with " . $cr_no. " is edited by " . auth()->user()->name, 
            $type . ""
        );
      return Redirect::to('sales/viewCustomerCreditNoteList?pageType=view&&parentCode=000&&m=' . $_GET['m'] . '#SFR');
    }

    function addType(Request $request)
    {
        $type = new Type();
        $type = $type->SetConnection('mysql2');
        $type->name        = $request->type_name;
        $type->status           = 1;
        $type->username         = Auth::user()->name;
        $type->date             = date('Y-m-d');
        $type->save();

        return Redirect::to('sales/typeList?pageType=view&&parentCode=000&&m=' . Input::get('m') . '#SFR');
    }

    function addCondition(Request $request)
    {
        $condition = new Conditions();
        $condition = $condition->SetConnection('mysql2');
        $condition->name        = $request->condition_name;
        $condition->status           = 1;
        $condition->username         = Auth::user()->name;
        $condition->date             = date('Y-m-d');
        $condition->save();

        return Redirect::to('sales/conditionList?pageType=view&&parentCode=000&&m=' . Input::get('m') . '#SFR');
    }

    function updateCondition(Request $request)
    {
        CommonHelper::companyDatabaseConnection($request->CompanyId);

        $ConditionData['name']        = $request->condition_name;
        DB::table('conditions')->where('condition_id', '=', $request->condition_id)->update($ConditionData);
        CommonHelper::reconnectMasterDatabase();
        return Redirect::to('sales/conditionList?pageType=view&&parentCode=000&&m=' . Input::get('m') . '#SFR');
    }

    function updateProductType(Request $request)
    {
        CommonHelper::companyDatabaseConnection($request->CompanyId);
        $data['type']        = $request->product_type;
        DB::table('product_type')->where('product_type_id', '=', $request->product_type_id)->update($data);
        CommonHelper::reconnectMasterDatabase();
        return Redirect::to('sales/producttypeList?pageType=view&&parentCode=000&&m=' . Input::get('m') . '#SFR');
    }
    function updateProductTrend(Request $request)
    {
        // CommonHelper::companyDatabaseConnection($request->CompanyId);
        $data['name']        = $request->product_trend;
        $productTrenObj = new ProductTrend();
        $productTrenObj->where('id', '=', $request->id)->update($data);
        // CommonHelper::reconnectMasterDatabase();
        return Redirect::to('sales/productTrendList?pageType=view&&parentCode=000&&m=' . Input::get('m') . '#SFR');
    }
    function updateProductClassification(Request $request)
    {
        // CommonHelper::companyDatabaseConnection($request->CompanyId);
        $data['name']        = $request->product_classification;
        $productClassificationObj = new ProductClassification();
        $productClassificationObj->where('id', '=', $request->id)->update($data);
        // CommonHelper::reconnectMasterDatabase();
        return Redirect::to('sales/productClassificationList?pageType=view&&parentCode=000&&m=' . Input::get('m') . '#SFR');
    }

    function updateResourceAssigned(Request $request)
    {
        CommonHelper::companyDatabaseConnection($request->CompanyId);
        $data['resource_type']        = $request->resource_type;
        DB::table('resource_assign')->where('id', '=', $request->id)->update($data);
        CommonHelper::reconnectMasterDatabase();
        return Redirect::to('sales/resourceAssignedList?pageType=view&&parentCode=000&&m=' . Input::get('m') . '#SFR');
    }

    function updateSurveyByForm(Request $request)
    {
        CommonHelper::companyDatabaseConnection($request->CompanyId);
        $data['name']        = $request->name;
        $data['remarks']     = $request->remarks;
        DB::table('survey_by')->where('id', '=', $request->id)->update($data);
        CommonHelper::reconnectMasterDatabase();
        return Redirect::to('sales/branchList?pageType=view&&parentCode=000&&m=' . Input::get('m') . '#SFR');
    }

    function updateTypeList(Request $request)
    {
        CommonHelper::companyDatabaseConnection($request->CompanyId);
        $data['name']        = $request->name;
        DB::table('type')->where('type_id', '=', $request->id)->update($data);
        CommonHelper::reconnectMasterDatabase();
        return Redirect::to('sales/typeList?pageType=view&&parentCode=000&&m=' . Input::get('m') . '#SFR');
    }

    function updateProductForm(Request $request)
    {

        $product['p_name']        = $request->product_name;
        $product['type_id']        = $request->type_id;
        DB::Connection('mysql2')->table('product')->where('product_id', '=', $request->product_id)->update($product);

        $acc_id = DB::Connection('mysql2')->table('product')->where('product_id', '=', $request->product_id)->select('acc_id')->first();
        $acc_id = $acc_id->acc_id;

        $data['name'] = $request->product_name;
        $acc_id = DB::Connection('mysql2')->table('accounts')->where('id', '=', $acc_id)->update($data);

        return Redirect::to('purchase/viewProduct?pageType=' . Input::get('pageType') . '&&parentCode=' . Input::get('parentCode') . '&&m=' . Input::get('m') . '#SFR');
    }

    function updateClientForm(Request $request)
    {
        CommonHelper::companyDatabaseConnection($request->CompanyId);
        $client['client_name']    = $request->client_name;
        $client['ntn']            = $request->ntn;
        $client['strn']           = $request->strn;
        $client['address']        = $request->address;
        if ($request->AccId != 0):
            $UpdateAcc['name'] = $request->client_name;
            DB::table('accounts')->where('id', '=', $request->AccId)->update($UpdateAcc);
        endif;
        DB::table('client')->where('id', '=', $request->ClientId)->update($client);
        CommonHelper::reconnectMasterDatabase();
        return Redirect::to('sales/clientList?pageType=&&parentCode=105&&m=' . Input::get('m') . '#SFR');
    }

    function updateClientBranchForm(Request $request)
    {
        CommonHelper::companyDatabaseConnection($request->CompanyId);
        $client['client_id']    = $request->client_id;
        $client['branch_name']    = $request->branch_name;
        $client['ntn']            = $request->ntn;
        $client['strn']           = $request->strn;
        $client['address']        = $request->address;
        if ($request->AccId != 0):
            $UpdateAcc['name'] = $request->client_name;
            DB::table('accounts')->where('id', '=', $request->AccId)->update($UpdateAcc);
        endif;
        DB::table('branch')->where('id', '=', $request->BranchId)->update($client);
        CommonHelper::reconnectMasterDatabase();
        return Redirect::to('sales/clientBranchList?pageType=&&parentCode=105&&m=' . Input::get('m') . '#SFR');
    }







    function addBranch(Request $request)
    {
        $survery_by = new SurveryBy();
        $survery_by = $survery_by->SetConnection('mysql2');
        $survery_by->name        = $request->branch_name;
        $survery_by->remarks        = $request->remarks;
        $survery_by->status           = 1;
        $survery_by->username         = Auth::user()->name;
        $survery_by->date             = date('Y-m-d');
        $survery_by->save();

        return Redirect::to('sales/branchList?pageType=view&&parentCode=000&&m=' . Input::get('m') . '#SFR');
    }

    function addClientDetail(Request $request)
    {
        $client = new Client();
        $client = $client->SetConnection('mysql2');
        $client->client_name    = $request->client_name;
        $client->ntn            = $request->ntn;
        $client->strn           = $request->strn;
        $client->address        = $request->address;
        $client->status         = 1;
        $client->username       = Auth::user()->name;
        $client->save();

        return Redirect::to('sales/clientList?pageType=&&parentCode=105&&m=' . Input::get('m') . '#SFR');
    }
    function addBranchDetail(Request $request)
    {
        $Branch = new Branch();
        $Branch = $Branch->SetConnection('mysql2');
        $Branch->client_id    = "";
        $Branch->branch_name    = $request->branch_name;
        $Branch->ntn            = "";
        $Branch->strn           = "";
        $Branch->address        = "";
        $Branch->status         = 1;
        $Branch->username       = Auth::user()->name;
        $Branch->save();

        return Redirect::to('sales/branchList?pageType=&&parentCode=105&&m=' . Input::get('m') . '#SFR');
    }
    function insertBranchAjax(Request $request)
    {
        $Branch = new Branch();
        $Branch = $Branch->SetConnection('mysql2');
        $Branch->client_id    = $request->client_id;
        $Branch->branch_name    = $request->branch_name;
        $Branch->ntn            = $request->ntn;
        $Branch->strn           = $request->strn;
        $Branch->address        = $request->address;
        $Branch->status         = 1;
        $Branch->username       = Auth::user()->name;
        $Branch->save();

        echo 'success';
    }




    function insertInvoiceDesc(Request $request)
    {
        $InvDesc = new InvDesc();
        $InvDesc = $InvDesc->SetConnection('mysql2');
        $InvDesc->invoice_desc    = $request->InvoiceDesc;
        $InvDesc->status         = 1;
        $InvDesc->username       = Auth::user()->name;
        $InvDesc->save();

        return Redirect::to('sales/clientList?pageType=&&parentCode=105&&m=' . Input::get('m') . '#SFR');
    }



    function addClientJob(Request $request)
    {
        //print_r($_POST); die();
        $ClientJob = new ClientJob();
        $ClientJob = $ClientJob->SetConnection('mysql2');
        $ClientJob->client_job    = $request->ClientJob;
        $ClientJob->status    = 1;
        $ClientJob->save();

        return Redirect::to('sales/addClientJob?pageType=&&parentCode=115&&m=' . Input::get('m') . '#SFR');
    }

    function addClientJobGET(Request $request)
    {
        $ClientJob = new ClientJob();
        $ClientJob = $ClientJob->SetConnection('mysql2');
        $ClientJob->client_job    = $request->ClientJob;
        $ClientJob->status    = 1;
        $ClientJob->save();
        $id = $ClientJob->id;
        echo $data = $id . "," . $request->ClientJob;
    }

    function addProductType(Request $request)
    {
        $productType = new  ProductType();
        $productType = $productType->SetConnection('mysql2');
        $productType->type        = $request->product_type;
        $productType->status      = 1;
        $productType->date        = date('Y-m-d');
        $productType->username    = Auth::user()->name;
        $productType->save();

        return Redirect::to('sales/producttypeList?pageType=&&parentCode=105&&m=' . Input::get('m') . '#SFR');
    }
    function addProductTrend(Request $request)
    {
        $productType = new  ProductTrend();
        $productType = $productType->SetConnection('mysql2');
        $productType->name        = $request->product_trend;
        $productType->status      = 1;
        $productType->save();

        return Redirect::to('sales/productTrendList?pageType=&&parentCode=105&&m=' . Input::get('m') . '#SFR');
    }
    function addProductClassification(Request $request)
    {
        $productType = new  ProductClassification();
        $productType = $productType->SetConnection('mysql2');
        $productType->name        = $request->product_classification;
        $productType->status      = 1;
        $productType->save();

        return Redirect::to('sales/productClassificationList?pageType=&&parentCode=105&&m=' . Input::get('m') . '#SFR');
    }

    function addResourceAssign(Request $request)
    {
        $resource = new  ResourceAssigned();
        $resource = $resource->SetConnection('mysql2');
        $resource->resource_type = $request->resource_type;
        $resource->status        = 1;
        $resource->date          = date('Y-m-d');
        $resource->username      = Auth::user()->name;
        $resource->save();

        return Redirect::to('sales/createResourceAssigned?pageType=&&parentCode=105&&m=' . Input::get('m') . '#SFR');
    }

    function updateQuotation(Request $request)
    {

        $EditId = $request->editId;

        $quotation = new Quotation();
        $quotation = $quotation->SetConnection('mysql2');
        $quotation = $quotation->find($EditId);
        $quotation->quotation_no = $request->quotation_no;
        if ($request->type == 1) {
            $quotation->tracking_no = $request->job_tracking_no;
            $quotation->survey_id = $request->tracking_no;
        }
        $quotation->client_id = $request->client_id;
        $quotation->branch_id = $request->branch_id;
        $quotation->quotation_to = $request->quotation_to;
        $quotation->designation = $request->designation;
        $quotation->surey_date = $request->survey_date;
        $quotation->quotation_date = $request->quotation_date;
        $quotation->reevived_date = $request->reviewed_date;
        $quotation->subject = $request->subject;
        $quotation->other_terms_conditions = $request->terms;
        $quotation->type = $request->type;
        $quotation->discount_percent = CommonHelper::check_str_replace($request->discount_percent);
        $quotation->discount_amount = CommonHelper::check_str_replace($request->discount_amount);
        $quotation->sales_tax_percent = CommonHelper::check_str_replace($request->sales_tax_percent);
        $quotation->sales_tax_amount = CommonHelper::check_str_replace($request->sales_tax_amount);
        $quotation->region_id = $request->region_id;
        $quotation->status = 1;
        $quotation->date = date('Y-m-d');
        $quotation->username = Auth::user()->name;
        $quotation->save();

        $data = $request->product_id;
        $count = 0;
        foreach ($data as $row):
            $quotation_id = $request->input('quotation_id');
            $quotation_edit_id = $quotation_id[$count];

            $quotation_data = new Quotation_Data();
            $quotation_data = $quotation_data->SetConnection('mysql2');
            if ($quotation_edit_id != 0) {
                $quotation_data = $quotation_data->find($quotation_edit_id);
            }
            $survey_data_id = $request->input('survey_data_id');
            $product_id     = $request->input('product_id');
            $description    = $request->input('descr');
            $height         = $request->input('height');
            $width          = $request->input('width');
            $uom            = $request->input('uom');
            $qty            = $request->input('qty');
            $rate           = $request->input('rate');
            $amount         = $request->input('amount');

            $quotation_data->master_id = $EditId;
            if ($request->type == 1) {
                $quotation_data->survey_main_id = $request->tracking_no;
            }
            $quotation_data->survey_data_id = $survey_data_id[$count];
            $quotation_data->product_id = $product_id[$count];
            $quotation_data->description = $description[$count];
            $quotation_data->height = $height[$count];
            $quotation_data->width = $width[$count];
            $quotation_data->uom_id = $uom[$count];
            $quotation_data->qty = CommonHelper::check_str_replace($qty[$count]);
            $quotation_data->rate = CommonHelper::check_str_replace($rate[$count]);
            $quotation_data->total_cost = CommonHelper::check_str_replace($amount[$count]);
            $quotation_data->username = Auth::user()->name;
            $quotation_data->status = 1;
            $quotation_data->save();
            $count++;
        endforeach;

        return Redirect::to('sales/quotationList?pageType=&&parentCode=105&&m=' . Input::get('m') . '#SFR');
    }

    function addQuotation(Request $request)
    {
        $quotation = new Quotation();
        $quotation = $quotation->SetConnection('mysql2');
        $quotation->quotation_no = $request->quotation_no;
        $quotation->tracking_no = $request->job_tracking_no;
        $quotation->survey_id = $request->tracking_no;
        $quotation->client_id = $request->client_id;
        $quotation->branch_id = $request->branch_id;
        $quotation->quotation_to = $request->quotation_to;
        $quotation->designation = $request->designation;
        $quotation->surey_date = $request->survey_date;
        $quotation->quotation_date = $request->quotation_date;
        $quotation->reevived_date = $request->reviewed_date;
        $quotation->subject = $request->subject;
        $quotation->other_terms_conditions = $request->terms;
        $quotation->type = $request->type;

        $quotation->discount_percent = CommonHelper::check_str_replace($request->discount_percent);
        $quotation->discount_amount = CommonHelper::check_str_replace($request->discount_amount);
        $quotation->sales_tax_percent = CommonHelper::check_str_replace($request->sales_tax_percent);
        $quotation->sales_tax_amount = CommonHelper::check_str_replace($request->sales_tax_amount);

        $quotation->region_id = $request->region_id;

        $quotation->status = 1;
        $quotation->date = date('Y-m-d');
        $quotation->username = Auth::user()->name;
        $quotation->save();
        $master_id = $quotation->id;

        $data = $request->product_id;
        $count = 0;
        foreach ($data as $row):



            $quotation_data = new Quotation_Data();
            $quotation_data = $quotation_data->SetConnection('mysql2');

            $survey_data_id = $request->input('survey_data_id');
            $product_id = $request->input('product_id');
            $description = $request->input('descr');
            $height = $request->input('height');
            $width = $request->input('width');
            $uom = $request->input('uom');
            $qty = $request->input('qty');
            $rate = $request->input('rate');
            $amount = $request->input('amount');


            $quotation_data->master_id = $master_id;
            $quotation_data->survey_main_id = $request->tracking_no;
            $quotation_data->survey_data_id = $survey_data_id[$count];
            $quotation_data->product_id = $product_id[$count];
            $quotation_data->description = $description[$count];
            $quotation_data->height = $height[$count];
            $quotation_data->width = $width[$count];
            $quotation_data->uom_id = $uom[$count];
            $quotation_data->qty = CommonHelper::check_str_replace($qty[$count]);
            $quotation_data->rate = CommonHelper::check_str_replace($rate[$count]);
            $quotation_data->total_cost = CommonHelper::check_str_replace($amount[$count]);
            $quotation_data->username = Auth::user()->name;
            $quotation_data->status = 1;
            $quotation_data->save();
            $count++;
        endforeach;

        if ($quotation->tracking_no != ''):
            $survery = new Survey();
            $survery = $survery->SetConnection('mysql2');
            $survery = $survery->find($request->tracking_no);
            $survery->quotation_type = 1;
            $survery->save();
        endif;

        $voucher_no     = $request->quotation_no;
        $voucher_date   = $request->quotation_date;
        $action_type    = 1;
        $client_id      = $request->client_id;
        $table_name     = "quotation";

        CommonHelper::logActivity($voucher_no, $voucher_date, $action_type, $client_id, $table_name);

        return Redirect::to('sales/quotationList?pageType=&&parentCode=105&&m=' . Input::get('m') . '#SFR');
    }

    function addComplaintDetail(Request $request)
    {
        //            echo "<pre>";
        //            print_r($_POST);die;
        $complaint = new Complaint();
        $complaint = $complaint->SetConnection('mysql2');
        $complaint->client_name         = $request->ClientId;
        $complaint->region_id         = $request->RegionId;
        $complaint->branch_name         = $request->BranchName;
        $complaint->branch_code         = $request->BranchCode;
        $complaint->date                = $request->ComplaintDate;
        $complaint->contanct_person     = $request->ContactPersonName;
        $complaint->designation         = $request->Designation;
        $complaint->phone               = $request->Phone;
        $complaint->address             = $request->Address;
        if ($request->Monthly == 1):
            $complaint->monthly             = $request->Monthly;
        elseif ($request->Quaterly == 1):
            $complaint->Quaterly            = $request->Quaterly;
        elseif ($request->SemiAnnually == 1):
            $complaint->Semi_Annually       = $request->SemiAnnually;
        elseif ($request->Annually == 1):
            $complaint->Annually            = $request->Annually;
        elseif ($request->OnCall == 1):
            $complaint->On_Call             = $request->OnCall;
        endif;

        $complaint->board_cleaning      = $request->BoardCleaning;
        $complaint->led_stip            = $request->LedStrip;
        $complaint->led_wiring          = $request->LedWiring;
        $complaint->led_rope            = $request->LedRope;
        $complaint->power_supply        = $request->PowerSupply;
        $complaint->sign_note           = $request->sign_note;
        $complaint->auto_manual         = $request->AutoManualSelector;
        $complaint->contractor          = $request->Contractor;
        $complaint->breaker             = $request->Breaker;
        $complaint->sun_switch            = $request->SunSwitch;
        $complaint->volt_led            = $request->VoltLed;
        $complaint->stabilizer          = $request->StabilizerLightingDevice;
        $complaint->note                = $request->Note;
        $complaint->timer_connection    = $request->timer_connection;
        $complaint->breaker_replaced    = $request->breaker_replaced;
        $complaint->wiring_additional   = $request->wiring_additional;
        $complaint->rft                 = $request->Rft;
        $complaint->comments            = $request->comments;
        $complaint->status              = 1;
        $complaint->created_date        = date('Y-m-d');
        $complaint->username            = Auth::user()->name;
        $complaint->save();
        $master_id = $complaint->id;

        $ProductIds = $request->ProductId;
        foreach ($ProductIds as $key => $row) {
            $ComplaintProduct = new ComplaintProduct();
            $ComplaintProduct = $ComplaintProduct->SetConnection('mysql2');
            $ComplaintProduct->product      = $request->ProductId[$key];
            $ComplaintProduct->front        = $request->Front[$key];
            $ComplaintProduct->p_left       = $request->Left[$key];
            $ComplaintProduct->p_right      = $request->Right[$key];
            $ComplaintProduct->back         = $request->Back[$key];
            $ComplaintProduct->complaint_id = $master_id;
            $ComplaintProduct->status       = 1;
            $ComplaintProduct->username     = Auth::user()->name;
            $ComplaintProduct->date         = date('Y-m-d');
            $ComplaintProduct->save();
        }
        $ImageCounter = $request->ImageCounter;
        foreach ($ImageCounter as $row):
            if ($request->file('input_img_' . $row)):
                $file_name = $master_id . '' . $row . 'complaint' . time() . '.' . $request->file('input_img_' . $row)->extension();
                $path = $request->file('input_img_' . $row)->storeAs('uploads/products', $file_name);
            else:
                $path = '';
            endif;

            $ComplaintDocument = new ComplaintDocument();
            $ComplaintDocument = $ComplaintDocument->SetConnection('mysql2');
            $ComplaintDocument->image_file   = $path;
            $ComplaintDocument->complaint_id = $master_id;
            $ComplaintDocument->status       = 1;
            $ComplaintDocument->save();
        endforeach;

        return Redirect::to('sales/addComplaint?pageType=&&parentCode=105&&m=' . Input::get('m') . '#SFR');
    }

    function addInvoiceDetail(Request $request)
    {
        //print_r($_POST); die;

        DB::Connection('mysql2')->beginTransaction();

        try {
            $InvNo = SalesHelper::get_unique_no_inv(date('y'), date('m'));

            $invoice = new Invoice();
            $invoice = $invoice->SetConnection('mysql2');

            $invoice->inv_no = $InvNo;
            $invoice->job_order_no = $request->data_id_hidden;
            $invoice->inv_date = $request->inv_date;
            $invoice->ship_to = $request->ship_too;
            $invoice->bill_to_client_id = $request->bill_to_client_id;
            $invoice->branch_id = $request->branch_id;
            $invoice->inv_desc_id = $request->InvDescId;
            $invoice->po_no = $request->po_no;
            $invoice->description = $request->inv_desc;
            $invoice->discount_percent = CommonHelper::check_str_replace($request->discount_percent);
            $invoice->discount_amount = CommonHelper::check_str_replace($request->discount_amount);
            if ($request->sales_tax_percent > 0) {
                $invoice->sales_tax_acc_id = $request->AccId;
            }
            $invoice->sales_tax_percent = CommonHelper::check_str_replace($request->sales_tax_percent);
            $invoice->sales_tax_amount = CommonHelper::check_str_replace($request->sales_tax_amount);
            $invoice->inv_status = 1;
            $invoice->Bbill_to = $request->bill_to;
            $invoice->client_ref = $request->client_ref;
            $invoice->advance_from_customer = $request->advance_from_customer;
            $invoice->status = 1;
            $invoice->date = date('Y-m-d');
            $invoice->username = Auth::user()->name;
            $invoice->save();


            $job_order_nos = $request->data_id_hidden;
            $job_order_nos = explode(',', $job_order_nos);
            $master_id = $invoice->id;
            $UpdateData['invoice_created'] = 1;
            DB::Connection('mysql2')->table('job_order')->whereIn('job_order_no', $job_order_nos)->update($UpdateData);

            $data = $request->product_id;


            $count = 0;
            foreach ($data as $row):

                $inv_data = new InvoiceData();
                $inv_data = $inv_data->SetConnection('mysql2');
                $inv_data->inv_no = $InvNo;
                $product_id = $request->input('product_id');
                $branch_id_multiple = $request->input('branch_id_multiple');

                $desc = $request->input('desc');
                $job_order_no = $request->job_order_no;
                $uom_id = $request->input('uom_id');
                $qty = $request->input('qty');
                $rate = $request->input('rate');
                $amount = $request->input('amount');

                $acc_id = $request->input('tax_id');
                $txt_amount = $request->input('tax_amount');
                $net_amount = $request->input('after_tax_amount');


                $inv_data->master_id = $master_id;
                $inv_data->job_order_no = $job_order_no[$count];
                $inv_data->product_id = $product_id[$count];
                $inv_data->branch_id = $branch_id_multiple[$count];
                $inv_data->description = $desc[$count];
                $inv_data->uom_id = $uom_id[$count];
                $inv_data->qty = CommonHelper::check_str_replace($qty[$count]);
                $inv_data->rate = CommonHelper::check_str_replace($rate[$count]);
                $inv_data->amount = CommonHelper::check_str_replace($amount[$count]);

                $acc_id = explode('*', $acc_id[$count]);
                $acc_id = $acc_id[0];
                $inv_data->txt_acc_id = $acc_id;

                $inv_data->txt_amount = CommonHelper::check_str_replace($txt_amount[$count]);
                $inv_data->net_amount = CommonHelper::check_str_replace($net_amount[$count]);
                $inv_data->status = 1;
                $inv_data->inv_status = 1;
                $inv_data->save();
                $count++;
            endforeach;


            $invoice_total = new Invoice_totals();
            $invoice_total = $invoice_total->SetConnection('mysql2');
            $invoice_total->master_id = $master_id;
            $invoice_total->inv_no = $InvNo;
            $invoice_total->discount_percntage = CommonHelper::check_str_replace($request->discount_percntage);
            $invoice_total->discount_amount = CommonHelper::check_str_replace($request->discount_amount);
            $invoice_total->discount_amount_tax = CommonHelper::check_str_replace($request->discount_amount_tax);
            $invoice_total->discount_amount_after_tax = CommonHelper::check_str_replace($request->discount_amount_after_tax);
            $invoice_total->total_amount_after_dicount_before_tax = CommonHelper::check_str_replace($request->total_amount_after_dicount_before_tax);
            $invoice_total->total_sales_tax_after_tax_dicount = CommonHelper::check_str_replace($request->total_sales_tax_after_tax_dicount);
            $invoice_total->total_amount_after_dicount = CommonHelper::check_str_replace($request->total_amount_after_dicount);

            $invoice_total->advance_percntage = CommonHelper::check_str_replace($request->advance_percntage);
            $invoice_total->advance_amount = CommonHelper::check_str_replace($request->advance_amount);
            $invoice_total->advance_amount_tax = CommonHelper::check_str_replace($request->advance_amount_tax);
            $invoice_total->advance_amount_after_tax = CommonHelper::check_str_replace($request->advance_amount_after_tax);
            $invoice_total->net_value_before_tax = CommonHelper::check_str_replace($request->net_value_before_tax);
            $invoice_total->net_tax_value = CommonHelper::check_str_replace($request->net_tax_value);
            $invoice_total->net_value = CommonHelper::check_str_replace($request->net_value);
            $invoice_total->save();



            $voucher_no     = strtoupper($InvNo);
            $voucher_date   = $request->inv_date;
            $action_type    = 1;
            $client_id      = $request->bill_to_client_id;
            $table_name     = "Invoice";
            CommonHelper::logActivity($voucher_no, $voucher_date, $action_type, $client_id, $table_name);
            DB::Connection('mysql2')->commit();
        } catch (\Exception $e) {
            DB::Connection('mysql2')->rollback();
            echo "EROOR"; //die();
            dd($e->getMessage());
        }
        return Redirect::to('sales/invoiceList?pageType=&&parentCode=96&&m=' . Input::get('m') . '#SFR');
    }

    function updateInvoiceDetail(Request $request)
    {
        //        echo "<pre>";
        //        print_r($_POST); die;

        DB::Connection('mysql2')->beginTransaction();
        try {
            $EditId  = $request->EditId;
            $inv_no  = $request->inv_no;
            $InvoiceUpdate['inv_date'] = $request->inv_date;
            $InvoiceUpdate['ship_to'] = $request->ship_too;
            $InvoiceUpdate['bill_to_client_id'] = $request->bill_to_client_id;
            $InvoiceUpdate['branch_id'] = $request->branch_id;
            $InvoiceUpdate['inv_desc_id'] = $request->InvDescId;
            $InvoiceUpdate['po_no'] = $request->po_no;
            $InvoiceUpdate['description'] = $request->inv_desc;
            $InvoiceUpdate['discount_percent'] = CommonHelper::check_str_replace($request->discount_percent);
            $InvoiceUpdate['discount_amount'] = CommonHelper::check_str_replace($request->discount_amount);
            $InvoiceUpdate['Bbill_to'] = $request->bill_to;
            $InvoiceUpdate['client_ref'] = $request->client_ref;
            $InvoiceUpdate['advance_from_customer'] = $request->advance_from_customer;
            if ($request->sales_tax_percent > 0) {
                $InvoiceUpdate['sales_tax_acc_id'] = $request->AccId;
            }
            $InvoiceUpdate['sales_tax_amount'] = CommonHelper::check_str_replace($request->sales_tax_amount);
            $InvoiceUpdate['inv_status'] = 1;
            $InvoiceUpdate['status'] = 1;
            $InvoiceUpdate['date'] = date('Y-m-d');
            DB::Connection('mysql2')->table('invoice')->where('id', '=', $EditId)->update($InvoiceUpdate);
            DB::Connection('mysql2')->table('inv_data')->where('master_id', $EditId)->delete();
            //DB::Connection('mysql2')->table('invoice_data_totals')->where('master_id', $EditId)->delete();
            $master_id = $EditId;


            $data = $request->product_id;
            $count = 0;
            foreach ($data as $row):

                $inv_data = new InvoiceData();
                $inv_data = $inv_data->SetConnection('mysql2');
                $inv_data->inv_no = $inv_no;
                $product_id = $request->input('product_id');
                $branch_id_multiple = $request->input('branch_id_multiple');
                $desc = $request->input('desc');
                $job_order_no = $request->job_order_no;
                $uom_id = $request->input('uom_id');
                $qty = $request->input('qty');
                $rate = $request->input('rate');
                $amount = $request->input('amount');
                $acc_id = $request->input('tax_id');
                $txt_amount = $request->input('tax_amount');
                $net_amount = $request->input('after_tax_amount');

                $inv_data->master_id = $master_id;
                $inv_data->job_order_no = $job_order_no[$count];
                $inv_data->product_id = $product_id[$count];
                $inv_data->branch_id = $branch_id_multiple[$count];
                $inv_data->description = $desc[$count];
                $inv_data->uom_id = $uom_id[$count];
                $inv_data->qty = CommonHelper::check_str_replace($qty[$count]);
                $inv_data->rate = CommonHelper::check_str_replace($rate[$count]);
                $inv_data->amount = CommonHelper::check_str_replace($amount[$count]);

                $acc_id = explode('*', $acc_id[$count]);
                $acc_id = $acc_id[0];
                $inv_data->txt_acc_id = $acc_id;

                $inv_data->txt_amount = CommonHelper::check_str_replace($txt_amount[$count]);
                $inv_data->net_amount = CommonHelper::check_str_replace($net_amount[$count]);
                $inv_data->status = 1;
                $inv_data->inv_status = 1;
                $inv_data->save();
                $count++;
            endforeach;


            $invoice_total = new Invoice_totals();
            $invoice_total = $invoice_total->SetConnection('mysql2');
            $invoice_total = $invoice_total->find($master_id);
            //$invoice_total->master_id=$master_id;
            //$invoice_total->inv_no=$inv_no;
            $invoice_total->discount_percntage = CommonHelper::check_str_replace($request->discount_percntage);
            $invoice_total->discount_amount = CommonHelper::check_str_replace($request->discount_amount);
            $invoice_total->discount_amount_tax = CommonHelper::check_str_replace($request->discount_amount_tax);
            $invoice_total->discount_amount_after_tax = CommonHelper::check_str_replace($request->discount_amount_after_tax);
            $invoice_total->total_amount_after_dicount_before_tax = CommonHelper::check_str_replace($request->total_amount_after_dicount_before_tax);
            $invoice_total->total_sales_tax_after_tax_dicount = CommonHelper::check_str_replace($request->total_sales_tax_after_tax_dicount);
            $invoice_total->total_amount_after_dicount = CommonHelper::check_str_replace($request->total_amount_after_dicount);

            $invoice_total->advance_percntage = CommonHelper::check_str_replace($request->advance_percntage);
            $invoice_total->advance_amount = CommonHelper::check_str_replace($request->advance_amount);
            $invoice_total->advance_amount_tax = CommonHelper::check_str_replace($request->advance_amount_tax);
            $invoice_total->advance_amount_after_tax = CommonHelper::check_str_replace($request->advance_amount_after_tax);
            $invoice_total->net_value_before_tax = CommonHelper::check_str_replace($request->net_value_before_tax);
            $invoice_total->net_tax_value = CommonHelper::check_str_replace($request->net_tax_value);
            $invoice_total->net_value = CommonHelper::check_str_replace($request->net_value);
            $invoice_total->save();

            $count = DB::Connection('mysql2')->table('transactions')->where('voucher_no', $inv_no)->count();
            $id = $EditId;
            if ($count > 0) {
                DB::Connection('mysql2')->table('transactions')->where('voucher_no', $inv_no)->delete();
                $invoice_data = DB::Connection('mysql2')->table('inv_data as a')
                    ->select(
                        'c.acc_id as prduct_acc_id',
                        'a.description',
                        'b.inv_no',
                        'b.sales_tax_acc_id',
                        'd.acc_id as client_acc_id',
                        'a.amount',
                        'a.id',
                        'b.inv_date',
                        'b.sales_tax_amount',
                        'b.discount_amount',
                        'b.advance_from_customer',
                        'a.branch_id',
                        'f.net_value',
                        'f.discount_amount as dis_am',
                        'f.advance_amount as adv_am',
                        'f.discount_percntage as dis_per',
                        'f.discount_amount_tax',
                        'f.advance_amount',
                        'f.advance_amount_tax',
                        'f.advance_percntage',
                        'b.bill_to_client_id'
                    )
                    ->join('invoice as b', 'b.id', '=', 'a.master_id')
                    ->join('invoice_data_totals as f', 'f.master_id', '=', 'b.id')
                    ->join('product as c', 'c.product_id', '=', 'a.product_id')
                    ->join('client as d', 'd.id', '=', 'b.bill_to_client_id')
                    ->where('a.master_id', $id)
                    ->where('b.inv_status', 1)
                    ->get();

                $count = 1;
                $tax = 0;
                foreach ($invoice_data as $row):

                    $inv_no = $row->inv_no;
                    $inv_date = $row->inv_date;
                    if ($count == 1):

                        $inv_amount = DB::Connection('mysql2')->table('inv_data')->where('master_id', $id)->sum('amount');
                        $discount = $row->discount_amount;
                        $sales_tax = $row->sales_tax_amount;
                        $total_amount = $row->net_value;

                        $trans1 = new Transactions();
                        $trans1 = $trans1->SetConnection('mysql2');
                        $trans1->acc_id = $row->client_acc_id;
                        $trans1->acc_code = FinanceHelper::getAccountCodeByAccId($row->client_acc_id, '');;
                        $trans1->master_id = $row->id;
                        $trans1->particulars = $row->description;
                        $trans1->opening_bal = 0;
                        $trans1->debit_credit = 1;
                        $trans1->paid_to = $row->branch_id;
                        $trans1->paid_to_type = 5;
                        $trans1->amount = $total_amount;
                        $trans1->voucher_no = $row->inv_no;
                        $trans1->voucher_type = 5;
                        $trans1->v_date = $row->inv_date;
                        $trans1->date = date('Y-m-d');
                        $trans1->action = 1;
                        $trans1->status = 1;
                        $trans1->username = Auth::user()->name;
                        $trans1->save();

                        $taxes = DB::Connection('mysql2')->selectOne('select sum(txt_amount)amount from inv_data where master_id="' . $id . '" ')->amount;
                        if ($row->dis_am > 0):
                            $trans1 = new Transactions();
                            $trans1 = $trans1->SetConnection('mysql2');
                            $trans1->acc_id = 612;
                            $trans1->acc_code = '4-1-3';;
                            $trans1->master_id = $row->id;
                            $trans1->particulars = 'Discount';
                            $trans1->opening_bal = 0;
                            $trans1->debit_credit = 1;
                            $trans1->paid_to = $row->bill_to_client_id;
                            $trans1->paid_to_type = 3;
                            $trans1->amount = $row->dis_am;
                            $trans1->voucher_no = $row->inv_no;
                            $trans1->voucher_type = 5;
                            $trans1->v_date = $row->inv_date;
                            $trans1->date = date('Y-m-d');
                            $trans1->action = 1;
                            $trans1->status = 1;
                            $trans1->username = Auth::user()->name;
                            $trans1->save();

                            if ($row->discount_amount_tax > 0):
                                $discount_data = DB::Connection('mysql2')->select('select sum(txt_amount)amount,txt_acc_id from inv_data where master_id="' . $id . '" group by txt_acc_id');

                                foreach ($discount_data as $row1):
                                    $trans1 = new Transactions();
                                    $trans1 = $trans1->SetConnection('mysql2');
                                    $trans1->acc_id = $row1->txt_acc_id;

                                    $trans1->acc_code = FinanceHelper::getAccountCodeByAccId($row1->txt_acc_id, '');
                                    $trans1->master_id = 0;
                                    $trans1->particulars = 'Discount Tax';
                                    $trans1->opening_bal = 0;
                                    $trans1->debit_credit = 1;

                                    $trans1->paid_to = $row->bill_to_client_id;
                                    $trans1->paid_to_type = 3;

                                    $trans1->amount = ($row1->amount / $taxes) * $row->discount_amount_tax;
                                    $trans1->voucher_no = $inv_no;
                                    $trans1->voucher_type = 5;
                                    $trans1->v_date = $inv_date;
                                    $trans1->date = date('Y-m-d');
                                    $trans1->action = 1;
                                    $trans1->status = 1;
                                    $trans1->username = Auth::user()->name;
                                    $trans1->save();
                                endforeach;
                            endif;
                        endif;

                        if ($row->adv_am > 0):
                            $trans1 = new Transactions();
                            $trans1 = $trans1->SetConnection('mysql2');
                            $trans1->acc_id = 521;
                            $trans1->acc_code = '3-2-3-8';;
                            $trans1->master_id = $row->id;
                            $trans1->particulars = 'Advance';
                            $trans1->paid_to = $row->bill_to_client_id;
                            $trans1->paid_to_type = 3;
                            $trans1->opening_bal = 0;
                            $trans1->debit_credit = 1;
                            $trans1->amount = $row->adv_am;
                            $trans1->voucher_no = $row->inv_no;
                            $trans1->voucher_type = 5;
                            $trans1->v_date = $row->inv_date;
                            $trans1->date = date('Y-m-d');
                            $trans1->action = 1;
                            $trans1->status = 1;
                            $trans1->username = Auth::user()->name;
                            $trans1->save();

                            if ($row->advance_amount_tax > 0):
                                $advance_data = DB::Connection('mysql2')->select('select sum(txt_amount)amount,txt_acc_id from inv_data where master_id="' . $id . '" group by txt_acc_id');

                                foreach ($advance_data as $row1):
                                    $trans1 = new Transactions();
                                    $trans1 = $trans1->SetConnection('mysql2');
                                    $trans1->acc_id = $row1->txt_acc_id;

                                    $trans1->acc_code = FinanceHelper::getAccountCodeByAccId($row1->txt_acc_id, '');
                                    $trans1->master_id = 0;
                                    $trans1->particulars = 'Advance Tax';
                                    $trans1->opening_bal = 0;
                                    $trans1->debit_credit = 1;

                                    $trans1->paid_to = $row->bill_to_client_id;
                                    $trans1->paid_to_type = 3;

                                    $trans1->amount = ($row1->amount / $taxes) * $row->advance_amount_tax;
                                    $trans1->voucher_no = $inv_no;
                                    $trans1->voucher_type = 5;
                                    $trans1->v_date = $inv_date;
                                    $trans1->date = date('Y-m-d');
                                    $trans1->action = 1;
                                    $trans1->status = 1;
                                    $trans1->username = Auth::user()->name;
                                    $trans1->save();
                                endforeach;
                            endif;
                        endif;
                    endif;

                    if ($row->amount > 0):
                        $trans1 = new Transactions();
                        $trans1 = $trans1->SetConnection('mysql2');
                        $trans1->acc_id = $row->prduct_acc_id;
                        $trans1->acc_code = FinanceHelper::getAccountCodeByAccId($row->prduct_acc_id, '');;
                        $trans1->master_id = $row->id;
                        $trans1->particulars = $row->description;
                        $trans1->opening_bal = 0;

                        $trans1->paid_to = $row->branch_id;
                        $trans1->paid_to_type = 5;

                        $trans1->debit_credit = 0;
                        $trans1->amount = $row->amount;
                        $trans1->voucher_no = $row->inv_no;
                        $trans1->voucher_type = 5;
                        $trans1->v_date = $row->inv_date;
                        $trans1->date = date('Y-m-d');
                        $trans1->action = 1;
                        $trans1->status = 1;
                        $trans1->username = Auth::user()->name;
                        $trans1->save();
                    endif;
                    $count++;
                endforeach;

                $advance_data = DB::Connection('mysql2')->select('select sum(txt_amount)amount,txt_acc_id,branch_id from inv_data where master_id="' . $id . '" group by txt_acc_id');
                foreach ($advance_data as $row1):

                    if ($row1->amount > 0):
                        $trans1 = new Transactions();
                        $trans1 = $trans1->SetConnection('mysql2');
                        $trans1->acc_id = $row1->txt_acc_id;

                        $trans1->acc_code = FinanceHelper::getAccountCodeByAccId($row1->txt_acc_id, '');
                        $trans1->master_id = 0;
                        $trans1->particulars = '';
                        $trans1->opening_bal = 0;
                        $trans1->debit_credit = 0;

                        $trans1->paid_to = $row1->branch_id;
                        $trans1->paid_to_type = 5;

                        $trans1->amount = $row1->amount;
                        $trans1->voucher_no = $inv_no;
                        $trans1->voucher_type = 5;
                        $trans1->v_date = $inv_date;
                        $trans1->date = date('Y-m-d');
                        $trans1->action = 1;
                        $trans1->status = 1;
                        $trans1->username = Auth::user()->name;
                        $trans1->save();
                    endif;
                endforeach;

                if ($row->sales_tax_amount > 0):
                    $trans1 = new Transactions();
                    $trans1 = $trans1->SetConnection('mysql2');
                    $trans1->acc_id = $row->sales_tax_acc_id;
                    $trans1->acc_code = FinanceHelper::getAccountCodeByAccId($row->sales_tax_acc_id, '');;
                    $trans1->master_id = $row->id;
                    $trans1->particulars = $row->description;
                    $trans1->opening_bal = 0;
                    $trans1->debit_credit = 0;

                    $trans1->paid_to = $row->branch_id;
                    $trans1->paid_to_type = 5;

                    $trans1->amount = $row->sales_tax_amount;
                    $trans1->voucher_no = $row->inv_no;
                    $trans1->voucher_type = 5;
                    $trans1->v_date = $row->inv_date;
                    $trans1->date = date('Y-m-d');
                    $trans1->action = 1;
                    $trans1->status = 1;
                    $trans1->username = Auth::user()->name;
                //     $trans1->save();
                endif;



                $invoice_data = new InvoiceData();
                $invoice_data = $invoice_data->SetConnection('mysql2');
                $invoice_data = $invoice_data->where('master_id', $id)->update(['inv_status' => 2]);

                $invoice = new Invoice();
                $invoice = $invoice->SetConnection('mysql2');
                $invoice = $invoice->where('id', $id)->update(['inv_status' => 2]);
            }

            $voucher_no     = $inv_no;
            $voucher_date   = $request->inv_date;
            $action_type    = 1;
            $client_id      = $request->bill_to_client_id;
            $table_name     = "Invoice";
            CommonHelper::logActivity($voucher_no, $voucher_date, $action_type, $client_id, $table_name);
            DB::Connection('mysql2')->commit();
        } catch (\Exception $e) {
            DB::Connection('mysql2')->rollback();
            echo "EROOR"; //die();
            dd($e->getMessage());
        }
        return Redirect::to('sales/invoiceList?pageType=&&parentCode=96&&m=' . Input::get('m') . '#SFR');
    }

    public function createbuldles(Request $request)
    {

        DB::Connection('mysql2')->beginTransaction();
        try {
            $master_id = $request->sales_order_idd;
            $sales_order_no = SalesHelper::get_unique_no(date('y'), date('m'));
            $sales_order = new Sales_Order();
            if ($master_id == ''):
                $sales_order = new Sales_Order();
                $sales_order = $sales_order->SetConnection('mysql2');
                $sales_order->so_no = $sales_order_no;
                $sales_order->status = 3;
                $sales_order->save();
                $master_id = $sales_order->id;
            endif;

            $bundle_id = $request->bundles_id;
            $budnles_record = DB::Connection('mysql2')->table('bundles')->where('status', 1)->where('so_id', $master_id);
            if ($bundle_id == ''):



                $number = $budnles_record->count() + 1;
                $bd_no = sprintf("%'03d", $number);


            else:
                $sales_order_no = $budnles_record->first()->so_no;
                $bd_no = $budnles_record->where('id', $bundle_id)->first()->bd_no;;
            endif;
            $data = array(

                'so_id' => $master_id,
                'so_no' => $sales_order_no,
                'bd_no' => $bd_no,
                'product_name' => $request->product_name,
                'bundle_unit' => $request->bundle_unit,
                'qty' => $request->bundle_qty,
                'rate' => $request->bundle_rate,
                'amount' => $request->bundle_amount,
                'discount_percent' => $request->bundle_discount_percent,
                'discount_amount' => $request->bundle_discount_amount,
                'net_amount' => $request->bundle_net_amount,
                'status' => 1,
                'date' => date('Y-m-d'),
            );

            if ($budnles_record->where('id', $bundle_id)->count() == 0):
                $bundle_data_id = DB::Connection('mysql2')->table('bundles')->insertGetId($data);


            else:
                DB::Connection('mysql2')->table('bundles')->where('id', $bundle_id)->update($data);
                DB::Connection('mysql2')->table('bundles_data')->where('master_id', $bundle_id)->delete();
                $bundle_data_id = $bundle_id;
            endif;


            $data = $request->bsub_ic_des;
            $total_qty = 0;
            foreach ($data as $key => $row):



                $data1 = array(
                    'master_id' => $bundle_data_id,
                    'bd_no' => $bd_no,
                    'product_name' => $request->product_name,
                    'item_id' => $request->input('bitem_id')[$key],
                    'desc' => $request->input('bsub_ic_des')[$key],
                    'qty' => $request->input('bactual_qty')[$key],
                    'rate' => $request->input('brate')[$key],
                    'amount' => $request->input('bamount')[$key],
                    'discount_percent' => $request->input('bdiscount_percent')[$key],
                    'discount_amount' => $request->input('bdiscount_amount')[$key],
                    'net_amount' => $request->input('bafter_dis_amount')[$key],
                    'status' => 1,
                    'date' => date('Y-m-d'),
                );
                DB::Connection('mysql2')->table('bundles_data')->insertGetId($data1);

            endforeach;
            DB::Connection('mysql2')->commit();
        } catch (Exception $ex) {


            DB::rollBack();
        }
        echo $request->product_name . '@' . $total_qty . '@' . $master_id . '@' . $bundle_data_id;
    }

    public function addTestForm()
    {

        $demandDataSection = Input::get('sub_ic_des');
        $wdemandDataSection = Input::get('wsub_ic_des');


        DB::Connection('mysql2')->beginTransaction();
        try {
            $VoucherNo = CommonHelper::get_unique_import_no(date('y'), date('m'));
            $CreatedDate = Input::get('created_date');
            $SupplierId = Input::get('supplier_id');
            $document_number = Input::get('document_number');
            $document_date = Input::get('document_date');

            $ImportPo['voucher_no'] = $VoucherNo;
            $ImportPo['voucher_date'] = $CreatedDate;
            $ImportPo['document_no'] = $document_number;
            $ImportPo['document_date'] = $document_date;
            $ImportPo['vendor'] = $SupplierId;
            $ImportPo['status'] = 1;
            $ImportPo['date'] = date('Y-m-d');
            $ImportPo['username'] = Auth::user()->name;

            $MasterId = DB::Connection('mysql2')->table('import_po')->insertGetId($ImportPo);

            $ItemId = Input::get('item_id');
            $SystemQty = Input::get('system_qty');
            $ForeignCurrency = Input::get('foreign_currency');
            $TotalAmount = Input::get('total_amount');


            if (count($demandDataSection) > 0):
                foreach ($demandDataSection as $key => $row2) {
                    $ImportPoData['master_id'] = $MasterId;
                    $ImportPoData['voucher_no'] = $VoucherNo;
                    $ImportPoData['item_id'] = $ItemId[$key];
                    $ImportPoData['qty'] = $SystemQty[$key];
                    $ImportPoData['total_weight'] = 0;
                    $ImportPoData['total_rate_per_weight'] = 0;
                    $ImportPoData['as_per_weight'] = 0;
                    $ImportPoData['foreign_currency_price'] = $ForeignCurrency[$key];
                    $ImportPoData['amount'] = $TotalAmount[$key];
                    $ImportPoData['type'] = 1;
                    DB::Connection('mysql2')->table('import_po_data')->insertGetId($ImportPoData);
                }
            endif;

            //W section
            if (count($wdemandDataSection) > 0):
                $wItemId = Input::get('witem_id');
                $wSystemQty = Input::get('wsystem_qty');
                $wtotal_weight = Input::get('wtotal_weight');
                $wtotal_rate_per_weight = Input::get('wtotal_rate_per_weight');
                $was_per_weight = Input::get('was_per_weight');
                $wForeignCurrency = Input::get('wforeign_currency');
                $wTotalAmount = Input::get('wtotal_amount');

                foreach ($wdemandDataSection as $key2 => $row3) {
                    $WImportPoData['master_id'] = $MasterId;
                    $WImportPoData['voucher_no'] = $VoucherNo;
                    $WImportPoData['item_id'] = $wItemId[$key2];
                    $WImportPoData['qty'] = $wSystemQty[$key2];
                    $WImportPoData['total_weight'] = strip_tags($wtotal_weight[$key2]);
                    $WImportPoData['total_rate_per_weight'] = strip_tags($wtotal_rate_per_weight[$key2]);
                    $WImportPoData['as_per_weight'] = strip_tags($was_per_weight[$key2]);
                    $WImportPoData['foreign_currency_price'] = $wForeignCurrency[$key2];
                    $WImportPoData['amount'] = $wTotalAmount[$key2];
                    $WImportPoData['type'] = 2;
                    DB::Connection('mysql2')->table('import_po_data')->insertGetId($WImportPoData);
                }
            endif;
            //W SEction

            DB::Connection('mysql2')->commit();
        } catch (\Exception $e) {
            DB::Connection('mysql2')->rollback();
            echo "EROOR"; //die();
            dd($e->getMessage());
        }
        echo "yes";
    }


    public function addConvertGrnData()
    {


        $demandDataSection = Input::get('GrnTotalAmount');

        $str = DB::Connection("mysql2")->selectOne("select max(convert(substr(`grn_no`,4,length(substr(`grn_no`,4))-4),signed integer)) reg from `goods_receipt_note` where substr(`grn_no`,-4,2) = " . date('m') . " and substr(`grn_no`,-2,2) = " . date('y') . "")->reg;
        $grn_no = 'grn' . ($str + 1) . date('my');
        DB::Connection('mysql2')->beginTransaction();
        try {

            $vendor_id = Input::get('vendor_id');
            $voucher_no = Input::get('voucher_no');
            $import_id = Input::get('import_id');

            $Master['supplier_id'] = $vendor_id;
            $Master['grn_no'] = $grn_no;
            $Master['grn_date'] = date('Y-m-d');
            $Master['import_id'] = $import_id;
            $Master['po_no'] = $voucher_no;
            $Master['type'] = 5;
            $Master['status'] = 1;
            $Master['date'] = date('Y-m-d');
            $Master['username'] = Auth::user()->name;
            //print_r($Master); die();

            $MasterId = DB::Connection('mysql2')->table('goods_receipt_note')->insertGetId($Master);
            $ItemId = Input::get('GrnItemId');
            $GetBatchCode = Input::get('GetBatchCode');
            $grn_qty = Input::get('grn_qty');
            $warehouse_id = Input::get('warehouse_id');

            $TotalAmount = Input::get('GrnTotalAmount');
            $GrnImportDataId = Input::get('GrnImportDataId');
            $RowNumber = Input::get('RowNumber');




            foreach ($demandDataSection as $key => $row2) {

                $Detail['master_id'] = $MasterId;
                //   $line_items = $RowNumber[$key];
                $Detail['grn_no'] = $grn_no;
                $Detail['import_data_id'] = strip_tags($GrnImportDataId[$key]);
                $Detail['sub_item_id'] = strip_tags($ItemId[$key]);
                $Detail['batch_code'] = strip_tags($GetBatchCode[$key]);
                $Detail['purchase_recived_qty'] = $grn_qty[$key];


                $rate = $TotalAmount[$key] / $grn_qty[$key];
                $Detail['rate'] = $TotalAmount[$key] / $grn_qty[$key];
                $Detail['warehouse_id'] = strip_tags($warehouse_id[$key]);
                $amount = ($rate * $grn_qty[$key]) / 2;
                $Detail['amount'] = $amount;
                $Detail['net_amount'] = $amount;

                DB::Connection('mysql2')->table('grn_data')->insertGetId($Detail);
            }


            DB::Connection('mysql2')->commit();
        } catch (\Exception $e) {
            DB::Connection('mysql2')->rollback();
            echo "EROOR"; //die();
            dd($e->getMessage());
        }
        return Redirect::to('sales/createTestForm?pageType=add&&parentCode=001&&m=1');
    }


    public function updateImportDocument()
    {

        $demandDataSection = Input::get('sub_ic_des');
        $wdemandDataSection = Input::get('wsub_ic_des');
        $edit_id = Input::get('edit_id');


        DB::Connection('mysql2')->beginTransaction();
        try {
            $VoucherNo = Input::get('voucher_no');
            $CreatedDate = Input::get('voucher_date');
            $SupplierId = Input::get('supplier_id');
            $document_number = Input::get('document_number');
            $document_date = Input::get('document_date');

            $UpdateImportPo['voucher_no'] = $VoucherNo;
            $UpdateImportPo['voucher_date'] = $CreatedDate;
            $UpdateImportPo['document_no'] = $document_number;
            $UpdateImportPo['document_date'] = $document_date;
            $UpdateImportPo['vendor'] = $SupplierId;
            $UpdateImportPo['username'] = Auth::user()->name;

            $MasterId = DB::Connection('mysql2')->table('import_po')->where('id', $edit_id)->update($UpdateImportPo);
            DB::Connection('mysql2')->table('import_po_data')->where('master_id', $edit_id)->delete();

            $ItemId = Input::get('item_id');
            $SystemQty = Input::get('system_qty');
            $ForeignCurrency = Input::get('foreign_currency');
            $TotalAmount = Input::get('total_amount');

            foreach ($demandDataSection as $key => $row2) {
                $ImportPoData['master_id'] = $edit_id;
                $ImportPoData['voucher_no'] = $VoucherNo;
                $ImportPoData['item_id'] = $ItemId[$key];
                $ImportPoData['qty'] = $SystemQty[$key];
                $ImportPoData['total_weight'] = 0;
                $ImportPoData['total_rate_per_weight'] = 0;
                $ImportPoData['as_per_weight'] = 0;
                $ImportPoData['foreign_currency_price'] = $ForeignCurrency[$key];
                $ImportPoData['amount'] = $TotalAmount[$key];
                $ImportPoData['type'] = 1;
                DB::Connection('mysql2')->table('import_po_data')->insertGetId($ImportPoData);
            }

            //W section
            if (count($wdemandDataSection) > 0):
                $wItemId = Input::get('witem_id');
                $wSystemQty = Input::get('wsystem_qty');
                $wtotal_weight = Input::get('wtotal_weight');
                $wtotal_rate_per_weight = Input::get('wtotal_rate_per_weight');
                $was_per_weight = Input::get('was_per_weight');
                $wForeignCurrency = Input::get('wforeign_currency');
                $wTotalAmount = Input::get('wtotal_amount');

                foreach ($wdemandDataSection as $key2 => $row3) {
                    $WImportPoData['master_id'] = $edit_id;
                    $WImportPoData['voucher_no'] = $VoucherNo;
                    $WImportPoData['item_id'] = $wItemId[$key2];
                    $WImportPoData['qty'] = $wSystemQty[$key2];
                    $WImportPoData['total_weight'] = strip_tags($wtotal_weight[$key2]);
                    $WImportPoData['total_rate_per_weight'] = strip_tags($wtotal_rate_per_weight[$key2]);
                    $WImportPoData['as_per_weight'] = strip_tags($was_per_weight[$key2]);
                    $WImportPoData['foreign_currency_price'] = $wForeignCurrency[$key2];
                    $WImportPoData['amount'] = $wTotalAmount[$key2];
                    $WImportPoData['type'] = 2;
                    DB::Connection('mysql2')->table('import_po_data')->insertGetId($WImportPoData);
                }
            endif;
            //W SEction

            DB::Connection('mysql2')->commit();
        } catch (\Exception $e) {
            DB::Connection('mysql2')->rollback();
            echo "EROOR"; //die();
            dd($e->getMessage());
        }
        Session::flash('dataDelete', 'Grn Create Successfully...!');
        return Redirect::to('sales/importDocumentList?pageType=' . Input::get('pageType') . '&&parentCode=' . Input::get('parentCode') . '&&m=' . $_GET['m'] . '#SFR');
    }


    function update_import_po(Request $request)
    {

        $EditId = $request->PaymentEditId;

        if ($request->type == 1):
            $data = array(
                'import_id' => $request->voucher_no,
                'bill_date' => date('Y-m-d'),
                'cheque_no' => $request->cheque_no_1,
                'cheque_date' => $request->cheque_date_1,
                'foreign_amount' => $request->f_currency,
                'cureency_rate' => $request->rate,
                'amount_in_pkr' => $request->amount_pkr,
                'des' => $request->description_1,
                'pv_date' => $request->pv_date_1,
                'cr_account' => $request->cr_account,

            );
            DB::Connection('mysql2')->table('import_payment')->where('id', $EditId)->update($data);


            $igm_data =   DB::Connection('mysql2')->table('import_po')->where('status', 1)->where('id', $request->voucher_no)->first();
            $igm_no = $igm_data->voucher_no;
            $document_no = $igm_data->document_no;
            $desc = 'IGM No: ' . $igm_no . ' - Doument No: ' . $document_no . ' - Grand Total: ' . $request->grand_total . ' - Amount In Foreign Currency: ' . $request->f_currency;




            $payment = new NewPv();
            $payment = $payment->SetConnection('mysql2');

            $check_existing_data = $payment->where('import_payment_id', $EditId)->where('status', 1)->where('type', 3);

            if ($check_existing_data->count() > 0):

                $id =   $check_existing_data->first()->id;
                $pv_no =  $check_existing_data->first()->pv_no;



                $payment = $payment->find($id);
                DB::Connection('mysql2')->table('new_pv_data')->where('master_id', $id)->delete();

            else:
                $pv_no = CommonHelper::uniqe_no_for_pv(date('y'), date('m'), $request->payment_typee);
            endif;

            $payment->pv_no = $pv_no;
            $payment->pv_date = $request->pv_date_1;
            $payment->bill_no = $document_no;
            $payment->bill_date = date('Y-m-d');

            if ($request->payment_typee == 1):
                $payment->cheque_no = $request->cheque_no_1;
                $payment->cheque_date = $request->cheque_date_1;
            endif;
            $payment->payment_type = $request->payment_typee;
            $payment->description = $desc;
            $payment->date = date('Y-m-d');
            $payment->status = 1;
            $payment->username = Auth::user()->name;
            $payment->pv_status = 1;
            $payment->type = 3;
            $payment->import_payment_id = $EditId;
            $payment->save();
            $master_id = $payment->id;




            $pv_data = new NewPvData();
            $pv_data = $pv_data->SetConnection('mysql2');
            $pv_data->master_id = $master_id;
            $pv_data->pv_no     = $pv_no;
            $pv_data->pv_date   = $request->pv_date_1;
            $acc_id = ReuseableCode::get_acc_id_by_code('1-2-12');
            $pv_data->acc_id = $acc_id;
            $pv_data->description = $desc;
            $pv_data->amount = $request->amount_pkr;
            $pv_data->debit_credit = 1;
            $pv_data->status = 1;
            $pv_data->pv_status = 1;
            $pv_data->date = date('Y-m-d');
            $pv_data->save();


            $pv_data = new NewPvData();
            $pv_data = $pv_data->SetConnection('mysql2');
            $pv_data->master_id = $master_id;
            $pv_data->pv_no     = $pv_no;
            $pv_data->pv_date   = $request->pv_date_1;
            $pv_data->acc_id = $request->cr_account;
            $pv_data->description = $desc;
            $pv_data->amount = $request->amount_pkr;
            $pv_data->debit_credit = 0;
            $pv_data->status = 1;
            $pv_data->pv_status = 1;
            $pv_data->date = date('Y-m-d');
            $pv_data->save();
        endif;
    }

    function update_import_exp(Request $request)
    {
        DB::Connection('mysql2')->beginTransaction();
        try {

            $EditId = $request->ExpenseEditId;

            if ($request->type == 2):
                $data1 = array(
                    'bill_date' => date('Y-m-d'),
                    'cheque_no' => $request->cheque_no_1,
                    'cheque_date' => $request->cheque_date_1,
                    'pv_date' => $request->pv_date_1,
                    'import_id' => $request->voucher_no,
                    'cr_account' => $request->cr_account,
                    'duty' => $request->duty,
                    'eto' => $request->eto,
                    'do' => $request->do,
                    'appraisal' => $request->appraisal,
                    'fright' => $request->fright,
                    'insurance' => $request->insurance,
                    'expense' => $request->expense,
                    'other_expense' => $request->other_expense,


                );
                DB::Connection('mysql2')->table('import_expense')->where('id', $EditId)->update($data1);



                $igm_data =   DB::Connection('mysql2')->table('import_po')->where('status', 1)->where('id', $request->voucher_no)->first();
                $igm_no = $igm_data->voucher_no;
                $document_no = $igm_data->document_no;
                $desc = 'IGM No: ' . $igm_no . ' - Doument No: ' . $document_no . ' - Grand Total: ' . $request->grand_total . ' - Amount In Foreign Currency: ' . $request->f_currency;


                $payment = new NewPv();
                $payment = $payment->SetConnection('mysql2');



                $check_existing_data = $payment->where('import_payment_id', $EditId)->where('status', 1)->where('type', 4);

                if ($check_existing_data->count() > 0):

                    $id =   $check_existing_data->first()->id;
                    $pv_no =  $check_existing_data->first()->pv_no;


                    $payment = $payment->find($id);
                    DB::Connection('mysql2')->table('new_pv_data')->where('master_id', $id)->delete();

                else:
                    $pv_no = CommonHelper::uniqe_no_for_pv(date('y'), date('m'), $request->payment_typee);
                endif;




                $payment->pv_no = $pv_no;
                $payment->pv_date = $request->pv_date_1;
                $payment->bill_no = $document_no;
                $payment->bill_date = date('Y-m-d');
                if ($request->payment_typee == 1):
                    $payment->cheque_no = $request->cheque_no_1;
                    $payment->cheque_date = $request->cheque_date_1;
                endif;

                $payment->description = $desc;
                $payment->date = date('Y-m-d');
                $payment->status = 1;
                $payment->username = Auth::user()->name;
                $payment->pv_status = 1;
                $payment->type = 4;
                $payment->payment_type = $request->payment_typee;
                $payment->import_payment_id = $EditId;
                $payment->save();
                $master_id = $payment->id;


                if ($request->duty > 0):
                    ReuseableCode::import_expense($request->pv_date_1, $master_id, $pv_no, $desc, $request->duty, '1-2-13-1');
                endif;

                if ($request->eto > 0):
                    ReuseableCode::import_expense($request->pv_date_1, $master_id, $pv_no, $desc, $request->eto, '1-2-13-2');
                endif;


                if ($request->do > 0):
                    ReuseableCode::import_expense($request->pv_date_1, $master_id, $pv_no, $desc, $request->do, '1-2-13-3');
                endif;


                if ($request->appraisal > 0):
                    ReuseableCode::import_expense($request->pv_date_1, $master_id, $pv_no, $desc, $request->appraisal, '1-2-13-5');
                endif;


                if ($request->insurance > 0):
                    ReuseableCode::import_expense($request->pv_date_1, $master_id, $pv_no, $desc, $request->insurance, '1-2-13-4');
                endif;


                if ($request->fright > 0):
                    ReuseableCode::import_expense($request->pv_date_1, $master_id, $pv_no, $desc, $request->fright, '1-2-13-4');
                endif;


                if ($request->expense > 0):
                    ReuseableCode::import_expense($request->pv_date_1, $master_id, $pv_no, $desc, $request->expense, '1-2-13-3');
                endif;


                if ($request->other_expense > 0):
                    ReuseableCode::import_expense($request->pv_date_1, $master_id, $pv_no, $desc, $request->other_expense, '1-2-13-3');
                endif;

                $total = $request->duty + $request->eto + $request->do + $request->appraisal + $request->insurance + $request->fright + $request->expense + $request->other_expense;

                $pv_data = new NewPvData();
                $pv_data = $pv_data->SetConnection('mysql2');
                $pv_data->master_id = $master_id;
                $pv_data->pv_no     = $pv_no;
                $pv_data->pv_date   = $request->pv_date_1;

                $pv_data->acc_id = $request->cr_account;
                $pv_data->description = $desc;
                $pv_data->amount = $total;
                $pv_data->debit_credit = 0;
                $pv_data->status = 1;
                $pv_data->pv_status = 1;
                $pv_data->date = date('Y-m-d');
                $pv_data->save();



            endif;
            DB::Connection('mysql2')->commit();
        } catch (Exception $ex) {


            DB::rollBack();
        }
    }
    function add_import_po(Request $request)
    {

        DB::Connection('mysql2')->beginTransaction();
        try {
            if ($request->type == 1):


                $data = array(
                    'import_id' => $request->voucher_no,
                    'bill_date' => date('Y-m-d'),
                    'cheque_no' => $request->cheque_no_1,
                    'cheque_date' => $request->cheque_date_1,
                    'foreign_amount' => $request->f_currency,
                    'cureency_rate' => $request->rate,
                    'amount_in_pkr' => $request->amount_pkr,
                    'des' => $request->description_1,
                    'pv_date' => $request->pv_date_1,
                    'cr_account' => $request->cr_account,
                    'payment_type' => $request->payment_typee

                );



                $import_id = DB::Connection('mysql2')->table('import_payment')->insertGetId($data);
                $igm_data =   DB::Connection('mysql2')->table('import_po')->where('status', 1)->where('id', $request->voucher_no)->first();
                $igm_no = $igm_data->voucher_no;
                $document_no = $igm_data->document_no;
                $desc = 'IGM No: ' . $igm_no . ' - Doument No: ' . $document_no . ' - Grand Total: ' . $request->grand_total . ' - Amount In Foreign Currency: ' . $request->f_currency;

                $pv_no = CommonHelper::uniqe_no_for_pv(date('y'), date('m'), $request->payment_typee);
                $payment = new NewPv();
                $payment = $payment->SetConnection('mysql2');
                $payment->pv_no = $pv_no;
                $payment->pv_date = $request->pv_date_1;
                $payment->bill_no = $document_no;
                $payment->bill_date = date('Y-m-d');
                if ($request->payment_typee == 1):
                    $payment->cheque_no = $request->cheque_no_1;
                    $payment->cheque_date = $request->cheque_date_1;
                endif;
                $payment->payment_type = $request->payment_typee;
                $payment->description = $desc;
                $payment->date = date('Y-m-d');
                $payment->status = 1;
                $payment->username = Auth::user()->name;
                $payment->pv_status = 1;
                $payment->type = 3;
                $payment->import_payment_id = $import_id;
                $payment->save();
                $master_id = $payment->id;




                $pv_data = new NewPvData();
                $pv_data = $pv_data->SetConnection('mysql2');
                $pv_data->master_id = $master_id;
                $pv_data->pv_no     = $pv_no;
                $pv_data->pv_date   = $request->pv_date_1;


                $acc_id = ReuseableCode::get_acc_id_by_code('1-2-12');
                $pv_data->acc_id = $acc_id;
                $pv_data->description = $desc;
                $pv_data->amount = $request->amount_pkr;
                $pv_data->debit_credit = 1;
                $pv_data->status = 1;
                $pv_data->pv_status = 1;
                $pv_data->date = date('Y-m-d');
                $pv_data->save();


                $pv_data = new NewPvData();
                $pv_data = $pv_data->SetConnection('mysql2');
                $pv_data->master_id = $master_id;
                $pv_data->pv_no     = $pv_no;
                $pv_data->pv_date   = $request->pv_date_1;
                $pv_data->acc_id = $request->cr_account;
                $pv_data->description = $desc;
                $pv_data->amount = $request->amount_pkr;
                $pv_data->debit_credit = 0;
                $pv_data->status = 1;
                $pv_data->pv_status = 1;
                $pv_data->date = date('Y-m-d');
                $pv_data->save();





            else:
                $data1 = array(
                    'bill_date' => date('Y-m-d'),
                    'cheque_no' => $request->cheque_no_1,
                    'cheque_date' => $request->cheque_date_1,
                    'pv_date' => $request->pv_date_1,
                    'import_id' => $request->voucher_no,
                    'cr_account' => $request->cr_account,
                    'payment_type' => $request->payment_typee,
                    'duty' => $request->duty,
                    'eto' => $request->eto,
                    'do' => $request->do,
                    'appraisal' => $request->appraisal,
                    'fright' => $request->fright,
                    'insurance' => $request->insurance,
                    'expense' => $request->expense,
                    'other_expense' => $request->other_expense,
                    'status' => 1,
                    'date' => date('Y-m-d'),
                    'username' => Auth::user()->name,


                );
                $import_id =     DB::Connection('mysql2')->table('import_expense')->insertGetId($data1);



                $igm_data =   DB::Connection('mysql2')->table('import_po')->where('status', 1)->where('id', $request->voucher_no)->first();
                $igm_no = $igm_data->voucher_no;
                $document_no = $igm_data->document_no;
                $desc = 'IGM No: ' . $igm_no . ' - Doument No: ' . $document_no . ' - Grand Total: ' . $request->grand_total . ' - Amount In Foreign Currency: ' . $request->f_currency;

                $pv_no = CommonHelper::uniqe_no_for_pv(date('y'), date('m'), $request->payment_typee);
                $payment = new NewPv();
                $payment = $payment->SetConnection('mysql2');
                $payment->pv_no = $pv_no;
                $payment->pv_date = $request->pv_date_1;
                $payment->bill_no = $document_no;
                $payment->bill_date = date('Y-m-d');
                if ($request->payment_typee == 1):
                    $payment->cheque_no = $request->cheque_no_1;
                    $payment->cheque_date = $request->cheque_date_1;
                endif;
                $payment->payment_type = $request->payment_typee;
                $payment->description = $desc;
                $payment->date = date('Y-m-d');
                $payment->status = 1;
                $payment->username = Auth::user()->name;
                $payment->pv_status = 1;
                $payment->type = 4;
                $payment->import_payment_id = $import_id;
                $payment->save();
                $master_id = $payment->id;


                if (Session::get('run_company') == 2):
                    $codes = array(
                        "duty" => "1-2-14-1",
                        "eto" => "1-2-14-2",
                        "do" => "1-2-14-3",
                        "appraisal" => "1-2-14-5",
                        "insurance" => "1-2-14-4",
                        "fright" => "1-2-14-4",
                        "expense" => "1-2-14-4",
                        "other_expense" => "1-2-14-4",
                    );
                else:
                    $codes = array(
                        "duty" => "1-2-13-1",
                        "eto" => "1-2-13-2",
                        "do" => "1-2-13-3",
                        "appraisal" => "1-2-13-5",
                        "insurance" => "1-2-13-4",
                        "fright" => "1-2-13-4",
                        "expense" => "1-2-13-3",
                        "other_expense" => "1-2-13-3",
                    );
                endif;
                if ($request->duty > 0):



                    ReuseableCode::import_expense($request->pv_date_1, $master_id, $pv_no, $desc, $request->duty, $codes['duty']);
                endif;

                if ($request->eto > 0):
                    ReuseableCode::import_expense($request->pv_date_1, $master_id, $pv_no, $desc, $request->eto, $codes['eto']);
                endif;


                if ($request->do > 0):
                    ReuseableCode::import_expense($request->pv_date_1, $master_id, $pv_no, $desc, $request->do, $codes['do']);
                endif;


                if ($request->appraisal > 0):
                    ReuseableCode::import_expense($request->pv_date_1, $master_id, $pv_no, $desc, $request->appraisal, $codes['appraisal']);
                endif;


                if ($request->insurance > 0):
                    ReuseableCode::import_expense($request->pv_date_1, $master_id, $pv_no, $desc, $request->insurance, $codes['insurance']);
                endif;


                if ($request->fright > 0):
                    ReuseableCode::import_expense($request->pv_date_1, $master_id, $pv_no, $desc, $request->fright, $codes['fright']);
                endif;


                if ($request->expense > 0):
                    ReuseableCode::import_expense($request->pv_date_1, $master_id, $pv_no, $desc, $request->expense, $codes['expense']);
                endif;


                if ($request->other_expense > 0):
                    ReuseableCode::import_expense($request->pv_date_1, $master_id, $pv_no, $desc, $request->other_expense, $codes['other_expense']);
                endif;

                $total = $request->duty + $request->eto + $request->do + $request->appraisal + $request->insurance + $request->fright + $request->expense + $request->other_expense;

                $pv_data = new NewPvData();
                $pv_data = $pv_data->SetConnection('mysql2');
                $pv_data->master_id = $master_id;
                $pv_data->pv_no     = $pv_no;
                $pv_data->pv_date   = $request->pv_date_1;

                $pv_data->acc_id = $request->cr_account;
                $pv_data->description = $desc;
                $pv_data->amount = $total;
                $pv_data->debit_credit = 0;
                $pv_data->status = 1;
                $pv_data->pv_status = 1;
                $pv_data->date = date('Y-m-d');
                $pv_data->save();

            endif;
            DB::Connection('mysql2')->commit();
        } catch (Exception $ex) {


            DB::rollBack();
        }
    }

    function addCustomerOpeningBalance(Request $request)
    {
       
        $BuyerId = $request->customer_id;
        $CoDate = $request->co_date;
        $SiNo = $request->si_no;
        $SoNo = $request->so_no;
        $InvoiceAmount = $request->invoice_amount;
        $BalanceAmount = $request->balance_amount;
        foreach ($CoDate as $key => $row):
            $InsertData['buyer_id'] = $BuyerId;
            $InsertData['date'] = $CoDate[$key];
            $InsertData['si_no'] = $SiNo[$key];
            $InsertData['so_no'] = $SoNo[$key];
            $InsertData['invoice_amount'] = $InvoiceAmount[$key];
            $InsertData['balance_amount'] = $BalanceAmount[$key];
            

            DB::Connection('mysql2')->table('customer_opening_balance')->insert($InsertData);
            ReuseableCode::insert_si($SiNo[$key]);
        endforeach;
        return Redirect::to('sales/createCustomerOpeningBalance?m=1');
    }


// function addCustomerOpeningBalance(Request $request)
// {
//     DB::connection('mysql2')->beginTransaction();


    
//     try {
//         $BuyerId = $request->customer_id;
//         $CoDate = $request->co_date;
//         $SiNo = $request->si_no;
//         $SoNo = $request->so_no;
//         $InvoiceAmount = $request->invoice_amount;
//         $BalanceAmount = $request->balance_amount;

//         $customer_acc_id = SalesHelper::get_customer_acc_id($BuyerId);
        

//         foreach ($CoDate as $key => $row) {

//             // Insert into customer_opening_balance
//             $InsertData = [
//                 'buyer_id' => $BuyerId,
//                 'date' => $CoDate[$key],
//                 'si_no' => $SiNo[$key],
//                 'so_no' => $SoNo[$key],
//                 'invoice_amount' => $InvoiceAmount[$key],
//                 'balance_amount' => $BalanceAmount[$key],
//             ];

//             DB::connection('mysql2')->table('customer_opening_balance')->insert($InsertData);

//             // Create corresponding entry in transactions table
//             $transactionData = [
//                 'acc_id'        => $customer_acc_id,
//                 'v_date'        => $CoDate[$key],
//                 'amount'        => $InvoiceAmount[$key],
//                 'opening_bal'   => 0,
//                 'status'        => 1,
//                 'username'      => Auth::user()->name,
//                 'date'          => date('Y-m-d'),
//                 'debit_credit'  => 1, 
//                 'acc_code'      => FinanceHelper::getAccountCodeByAccId($customer_acc_id),
//             ];

//             DB::connection('mysql2')->table('transactions')->insert($transactionData);

//             // Optional reusable code
//             ReuseableCode::insert_si($SiNo[$key]);
//         }

//         DB::connection('mysql2')->commit();

//         return Redirect::to('sales/createCustomerOpeningBalance?m=1');
//     } catch (\Exception $e) {
//         DB::connection('mysql2')->rollBack();
//         dd($e->getMessage());
//     }
// }

    function addVendorOpeningBalance(Request $request)
    {
        DB::Connection('mysql2')->beginTransaction();
        try {



            $VendorId = $request->vendor_id;
            $VoDate = $request->vo_date;
            $PiNo = $request->pi_no;
            $PoNo = $request->po_no;
            $InvoiceAmount = $request->invoice_amount;
            $BalanceAmount = $request->balance_amount;
            $balance_amount = 0;
            $invoice_amount = 0;
            foreach ($VoDate as $key => $row):
                $InsertData['vendor_id'] = $VendorId;
                $InsertData['date'] = $VoDate[$key];
                $InsertData['pi_no'] = $PiNo[$key];
                $InsertData['po_no'] = $PoNo[$key];
                $InsertData['invoice_amount'] = $InvoiceAmount[$key];
                $InsertData['balance_amount'] = $BalanceAmount[$key];

                $balance_amount += $BalanceAmount[$key];
                $invoice_amount += $BalanceAmount[$key];
                DB::Connection('mysql2')->table('vendor_opening_balance')->insert($InsertData);
                ReuseableCode::insert_pv($PiNo[$key]);
           
            endforeach;

            ReuseableCode::hit_ledger_vendor_opening($VendorId, $VoDate);



            DB::Connection('mysql2')->commit();
        } catch (Exception $ex) {


            DB::rollBack();
        }

        Session::flash("success", "Vendor Opening balance has been created");
        return Redirect::to('sales/creatVendorOpeningBalance?m=1');
    }


    function add_pos(Request $request)
    {
        $data = $request->all();
        return View('Sales.pos_data', compact('data'));
    }

    function pos_data(Request $request)
    {

        DB::Connection('mysql2')->beginTransaction();
        try {

            $pos_no = SalesHelper::uniqe_no_for_pos(date('y'), date('m'), 1);
            $data = array(
                'pos_no' => $pos_no,
                'pos_date' => $request->pos_datee,
                'customer_name' => $request->customer_namee,
                'customer_contact_no' => $request->customer_contact_noo,
                'ref_no' => $request->ref_noo,
                'ledger_id' => 813,
                'status' => 1,
                'date' => date('Y-m-d'),
                'username' => Auth::user()->name,

            );
            $id = DB::Connection('mysql2')->table('pos')->insertGetId($data);

            $pos_data = $request->sub_ic_dess;
            $total_cost = 0;
            $total_amount = 0;
            $net_total = 0;
            $cash_sales_total = 0;
            foreach ($pos_data as $key => $row):
                $total_amount += $request->input('after_dis_amountt')[$key];
                $net_total += $request->input('after_dis_amountt')[$key];
                $cash_sales_total += $request->input('after_dis_amountt')[$key];
                $data1 = array(
                    'master_id' => $id,
                    'pos_no' => $pos_no,
                    'item_id' => $request->input('item_idd')[$key],
                    'item_des' => $row,
                    'batch_code' => $request->input('batch_codee')[$key],
                    'warehouse_id' => $request->location_idd,
                    'qty' => $request->input('actual_qtyy')[$key],
                    'rate' => $request->input('ratee')[$key],
                    'amount' => $request->input('amountt')[$key],
                    'discount_percent' => $request->input('discount_percentt')[$key],
                    'discount_amount' => $request->input('discount_amountt')[$key],
                    'net_amount' => $request->input('after_dis_amountt')[$key],
                    'additional_exp' => 0,
                    'status' => 1,
                    'date' => date('Y-m-d'),
                    'username' => Auth::user()->name,

                );
                $master_id = DB::Connection('mysql2')->table('pos_data')->insertGetId($data1);


                $average_cost = ReuseableCode::average_cost_sales($request->input('item_idd')[$key], $request->location_idd, $request->input('batch_codee')[$key]);
                $total_cost += $average_cost * $request->input('actual_qtyy')[$key];
                $data2 = array(
                    'main_id' => $id,
                    'master_id' => $master_id,
                    'voucher_no' => $pos_no,
                    'voucher_date' => $request->pos_datee,
                    'voucher_type' => 5,
                    'sub_item_id' => $request->input('item_idd')[$key],
                    'batch_code' => $request->input('batch_codee')[$key],
                    'qty' => $request->input('actual_qtyy')[$key],
                    'rate' => $request->input('ratee')[$key],
                    'amount_before_discount' => $request->input('amountt')[$key],
                    'discount_percent' => $request->input('discount_percentt')[$key],
                    'discount_amount' => $request->input('discount_amountt')[$key],
                    'amount' => $average_cost * $request->input('actual_qtyy')[$key],
                    'warehouse_id' => $request->location_idd,
                    'status' => 1,
                    'created_date' => date('Y-m-d'),
                    'username' => Auth::user()->name,
                    'pos_status' => 1,

                );
                DB::Connection('mysql2')->table('stock')->insertGetId($data2);


            endforeach;



            $transaction = new Transactions();
            $transaction = $transaction->SetConnection('mysql2');
            $transaction->voucher_no = $pos_no;
            $transaction->v_date = $request->pos_datee;
            $transaction->acc_id = 768;
            $transaction->acc_code = FinanceHelper::getAccountCodeByAccId(768);
            $transaction->particulars = $request->description;
            $transaction->opening_bal = 0;
            $transaction->debit_credit = 1;
            $transaction->amount = $total_cost;
            $transaction->username = Auth::user()->name;;
            $transaction->status = 1;
            $transaction->voucher_type = 8;
            $transaction->save();


            $transaction = new Transactions();
            $transaction = $transaction->SetConnection('mysql2');
            $transaction->voucher_no = $pos_no;
            $transaction->v_date = $request->pos_datee;
            $transaction->acc_id = 97;
            $transaction->acc_code = FinanceHelper::getAccountCodeByAccId(97);
            $transaction->particulars = $request->description;
            $transaction->opening_bal = 0;
            $transaction->debit_credit = 0;
            $transaction->amount = $total_cost;
            $transaction->username = Auth::user()->name;;
            $transaction->status = 1;
            $transaction->voucher_type = 8;
            $transaction->save();



            $additional_exp = $request->account_idd;

            if (!empty($additional_exp)):
                foreach ($additional_exp as $key => $row):
                    //   $total_amount+=$request->input('expense_amountt')[$key];
                    $net_total += $request->input('expense_amountt')[$key];
                    $data1 = array(
                        'master_id' => $id,
                        'pos_no' => $pos_no,
                        'item_id' => 0,
                        'item_des' => 0,
                        'batch_code' => 0,
                        'warehouse_id' => $request->location_idd,
                        'qty' => 0,
                        'rate' => 0,
                        'amount' => $request->input('expense_amountt')[$key],
                        'discount_percent' => 0,
                        'discount_amount' => 0,
                        'net_amount' => $request->input('expense_amountt')[$key],
                        'additional_exp' => 1,
                        'status' => 1,
                        'acc_id' => $row,
                        'date' => date('Y-m-d'),
                        'username' => Auth::user()->name,

                    );
                    DB::Connection('mysql2')->table('pos_data')->insertGetId($data1);

                endforeach;
            endif;


            $transaction = new Transactions();
            $transaction = $transaction->SetConnection('mysql2');
            $transaction->voucher_no = $pos_no;
            $transaction->v_date = $request->pos_datee;
            $transaction->acc_id = 380;
            $transaction->acc_code = FinanceHelper::getAccountCodeByAccId(380);
            $transaction->particulars = $request->description;
            $transaction->opening_bal = 0;
            $transaction->debit_credit = 1;
            $transaction->amount = $net_total;
            $transaction->username = Auth::user()->name;;
            $transaction->status = 1;
            $transaction->voucher_type = 11;
            $transaction->save();


            $transaction = new Transactions();
            $transaction = $transaction->SetConnection('mysql2');
            $transaction->voucher_no = $pos_no;
            $transaction->v_date = $request->pos_datee;
            $transaction->acc_id = 813;
            $transaction->acc_code = FinanceHelper::getAccountCodeByAccId(813);
            $transaction->particulars = $request->description;
            $transaction->opening_bal = 0;
            $transaction->debit_credit = 0;
            $transaction->amount = $total_amount;
            $transaction->username = Auth::user()->name;;
            $transaction->status = 1;
            $transaction->voucher_type = 11;
            $transaction->save();


            if (!empty($additional_exp)):
                foreach ($additional_exp as $key => $row):
                    $transaction = new Transactions();
                    $transaction = $transaction->SetConnection('mysql2');
                    $transaction->voucher_no = $pos_no;
                    $transaction->v_date = $request->pos_datee;
                    $transaction->acc_id = $row;
                    $transaction->acc_code = FinanceHelper::getAccountCodeByAccId($row);
                    $transaction->particulars = $request->description;
                    $transaction->opening_bal = 0;
                    $transaction->debit_credit = 0;
                    $transaction->amount = $request->input('expense_amountt')[$key];
                    $transaction->username = Auth::user()->name;;
                    $transaction->status = 1;
                    $transaction->voucher_type = 11;
                    $transaction->save();


                endforeach;
            endif;







            DB::Connection('mysql2')->commit();
        } catch (Exception $ex) {


            DB::rollBack();
        }
    }


    function pos_return(Request $request)
    {

        DB::Connection('mysql2')->beginTransaction();
        try {


            $cr_no = SalesHelper::generateCreditNotNo(date('y'), date('m'));

            $cr_no = SalesHelper::generateCreditNotNo(date('y'), date('m'));
            $credit_note = new CreditNote();
            $credit_note = $credit_note->SetConnection('mysql2');

            $credit_note->so_id = 0;
            $credit_note->pos_id = $request->pos_id;
            $credit_note->cr_no = $cr_no;
            $credit_note->cr_date = date('Y-m-d');
            $credit_note->buyer_id = 123;
            $credit_note->description = $request->description_1;
            $credit_note->sales_tax = 0;
            $credit_note->sales_tax_further = 0;
            $credit_note->create_date = date('Y-m-d');
            $credit_note->status = 1;
            $credit_note->type = 3;
            $credit_note->username = Auth::user()->name;
            $credit_note->save();
            $id = $credit_note->id;





            $count = count($request->return_qty);
            $count = $count - 1;
            $total_amout = 0;
            $total_cost_amount = 0;
            for ($i = 0; $i <= $count; $i++):
                $credit_note_data = new CreditNoteData();
                $credit_note_data = $credit_note_data->SetConnection('mysql2');
                $credit_note_data->master_id = $id;
                $credit_note_data->voucher_data_id = $request->input('pos_data_id')[$i];

                $credit_note_data->voucher_no = $request->input('pos_no');;
                $credit_note_data->voucher_date = $request->input('pos_date');;
                $credit_note_data->item = $request->input('item_id')[$i];;

                $qty = CommonHelper::check_str_replace($request->input('return_qty')[$i]);
                $rate = CommonHelper::check_str_replace($request->input('rate')[$i]);


                $credit_note_data->qty = $qty;
                $credit_note_data->rate = $rate;
                $amount = $qty * $rate;
                $credit_note_data->amount = $amount;


                $discount_percent = CommonHelper::check_str_replace($request->input('discount_percent')[$i]);

                if ($discount_percent > 0):

                    $discount_amount = ($discount_percent * $amount) / 100;
                    $net_amount = $amount - $discount_amount;
                else:
                    $discount_amount = 0;
                    $net_amount = $amount;
                endif;

                $credit_note_data->discount_percent = $discount_percent;
                $credit_note_data->discount_amount = $discount_amount;
                $credit_note_data->net_amount = $net_amount;
                $credit_note_data->batch_code = $request->input('batch_code')[$i];

                $credit_note_data->date = date("Y-m-d");
                $credit_note_data->type = 3;
                $credit_note_data->status = 1;
                $credit_note_data->username = Auth::user()->name;
                $credit_note_data->save();
                $master_data_id = $credit_note_data->id;

                $amount = CommonHelper::check_str_replace($request->input('net_amount')[$i]);


                $stock_rate = DB::Connection('mysql2')->table('stock')
                    ->where('master_id', $request->input('pos_data_id')[$i])
                    ->where('voucher_type', 5)
                    ->where('pos_status', 1)
                    ->where('voucher_no', $request->pos_no)
                    ->select('amount', 'qty')
                    ->first();
                $stock_rate = $stock_rate->amount / $stock_rate->qty;
                $stock = array(
                    'main_id' => $id,
                    'master_id' => $master_data_id,
                    'voucher_no' => $cr_no,
                    'voucher_date' => date('Y-m-d'),
                    'supplier_id' => 0,
                    'customer_id' => 123,
                    'batch_code' => $request->input('batch_code')[$i],
                    'voucher_type' => 6,
                    'rate' => $rate,
                    'sub_item_id' => $request->input('item_id')[$i],
                    'qty' => $qty,
                    'discount_percent' => $discount_percent,
                    'discount_amount' => $discount_amount,
                    'amount' => $qty * $stock_rate,
                    'status' => 1,
                    'warehouse_id' => $request->input('warehouse_id')[$i],
                    'username' => Auth::user()->username,
                    'created_date' => date('Y-m-d'),
                    'created_date' => date('Y-m-d'),
                    'opening' => 0,
                );
                $cost = $qty * $stock_rate;
                $total_cost_amount += $cost;
                $total_amout += $net_amount;
                DB::Connection('mysql2')->table('stock')->insert($stock);
            endfor;




            $transaction = new Transactions();
            $transaction = $transaction->SetConnection('mysql2');
            $transaction->voucher_no = $cr_no;
            $transaction->v_date = date('Y-m-d');
            $transaction->acc_id = 814;
            $transaction->acc_code = FinanceHelper::getAccountCodeByAccId(814);
            $transaction->particulars = $request->description_1;
            $transaction->opening_bal = 0;
            $transaction->debit_credit = 1;
            $transaction->amount = $total_amout;
            $transaction->username = Auth::user()->name;;
            $transaction->status = 1;
            $transaction->voucher_type = 7;
            $transaction->save();








            $customer_acc_id = SalesHelper::get_customer_acc_id(123);;
            $transaction = new Transactions();
            $transaction = $transaction->SetConnection('mysql2');
            $transaction->voucher_no = $cr_no;
            $transaction->v_date = date('Y-m-d');
            $transaction->acc_id = $customer_acc_id;
            $transaction->acc_code = FinanceHelper::getAccountCodeByAccId($customer_acc_id);
            $transaction->particulars = $request->description_1;
            $transaction->opening_bal = 0;
            $transaction->debit_credit = 0;
            $transaction->amount = $total_amout;
            $transaction->username = Auth::user()->name;;
            $transaction->status = 1;
            $transaction->voucher_type = 7;
            $transaction->save();







            $data_collection = DB::Connection('mysql2')->table('stock')->where('voucher_no', $request->input('credit_not_no'))->where('status', 1);
            $data = $data_collection->first();
            $amount = $data_collection->sum('amount');

            $transaction = new Transactions();
            $transaction = $transaction->SetConnection('mysql2');
            $transaction->voucher_no = $cr_no;
            $transaction->v_date = date('Y-m-d');
            $transaction->acc_id = 97;
            $transaction->acc_code = '1-2-1-1';
            $transaction->particulars = $request->description_1;
            $transaction->opening_bal = 0;
            $transaction->debit_credit = 1;
            $transaction->amount = $total_cost_amount;
            $transaction->username = Auth::user()->name;;
            $transaction->status = 1;
            $transaction->voucher_type = 9;
            $transaction->save();


            $transaction = new Transactions();
            $transaction = $transaction->SetConnection('mysql2');
            $transaction->voucher_no = $cr_no;
            $transaction->v_date = date('Y-m-d');
            $transaction->acc_id = 768;
            $transaction->acc_code = '6-1';
            $transaction->particulars = $request->description_1;
            $transaction->opening_bal = 0;
            $transaction->debit_credit = 0;
            $transaction->amount = $total_cost_amount;
            $transaction->username = Auth::user()->name;;
            $transaction->status = 1;
            $transaction->voucher_type = 9;
            $transaction->save();






            DB::Connection('mysql2')->commit();
        } catch (Exception $ex) {

            DB::rollBack();
        }
        return Redirect::to('sales/viewCustomerCreditNoteList?pageType=view&&parentCode=000&&m=' . Session::get('run_company') . '#SFR');
    }

    function  update_cost(Request $request)
    {
        $id = $request->id;
        $value = $request->value;
        $update['amount'] = $value;
        DB::Connection('mysql2')->table('stock')->where('id', $id)->update($update);
    }


    function set_cost(Request $request)
    {

        if ($request->type == 1):
            $data =  DB::Connection('mysql2')->select('select sum(a.amount)amount,b.buyers_id from sales_tax_invoice_data a
        inner join
        sales_tax_invoice b
        on
        a.master_id=b.id
        where b.status=1
        and b.so_type=0
        group by b.buyers_id');

            foreach ($data as $row):
                $data1 = array(
                    'buyers' => $row->buyers_id,
                    'gross' => $row->amount,
                    'cost' => 0,
                    'net' => 0
                );
                DB::Connection('mysql2')->table('si_criteria')->insert($data1);
            endforeach;
        endif;



        if ($request->type == 2):
            $data =  DB::Connection('mysql2')->select('select sum(a.amount)amount,b.buyers_id from transactions a
        inner join
        sales_tax_invoice b
        on
        a.voucher_no=b.gi_no
        where b.status=1
        and a.status=1
        and a.voucher_type=8
        and a.debit_credit=1
        group by b.buyers_id');

            foreach ($data as $row):
                $data1 = array(
                    'cost' => $row->amount,
                );

                $gross = DB::Connection('mysql2')->table('si_criteria')->where('buyers', $row->buyers_id)->select('gross')->value('gross');
                $data1['net'] = $gross - $row->amount;
                DB::Connection('mysql2')->table('si_criteria')->where('buyers', $row->buyers_id)->Update($data1);
            endforeach;
        endif;


        if ($request->type == 3):
            $data =  DB::Connection('mysql2')->select('select sum(a.amount)amount,b.buyer_id from transactions a
        inner join
        credit_note b
        on
        a.voucher_no=b.cr_no
        where b.status=1
        and a.status=1
        and a.voucher_type=9
        and a.debit_credit=1
        group by b.buyer_id');

            foreach ($data as $row):


                $gross = DB::Connection('mysql2')->table('si_criteria')->where('buyers', $row->buyer_id)->select('gross', 'cost')->first();

                $cost = $gross->cost - $row->amount;
                $data1['cost'] = $cost;
                $data1['back_off'] = $row->amount;
                $data1['net'] = $gross->gross - $cost;
                DB::Connection('mysql2')->table('si_criteria')->where('buyers', $row->buyer_id)->Update($data1);
            endforeach;
        endif;
    }


    function set_cogs() {}

    public  function uploadFile()
    {
        return view('uploadDocument');
    }

    public  function uploadCustomer(Request $request)
    {
        DB::Connection('mysql2')->beginTransaction();
        $errorCheck = "";

        try {

            if ($request->file('file')) {

                $file = Excel::toArray([], $request->file('file'));
                $html = '';
                $file = $file[0];

                foreach ($file as $key => $value) :
                    if ($key == 0) continue;
                    $item  =  new Subitem();
                    $item->item_code =  $value[1];
                    $item->sub_ic =   $value[1];
                    $item->main_ic_id =  8;
                    $item->sub_category_id =  1;
                    $item->uom = 4;
                    $item->stockType = 2;
                    $item->itemType = 3;
                    $item->sku_code = $value[1];
                    $item->type = 3;
                    $item->date = date('y-m-d');
                    $item->save();
                endforeach;
                
            }
            DB::Connection('mysql2')->commit();
            return redirect()->back()->with('dataInsert', 'Item create SuccessFully');
        } catch (Exception $e) {
            DB::Connection('mysql2')->rollback();
            echo "EROOR "; //die();
            echo $e->getLine(); //die();
            echo "<br>" . $errorCheck; //die();
            dd($e->getMessage(), $e->getTraceAsString());
        }
    }
}
