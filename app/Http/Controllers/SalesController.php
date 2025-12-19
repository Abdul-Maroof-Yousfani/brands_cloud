<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Models\Quotation_Data;
use Illuminate\Http\Request;
use Auth;
use DB;
use Config;
use App\Helpers\CommonHelper;
use App\Models\SubDepartment;
use App\Models\Account;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Countries;
use App\Models\BankDetail;
use App\Models\Subitem;
use App\Models\Sales_Order;
use App\Models\Sales_Order_Data;
use App\Models\CreditNote;
use App\Models\CreditNoteData;
use App\Models\Region;
use App\Models\Cities;
use App\Models\Type;
use App\Models\Conditions;
use App\Models\SurveryBy;
use App\Models\Client;
use App\Models\Branch;
use App\Models\Survey;
use App\Models\SurveyData;
use App\Models\SurveyDocument;
use App\Models\JobTracking;
use App\Models\ProductType;
use App\Models\ResourceAssigned;
use App\Models\Quotation;
use App\Models\Complaint;
use App\Models\ComplaintProduct;
use App\Models\InvDesc;
use App\Models\NewRvs;
use App\Models\NewRvData;
use App\Models\Supplier;
use App\Models\ProductTrend;
use App\Models\StoresCategory;
use App\Models\ProductClassification;
use App\Helpers\NotificationHelper;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Helpers\ReuseableCode;
use Input;
use App\Models\DeliveryNote;

use App\Helpers\SalesHelper;

use App\Models\DeliveryNoteData;
use App\Models\SalesTaxInvoice;
use App\Models\SalesTaxInvoiceData;
use App\Models\JobOrder;
use App\Models\JobOrderData;
use App\Models\Invoice;
use App\Models\InvoiceData;
use App\Models\Product;
use App\Models\Territory;
use App\Models\CustomerType;
use App\Models\SpecialPrice;
use App\Models\CustomerSpecialPrice;
use App\Models\ClientJob;
use App\Models\LOGACTIVITY;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use App\Models\ComplaintDocument;

use App\Imports\ProductsImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CustomersExport;

class SalesController extends Controller
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
public function exportCustomers(Request $request)
{
   
    CommonHelper::companyDatabaseConnection($request->m);

    return Excel::download(new CustomersExport($request->all()), 'CustomerList.xlsx');
}
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function toDayActivity()
    {
        return view('Sales.toDayActivity');
    }

    public function addOpeningAgainstCustomerForm()
    {
        return view('Sales.addOpeningAgainstCustomerForm');
    }

    public function addReceiptVoucherAgainstSOForm()
    {
        // $salesOrdersListApprovedAndNotReceived = DB::Connection('mysql2')->table('sales_order')->where('so_status',4)->where('delivery_note_status',0)->where('amount_received_status',1)->get();
        $salesQuotationListApprovedAndNotReceived = DB::Connection('mysql2')->table('sale_quotations')->where('approved_status', 1)->get();
        return view('Sales.addReceiptVoucherAgainstSOForm', compact('salesQuotationListApprovedAndNotReceived'));
    }

    public function topFiveSalesReportPage()
    {
        $Customers = DB::Connection('mysql2')->table('customers')->where('status', 1)->get();
        return view('Sales.topFiveSalesReportPage', compact('Customers'));
    }




    // public function uploadCreditCustomer(request $request)
    // {

    //     DB::Connection('mysql2')->beginTransaction();
    //     try {

    //     $fileMimes = array(
    //         // 'text/x-comma-separated-values',
    //         // 'text/comma-separated-values',
    //         // 'application/octet-stream',
    //         // 'application/vnd.ms-excel',
    //         'application/x-csv',
    //         'text/x-csv',
    //         'text/csv',
    //         'application/csv',
    //         // 'application/excel',
    //         // 'application/vnd.msexcel',
    //         // 'text/plain'
    //     );

    //     // Validate whether selected file is a CSV file
    //     if (!empty($_FILES['file']['name']) && in_array($_FILES['file']['type'], $fileMimes)) {

    //         $row = 0;
    //         // add you row number for skip
    //         // hear we pass 1st row for skip in csv
    //         $skip_row_number = array("1");

    //         // Open uploaded CSV file with read-only mode
    //         $csvFile = fopen($_FILES['file']['tmp_name'], 'r');

    //         // Skip the first line
    //         fgetcsv($csvFile);

    //         // Parse data from CSV file line by line
    //         // Parse data from CSV file line by line
    //         while (($getData = fgetcsv($csvFile, 10000, ",")) !== false) {

    //             if (in_array($row, $skip_row_number)) {
    //                 continue;
    //                 // skip row of csv
    //             } else {

    //                 if ($getData[0] && $getData[1] && $getData[2]) {

    //                     (!empty($getData[1])) ? $city = DB::connection('mysql')->table('cities')->whereRaw('LOWER(name) = ?', [strtolower($getData[1])])->value('id') : 0;
    //                     CommonHelper::companyDatabaseConnection(Input::get('m'));



    //                     $account_head = Input::get('account_head');
    //                     $customer_code = SalesHelper::generateCustomerCode();
    //                     $customer_name = $getData[0];

    //                     if(DB::connection('mysql2')->table('customers')->where('status',1)->where('name',$customer_name)->count()>0){
    //                         continue;
    //                     }

    //                    $state=0;
    //                    $country=0;
    //                     if($city > 0){
    //                        $state= DB::connection('mysql')->table('cities')->select('state_id')->where('id',$city)->value('state_id');
    //                        $country = DB::connection('mysql')->table('states')->select('country_id')->where('id',$state)->value('country_id');

    //                     }


    //                     $contact_person = $getData[2];
    //                     $contact_no = $getData[3] ?? 0;
    //                     // $fax = $getData[4];
    //                     $address = $getData[4] ?? '-';
    //                     $email = $getData[5] ?? '-';
    //                     $o_blnc_trans = Input::get('o_blnc_trans');
    //                     $o_blnc = Input::get('o_blnc');
    //                     $operational = '1';
    //                     $customer_type = '3';
    //                     $ntn = $getData[6] ?? '-';
    //                     $strn = $getData[7] ?? '-';
    //                     $dicount = $getData[9] ?? 0;
    //                     $credit_days = $getData[10] ?? 0;




    //                     $account_head ='Trade Payable';
    //                     $sent_code='1-2-2';//'Trade Receivables';
    //                     // $sent_code = $account_head;

    //                     $max_id = DB::Connection('mysql2')->selectOne('SELECT max(`id`) as id  FROM `accounts` WHERE `parent_code` LIKE \'' . $sent_code . '\'')->id;

    //                     if ($max_id == '') {
    //                         $code = $sent_code . '-1';
    //                     } else {
    //                         $max_code2 = DB::Connection('mysql2')->selectOne('SELECT `code`  FROM `accounts` WHERE `id` LIKE \'' . $max_id . '\'')->code;
    //                         $max_code2;
    //                         $max = explode('-', $max_code2);
    //                         $code = $sent_code . '-' . (end($max) + 1);
    //                     }

    //                     $level_array = explode('-', $code);
    //                     $counter = 1;
    //                     foreach ($level_array as $level):
    //                         $data1['level' . $counter] = strip_tags($level);
    //                         $counter++;
    //                     endforeach;

    //                         $data1['code'] = strip_tags($code);
    //                         $data1['name'] =$customer_name;
    //                         $data1['parent_code'] = strip_tags($sent_code);
    //                         $data1['username'] = Auth::user()->name;
    //                         $data1['date'] = date("Y-m-d");
    //                         $data1['time'] = date("H:i:s");
    //                         $data1['action'] = 'create';
    //                         $data1['type'] = 1;
    //                         $data1['operational'] = 1;
    //                         $acc_id = DB::Connection('mysql2')->table('accounts')->insertGetId($data1);

    //                     $data2['acc_id']		     = $acc_id;
    //                     $data2['name']     		   = $customer_name;
    //                     $data2['customer_code']   = $customer_code;
    //                     $data2['country']     		= $country ?? 0;
    //                     $data2['province']     	   = $state ?? 0;
    //                     $data2['city']     	       = $city ?? 0;
    //                     $data2['cnic_ntn']   		      = $ntn ?? '';
    //                     $data2['strn']   		      = $strn ?? '';
    //                     $data2['contact_person']   		    = $contact_person ?? '';
    //                     $data2['contact']   		    = $contact_no ?? 0;
    //                     $data2['fax']   		    = $fax ?? '';
    //                     $data2['address']   		      = $address ?? '';

    //                     $data2['email']   		      = $email ?? '';
    //                     $data2['username']	 	   = Auth::user()->name;
    //                     $data2['date']     		   = date("Y-m-d");
    //                     $data2['time']     		   = date("H:i:s");
    //                     $data2['action']     		 = 'create';
    //                     $data2['customer_type']     = $customer_type;
    //                     $data2['terms_of_payment']     = $getData[8] ?? 0;
    //                     $data2['credit_days']     = $credit_days;
    //                     $data2['discount_percent']     = $dicount;
    //                     $CustId = DB::table('customers')->insertGetId($data2);

    //                     $data3['acc_id'] =	$acc_id;
    //                     $data3['acc_code']=	$code;
    //                     $data3['debit_credit']=1;
    //                     $data3['amount'] 	  =0.00;
    //                     $data3['opening_bal'] 	  = 	1;
    //                     $data3['username'] 		 	= Auth::user()->name;
    //                     $data3['date']     		  = date("Y-m-d");
    //                     $data3['v_date']     		= '2023-07-01';
    //                     $data3['time']     		  = date("H:i:s");
    //                     $data3['action']     		  = 'create';
    //                     DB::table('transactions')->insert($data3);

    //                     $contact_person_more = Input::get('contact_person_more');
    //                     $contact_no_more  = Input::get('contact_no_more');
    //                     $fax_more  = Input::get('fax_more');
    //                     $address_more  = Input::get('address_more');
    //                     if(isset($contact_person_more)):
    //                     foreach($contact_person_more as $key => $row)
    //                     {
    //                         if($contact_person_more[$key] != "" || $contact_no_more[$key] !="" || $fax_more[$key] !="" || $address_more[$key] !="")
    //                         {
    //                             $InfoData['cust_id'] = $CustId;
    //                             $InfoData['contact_person'] = $contact_person_more[$key] ?? '';
    //                             $InfoData['contact_no'] = $contact_no_more[$key] ?? 0;
    //                             $InfoData['fax'] = $fax_more[$key] ?? '';
    //                             $InfoData['address'] = $address_more[$key] ?? '';
    //                             DB::Connection('mysql2')->table('customer_info')->insert($InfoData);
    //                         }
    //                     }
    //                         endif;
    //                     CommonHelper::reconnectMasterDatabase();
    //                 }

    //             }

    //         }

    //         // Close opened CSV file
    //         fclose($csvFile);

    //         CommonHelper::reconnectMasterDatabase();
    //         Session::flash('dataInsert', 'Successfully Saved.');

    //     } else {
    //         Session::flash('dataDelete', 'Please upload csv file');

    //     }

    //         DB::Connection('mysql2')->commit();

    //     }
    //     catch ( Exception $ex )
    //     {


    //         DB::rollBack();
    //         dd($ex->getMessage());

    //     }

    //     return Redirect::to('/sales/createCreditCustomerForm?pageType='.Input::get('pageType').'&&parentCode='.Input::get('parentCode').'&&m='.Input::get('m').'#SFR');

    // }

    // public function uploadCreditCustomer(Request $request)
    // {
    //     $file = $request->file('import_file');
    //     $data = array_map('str_getcsv', file($file->getRealPath()));

    //     foreach ($data as $key => $row) {
    //         if ($key == 0) {
    //             continue; // Skip the header row
    //         }

    //         $account_head = 'Trade Payable';
    //         $sent_code = '1-2-2';

    //         $max_id = DB::Connection('mysql2')->selectOne('SELECT max(`id`) as id  FROM `accounts` WHERE `parent_code` LIKE \'' . $sent_code . '\'')->id;
    //         if ($max_id == '') {
    //             $code = $sent_code . '-1';
    //         } else {
    //             $max_code2 = DB::Connection('mysql2')->selectOne('SELECT `code`  FROM `accounts` WHERE `id` LIKE \'' . $max_id . '\'')->code;
    //             $max = explode('-', $max_code2);
    //             $code = $sent_code . '-' . (end($max) + 1);
    //         }
    //         // $wareHouseFrom = isset($row[41]) && $row[41] != ""  ? $row[41] : NULL;
    //         // $wareHouseFromId = 0;
    //         // if($wareHouseFrom){
    //         //     $wareHouseFromId = DB::connection('mysql2')->table('warehouse')->select('id')->where('name',$wareHouseFrom)->value('id');
    //         // }
    //         // $wareHouseToId = NULL;
    //         // if(isset($row[42]) && $row[42] != ""){
    //         //     $wareHouseToId = DB::connection('mysql2')->table('warehouse')->select('id')->where('name',$row[42])->value('id');
    //         // }

    //         $level_array = explode('-', $code);
    //         $counter = 1;
    //         foreach ($level_array as $level):
    //             $data1['level' . $counter] = strip_tags($level);
    //             $counter++;
    //         endforeach;
    //         // dd($row[28]);

    //         $data1['code'] = strip_tags($code);
    //         $data1['name'] = $row[0];
    //         $data1['parent_code'] = strip_tags($sent_code);
    //         $data1['username'] = Auth::user()->name;
    //         $data1['date'] = date("Y-m-d");
    //         $data1['time'] = date("H:i:s");
    //         $data1['action'] = 'create';
    //         $data1['type'] = 1;
    //         $data1['operational'] = 1;
    //         $acc_id = DB::Connection('mysql2')->table('accounts')->insertGetId($data1);

    //         $row = array_pad($row, 42, null);
    //         // dd($row);
    //         $cityData = CommonHelper::get_city_id_by_name($row[7]);
    //         $cityid = $cityData->id ?? 0;
    //         $stateid = $cityData->state_id ?? 0;
    //         $countryid = CommonHelper::get_country_id_by_state_id($stateid);
    //         $customerData = [
    //             'acc_id' => $acc_id,
    //             'store_code' => SalesHelper::generateCustomerCode(),
    //             'name' => !empty($row[0]) ? $row[0] : "",
    //             'customer_code' => !empty($row[1]) ? $row[1] : "",
    //             'email' => !empty($row[2]) ? $row[2] : "",
    //             'phone_1' => !empty($row[3]) ? $row[3] : "",
    //             'phone_2' => !empty($row[4]) ? $row[4] : null,
    //             'country' => $countryid, // Assuming this is set elsewhere
    //             'province' => $stateid, // Assuming this is set elsewhere
    //             'city' => $cityid ?? 0, // Assuming this is set elsewhere
    //             'address' => !empty($row[8]) ? $row[8] : "",
    //             'zip' => !empty($row[9]) ? $row[9] : "",
    //             'title' => !empty($row[10]) ? $row[10] : "",
    //             'contact_person' => !empty($row[11]) ? $row[11] : "",
    //             'contact_person_email' => !empty($row[12]) ? $row[12] : "",
    //             'company_shipping_type' => !empty($row[13]) ? $row[13] : "",
    //             'shipping_city' => !empty($row[14]) ? $row[14] : "",
    //             'shipping_state' => !empty($row[15]) ? $row[15] : "",
    //             'shipping_country' => !empty($row[16]) ? $row[16] : "",
    //             'opening_balance' => !empty($row[17]) ? $row[17] : 0,
    //             'opening_balance_date' => !empty($row[18]) ? $row[18] : null,
    //             'regd_in_income_tax' => !empty($row[19]) ? $row[19] : "",
    //             'cnic_ntn' => !empty($row[20]) ? $row[20] : "",
    //             'strn' => !empty($row[21]) ? $row[21] : "",
    //             'strn_term' => !empty($row[22]) ? $row[22] : "",
    //             'display_note_invoice' => !empty($row[23]) ? $row[23] : "",
    //             'wh_tax' => !empty($row[24]) ? $row[24] : "",
    //             'adv_tax' => !empty($row[44]) ? $row[44] : 0,
    //             'creditDaysLimit' => !empty($row[25]) ? $row[25] : 0,
    //             'creditLimit' => !empty($row[26]) ? $row[26] : 0,
    //             // 'locality' => $row[27],
    //             'locality' => 1,
    //             // 'store_category' => $row[28],
    //             'store_category' => CommonHelper::get_id_from_db_by_name($row[28], 'stores_categories') ?? 0,
    //             // 'territory_id' => $row[29],
    //             'territory_id' => CommonHelper::get_id_from_db_by_name($row[29], 'territories') ?? 0,
    //             // 'status' => !empty($row[38]) && $row[38] == "Active" ? 1 : 0,
    //             'status' => 1,
    //             // 'ba_mapping' => $row[43], need to add 43 index for ba mapping
    //             'ba_mapping' => $row[43] == "No" ? 0 : 1,
    //             'SaleRep' => !empty($row[30]) ? $row[30] : "",
    //             'accept_cheque' => !empty($row[31]) ? $row[31] : "",
    //             'display_pending_payment_invoice' => !empty($row[32]) ? $row[32] : "",
    //             'CustomerType' => CommonHelper::get_id_from_db_by_name($row[37], 'customer_types') ?? 0,
    //             'employee_id' => !empty($row[39]) ? $row[39] : null,
    //             'special_price_mapped' => !empty($row[40]) ? $row[40] : "",
    //             'warehouse_from' => CommonHelper::get_warehouse_id_by_name($row[41]) ?? null, // Assuming this is set elsewhere
    //             'warehouse_to' => $wareHouseToId ?? null, // Assuming this is set elsewhere
    //             'username' => Auth::user()->name,
    //             'date' => date("Y-m-d"),
    //             'time' => date("H:i:s"),
    //             'action' => 'create',
    //         ];

    //         $CustId = DB::Connection('mysql2')->table('customers')->insertGetId($customerData);

    //         $data3['acc_id'] =    $acc_id;
    //         $data3['acc_code'] =    $code;
    //         $data3['debit_credit'] = 1;
    //         $data3['amount']       = 0.00;
    //         $data3['opening_bal']       =     1;
    //         $data3['username']              = Auth::user()->name;
    //         $data3['date']               = date("Y-m-d");
    //         $data3['v_date']             = '2023-07-01';
    //         $data3['time']               = date("H:i:s");
    //         $data3['action']               = 'create';
    //         DB::Connection('mysql2')->table('transactions')->insert($data3);

    //         if ($row[31] === 'Yes') {
    //             // Insert bank details if "Accept Cheque" is "Yes"
    //             $bankData = [
    //                 'acc_id' => $acc_id,
    //                 'bank_name' => $row[35],
    //                 'account_title' => $row[34],
    //                 'account_no' => $row[33],
    //                 'swift_code' => $row[36],
    //                 'bank_address' => 'Karachi Pakistan',
    //                 'username' => Auth::user()->name,
    //                 'date' => date("Y-m-d"),
    //                 'status' => 1,
    //             ];

    //             DB::Connection('mysql2')->table('bank_detail')->insert($bankData);
    //         }
    //     }

    //     return Redirect::to('sales/viewCreditCustomerList?pageType=' . Input::get('pageType') . '&&parentCode=' . Input::get('parentCode') . '&&m=' . Input::get('m') . '#SFR');
    // }


    public function uploadCreditCustomer(Request $request)
{
    dd("test");
    $file = $request->file('import_file');
    $data = array_map('str_getcsv', file($file->getRealPath()));

    foreach ($data as $key => $row) {
        if ($key == 0) {
            continue; // Skip the header row
        }

        $row = array_pad($row, 45, null); // ensure enough indexes

        // city, state, country
        $cityData = CommonHelper::get_city_id_by_name($row[7]);
        $cityid = $cityData->id ?? 0;
        $stateid = $cityData->state_id ?? 0;
        $countryid = CommonHelper::get_country_id_by_state_id($stateid);

        $customerCode = !empty($row[1]) ? $row[1] : "";
        $customerData = [
            'name' => $row[0] ?? "",
            'customer_code' => $customerCode,
            'email' => $row[2] ?? "",
            'phone_1' => $row[3] ?? "",
            'phone_2' => $row[4] ?? null,
            'country' => $countryid,
            'province' => $stateid,
            'city' => $cityid,
            'address' => $row[8] ?? "",
            'zip' => $row[9] ?? "",
            'title' => $row[10] ?? "",
            'contact_person' => $row[11] ?? "",
            'contact_person_email' => $row[12] ?? "",
            'company_shipping_type' => $row[13] ?? "",
            'shipping_city' => $row[14] ?? "",
            'shipping_state' => $row[15] ?? "",
            'shipping_country' => $row[16] ?? "",
            'opening_balance' => $row[17] ?? 0,
            'opening_balance_date' => $row[18] ?? null,
            'regd_in_income_tax' => $row[19] ?? "",
            'cnic_ntn' => $row[20] ?? "",
            'strn' => $row[21] ?? "",
            'strn_term' => $row[22] ?? "",
            'display_note_invoice' => $row[23] ?? "",
            'wh_tax' => $row[24] ?? "",
            'adv_tax' => $row[44] ?? 0,
            'creditDaysLimit' => $row[25] ?? 0,
            'creditLimit' => $row[26] ?? 0,
            'locality' => 1,
            'store_category' => CommonHelper::get_id_from_db_by_name($row[28], 'stores_categories') ?? 0,
            'territory_id' => CommonHelper::get_id_from_db_by_name($row[29], 'territories') ?? 0,
            'status' => 1,
            'ba_mapping' => $row[43] == "No" ? 0 : 1,
            'SaleRep' => $row[30] ?? "",
            'accept_cheque' => $row[31] ?? "",
            'display_pending_payment_invoice' => $row[32] ?? "",
            'CustomerType' => CommonHelper::get_id_from_db_by_name($row[37], 'customer_types') ?? 0,
            'employee_id' => $row[39] ?? null,
            'special_price_mapped' => $row[40] ?? "",
            'warehouse_from' => CommonHelper::get_warehouse_id_by_name($row[41]) ?? null,
            'warehouse_to' => $row[42] ?? null,
            'username' => Auth::user()->name,
            'date' => date("Y-m-d"),
            'time' => date("H:i:s"),
            'action' => 'create',
        ];

        // --- Check if customer already exists ---
        $existingCustomer = DB::connection('mysql2')
            ->table('customers')
            ->where('customer_code', $customerCode)
            ->first();


        if ($existingCustomer && strlen($customerCode) > 0) {
         
            // --- Update existing customer ---
            DB::connection('mysql2')->table('customers')
                ->where('id', $existingCustomer->id)
                ->update($customerData);

        } else {
      
            // --- Insert new customer (with accounts & transactions) ---
            // generate account code
            $account_head = 'Trade Payable';
            $sent_code = '1-2-2';

            $max_id = DB::connection('mysql2')->table('accounts')
                ->where('parent_code', $sent_code)->max('id');

            if (!$max_id) {
                $code = $sent_code . '-1';
            } else {
                $max_code2 = DB::connection('mysql2')->table('accounts')
                    ->where('id', $max_id)->value('code');
                $max = explode('-', $max_code2);
                $code = $sent_code . '-' . (end($max) + 1);
            }

            // prepare account data
            $level_array = explode('-', $code);
            $counter = 1;
            $data1 = [];
            foreach ($level_array as $level) {
                $data1['level' . $counter] = strip_tags($level);
                $counter++;
            }
            $data1['code'] = $code;
            $data1['name'] = $row[0];
            $data1['parent_code'] = $sent_code;
            $data1['username'] = Auth::user()->name;
            $data1['date'] = date("Y-m-d");
            $data1['time'] = date("H:i:s");
            $data1['action'] = 'create';
            $data1['type'] = 1;
            $data1['operational'] = 1;

            $acc_id = DB::connection('mysql2')->table('accounts')->insertGetId($data1);

            // add acc_id & store_code
            $customerData['acc_id'] = $acc_id;
            $customerData['store_code'] = SalesHelper::generateCustomerCode();

            $CustId = DB::connection('mysql2')->table('customers')->insertGetId($customerData);

            // insert transaction
            $data3 = [
                'acc_id' => $acc_id,
                'acc_code' => $code,
                'debit_credit' => 1,
                'amount' => 0.00,
                'opening_bal' => 1,
                'username' => Auth::user()->name,
                'date' => date("Y-m-d"),
                'v_date' => '2023-07-01',
                'time' => date("H:i:s"),
                'action' => 'create',
            ];
            DB::connection('mysql2')->table('transactions')->insert($data3);

            // insert bank details if applicable
            if ($row[31] === 'Yes') {
                $bankData = [
                    'acc_id' => $acc_id,
                    'bank_name' => $row[35],
                    'account_title' => $row[34],
                    'account_no' => $row[33],
                    'swift_code' => $row[36],
                    'bank_address' => 'Karachi Pakistan',
                    'username' => Auth::user()->name,
                    'date' => date("Y-m-d"),
                    'status' => 1,
                ];
                DB::connection('mysql2')->table('bank_detail')->insert($bankData);
            }
        }
    }

    return Redirect::to('sales/viewCreditCustomerList?pageType=' . Input::get('pageType') . '&&parentCode=' . Input::get('parentCode') . '&&m=' . Input::get('m') . '#SFR');
}

    // public function uploadProductbkbk(Request $request)
    // {



    //     //dd($request);
    //     DB::Connection('mysql2')->beginTransaction();

    //     try {
    //         $file = $request->file('import_file');
    //         $data = array_map('str_getcsv', file($file->getRealPath()));

    //         $insertData = []; // To store bulk data for insertion
    //         foreach ($data as $key => $row) {
    //             if ($key == 0) {
    //                 continue; // Skip the header row
    //             }
    //             $sys_code = CommonHelper::generateUniqueNumber('ITEM-', 'subitem', 'sys_no');

    //             // $sys_code = CommonHelper::generateUniquePosNo('subitem','sys_no','ITEM');
    //             $row = array_pad($row, 28, null); // Ensure the array has at least 28 elements

    //             $insertData[] = [
    //                 'sys_no' => $sys_code,
    //                 'sku_code' => !empty($row[0]) ? $row[0] : null,
    //                 'product_name' => !empty($row[1]) ? $row[1] : null,
    //                 'product_description' => !empty($row[2]) ? $row[2] : null,
    //                 'uom' => CommonHelper::get_id_from_db_by_name_for_product($row[3], 'uom') ?? 0,
    //                 'packing' => !empty($row[4]) ? $row[4] : null,
    //                 'product_barcode' => !empty($row[5]) ? $row[5] : null,
    //                 'brand_id' => CommonHelper::get_id_from_db_by_name_for_product($row[6], 'brands') ?? 0,
    //                 'group_id' => CommonHelper::get_id_from_db_by_name_for_product($row[7], 'company_groups') ?? 0,
    //                 'main_ic_id' => CommonHelper::get_id_from_db_by_name_for_product($row[8], 'category') ?? 0,
    //                 'sub_category_id' => CommonHelper::get_id_from_db_by_name_for_product($row[9], 'sub_category') ?? 0,
    //                 'product_classification_id' => CommonHelper::get_id_from_db_by_name_for_product($row[10], 'product_classifications') ?? 0,
    //                 'product_type_id' => CommonHelper::get_id_from_db_by_name_for_product($row[11], 'product_type') ?? 0,
    //                 'product_trend_id' => CommonHelper::get_id_from_db_by_name_for_product($row[12], 'product_trends') ?? 0,
    //                 'purchase_price' => !empty($row[13]) ? $row[13] : 0,
    //                 'sale_price' => !empty($row[14]) ? $row[14] : 0,
    //                 'mrp_price' => !empty($row[15]) ? $row[15] : 0,
    //                 'is_tax_apply' => strtolower($row[16]) === 'yes' ? 1 : 0,
    //                 'tax_type_id' => CommonHelper::get_id_from_db_by_name_for_product($row[17], 'tax_types') ?? 0,
    //                 'tax_applied_on' => !empty($row[18]) ? $row[18] : null,
    //                 'tax' => !empty($row[19]) ? $row[19] : null,
    //                 'flat_discount' => !empty($row[20]) ? $row[20] : 0,
    //                 'min_qty' => !empty($row[21]) ? $row[21] : 0,
    //                 'max_qty' => !empty($row[22]) ? $row[22] : 0,
    //                 'hs_code' => CommonHelper::get_id_from_db_by_name_for_product($row[24], 'hs_codes') ?? 0,
    //                 'locality' => !empty($row[25]) ? $row[25] : null,
    //                 'origin' => !empty($row[26]) ? $row[26] : null,
    //                 'color' => !empty($row[27]) ? $row[27] : null,
    //                 'product_status' => $row[28],
    //                 'username' => Auth::user()->name,
    //                 'date' => date('Y-m-d')
    //             ];

    //             // Insert in batches of 500 rows to prevent memory issues
    //             if (count($insertData) == 5000) {
    //                 DB::Connection('mysql2')->table('subitem')->insert($insertData);
    //                 $insertData = []; // Clear array after insertion
    //             }
    //         }

    //         // Insert any remaining data
    //         if (!empty($insertData)) {
    //             DB::Connection('mysql2')->table('subitem')->insert($insertData);
    //         }

    //         DB::Connection('mysql2')->commit();
    //         return redirect()->back()->with('dataInsert', 'Products Uploaded Successfully');
    //     } catch (\Exception $e) {
    //         DB::Connection('mysql2')->rollBack(); // Rollback transaction on error
    //         return redirect()->back()->with('error',  $e->getMessage());
    //     }
    // }



    // public function uploadProduct(Request $request)
    // {
    //     DB::Connection('mysql2')->beginTransaction();

    //     try {
    //         $file = $request->file('import_file');
    //         $data = array_map('str_getcsv', file($file->getRealPath()));

    //         $insertData = [];
    //         foreach ($data as $key => $row) {
    //             if ($key == 0) {
    //                 continue; // Skip the header row
    //             }
    //             $row = array_pad($row, 33, null);
    //             $sys_no = !empty($row[1]) ? $row[1] : null;
                
    //             $productData = [
    //                 'sku_code' => !empty($row[2]) ? $row[2] : null,
    //                 'product_name' => !empty($row[3]) ? $row[3] : null,
    //                 'product_description' => !empty($row[4]) ? $row[4] : null,
    //                 'uom' => CommonHelper::get_id_from_db_by_name_for_product($row[5], 'uom') ?? 0,
    //                 'packing' => !empty($row[6]) ? $row[6] : null,
    //                 'product_barcode' => !empty($row[7]) ? $row[7] : null,
    //                 'brand_id' => CommonHelper::get_id_from_db_by_name_for_product($row[8], 'brands') ?? 0,
    //                 'group_id' => CommonHelper::get_id_from_db_by_name_for_product($row[9], 'company_groups') ?? 0,
    //                 'main_ic_id' => CommonHelper::get_id_from_db_by_name_for_product($row[10], 'category') ?? 0,
    //                 'sub_category_id' => CommonHelper::get_id_from_db_by_name_for_product($row[11], 'sub_category') ?? 0,
    //                 'product_classification_id' => CommonHelper::get_id_from_db_by_name_for_product($row[12], 'product_classifications') ?? 0,
    //                 'product_type_id' => CommonHelper::get_id_from_db_by_name_for_product($row[13], 'product_type') ?? 0,
    //                 'product_trend_id' => CommonHelper::get_id_from_db_by_name_for_product($row[14], 'product_trends') ?? 0,
    //                 'purchase_price' => !empty($row[15]) ? $row[15] : 0,
    //                 'sale_price' => !empty($row[16]) ? $row[16] : 0,
    //                 'mrp_price' => !empty($row[17]) ? $row[17] : 0,
    //                 'is_tax_apply' => strtolower($row[18]) === 'yes' ? 1 : 0,
    //                 'tax_type_id' => CommonHelper::get_id_from_db_by_name_for_product($row[19], 'tax_types') ?? 0,
    //                 'tax_applied_on' => !empty($row[20]) ? $row[20] : null, // Tax policy mapped after index 19
    //                 'tax_policy' => !empty($row[21]) ? $row[21] : null,

    //                 'tax' => !empty($row[22]) ? $row[22] : null,
    //                 'flat_discount' => !empty($row[23]) ? $row[23] : 0,
    //                 'min_qty' => !empty($row[24]) ? $row[24] : 0,
    //                 'max_qty' => !empty($row[25]) ? $row[25] : 0,
    //                 'hs_code' => CommonHelper::get_id_from_db_by_name_for_product($row[26], 'hs_codes') ?? 0,
    //                 'locality' => !empty($row[27]) ? $row[27] : null,
    //                 'origin' => !empty($row[28]) ? $row[28] : null,
    //                 'color' => !empty($row[29]) ? $row[29] : null,
    //                 'product_status' => !empty($row[30]) ? $row[30] : null,
    //                 'is_barcode_scanning' => strtolower($row[32]) == 'yes' ? 1 : (strtolower($row[32]) == 'no' ? 0 : null),
    //                 'username' => Auth::user()->name,
    //                 'date' => date('Y-m-d'),
    //             ];

    //             // If sys_no exists, update the product, else insert
    //             if ($sys_no) {
    //                 // Check if the product with sys_no exists
    //                 $existingProduct = DB::Connection('mysql2')->table('subitem')->where('sys_no', $sys_no)->first();

    //                 if ($existingProduct) {
    //                     // Update existing product
    //                     DB::Connection('mysql2')->table('subitem')->where('sys_no', $sys_no)->update($productData);
    //                 } else {
    //                     // Insert new product if sys_no doesn't exist
    //                     $productData['sys_no'] = CommonHelper::generateUniqueNumber('ITEM-', 'subitem', 'sys_no'); // Use the provided sys_no
    //                     $insertData[] = $productData;
    //                 }
    //             } else {
    //                 // If sys_no is null, generate and insert a new product
    //                 $productData['sys_no'] = CommonHelper::generateUniqueNumber('ITEM-', 'subitem', 'sys_no');
    //                 $insertData[] = $productData;
    //             }
    //             DB::Connection('mysql2')->table('subitem')->insert($productData);
    //             // Insert in batches of 500 rows to prevent memory issues
    //             if (count($insertData) === 0) {
    //                 dd($insertData);
    //                 DB::Connection('mysql2')->table('subitem')->insert($insertData);
    //                 $insertData = [];
    //             }
    //         }

    //         // Insert any remaining data
    //         if (!empty($insertData)) {
    //             //DB::Connection('mysql2')->table('subitem')->insert($insertData);
    //         }

    //         DB::Connection('mysql2')->commit();
    //         return redirect()->back()->with('dataInsert', 'Products Uploaded Successfully');
    //     } catch (\Exception $e) {
    //         DB::Connection('mysql2')->rollBack();
    //         dd($e->getMessage());
    //         return redirect()->back()->with('error', $e->getMessage());
    //     }
    // }



    
    public function uploadProductvvk(Request $request)
    {

        ini_set('max_execution_time', 300); // increase time limit to 5 mins
ini_set('memory_limit', '512M');
 
        $request->validate([
            'import_file' => 'required|file|mimes:xlsx,xls'
        ]);

        Excel::import(new ProductsImport, $request->file('import_file'));

        return back()->with('success', 'Products imported successfully!');

    }




// public function uploadProduct(Request $request)
// {


    
//         ini_set('max_execution_time', 300); // increase time limit to 5 mins
// ini_set('memory_limit', '512M');
//     DB::connection('mysql2')->beginTransaction();

//     try {
//         // Validate the file input
  

//         $file = $request->file('import_file');



//         // Check if file exists and is valid
//         if (!$file || !$file->isValid()) {
//           //  throw new \Exception('Invalid file uploaded');
//         }

//         // Get the file path
//         $filePath = $file->getRealPath();
//         if (!$filePath || !is_readable($filePath)) {
//             throw new \Exception('Could not read the uploaded file');
//         }

//         // Read CSV data
//         $data = array_map('str_getcsv', file($filePath));


 
        
//         $insertData = [];
//         $updatedCount = 0;
//         $insertedCount = 0;

//         foreach ($data as $key => $row) {


    
//             if ($key == 0) {
//                 continue; // Skip header row
//             }

//             // Ensure row has at least 33 elements
//             $row = array_pad($row, 33, null);
            
//             $sys_no = !empty($row[1]) ? trim($row[1]) : null;
           
//             $productData = [
//                 'sku_code' => !empty($row[2]) ? trim($row[2]) : null,
//                 'product_name' => !empty($row[3]) ? trim($row[3]) : null,
//                 'product_description' => !empty($row[4]) ? trim($row[4]) : null,
//                 'uom' => !empty($row[5]) ? CommonHelper::get_id_from_db_by_name_for_product(trim($row[5]), 'uom') : 0,
//                 'packing' => !empty($row[6]) ? trim($row[6]) : null,
//                 'product_barcode' => !empty($row[7]) ? trim($row[7]) : null,
//                 'brand_id' => !empty($row[8]) ? CommonHelper::get_id_from_db_by_name_for_product(trim($row[8]), 'brands') : 0,
//                 'group_id' => !empty($row[9]) ? CommonHelper::get_id_from_db_by_name_for_product(trim($row[9]), 'company_groups') : 0,
//                 'main_ic_id' => !empty($row[10]) ? CommonHelper::get_id_from_db_by_name_for_product(trim($row[10]), 'category') : 0,
//                 'sub_category_id' => !empty($row[11]) ? CommonHelper::get_id_from_db_by_name_for_product(trim($row[11]), 'sub_category') : 0,
//                 'product_classification_id' => !empty($row[12]) ? CommonHelper::get_id_from_db_by_name_for_product(trim($row[12]), 'product_classifications') : 0,
//                 'product_type_id' => !empty($row[13]) ? CommonHelper::get_id_from_db_by_name_for_product(trim($row[13]), 'product_type') : 0,
//                 'product_trend_id' => !empty($row[14]) ? CommonHelper::get_id_from_db_by_name_for_product(trim($row[14]), 'product_trends') : 0,
//                 'purchase_price' => !empty($row[15]) ? (float) str_replace(',', '', trim($row[15])) : 0,
//                 'sale_price' => !empty($row[16]) ? (float) str_replace(',', '', trim($row[16])) : 0,
//                 'mrp_price' => !empty($row[17]) ? (float) str_replace(',', '', trim($row[17])) : 0,
//                 'is_tax_apply' => !empty($row[18]) && strtolower(trim($row[18])) === 'yes' ? 1 : 0,
//                 'tax_type_id' => !empty($row[19]) ? CommonHelper::get_id_from_db_by_name_for_product(trim($row[19]), 'tax_types') : 0,
//                 'tax_applied_on' => !empty($row[20]) ? trim($row[20]) : null,
//                 'tax_policy' => !empty($row[21]) ? trim($row[21]) : null,
//                 'tax' => !empty($row[22]) ? (float) str_replace(',', '', trim($row[22])) : null,
//                 'flat_discount' => !empty($row[23]) ? (float) str_replace(',', '', trim($row[23])) : 0,
//                 'min_qty' => !empty($row[24]) ? (int) trim($row[24]) : 0,
//                 'max_qty' => !empty($row[25]) ? (int) trim($row[25]) : 0,
//                 'hs_code' => !empty($row[26]) ? CommonHelper::get_id_from_db_by_name_for_product(trim($row[26]), 'hs_codes') : 0,
//                 'locality' => !empty($row[27]) ? trim($row[27]) : null,
//                 'origin' => !empty($row[28]) ? trim($row[28]) : null,
//                 'color' => !empty($row[29]) ? trim($row[29]) : null,
//                 'product_status' => !empty($row[30]) ? trim($row[30]) : null,
//                 'is_barcode_scanning' => !empty($row[32]) ? (strtolower(trim($row[32])) == 'yes' ? 1 : 0) : null,
//                 'username' => Auth::user()->name,
//                 'date' => date('Y-m-d'),
//             ];

//             // Handle existing products
//             if ($sys_no) {
//                 $existingProduct = DB::connection('mysql2')->table('subitem')->where('sys_no', $sys_no)->first();
//                 if ($existingProduct) {
//                     DB::connection('mysql2')->table('subitem')->where('sys_no', $sys_no)->update($productData);
//                     $updatedCount++;
//                     continue;
//                 }
//                 $productData['sys_no'] = $sys_no;
//             } else {
//                 $productData['sys_no'] = CommonHelper::generateUniqueNumber('ITEM-', 'subitem', 'sys_no');
//             }

//             $insertData[] = $productData;
//             $insertedCount++;

//             // Insert in batches of 500
//             if (count($insertData) >= 500) {
//                 DB::connection('mysql2')->table('subitem')->insert($insertData);
//                 $insertData = [];
//             }
//         }

//         // Insert remaining records
//         if (!empty($insertData)) {
//             DB::connection('mysql2')->table('subitem')->insert($insertData);
//         }

//         DB::connection('mysql2')->commit();
        
//         return redirect()->back()->with([
//             'success' => 'Products uploaded successfully',
//             'stats' => "Inserted: {$insertedCount}, Updated: {$updatedCount}"
//         ]);

//     } catch (\Exception $e) {
//         DB::connection('mysql2')->rollBack();
//         return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
//     }
// }

public function uploadProduct(Request $request)
{
    ini_set('max_execution_time', 300);
    ini_set('memory_limit', '512M');
    DB::connection('mysql2')->beginTransaction();

    $request->validate([
    'import_file' => 'required|file|mimetypes:text/plain,text/csv|max:2048',
]);

    try {
        $file = $request->file('import_file');
        if (!$file || !$file->isValid()) {
            throw new \Exception('Invalid file uploaded');
        }

        $filePath = $file->getRealPath();
        if (!$filePath || !is_readable($filePath)) {
            throw new \Exception('Could not read the uploaded file');
        }

        // Use fgetcsv to safely parse CSV data
        $csv = fopen($filePath, 'r');
        $data = [];
        while (($row = fgetcsv($csv)) !== false) {
            $data[] = $row;
        }
        fclose($csv);

        $insertData = [];
        $updatedCount = 0;
        $insertedCount = 0;

        foreach ($data as $key => $row) {
            if ($key == 0) continue; // skip header

            $row = array_pad($row, 33, null);
            $product_name = trim($row[3]);
            $brand_name = trim($row[8]);

            // Get brand_id from brands table
            $brand_id = 0;
            if (!empty($brand_name)) {
                $brand = DB::connection('mysql2')->table('brands')
                    ->whereRaw("CONVERT(`name` USING utf8mb4) COLLATE utf8mb4_unicode_ci = ?", [$brand_name])
                    ->first();
                $brand_id = $brand ? $brand->id : 0;
            }
          $sku_code = !empty($row[2]) ? trim($row[2]) : null;

            $productData = [
                'sku_code' => !empty($row[2]) ? trim($row[2]) : null,
                'product_name' => $product_name,
                'product_description' => !empty($row[4]) ? trim($row[4]) : null,
                'uom' => !empty($row[5]) ? CommonHelper::get_id_from_db_by_name_for_product(trim($row[5]), 'uom') : 0,
                'packing' => !empty($row[6]) ? trim($row[6]) : null,
                'product_barcode' => !empty($row[7]) ? trim($row[7]) : null,
                'brand_id' => $brand_id,
                'group_id' => !empty($row[9]) ? CommonHelper::get_id_from_db_by_name_for_product(trim($row[9]), 'company_groups') : 0,
                'main_ic_id' => !empty($row[10]) ? CommonHelper::get_id_from_db_by_name_for_product(trim($row[10]), 'category') : 0,
                'sub_category_id' => !empty($row[11]) ? CommonHelper::get_id_from_db_by_name_for_product(trim($row[11]), 'sub_category') : 0,
                'product_classification_id' => !empty($row[12]) ? CommonHelper::get_id_from_db_by_name_for_product(trim($row[12]), 'product_classifications') : 0,
                'product_type_id' => !empty($row[13]) ? CommonHelper::get_id_from_db_by_name_for_product(trim($row[13]), 'product_type') : 0,
                'product_trend_id' => !empty($row[14]) ? CommonHelper::get_id_from_db_by_name_for_product(trim($row[14]), 'product_trends') : 0,
                'purchase_price' => !empty($row[15]) ? (float) str_replace(',', '', trim($row[15])) : 0,
                'sale_price' => !empty($row[16]) ? (float) str_replace(',', '', trim($row[16])) : 0,
                'mrp_price' => !empty($row[17]) ? (float) str_replace(',', '', trim($row[17])) : 0,
                'is_tax_apply' => !empty($row[18]) && strtolower(trim($row[18])) === 'yes' ? 1 : 0,
                    'tax_type_id' => !empty($row[19]) 
                        ? (strtolower(trim($row[19])) === 'include in' 
                            ? 1 
                            : (strtolower(trim($row[19])) === 'tax on' 
                                ? 2 
                                : 0)
                        ) 
                        : 0,

                // 'tax_type_id' => !empty($row[19]) ? CommonHelper::get_id_from_db_by_name_for_product(trim($row[19]), 'tax_types') : 0,
                'tax_applied_on' => !empty($row[20]) ? trim($row[20]) : null,
                'tax_policy' => !empty($row[21]) ? trim($row[21]) : null,
                'tax' => !empty($row[22]) ? (float) str_replace(',', '', trim($row[22])) : null,
                'flat_discount' => !empty($row[23]) ? (float) str_replace(',', '', trim($row[23])) : 0,
                'min_qty' => !empty($row[24]) ? (int) trim($row[24]) : 0,
                'max_qty' => !empty($row[25]) ? (int) trim($row[25]) : 0,
                  'hs_code' => !empty($row[27]) ? trim($row[27]): 0,
                // 'hs_code' => !empty($row[27]) ? CommonHelper::get_id_from_db_by_name_for_product(trim($row[27]), 'hs_codes') : 0,
                'locality' => !empty($row[28]) ? trim($row[28]) : null,
                'origin' => !empty($row[29]) ? trim($row[29]) : null,
                'color' => !empty($row[30]) ? trim($row[30]) : null,
                'product_status' => !empty($row[31]) ? trim($row[31]) : null,
                'is_barcode_scanning' => !empty($row[32]) ? (strtolower(trim($row[32])) == 'yes' ? 1 : 0) : null,
                'principal_group_id' => !empty($row[33]) ? CommonHelper::get_id_from_db_by_name_for_product(trim($row[33]), 'products_principal_group') : 0,
                'username' => Auth::user()->name,
                'date' => date('Y-m-d'),
            ];

            // Collation-safe check for existing product
            // $existingProduct = DB::connection('mysql2')->table('subitem')
            //     ->whereRaw("CONVERT(`product_name` USING utf8mb4) COLLATE utf8mb4_unicode_ci = ?", [$product_name])
            //     ->where('brand_id', $brand_id)
            //     ->first();


                $existingProduct = DB::connection('mysql2')->table('subitem')
                ->where('sku_code', $sku_code)
                ->where('brand_id', $brand_id)
                ->first();


            if ($existingProduct) {
                DB::connection('mysql2')->table('subitem')
                    ->where('id', $existingProduct->id)
                    ->update($productData);
                $updatedCount++;
            } else {
                $productData['sys_no'] = CommonHelper::generateUniqueNumber('ITEM-', 'subitem', 'sys_no');
                $insertData[] = $productData;
                $insertedCount++;
            }

            if (count($insertData) >= 500) {
                DB::connection('mysql2')->table('subitem')->insert($insertData);
                $insertData = [];
            }
        }

        if (!empty($insertData)) {
            DB::connection('mysql2')->table('subitem')->insert($insertData);
        }

        DB::connection('mysql2')->commit();

        return redirect()->back()->with([
            'success' => 'Products uploaded successfully',
            'stats' => "Inserted: {$insertedCount}, Updated: {$updatedCount}"
        ]);
    } catch (\Exception $e) {
        DB::connection('mysql2')->rollBack();
        return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
    }
}




    // public function uploadProduct(Request $request)   //correct right working perfectly
    // {
    //     $file = $request->file('import_file');
    //     $data = array_map('str_getcsv', file($file->getRealPath()));

    //     foreach ($data as $key => $row) {
    //         if ($key == 0) {
    //             continue; // Skip the header row
    //         }
    //         $sys_code =CommonHelper::generateUniquePosNo('subitem','sys_no','ITEM');


    //         $row = array_pad($row, 28, null); // Ensure the array has at least 28 elements

    //         $sub_item = new Subitem();
    //         $sub_item = $sub_item->setConnection('mysql2');

    //         $sub_item->sys_no = $sys_code; // Assuming this is the sys_code
    //         $sub_item->sku_code = !empty($row[0]) ? $row[0] : null; // SKU/Article
    //         $sub_item->product_name = !empty($row[1]) ? $row[1] : null; // Product Name
    //         $sub_item->product_description = !empty($row[2]) ? $row[2] : null; // Product Description
    //         $sub_item->uom = CommonHelper::get_id_from_db_by_name_for_product($row[3], 'uom') ?? 0; // UOM
    //         $sub_item->packing = !empty($row[4]) ? $row[4] : null; // Packing
    //         $sub_item->product_barcode = !empty($row[5]) ? $row[5] : null; // Product Barcode
    //         $sub_item->brand_id = CommonHelper::get_id_from_db_by_name_for_product($row[6], 'brands') ?? 0; // Brand
    //         $sub_item->group_id = CommonHelper::get_id_from_db_by_name_for_product($row[7], 'company_groups') ?? 0; // Group
    //         $sub_item->main_ic_id = CommonHelper::get_id_from_db_by_name_for_product($row[8], 'category') ?? 0; // Category
    //         $sub_item->sub_category_id = CommonHelper::get_id_from_db_by_name_for_product($row[9], 'sub_category') ?? 0; // Sub-Category
    //         $sub_item->product_classification_id = CommonHelper::get_id_from_db_by_name_for_product($row[10], 'product_classifications') ?? 0; // Product Classification
    //         $sub_item->product_type_id = CommonHelper::get_id_from_db_by_name_for_product($row[11], 'product_type') ?? 0; // Product Type
    //         $sub_item->product_trend_id = CommonHelper::get_id_from_db_by_name_for_product($row[12], 'product_trends') ?? 0; // Product Trend
    //         $sub_item->purchase_price = !empty($row[13]) ? $row[13] : 0; // Purchase Price
    //         $sub_item->sale_price = !empty($row[14]) ? $row[14] : 0; // Sale Price
    //         $sub_item->mrp_price = !empty($row[15]) ? $row[15] : 0; // Mrp Price
    //         $sub_item->is_tax_apply = strtolower($row[16]) === 'yes' ? 1 : 0; // Is Tax Apply
    //         $sub_item->tax_type_id = CommonHelper::get_id_from_db_by_name_for_product($row[17], 'tax_types') ?? 0; // Tax Type
    //         $sub_item->tax_applied_on = !empty($row[18]) ? $row[18] : null; // Tax Applied On
    //         $sub_item->tax = !empty($row[19]) ? $row[19] : null; // Tax %
    //         $sub_item->flat_discount = !empty($row[20]) ? $row[20] : 0; // Product Flat Discount(%)
    //         $sub_item->min_qty = !empty($row[21]) ? $row[21] : 0; // Min Qty
    //         $sub_item->max_qty = !empty($row[22]) ? $row[22] : 0; // Max Qty
    //         $sub_item->hs_code = CommonHelper::get_id_from_db_by_name_for_product($row[24], 'hs_codes') ?? 0; // H.S Code
    //         $sub_item->locality = !empty($row[25]) ? $row[25] : null; // Locality
    //         $sub_item->origin = !empty($row[26]) ? $row[26] : null; // Origin
    //         $sub_item->color = !empty($row[27]) ? $row[27] : null; // Color
    //         $sub_item->product_status = $row[28]; // Product Status
    //         // $sub_item->product_status = strtolower($row[28]) === 'active' ? 1 : 0; // Product Status
    //         $sub_item->username = Auth::user()->name;
    //         $sub_item->date = date('Y-m-d');
    //         $sub_item->save();
    //     }
    //     // $HsCode = HsCode::where('status',1)->select('id','hs_code')->get();

    //     return redirect()->back()->with('dataInsert', 'Products Uploaded Successfully');
    // }

    // public function uploadProduct(Request $request)
    // {
    //     $file = $request->file('import_file');
    //     $data = array_map('str_getcsv', file($file->getRealPath()));

    //     foreach ($data as $key => $row) {
    //         if ($key == 0) {
    //             continue; // Skip the header row
    //         }
    //         $sys_code =CommonHelper::generateUniquePosNo('subitem','sys_no','ITEM');

    //         $sub_item = new Subitem();
    //         $sub_item->SetConnection('mysql2');

    //         // Mapping CSV columns to Subitem model fields
    //         $sub_item->sub_category_id = $row[0]; // Assuming this is the SubCategoryId
    //         $sub_item->sys_no = $sys_code; // Assuming this is the sys_code
    //         $sub_item->main_ic_id = $row[2]; // Assuming this is the CategoryId
    //         $sub_item->sub_ic = $row[3]; // Assuming this is the SubCategoryId
    //         $sub_item->sku_code = $row[4] ?? $row[5]; // Assuming this is the SKU or sub_item_name
    //         $sub_item->uom = $row[6]; // Assuming this is the uom_id
    //         $sub_item->hs_code_id = $row[7]; // Assuming this is the hs_code_id
    //         $sub_item->brand_id = $row[8]; // Assuming this is the brand
    //         $sub_item->product_name = $row[9]; // Assuming this is the product_name
    //         $sub_item->product_description = $row[10]; // Assuming this is the product_description
    //         $sub_item->packing = $row[11]; // Assuming this is the packing
    //         $sub_item->product_barcode = $row[12]; // Assuming this is the product_barcode
    //         $sub_item->group_id = $row[13]; // Assuming this is the group_id
    //         $sub_item->product_classification_id = $row[14]; // Assuming this is the product_classification_id
    //         $sub_item->product_type_id = $row[15]; // Assuming this is the product_type_id
    //         $sub_item->product_trend_id = $row[16]; // Assuming this is the product_trend_id
    //         $sub_item->purchase_price = $row[17]; // Assuming this is the purchase_price
    //         $sub_item->sale_price = $row[18]; // Assuming this is the sale_price
    //         $sub_item->mrp_price = $row[19]; // Assuming this is the mrp_price
    //         $sub_item->is_tax_apply = $row[20]; // Assuming this is the tax_applied
    //         $sub_item->tax_type_id = $row[21]; // Assuming this is the tax_type_id
    //         $sub_item->tax_applied_on = $row[22]; // Assuming this is the tax_applied_on
    //         $sub_item->tax_policy = $row[23]; // Assuming this is the tax_policy
    //         $sub_item->flat_discount = $row[24]; // Assuming this is the discount
    //         $sub_item->min_qty = $row[25]; // Assuming this is the min_qty
    //         $sub_item->max_qty = $row[26]; // Assuming this is the max_qty
    //         $sub_item->locality = $row[27]; // Assuming this is the locality
    //         $sub_item->origin = $row[28]; // Assuming this is the origin
    //         $sub_item->color = $row[29]; // Assuming this is the color
    //         $sub_item->product_status = $row[30]; // Assuming this is the product_status
    //         $sub_item->username = Auth::user()->name;
    //         $sub_item->date = date('Y-m-d');
    //         $sub_item->save();

    //         // Assuming you have a logic to handle stock or other data based on the warehouse information in the CSV
    //         // foreach($request->warehouse as $key => $row):
    //         //    Stock::create([...]);
    //         // endforeach;

    //         ReuseableCode::stockOpening();
    //     }

    //     return redirect('purchase/createSubItemForm')->with('dataInsert', 'Items Added');
    // }




    public function debtor_balance_page()
    {
        $Customer = DB::Connection('mysql2')->table('customers')->where('status', 1)->get();
        return view('Sales.debtor_balance_page', compact('Customer'));
    }
    public function commission_report_page()
    {
        $Agent = DB::Connection('mysql2')->select('select a.id,a.agent_name from sales_agent a
                                                  INNER  JOIN commision b ON b.agent = a.id
                                                  WHERE b.status = 1');
        return view('Sales.commission_report_page', compact('Agent'));
    }



    public function add_point_of_sale()
    {
        $BatchCode = DB::Connection('mysql2')->table('stock')->where('status', 1)->where('opening', 0)->select('batch_code')->groupBy('batch_code')->get();
        return view('Sales.add_point_of_sale', compact('BatchCode'));
    }

    public function salesActivityPage()
    {
        return view('Sales.salesActivityPage');
    }

    public function freight_collection_page()
    {
        return view('Sales.freight_collection_page');
    }


    public function salesActivityAjax()
    {
        return view('Sales.salesActivityAjax');
    }
    public function debtor_payment_detail()
    {
        $data =  DB::Connection('mysql2')->table('customers as a')
            ->select('a.name', 'a.id')
            ->join('sales_tax_invoice as b', 'a.id', '=', 'b.buyers_id')
            ->where('a.status', 1)
            ->where('b.status', 1)
            ->groupBy('b.buyers_id')
            ->get();
        return view('Sales.debtor_payment_detail', compact('data'));
    }

    public function soTrackingQtyPage()
    {
        return view('Sales.soTrackingQtyPage');
    }

    public function salesAgingReport()
    {
        $Customer = DB::Connection('mysql2')->table('customers')->where('status', 1)->get();
        return view('Sales.salesAgingReport', compact('Customer'));
    }

    public function getAgingReportDataAjaxSales(Request $request)
    {
        if ($request->ReportType == 1) {
            return view('Sales.getAgingReportDataAjaxSalesSummary');
        } else {
            return view('Sales.getAgingReportDataAjaxSales');
        }
    }



    public function createCashCustomerForm()
    {
        $countries = new Countries;
        $countries = $countries::where('status', '=', 1)->get();
        CommonHelper::companyDatabaseConnection($_GET['m']);
        $accounts = new Account;
        $accounts = $accounts::orderBy('level1', 'ASC')
            ->orderBy('level2', 'ASC')
            ->orderBy('level3', 'ASC')
            ->orderBy('level4', 'ASC')
            ->orderBy('level5', 'ASC')
            ->orderBy('level6', 'ASC')
            ->orderBy('level7', 'ASC')
            ->get();
        CommonHelper::reconnectMasterDatabase();
        return view('Sales.createCashCustomerForm', compact('accounts', 'countries'));
    }

    public function viewCashCustomerList()
    {
        return view('Sales.viewCashCustomerList');
    }

    public function outstandingReportPage()
    {
        $Customer = DB::Connection('mysql2')->table('customers')->where('status', 1)->get();
        return view('Sales.outstandingReportPage', compact('Customer'));
    }

    public function soTrackingPage()
    {
        $SoNo = DB::Connection('mysql2')->table('sales_order')->where('status', 1)->select('so_no', 'id')->get();
        return view('Sales.soTrackingPage', compact('SoNo'));
    }


    public function ViewMultipleDeliveryNotesDetail()
    {
        return view('Sales.ViewMultipleDeliveryNotesDetail');
    }

    public function soReportPage()
    {
        return view('Sales.soReportPage');
    }
    public function dnReportPage()
    {
        return view('Sales.dnReportPage');
    }


    public function ViewMultipleSalesTaxInvoices()
    {
        return view('Sales.ViewMultipleSalesTaxInvoices');
    }
    public function ViewMultipleCreditNoteDetail()
    {
        return view('Sales.ViewMultipleCreditNoteDetail');
    }




    public function CreateMultipleSalesTaxInvoices()
    {
        return view('Sales.CreateMultipleSalesTaxInvoices');
    }



    public function createCreditCustomerForm()
    {
        $countries = new Countries;
        $countries = $countries::where('status', '=', 1)->get();
        CommonHelper::companyDatabaseConnection($_GET['m']);
        $accounts = new Account;
        $accounts = $accounts::orderBy('level1', 'ASC')
            ->orderBy('level2', 'ASC')
            ->orderBy('level3', 'ASC')
            ->orderBy('level4', 'ASC')
            ->orderBy('level5', 'ASC')
            ->orderBy('level6', 'ASC')
            ->orderBy('level7', 'ASC')

            ->get();
        $StoresCategory = StoresCategory::where('status', '=', 1)->get();
        $Territory = Territory::where('status', '=', 1)->get();
        $CustomerType = CustomerType::where('status', '=', 1)->get();

       
        CommonHelper::reconnectMasterDatabase();

         $SubDepartments = SubDepartment::where('status','=', 1)->orderBy('id')->get();
        return view('Sales.createCreditCustomerForm', compact('accounts', 'countries', 'StoresCategory', 'Territory', 'CustomerType','SubDepartments'));
    }

    public function editCustomerForm($id)
    {
        $countries = new Countries;
        $countries = $countries::where('status', '=', 1)->get();
        CommonHelper::companyDatabaseConnection($_GET['m']);
        $accounts = new Account;
        $accounts = $accounts::orderBy('level1', 'ASC')
            ->orderBy('level2', 'ASC')
            ->orderBy('level3', 'ASC')
            ->orderBy('level4', 'ASC')
            ->orderBy('level5', 'ASC')
            ->orderBy('level6', 'ASC')
            ->orderBy('level7', 'ASC')
            ->get();
        $StoresCategory = StoresCategory::where('status', '=', 1)->get();
        $Territory = Territory::where('status', '=', 1)->get();
        $CustomerType = CustomerType::where('status', '=', 1)->get();
        CommonHelper::reconnectMasterDatabase();

         $salesPersons = SubDepartment::where('status','=', 1)->orderBy('id')->get();

        return view('Sales.editCustomerForm', compact('accounts', 'countries', 'id', 'StoresCategory', 'Territory', 'CustomerType','salesPersons'));
    }
    public function approveCustomer(Request $request)
    {
        $customerApprove = Customer::find($request->id);
        $customerApprove->status = 1;
        $customerApprove->save();
        $territories = Territory::all();
        return view('Sales.viewCreditCustomerList', compact('territories'));
    }



    public function viewCreditCustomerList()
    {
        $territories = Territory::all();
        return view('Sales.viewCreditCustomerList', compact('territories'));
    }
    public function add_agent_list()
    {
        return view('Sales.add_agent_list');
    }


    public function jobTrackingSheet()
    {

        $customer = new Customer();
        $customer = $customer->SetConnection('mysql2');
        $customer = $customer->where('status', 1)->get();
        $region = new Region();
        $region = $region->SetConnection('mysql2');
        $region = $region->where('status', 1)->get();
        $survey = new Survey();
        $survey = $survey->SetConnection('mysql2');
        $survey = $survey->where('status', 1)->where('survey_status', 2)->get();
        $cities = new Cities();
        //$cities = $cities->SetConnection('mysql2');
        $cities = $cities->where('status', 1)->whereIn('state_id', array(2723, 2724, 2725, 2726, 2727, 2728, 2729))->get();
        return view('Sales.jobTrackingSheet', compact('customer', 'region', 'survey', 'cities'));
    }

    public function createCreditSaleVoucherForm()
    {
        CommonHelper::companyDatabaseConnection($_GET['m']);
        $accounts = new Account;
        $accounts = $accounts::orderBy('level1', 'ASC')
            ->orderBy('level2', 'ASC')
            ->orderBy('level3', 'ASC')
            ->orderBy('level4', 'ASC')
            ->orderBy('level5', 'ASC')
            ->orderBy('level6', 'ASC')
            ->orderBy('level7', 'ASC')
            ->where('code', 'like', '5%')
            ->get();
        $categories = new Category;
        $categories = $categories::where('status', '=', '1')->get();
        $Customers = new Customer;
        $Customers = $Customers::where('status', '=', '1')->where('customer_type', '=', '3')->get();
        CommonHelper::reconnectMasterDatabase();
        return view('Sales.createCreditSaleVoucherForm', compact('accounts', 'categories', 'Customers'));
    }

    public function createCashSaleVoucherForm()
    {
        CommonHelper::companyDatabaseConnection($_GET['m']);
        $creditAccounts = new Account;
        $creditAccounts = $creditAccounts::orderBy('level1', 'ASC')
            ->orderBy('level2', 'ASC')
            ->orderBy('level3', 'ASC')
            ->orderBy('level4', 'ASC')
            ->orderBy('level5', 'ASC')
            ->orderBy('level6', 'ASC')
            ->orderBy('level7', 'ASC')
            ->where('code', 'like', '5%')
            ->get();

        $debitAccounts = new Account;
        $debitAccounts = $debitAccounts::orderBy('level1', 'ASC')
            ->orderBy('level2', 'ASC')
            ->orderBy('level3', 'ASC')
            ->orderBy('level4', 'ASC')
            ->orderBy('level5', 'ASC')
            ->orderBy('level6', 'ASC')
            ->orderBy('level7', 'ASC')
            ->where('code', 'like', '1-3')
            ->get();
        $categories = new Category;
        $categories = $categories::where('status', '=', '1')->get();
        $Customers = new Customer;
        $Customers = $Customers::where('status', '=', '1')->where('customer_type', '=', '2')->get();
        CommonHelper::reconnectMasterDatabase();
        return view('Sales.createCashSaleVoucherForm', compact('creditAccounts', 'debitAccounts', 'categories', 'Customers'));
    }

    public function viewCashSaleVouchersList()
    {
        return view('Sales.viewCashSaleVouchersList');
    }

    public function viewCreditSaleVouchersList()
    {
        return view('Sales.viewCreditSaleVouchersList');
    }
    public function CreateSalesOrder()
    {
        return view('Sales.CreateSalesOrder');
    }
    public function CreateDirectSalesTaxInvoice()
    {
        return view('Sales.CreateDirectSalesTaxInvoice');
    }

    public function EditSalesOrder($id)
    {


        $sales_order = new Sales_Order();
        $sales_order = $sales_order->SetConnection('mysql2');
        $sales_order = $sales_order->where('id', $id)->first();
        $sales_order_id = $id;

        //        $sales_order_data=new Sales_Order_Data();
        //        $sales_order_data=$sales_order_data->SetConnection('mysql2');
        //        $sales_order_data=$sales_order_data->where('master_id',$id)->get();

        $sales_order_data = DB::Connection('mysql2')->select('select a.id,a.master_id,a.qty,a.rate,a.amount,a.bundles_id,a.desc,
        a.groupby,a.item_id,a.sub_total,a.tax,a.tax_amount,b.product_name,b.rate as bundle_rate,b.amount as bundle_amount
        ,b.discount_percent as b_percent,b.discount_amount as b_dis_amount,b.net_amount as b_net,b.qty as bqty,b.bundle_unit
         from sales_order_data a
        left join
        bundles b
        on
        a.bundles_id=b.id
        where a.master_id="' . $id . '"

        group by a.groupby');

        $BuyerData = CommonHelper::get_single_row('customers', 'id', $sales_order->buyers_id);
        $Addional = DB::Connection('mysql2')->table('addional_expense_sales_order')->where('status', 1)->where('main_id', $id)->get();
        $accounts = new Account();
        $accounts = $accounts->SetConnection('mysql2');
        $accounts = $accounts->where('status', 1)->get();
        return view('Sales.EditSalesOrder', compact('sales_order', 'sales_order_data', 'id', 'BuyerData', 'sales_order_id', 'Addional', 'accounts'));
    }

    public function  ShowAllImages($id)
    {
        $surveyDocs = new SurveyDocument();
        $surveyDocs = $surveyDocs->SetConnection('mysql2');
        $surveyDocs = $surveyDocs->where('status', 1)->where('survey_id', $id)->get();

        return view('Sales.ShowAllImages', compact('surveyDocs'));
    }
    public function  customer_opening_list()
    {
        $data =  DB::Connection('mysql2')->table('customers as a')
            ->select('a.name', 'a.id', 'a.acc_id', DB::raw('sum(b.balance_amount) as bal'))
            ->join('customer_opening_balance as b', 'a.id', '=', 'b.buyer_id')
            ->where('a.status', 1)
            ->groupBy('b.buyer_id')
            ->get();
        return view('Sales.customer_opening_list', compact('data'));
    }


    public function  ShowAllImagesComplaint($id)
    {
        $ComplaintDocs = new ComplaintDocument();
        $ComplaintDocs = $ComplaintDocs->SetConnection('mysql2');
        $ComplaintDocs = $ComplaintDocs->where('status', 1)->where('complaint_id', $id)->get();

        return view('Sales.ShowAllImagesComplaint', compact('ComplaintDocs'));
    }


    public function viewSalesOrderList()
    {
        $currentMonthStartDate = date('Y-m-01');
        $currentMonthEndDate   = date('Y-m-t');
        $sale_order = new Sales_Order();
        $sale_order = $sale_order->SetConnection('mysql2');
        $sale_order = $sale_order->where('status', 1)->whereBetween('so_date', [$currentMonthStartDate, $currentMonthEndDate])->orderBy('id', 'DESC')->get();
        $Customer = DB::Connection('mysql2')->table('customers')->where('status', 1)->get();
        return view('Sales.viewSalesOrderList', compact('sale_order', 'Customer'));
    }
    public  function viewSalesOrderDetail()
    {
        $id = Input::get('id');
        $sales_order = new Sales_Order();
        $sales_order = $sales_order->SetConnection('mysql2');
        $sales_order = $sales_order->where('id', $id)->first();


        $sales_order_data = new Sales_Order_Data();
        $sales_order_data = $sales_order_data->SetConnection('mysql2');
        $sales_order_data =  $sales_order_data->where('master_id', $id)->get();

        $AddionalExpense = DB::Connection('mysql2')->table('addional_expense_sales_order')->where('main_id', $id);
        
        return view('Sales.AjaxPages.viewSalesOrderDetail', compact('sales_order', 'sales_order_data', 'AddionalExpense'));
    }

    public function CreateDeliveryNoteList()
    {
        $currentMonthStartDate = date('Y-m-01');
        $currentMonthEndDate   = date('Y-m-t');
        $sale_order = new Sales_Order();
        $sale_order = $sale_order->SetConnection('mysql2');
        $sale_order = $sale_order->where('status', 0)->where('delivery_note_status', 0)
            ->whereIn('so_status', [1, 2, 3, 4])
            ->whereBetween('so_date', [$currentMonthStartDate, $currentMonthEndDate])->get();
        $Customer = DB::Connection('mysql2')->table('customers')->where('status', 1)->get();
        return view('Sales.CreateDeliveryNoteList', compact('sale_order', 'Customer'));
    }

    public function CreateDeliveryChallanList()
    {
        $currentMonthStartDate = date('Y-m-01');
        $currentMonthEndDate   = date('Y-m-t');
        $sale_order = new Sales_Order();
        $sale_order = $sale_order->SetConnection('mysql2');
        $sale_order = $sale_order->where('status', 1)->where('delivery_note_status', 0)
            ->whereIn('so_status', [1, 2, 3, 4])
            ->whereBetween('so_date', [$currentMonthStartDate, $currentMonthEndDate])->get();
        $Customer = DB::Connection('mysql2')->table('customers')->where('status', 1)->get();
        return view('Sales.CreateDeliveryChallanList', compact('sale_order', 'Customer'));
    }

    public function CreateDeliveryNote()
    {
        $id = Input::get('id');
        
        $sales_order = new Sales_Order();
        $sales_order = $sales_order->SetConnection('mysql2');
        $sales_order = $sales_order->where('id', $id)->first();


        $sale_order_data_other = new Sales_Order_Data();
        $sale_order_data_other = $sale_order_data_other->SetConnection('mysql2');
        $sale_order_data_other_indi = $sale_order_data_other->where('master_id', $id)->where('bundles_id', '=', 0)->get();

        $sale_order_data = DB::Connection('mysql2')->select('select 
        a.id,
        a.master_id,
        a.qty,
        a.rate,
        a.mrp_price,
        a.amount,
        a.bundles_id,
        a.groupby,
        a.groupby,
        a.item_id,
        a.sub_total,
        a.tax,
        a.tax_amount,
        a.discount_percent_1,
        a.discount_amount_1,
        b.product_name,
        b.rate as bundle_rate,
        b.amount as bundle_amount,
        b.discount_percent as b_percent,
        b.discount_amount as b_dis_amount,
        b.net_amount as b_net,
        b.qty as bqty,
        b.bundle_unit,
        a.desc,
        a.foc ,
        a.warehouse_id,
        a.brand_id
        from sales_order_data a
        left join bundles b on a.bundles_id=b.id where a.master_id="' . $id . '"');

        

        return view('Sales.CreateDeliveryNote', compact('sales_order', 'sale_order_data', 'sale_order_data_other_indi'));
    }

    public function CreateDeliveryChallan()
    {
        $id = Input::get('id');
        $packing_id = Input::get('packing_id');
        $qc_packing_id = Input::get('qc_packing_id');

        $sales_order = new Sales_Order();
        $sales_order = $sales_order->SetConnection('mysql2');
        $sales_order = $sales_order->where('id', $id)->where('delivery_note_status', 0)->first();

        $packing = db::connection('mysql2')->table('packings')->where('id', $packing_id)->where('status', 1)->first();


        $sale_order_data_other = new Sales_Order_Data();
        $sale_order_data_other = $sale_order_data_other->SetConnection('mysql2');
        $sale_order_data_other_indi = $sale_order_data_other->where('master_id', $id)->where('bundles_id', '=', 0)->get();

        $sale_order_data = DB::Connection('mysql2')->select("
        select 
        sod.id,
        sod.master_id,
        pd.qty,
        sod.rate,
        sod.amount,
        sod.bundles_id,
        sod.groupby,
        sod.groupby,
        sod.item_id,
        sod.sub_total,
        sod.tax_amount,
        sod.desc,
        p.id packing_id, 
        pd.id packing_data_id
        from sales_order_data sod
        inner join packings p 
        ON p.item_id = sod.item_id
        INNER JOIN packing_datas pd 
        ON pd.packing_id = p.id
        inner join qc_packings qp 
        ON qp.packing_list_id = p.id
        
        where p.qc_status = 3 and sod.master_id = $id AND p.id = $packing_id");

        return view('Sales.CreateDeliveryChallan', compact('sales_order', 'sale_order_data', 'sale_order_data_other_indi', 'packing_id', 'qc_packing_id', 'packing'));
    }

    public function EditDeliveryNote()
    {
        $id = Input::get('id');

        $delivery_note = new DeliveryNote();
        $delivery_note = $delivery_note->SetConnection('mysql2');

        // editi my maroof
        // $delivery_note = $delivery_note->where('id',$id)->where('status',0)->first();
        $delivery_note = $delivery_note->where('id', $id)->first();
        // getting credit limit to show agains a SO

        $sales_order = new Sales_Order();
        $sales_order = $sales_order->SetConnection('mysql2');
        $sales_order = $sales_order->where('so_no', $delivery_note->so_no)->first();

        //        $delivery_note_data=new DeliveryNoteData();
        //        $delivery_note_data=$delivery_note_data->SetConnection('mysql2');
        //        $delivery_note_data=$delivery_note_data->where('master_id',$id)->get();

        $delivery_note_data = DB::Connection('mysql2')->select('select a.id,a.master_id,a.so_data_id,a.qty,a.rate,a.amount,a.bundles_id,a.warehouse_id,a.batch_code,
        a.item_id,a.groupby, a.tax, a.tax_amount,a.mrp_price,a.warehouse_to_id, b.product_name,b.rate as bundle_rate,b.amount as bundle_amount
        ,b.discount_percent as b_percent,b.discount_amount as b_dis_amount,b.net_amount as b_net,b.qty as bqty,b.bundle_unit
         from delivery_note_data a
        left join
        bundles b
        on
        a.bundles_id=b.id
        where a.master_id=' . $id . '
        group by a.groupby');
        //        echo "<pre>";
        //        print_r($delivery_note_data); die();
        $FinalTot = DB::Connection('mysql2')->selectOne('select sum(amount) as amount from delivery_note_data where master_id = ' . $id . '')->amount;
        return view('Sales.EditDeliveryNote', compact('delivery_note', 'delivery_note_data', 'FinalTot', 'sales_order'));
    }

    public function editSalesReturn($id)
    {

        $CreditNote = new CreditNote();
        $CreditNote = $CreditNote->SetConnection('mysql2');
        $CreditNote = $CreditNote->where('id', $id)->where('status', 1)->first();

        $CreditNoteData = new CreditNoteData();
        $CreditNoteData = $CreditNoteData->SetConnection('mysql2');
        $CreditNoteData = $CreditNoteData->where('master_id', $id)->get();

        return view('Sales.editSalesReturn', compact('CreditNote', 'CreditNoteData'));
    }


    public function editImportDocument($id)
    {
        $Master = DB::Connection('mysql2')->table('import_po')->where('status', 1)->where('id', $id)->first();
        $Detail = DB::Connection('mysql2')->table('import_po_data')->where('status', 1)->where('master_id', $id)->orderBy('id', 'ASC')->get();
        $supplier = new Supplier();
        $supplier = $supplier->SetConnection('mysql2');
        $supplier = $supplier->where('status', 1)->select('id', 'name')->get();

        return view('Sales.editImportDocument', compact('Master', 'Detail', 'id', 'supplier'));
    }



    public function viewDeliveryNoteList()
    {
        $delivery_note = new DeliveryNote();
        $delivery_note = $delivery_note->SetConnection('mysql2');
        // where('status',1)->
        $territory_ids = json_decode(auth()->user()->territory_id); 
        $delivery_note = $delivery_note
                                ->whereHas("customer", function($query) use ($territory_ids) {
                                    $query->whereIn("territory_id", $territory_ids);
                                })
                                ->orderBy('id', 'DESC')
                                ->get();
                                
                                
        $username = DB::Connection('mysql2')->table('delivery_note')->select('username')->groupBy('username')->get();
        $Customer = DB::Connection('mysql2')->table('customers')->where('status', 0)->get();
        // echo "<pre>";
        // print_r($delivery_note[0]->so_no);
        // echo "<pre>";
        // exit;
        return view('Sales.viewDeliveryNoteList', compact('delivery_note', 'Customer', 'username'));
    }

// For global search
public function getDeliveryNoteGlobalSearch(Request $request)
{
    $searchTerm = $request->searchTerm;
    $m = $request->m;
    $territory_id = $request->territory_id;
    
    $query = DB::table('delivery_note_master as dnm')
        ->leftJoin('buyers as b', 'dnm.buyers_id', '=', 'b.id')
        ->leftJoin('sales_order_master as som', 'dnm.so_no', '=', 'som.so_no')
        ->select('dnm.*', 'b.name as customer_name', 'som.so_date');
    
    // Apply territory filter if needed
    if(!empty($territory_id)) {
        // Add territory filtering logic here
    }
    
    // Global search across multiple fields
    if(!empty($searchTerm)) {
        $query->where(function($q) use ($searchTerm) {
            $q->where('dnm.gd_no', 'LIKE', "%{$searchTerm}%")
              ->orWhere('dnm.so_no', 'LIKE', "%{$searchTerm}%")
              ->orWhere('b.name', 'LIKE', "%{$searchTerm}%")
              ->orWhere('dnm.username', 'LIKE', "%{$searchTerm}%")
              ->orWhereExists(function($subQuery) use ($searchTerm) {
                  $subQuery->select(DB::raw(1))
                          ->from('delivery_note_data as dnd')
                          ->join('subitem as si', 'dnd.item_id', '=', 'si.id')
                          ->whereRaw('dnd.master_id = dnm.id')
                          ->where(function($itemQuery) use ($searchTerm) {
                              $itemQuery->where('si.item_name', 'LIKE', "%{$searchTerm}%")
                                       ->orWhere('si.item_code', 'LIKE', "%{$searchTerm}%")
                                       ->orWhere('si.sku', 'LIKE', "%{$searchTerm}%");
                          });
              });
        });
    }
    
    $delivery_notes = $query->orderBy('dnm.id', 'desc')->get();
    
    // Return the view with filtered data
    return view('your_view_partial', compact('delivery_notes'));
}

// For advanced search
public function getDeliveryNoteAdvancedSearch(Request $request)
{
    // Similar to above but with individual field filtering
    $query = DB::table('delivery_note_master as dnm')
        ->leftJoin('buyers as b', 'dnm.buyers_id', '=', 'b.id')
        ->leftJoin('sales_order_master as som', 'dnm.so_no', '=', 'som.so_no')
        ->select('dnm.*', 'b.name as customer_name', 'som.so_date');
    
    // Apply individual filters
    if(!empty($request->gdn_no)) {
        $query->where('dnm.gd_no', 'LIKE', "%{$request->gdn_no}%");
    }
    
    if(!empty($request->so_no)) {
        $query->where('dnm.so_no', 'LIKE', "%{$request->so_no}%");
    }
    
    if(!empty($request->from) && !empty($request->to)) {
        $query->whereBetween('dnm.gd_date', [$request->from, $request->to]);
    }
    
    if(!empty($request->username)) {
        $query->where('dnm.username', $request->username);
    }
    
    if(!empty($request->dnStatus) && $request->dnStatus !== 'All') {
        $query->where('dnm.status', $request->dnStatus);
    }
    
    // Add document status filtering logic here
    
    $delivery_notes = $query->orderBy('dnm.id', 'desc')->get();
    
    return view('your_view_partial', compact('delivery_notes'));
}

// For default data
public function getDeliveryNoteDefaultData(Request $request)
{
    // Return all data (your original query)
    $delivery_notes = DB::table('delivery_note_master as dnm')
        ->leftJoin('buyers as b', 'dnm.buyers_id', '=', 'b.id')
        ->leftJoin('sales_order_master as som', 'dnm.so_no', '=', 'som.so_no')
        ->select('dnm.*', 'b.name as customer_name', 'som.so_date')
        ->orderBy('dnm.id', 'desc')
        ->get();
    
    return view('your_view_partial', compact('delivery_notes'));
}

    public function viewDeliveryChallanList()
    {
        $currentMonthStartDate = date('Y-m-01');
        $currentMonthEndDate   = date('Y-m-t');
        $delivery_note = new DeliveryNote();
        $delivery_note = $delivery_note->SetConnection('mysql2');
        $delivery_note = $delivery_note->where('status', 1)->whereBetween('gd_date', [$currentMonthStartDate, $currentMonthEndDate])->orderBy('id', 'DESC')->get();
        $Customer = DB::Connection('mysql2')->table('customers')->where('status', 1)->get();

        return view('Sales.viewDeliveryChallanList', compact('delivery_note', 'Customer'));
    }
    public function viewDeliveryNoteListOther()
    {
        $delivery_note = new DeliveryNote();
        $delivery_note = $delivery_note->SetConnection('mysql2');
        $delivery_note = $delivery_note->where('status', 1)->get();

        return view('Sales.viewDeliveryNoteListOther', compact('delivery_note'));
    }


    public  function viewDeliveryNoteDetail($id)
    {
        $delivery_note = new DeliveryNote();
        $delivery_note = $delivery_note->SetConnection('mysql2');
        $delivery_note = $delivery_note->where('id', $id)->first();

        $delivery_note_data_other = new DeliveryNoteData();
        $delivery_note_data_other = $delivery_note_data_other->SetConnection('mysql2');
        $delivery_note_data = $delivery_note_data_other->where('master_id', $id)->get();

        return view('Sales.AjaxPages.viewDeliveryNoteDetail', compact('delivery_note', 'delivery_note_data', 'id'));
    }

    public  function viewDeliveryChallanDetail($id)
    {

        $delivery_note = new DeliveryNote();
        $delivery_note = $delivery_note->SetConnection('mysql2');
        $delivery_note = $delivery_note->where('id', $id)->first();

        // echo "<pre>";
        // print_r($delivery_note);
        // exit();
        $delivery_note_data_other = new DeliveryNoteData();
        $delivery_note_data_other = $delivery_note_data_other->SetConnection('mysql2');
        $delivery_note_data = $delivery_note_data_other->where('master_id', $id)->get();

        return view('Sales.AjaxPages.viewDeliveryChallanDetail', compact('delivery_note', 'delivery_note_data', 'id'));
    }

    public  function viewDeliveryNoteDetailTwo($id)
    {

        $delivery_note = new DeliveryNote();
        $delivery_note = $delivery_note->SetConnection('mysql2');
        $delivery_note = $delivery_note->where('id', $id)->first();


        $delivery_note_data = DB::Connection('mysql2')->select('select a.id,a.master_id,a.qty,a.rate,a.amount,a.bundles_id,a.desc,
        a.item_id,a.discount_percent,a.discount_amount,b.product_name,b.rate as bundle_rate,b.amount as bundle_amount
        ,b.discount_percent as b_percent,b.discount_amount as b_dis_amount,b.net_amount as b_net,b.qty as bqty,b.bundle_unit
         from delivery_note_data a

        left join
        bundles b
        on
        a.bundles_id=b.id
        where a.master_id="' . $id . '"

        group by a.groupby');
        $delivery_note_data_other = new DeliveryNoteData();
        $delivery_note_data_other = $delivery_note_data_other->SetConnection('mysql2');
        $delivery_note_data_other = $delivery_note_data_other->where('master_id', $id)->get();

        return view('Sales.AjaxPages.viewDeliveryNoteDetailTwo', compact('delivery_note', 'delivery_note_data', 'delivery_note_data_other', 'id'));
    }


    public function CreateSalesTaxInvoiceList()
    {
        $delivery_note = new DeliveryNote();
        $delivery_note = $delivery_note->SetConnection('mysql2');
            $user = auth()->user();
    $territory_ids = json_decode($user->territory_id);     
    $territory_ids = [100];
        $delivery_note = $delivery_note
                                ->join('customers', 'customers.id', '=', 'delivery_note.buyers_id')
                                ->whereIn('customers.territory_id', $territory_ids)
                                ->where('delivery_note.status', 1)
                                ->where('delivery_note.sales_tax_invoice', 0)
                                ->orderBy('delivery_note.id', 'DESC')
                                ->get();


        $Customers = DB::Connection('mysql2')->table('customers')->where('status', 1)->get();

        
        return view('Sales.CreateSalesTaxInvoiceList', compact('delivery_note', 'Customers'));
    }

    public function createInvoiceForm(Request $request)
    {

        $data = $request->job_order_id;
        $Id = $data[0];
        $data_id = implode(',', $data);

        $joborder = new JobOrder();
        $joborder = $joborder->SetConnection('mysql2');
        $joborder = $joborder->where('status', 1)->where('jo_status', 2)->where('job_order_id', $Id)->select('*')->first();

        $joborderdata = new JobOrderData();
        $joborderdata = $joborderdata->SetConnection('mysql2');
        $joborderdata = $joborderdata->where('status', 1)->whereIn('job_order_id', $data)->select('*')->get();

        // echo '<pre>';
        // print_r($joborderdata);


        $client = new Client();
        $client = $client->SetConnection('mysql2');
        $client = $client->where('status', 1)->get();

        $InvDesc = new InvDesc();
        $InvDesc = $InvDesc->SetConnection('mysql2');
        $InvDesc = $InvDesc->where('status', 1)->get();

        $Account = new Account();
        $Account = $Account->SetConnection('mysql2');
        $Account = $Account->where('status', 1)->orderBy('level1', 'ASC')
            ->orderBy('level2', 'ASC')
            ->orderBy('level3', 'ASC')
            ->orderBy('level4', 'ASC')
            ->orderBy('level5', 'ASC')
            ->orderBy('level6', 'ASC')
            ->orderBy('level7', 'ASC')
            ->get();


        return view('Sales.createInvoiceForm', compact('Id', 'joborder', 'joborderdata', 'client', 'InvDesc', 'Account', 'data_id'));
    }


    public function createInvoiceFormseprate($id)
    {



        echo 'sas';
    }
    public function editInvoice($Id)
    {
        $EditId = $Id;
        $Invoice = new Invoice();
        $Invoice = $Invoice->SetConnection('mysql2');
        $Invoice = $Invoice->where('status', 1)->where('id', $Id)->select('*')->first();
        $InvoiceData = new InvoiceData();
        $InvoiceData = $InvoiceData->SetConnection('mysql2');
        $InvoiceData = $InvoiceData->where('status', 1)->where('master_id', $Id)->select('*')->get();
        $client = new Client();
        $client = $client->SetConnection('mysql2');
        $client = $client->where('status', 1)->get();
        $InvDesc = new InvDesc();
        $InvDesc = $InvDesc->SetConnection('mysql2');
        $InvDesc = $InvDesc->where('status', 1)->get();
        $Account = new Account();
        $Account = $Account->SetConnection('mysql2');
        $Account = $Account->where('status', 1)->orderBy('level1', 'ASC')
            ->orderBy('level2', 'ASC')
            ->orderBy('level3', 'ASC')
            ->orderBy('level4', 'ASC')
            ->orderBy('level5', 'ASC')
            ->orderBy('level6', 'ASC')
            ->orderBy('level7', 'ASC')
            ->get();
        return view('Sales.editInvoice', compact('EditId', 'Invoice', 'InvoiceData', 'client', 'InvDesc', 'Account'));
    }


    public function editQuotation($Id)
    {
        $EditId = $Id;
        $Quotation = new Quotation();
        $Quotation = $Quotation->SetConnection('mysql2');
        $Quotation = $Quotation->where('status', 1)->where('id', $Id)->select('*')->first();


        $QuotationData = new Quotation_Data();
        $QuotationData = $QuotationData->SetConnection('mysql2');
        $QuotationData = $QuotationData->where('status', 1)->where('master_id', $Id)->select('*')->get();
        return view('Sales.editQuotation', compact('EditId', 'Quotation', 'QuotationData'));
    }

    public function editClientBranchForm($BranchId)
    {


        $client = new Client();
        $client = $client->SetConnection('mysql2');
        $client = $client->where('status', 1)->get();
        $Branch = new Branch();
        $Branch = $Branch->SetConnection('mysql2');
        $Branch = $Branch->where('id', $BranchId)->where('status', 1)->select('id', 'acc_id', 'client_id', 'branch_name', 'ntn', 'strn', 'address')->first();


        return view('Sales.AjaxPages.editClientBranchForm', compact('Branch', 'client'));
    }



    public function addComplaint()
    {
        $client = new Client();
        $client = $client->SetConnection('mysql2');
        $client = $client->where('status', 1)->get();

        $product = new Product();
        $product = $product->SetConnection('mysql2');
        $product = $product->where('p_status', 1)->select('*')->get();

        return view('Sales.addComplaint', compact('client', 'product'));
    }

    public function createTestForm()
    {
        $supplier = new Supplier();
        $supplier = $supplier->SetConnection('mysql2');
        $supplier = $supplier->where('status', 1)->select('id', 'name')->get();
        return view('Sales.createTestForm', compact('supplier'));
    }

    public function import_payment_process()
    {
        $supplier = new Supplier();
        $supplier = $supplier->SetConnection('mysql2');
        $supplier = $supplier->where('status', 1)->select('id', 'name')->get();
        return view('Sales.import_payment_process', compact('supplier'));
    }

    public function importDocumentList()
    {
        $ImportPo = DB::Connection('mysql2')->table('import_po')->where('status', 1)->get();
        return view('Sales.importDocumentList', compact('ImportPo'));
    }

    public function createCustomerOpeningBalance()
    {
        $Customers = DB::Connection('mysql2')->table('customers')->where('status', 1)->get();
        return view('Sales.createCustomerOpeningBalance', compact('Customers'));
    }

    public function creatVendorOpeningBalance()
    {
        $Supplier = DB::Connection('mysql2')->table('supplier')->where('status', 1)->get();
        return view('Sales.creatVendorOpeningBalance', compact('Supplier'));
    }




    public function complaintList()
    {
        $Complaint = new Complaint();
        $Complaint = $Complaint->SetConnection('mysql2');
        $Complaint = $Complaint->where('status', 1)->get();
        $Client = new Client();
        $Client = $Client->SetConnection('mysql2');
        $Client = $Client->where('status', 1)->select('*')->get();
        $Region = new Region();
        $Region = $Region->SetConnection('mysql2');
        $Region = $Region->where('status', 1)->select('*')->get();

        return view('Sales.complaintList', compact('Complaint', 'Client', 'Region'));
    }

    public function CreateSalesTaxInvoice(Request $request)
    {


        $sale_order_data = new DeliveryNoteData();
        $sale_order_data = $sale_order_data->SetConnection('mysql2');
        $sale_order_data = $sale_order_data->whereIn('master_id', $request->checkbox)->orderBy('id', 'ASC')->get();

        $ids = implode(',', $request->checkbox);
        //  dd($ids);
        //        $sale_order_data=DB::Connection('mysql2')->select('select a.id,sum(a.qty)qty,a.rate,a.amount,a.discount_percent,a.master_id,a.so_id,a.so_data_id,
        //        a.gd_no,a.id,a.bundles_id,a.groupby,a.item_id,a.warehouse_id,a.discount_amount,a.batch_code,b.product_name,b.rate as bundle_rate,b.amount as bundle_amount
        //        ,b.discount_percent as b_percent,b.discount_amount as b_dis_amount,b.net_amount as b_net,b.qty as bqty,b.bundle_unit,b.id as bundl
        //        from delivery_note_data  a
        //        left join
        //        bundles b
        //        on
        //        a.bundles_id=b.id
        //        where a.status=1
        //        and a.master_id in ('.$ids.')
        //        group by  a.groupby,
        //        a.so_data_id
        //        ');


        $sale_order_data = DB::Connection('mysql2')->select('select a.item_id,a.groupby,a.id,b.master_id,b.bundles_id,a.id as so_data_id,a.desc,
        b.gd_no,sum(b.qty) as qty,a.master_id as so_id,b.warehouse_id,b.rate,b.tax,c.product_name,c.bundle_unit,c.qty as bqty,
        c.rate as bundle_rate,c.amount as bundle_amount ,c.discount_percent as b_percent,c.discount_amount as b_dis_amount,c.net_amount as b_net

        from sales_order_data  a
        inner join
        delivery_note_data b
        on
        a.id=b.so_data_id
        left join
        bundles c
        on
        a.bundles_id=c.id
        where a.status=1
        and b.status=1

        and b.master_id in (' . $ids . ')
        group by  a.groupby
        ');


        //    echo '<pre>';
        //   print_r($sale_order_data);die;




        //        $sale_order_data=DB::Connection('mysql2')->select('select a.id,a.master_id,a.qty,a.rate,a.amount,a.bundles_id,
        //        a.item_id,a.discount_percent,a.discount_amount,b.product_name,b.rate as bundle_rate,b.amount as bundle_amount
        //        ,b.discount_percent as b_percent,b.discount_amount as b_dis_amount,b.net_amount as b_net,b.qty as bqty,b.bundle_unit
        //         from delivery_note_data a
        //
        //        left join
        //        bundles b
        //        on
        //        a.bundles_id=b.id
        //        where a.master_id in ('.$ids.')
        //        group by a.groupby');
        //   echo '<pre>';
        //   print_r($sale_order_data);die;

        $delivery_note = new DeliveryNote();
        $delivery_note = $delivery_note->SetConnection('mysql2');
        $delivery_not = $delivery_note
            ->where('status', 1)
            ->whereIn('id', $request->checkbox)
            ->select('gd_no', 'gd_date', 'despacth_document_no', 'despacth_document_date', 'so_no', 'so_date', 'master_id', 'id')->first();

        $so_id = $delivery_not->master_id;

        $delivery_note_data = DB::connection('mysql2')->table('delivery_note_data')->where('master_id', $delivery_not->id)->get();

      
        // dd($delivery_note_data);
        $sales_order = new Sales_Order();
        $sales_order = $sales_order->SetConnection('mysql2');
        $sales_order = $sales_order
            ->where('id', $so_id)->first();


        $accounts = new Account();
        $accounts = $accounts->SetConnection('mysql2');
        $accounts = $accounts->where('status', 1)->get();
        return view('Sales.CreateSalesTaxInvoice', compact('sales_order', 'sale_order_data', 'delivery_not', 'delivery_note_data', 'accounts', 'ids'));
    }

    public function EditSalesTaxInvoice($id)
    {
        $sales_tax_invoice = new SalesTaxInvoice();
        $sales_tax_invoice = $sales_tax_invoice->SetConnection('mysql2');
        $sales_tax_invoice = $sales_tax_invoice->where('id', $id)->first();

        $sales_tax_invoice_data = new SalesTaxInvoiceData();
        $sales_tax_invoice_data = $sales_tax_invoice_data->SetConnection('mysql2');
        $sales_tax_invoice_data = $sales_tax_invoice_data->where('master_id', $id)->get();

        // Got this data from sale tax invoice




        return view('Sales.EditSalesTaxInvoice', compact('sales_tax_invoice', 'sales_tax_invoice_data'));
    }

 public function viewSalesTaxInvoiceList()
    {

        $currentMonthStartDate = date('Y-m-01');
        $currentMonthEndDate   = date('Y-m-t');

        $sales_tax_invoice = new SalesTaxInvoice();
        $sales_tax_invoice = $sales_tax_invoice->SetConnection('mysql2');            
        $user = auth()->user();
        $territory_ids = json_decode($user->territory_id);                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   
       
        $sales_tax_invoice = $sales_tax_invoice
            ->join('customers', 'customers.id', '=', 'sales_tax_invoice.buyers_id')
            ->whereIn('customers.territory_id', $territory_ids)
            ->where('sales_tax_invoice.status', 1)
            ->where(function ($q) {
                $q->where('sales_tax_invoice.pre_status', '!=', 1)
                ->orWhereNull('sales_tax_invoice.pre_status');
            })
            ->select('sales_tax_invoice.*', 'customers.territory_id') // only territory_id from customers
            ->get();

        $Customer = DB::Connection('mysql2')->table('customers')->where('status', 1)->get();
        $username = SalesTaxInvoice::select('username')->groupBy('username')->get();

      
        return view('Sales.viewSalesTaxInvoiceList', compact('sales_tax_invoice', 'Customer', 'username'));                                                                                                             
    }

    public function viewSalesTaxInvoiceDetailList() 
    {

        $Customer = DB::Connection('mysql2')->table('customers')->where('status', 1)->get();

        return view('Sales.viewSalesTaxInvoiceDetailList', compact('Customer'));
    }

    public function viewSalesTaxInvoiceDetailListAjax(Request $request)
    {

        $fromDate = $request->fromDate;
        $toDate = $request->toDate;
        $customer_id = $request->customer_id;

        $data = DB::Connection('mysql2')->table('sales_tax_invoice as sti')
            ->join('sales_tax_invoice_data as stid', 'sti.id', '=', 'stid.master_id')
            ->join('customers as c', 'sti.buyers_id', '=', 'c.id')
            ->join('subitem as s', 'stid.item_id', '=', 's.id')
            ->join('uom as u', 'u.id', '=', 's.uom')
            ->join('sales_order as so', 'so.id', '=', 'sti.so_id')
            ->selectRaw('IFNULL(c.NTNNumber, c.cnic_ntn) as ntn, c.name, c.address, sti.gi_no, sti.gi_date, sum(stid.qty) qty, u.uom_name, SUM(stid.amount) as amount, so.sales_tax_rate, so.sales_tax_further');


        if ($fromDate) {
            $data = $data->where('sti.gi_date', '>=', $fromDate);
        }
        if ($toDate) {
            $data = $data->where('sti.gi_date', '<=', $toDate);
        }
        if ($customer_id) {
            $data = $data->where('c.id', $customer_id);
        }

        $data = $data->groupBy('so.id')->get();



        return view('Sales.AjaxPages.viewSalesTaxInvoiceDetailListAjax', compact('data'));
    }
    public function viewPerformaInvoice(Request $request)
    {
        $delivery_note = DeliveryNote::find($request->id);
        $delivery_note_data = DeliveryNoteData::where('master_id', $request->id)->get();
        return view('Sales.AjaxPages.viewPerformaInvoice', compact('delivery_note', 'delivery_note_data'));
    }

    public  function viewSalesTaxInvoiceDetail()
    {
        $ID = Input::get('id');
        $Checking = $ID;
        $Checking = explode(',', $Checking);

        if (count($Checking) > 1) {
            $si = DB::Connection('mysql2')->table('sales_tax_invoice')->where('gi_no', $Checking[1])->select('id')->first();
            $id = $si->id;
        } else {
            $id = $Checking[0];
        }
        $sales_tax_invoice = new SalesTaxInvoice();
        $sales_tax_invoice = $sales_tax_invoice->SetConnection('mysql2');
        $sales_tax_invoice = $sales_tax_invoice->where('id', $id)->first();



        $sales_tax_invoice_data = DB::Connection('mysql2')->select('select a.item_id,a.qty,a.rate,a.tax as tax ,a.tax_amount,a.amount,a.gd_no,a.bundles_id,a.so_data_id,
        a.description,b.rate as bundle_rate, b.amount as bundle_amount , b.discount_percent as b_percent, b.discount_amount as b_dis_amount, b.net_amount as b_net, b.product_name, b.qty as bqty
        ,b.bundle_unit,a.so_type,a.dn_data_ids
        from sales_tax_invoice_data a
        left join
        bundles b
        on
        a.bundles_id=b.id
        where a.status=1
        and a.master_id  ="' . $id . '"
        ');



        $AddionalExpense = DB::Connection('mysql2')->table('addional_expense_sales_tax_invoice')->where('main_id', $id);
        $sales_tax_invoice_data_other = DB::Connection('mysql2')->table('sales_tax_invoice_data')->where('master_id', $id)->get();

        return view('Sales.AjaxPages.viewSalesTaxInvoiceDetail', compact('sales_tax_invoice', 'sales_tax_invoice_data', 'AddionalExpense', 'sales_tax_invoice_data_other'));
    }

    public  function viewReceivedAllVoucher()
    {
        $id = Input::get('id');
        $AllReceipt = DB::Connection('mysql2')->table('received_paymet')->where('status', 1)->where('sales_tax_invoice_id', $id)->get();
        return view('Sales.viewReceivedAllVoucher', compact('AllReceipt'));
    }

    public  function PrintSalesTaxInvoice()
    {
        $id = Input::get('id');
        $sales_tax_invoice = new SalesTaxInvoice();
        $sales_tax_invoice = $sales_tax_invoice->SetConnection('mysql2');
        $sales_tax_invoice = $sales_tax_invoice->where('id', $id)->first();

        //        $sales_tax_invoice_data=new SalesTaxInvoiceData();
        //        $sales_tax_invoice_data=$sales_tax_invoice_data->SetConnection('mysql2');
        //        $sales_tax_invoice_data=$sales_tax_invoice_data->where('master_id',$id)->get();


        $sales_tax_invoice_data = DB::Connection('mysql2')->select('select a.item_id,a.qty,a.rate,a.discount as discount_percent ,a.discount_amount,a.amount,a.gd_no,a.bundles_id,a.so_data_id,
        a.description,b.rate as bundle_rate, b.amount as bundle_amount , b.discount_percent as b_percent, b.discount_amount as b_dis_amount, b.net_amount as b_net, b.product_name, b.qty as bqty
        ,b.bundle_unit,a.so_type
        from sales_tax_invoice_data  a
        left join
        bundles b
        on
        a.bundles_id=b.id
        where a.status=1
        and a.master_id  ="' . $id . '"
        group by  a.groupby
        ');



        $AddionalExpense = DB::Connection('mysql2')->table('addional_expense_sales_tax_invoice')->where('main_id', $id);

        return view('Sales.AjaxPages.PrintSalesTaxInvoice', compact('sales_tax_invoice', 'sales_tax_invoice_data', 'AddionalExpense'));
    }


    public  function PrintSalesTaxInvoiceDirect()
    {
        $id = Input::get('id');
        $sales_tax_invoice = new SalesTaxInvoice();
        $sales_tax_invoice = $sales_tax_invoice->SetConnection('mysql2');
        $sales_tax_invoice = $sales_tax_invoice->where('id', $id)->first();

        //        $sales_tax_invoice_data=new SalesTaxInvoiceData();
        //        $sales_tax_invoice_data=$sales_tax_invoice_data->SetConnection('mysql2');
        //        $sales_tax_invoice_data=$sales_tax_invoice_data->where('master_id',$id)->get();


        $sales_tax_invoice_data = DB::Connection('mysql2')->select('select
            a.item_id,
            a.qty,
            a.rate,
            a.discount as discount_percent ,
            a.discount_amount,
            a.amount,
            a.gd_no,
            a.bundles_id,
            a.so_data_id,
            a.description,
            b.rate as bundle_rate,
            b.amount as bundle_amount,
            b.discount_percent as b_percent,
            b.discount_amount as b_dis_amount,
            b.net_amount as b_net,b.product_name, b.qty as bqty
        ,b.bundle_unit,a.so_type
        from sales_tax_invoice_data  a
        left join
        bundles b
        on
        a.bundles_id=b.id
        where a.status=1
        and a.master_id  ="' . $id . '"
        group by  a.groupby
        ');



        $AddionalExpense = DB::Connection('mysql2')->table('addional_expense_sales_tax_invoice')->where('main_id', $id);

        return view('Sales.AjaxPages.PrintSalesTaxInvoiceDirect', compact('sales_tax_invoice', 'sales_tax_invoice_data', 'AddionalExpense'));
    }

    // public function CreateReceiptVoucherList()
    // {
    //     $Customer = DB::Connection('mysql2')->table('customers')->where('status', 1)->get();
    //     $SiMaster = DB::Connection('mysql2')->table('sales_tax_invoice')->where('status', 1)->get();

    //     return view('Sales.CreateReceiptVoucherList', compact('Customer', 'SiMaster'));
    // }


    public function CreateReceiptVoucherList()
{
  $user = Auth::user();

if (!$user) {
    return collect(); // Not logged in
}

// If user is restricted by territory
if (in_array($user->acc_type, ['user'])) {

    // Get territory IDs (can be JSON array or single ID)
    $territory_ids = json_decode($user->territory_id, true);
    if (!is_array($territory_ids)) {
        $territory_ids = [$user->territory_id];
    }

    
    $Customer = DB::connection('mysql2')
        ->table('customers')
        ->where('status', 1)
        ->whereIn('territory_id', $territory_ids)
        ->get();
} else {
    $Customer = DB::connection('mysql2')
        ->table('customers')
        ->where('status', 1)
        ->get();
}
   

    $SiMaster = DB::connection('mysql2')
        ->table('sales_tax_invoice')
        ->where('status', 1)
        ->get();

    return view('Sales.CreateReceiptVoucherList', compact('Customer', 'SiMaster'));
}

    public function receiptVoucherList()
    {
        $currentMonthStartDate = date('Y-m-01');
        $currentMonthEndDate   = date('Y-m-t');
        $accounts = new Account;
        $accounts = $accounts->SetConnection('mysql2');
        $accounts = $accounts->where('status', 1)->select('id', 'name', 'code')->orderBy('level1', 'ASC')
            ->orderBy('level2', 'ASC')
            ->orderBy('level3', 'ASC')
            ->orderBy('level4', 'ASC')
            ->orderBy('level5', 'ASC')
            ->orderBy('level6', 'ASC')
            ->orderBy('level7', 'ASC')
            ->get();

        $NewRvs = new NewRvs();
        $NewRvs = $NewRvs->SetConnection('mysql2');
        $NewRvs = $NewRvs->where('status', 1)->where('sales', 1)->orderBy('id', 'DESC')->get();
        CommonHelper::reconnectMasterDatabase();
        return view('Sales.receiptVoucherList', compact('NewRvs', 'accounts'));
    }

    public function editVoucherList(int $id)
    {  
        $accounts = new Account;
        $accounts = $accounts->SetConnection('mysql2');
        $accounts = $accounts->where('status', 1)->select('id', 'name', 'code')->orderBy('level1', 'ASC')
            ->orderBy('level2', 'ASC')
            ->orderBy('level3', 'ASC')
            ->orderBy('level4', 'ASC')
            ->orderBy('level5', 'ASC')
            ->orderBy('level6', 'ASC')
            ->orderBy('level7', 'ASC')
            ->get();

        $NewRvs = new NewRvs();
        $NewRvs = $NewRvs->SetConnection('mysql2');
        $NewRvs = $NewRvs->where('status', 1)->where('sales', 1)->where('id', $id)->first();

        $NewRvsData = DB::Connection('mysql2')->table('new_rv_data')->where('status', 1)->where('master_id', '=', $id)->get();
        $brige_table = DB::Connection('mysql2')->table('brige_table_sales_receipt')->where('status', 1)->where('rv_id', '=', $id)->get();

        return view('Sales.editVoucherList', compact('NewRvs', 'NewRvsData', 'brige_table', 'accounts', 'id'));
    }

    public function undertaking()
    {

        $id = Input::get('id');
        $sales_tax_invoice = new SalesTaxInvoice();
        $sales_tax_invoice = $sales_tax_invoice->SetConnection('mysql2');
        $sales_tax_invoice = $sales_tax_invoice->where('id', $id)->first();

        return view('Sales.undertaking', compact('sales_tax_invoice'));
    }
    public function CreateCustomerCreditNote()
    {
        $sales_tax_invoice = new SalesTaxInvoice();
        $sales_tax_invoice = $sales_tax_invoice->SetConnection('mysql2');
        $sales_tax_invoice = $sales_tax_invoice->where('status', 1)->get();
        return view('Sales.CreateCustomerCreditNote', compact('sales_tax_invoice'));
    }

    public function addCustomerCredit_no(Request $request)
    {
        $values = $request->checkbox;
        $buyer_id = $request->buyer_id;
        $type = $request->type;
        
        return view('Sales.addCustomerCredit_no', compact('values', 'buyer_id', 'type'));
    }
    public function editCustomerCredit_no($id)
    {
        $credit_note = new CreditNote();
        $credit_note = $credit_note->SetConnection('mysql2');
        $credit_note = $credit_note->find($id);
        // dd($credit_note->creditNoteData);
        return view('Sales.editCustomerCredit_no', compact('credit_note'));
    }

    public function viewCustomerCreditNoteList()
    {
        $currentMonthStartDate = date('Y-m-01');
        $currentMonthEndDate   = date('Y-m-t');

        $credit_note = new CreditNote();
        $credit_note = $credit_note->SetConnection('mysql2');
        $credit_note = $credit_note->where('status', 1)->whereBetween('cr_date', [$currentMonthStartDate, $currentMonthEndDate])->orderBy('id', 'DESC')->get();
        return view('Sales.viewCustomerCreditNoteList', compact('credit_note'));
    }
    public function viewCustomer(Request $request)
    {

     
        $customer = Customer::where('customers.id',$request->id)->leftJoin("territories", "territories.id", "customers.territory_id")
            ->leftJoin("warehouse", "warehouse.id", "customers.warehouse_from")
            ->leftJoin("stores_categories", "stores_categories.id", "customers.store_category")
            ->select("territories.name as tname", "customers.*", "warehouse.name as warehouse_name", "stores_categories.name as store_categoryname")->first();
    //    echo $request->id;

        
        // dd($customer->acc_id);
     

    if (!$customer) {
        abort(404, 'Customer not found');
    }
        // dd($customer);
       
            $bankDetail = BankDetail::where('acc_id', $customer->acc_id)->first();
        return view('Sales.viewCustomer', compact('customer', 'bankDetail'));
    }
    public function customerOrderTracking(Request $request)
    {
        return view('Sales.customerOrderTracking');
    }

    public  function viewCreditNoteDetail()
    {
        $id = Input::get('id');
        $creit_note = new CreditNote();
        $creit_note = $creit_note->SetConnection('mysql2');
        $creit_note = $creit_note->where('id', $id)->first();

        $credit_note_data = new CreditNoteData();
        $credit_note_data = $credit_note_data->SetConnection('mysql2');
        $credit_note_data = $credit_note_data->where('master_id', $id)->get();

        return view('Sales.AjaxPages.viewCreditNoteDetail', compact('creit_note', 'credit_note_data'));
    }

    public function createType()
    {
        return view('Sales.createType');
    }

    public function createConditions()
    {
        return view('Sales.createConditions');
    }

    public function createSurveyBy()
    {
        return view('Sales.createSurveyBy');
    }

    public function typeList()
    {
        $type = new Type();
        $type = $type->SetConnection('mysql2');
        $type = $type->where('status', 1)->get();

        return view('Sales.typeList', compact('type'));
    }

    public function conditionList()
    {
        $conditions = new Conditions();
        $conditions = $conditions->SetConnection('mysql2');
        $conditions = $conditions->where('status', 1)->get();

        return view('Sales.conditionList', compact('conditions'));
    }

    public function clientJobList()
    {
        $ClientJob = new ClientJob();
        $ClientJob = $ClientJob->SetConnection('mysql2');
        $ClientJob = $ClientJob->where('status', 1)->get();

        return view('Sales.clientJobList', compact('ClientJob'));
    }


    public function branchList()
    {
        $branches = new Branch();
        $branches = $branches->SetConnection('mysql2');
        $branches = $branches->where('status', 1)->get();

        return view('Sales.branchList', compact('branches'));
    }

    public function surveylist()
    {
        $survey = new Survey();
        $survey = $survey->SetConnection('mysql2');
        $survey = $survey->where('status', 1)->get();
        $Client = new Client();
        $Client = $Client->SetConnection('mysql2');
        $Client = $Client->where('status', 1)->select('*')->get();
        $Region = new Region();
        $Region = $Region->SetConnection('mysql2');
        $Region = $Region->where('status', 1)->select('*')->get();

        return view('Sales.surveylist', compact('survey', 'Client', 'Region'));
    }

    public function jobtrackinglist()
    {
        $jobtracking = new JobTracking();
        $jobtracking = $jobtracking->SetConnection('mysql2');
        $jobtracking = $jobtracking->where('status', 1)->get();

        return view('Sales.jobtrackinglist', compact('jobtracking'));
    }

    public function addquotationForm()
    {
        $survey = new Survey();
        $survey = $survey->SetConnection('mysql2');
        $survey = $survey->where('status', 1)->where('survey_status', 2)->where('quotation_type', 0)->get();
        return view('Sales.addquotationForm', compact('survey'));
    }
    public function quotationList()
    {
        $quotation = new Quotation();
        $quotation = $quotation->SetConnection('mysql2');
        $quotation = $quotation->where('status', 1)->get();
        $Client = new Client();
        $Client = $Client->SetConnection('mysql2');
        $Client = $Client->where('status', 1)->select('*')->get();
        $Region = new Region();
        $Region = $Region->SetConnection('mysql2');
        $Region = $Region->where('status', 1)->select('*')->get();
        return view('Sales.quotationList', compact('quotation', 'Client', 'Region'));
    }

    public function invoiceList()
    {
        $invoice = new Invoice();
        $invoice = $invoice->SetConnection('mysql2');
        $invoice = $invoice->where('status', 1)->where('type', 0)->orderBy('id', 'DESC')->get()->take(50);

        $Client = new Client();
        $Client = $Client->SetConnection('mysql2');
        $Client = $Client->where('status', 1)->get();


        return view('Sales.invoiceList', compact('invoice', 'Client'));
    }




    public function addClient()
    {
        return view('Sales.addClient');
    }

    public function createBranch()
    {
        $Client = new Client();
        $Client = $Client->SetConnection('mysql2');
        $Client = $Client->where('status', 1)->get();
        return view('Sales.createBranch', compact('Client'));
    }

    public function addDesc()
    {
        return view('Sales.addDesc');
    }
    public function invoiceDescList()
    {

        $InvDesc = new InvDesc();
        $InvDesc = $InvDesc->SetConnection('mysql2');
        $InvDesc = $InvDesc->where('status', 1)->get();
        return view('Sales.invoiceDescList', compact('InvDesc'));
    }



    public function addClientJob()
    {
        return view('Sales.addClientJob');
    }

    public function addClientJobAjax()
    {
        return view('Sales.addClientJobAjax');
    }
    public function addBranchAjax()
    {
        $Client = new Client();
        $Client = $Client->SetConnection('mysql2');
        $Client = $Client->where('status', 1)->get();
        return view('Sales.addBranchAjax', compact('Client'));
    }


    public function clientList()
    {

        $client = new Client();
        $client = $client->SetConnection('mysql2');
        $client = $client->where('status', 1)->get();


        $Account = new Account();
        $Account = $Account->SetConnection('mysql2');
        $Account = $Account->orderBy('level1', 'ASC')
            ->orderBy('level2', 'ASC')
            ->orderBy('level3', 'ASC')
            ->orderBy('level4', 'ASC')
            ->orderBy('level5', 'ASC')
            ->orderBy('level6', 'ASC')
            ->orderBy('level7', 'ASC')
            ->get();

        return view('Sales.clientList', compact('client', 'Account'));
    }

    public function clientBranchList()
    {

        $Branch = new Branch();
        $Branch = $Branch->SetConnection('mysql2');
        $Branch = $Branch->where('status', 1)->get();

        return view('Sales.clientBranchList', compact('Branch'));
    }


    public function jobTrackingSheetCopy()
    {
        $customer = new Customer();
        $customer = $customer->SetConnection('mysql2');
        $customer = $customer->where('status', 1)->get();
        $region = new Region();
        $region = $region->SetConnection('mysql2');
        $region = $region->where('status', 1)->get();
        $survey = new Survey();
        $survey = $survey->SetConnection('mysql2');
        $survey = $survey->where('status', 1)->get();
        $cities = new Cities();
        //$cities = $cities->SetConnection('mysql2');
        $cities = $cities->where('status', 1)->get();
        return view('Sales.jobTrackingSheetCopy', compact('customer', 'region', 'survey', 'cities'));
    }

    public function createProductType()
    {
        return view('Sales.createProductType');
    }
    public function createProductTrend()
    {
        return view('Sales.ProductTrend.create');
    }
    public function createProductClassification()
    {
        return view('Sales.ProductClassification.create');
    }

    public function createResourceAssigned()
    {
        return view('Sales.createResourceAssigned');
    }

    public function producttypeList()
    {
        $productType = new  ProductType();
        $productType = $productType->SetConnection('mysql2');
        $productType = $productType->where('status', 1)->get();
        return view('Sales.producttypeList', compact('productType'));
    }
    public function productTrendList()
    {
        $ProductTrend = new  ProductTrend();
        $ProductTrend = $ProductTrend->SetConnection('mysql2');
        $ProductTrend = $ProductTrend->where('status', 1)->get();
        return view('Sales.ProductTrend.List', compact('ProductTrend'));
    }
    public function productClassificationList()
    {
        $ProductClassification = new  ProductClassification();
        $ProductClassification = $ProductClassification->SetConnection('mysql2');
        $ProductClassification = $ProductClassification->where('status', 1)->get();
        return view('Sales.ProductClassification.List', compact('ProductClassification'));
    }


    public function resourceAssignedList()
    {
        $resourceAssign = new  ResourceAssigned();
        $resourceAssign = $resourceAssign->SetConnection('mysql2');
        $resourceAssign = $resourceAssign->where('status', 1)->get();
        return view('Sales.resourceAssignedList', compact('resourceAssign'));
    }
    public function createInvoice()
    {
        $joborder = new JobOrder();
        $joborder = $joborder->SetConnection('mysql2');
        $joborder = $joborder->where('status', 1)->where('jo_status', 2)->where('invoice_created', 0)->select('*')->get();
        $client = new Client();
        $client = $client->SetConnection('mysql2');
        $client = $client->where('status', 1)->get();
        return view('Sales.createInvoice', compact('joborder', 'client'));
    }

    public function logActivity()
    {
        return view('Sales.logActivity');
    }
    public function CreateSalesTaxInvoiceBySO(Request $request)
    {
        $so_no = $request->so_no;
        $customer_id = $request->customer_id;

        $delivery_note = new DeliveryNote();
        $delivery_note = $delivery_note->SetConnection('mysql2');

        $territory_ids = json_decode(auth()->user()->territory_id);
        $delivery_note = $delivery_note
            ->join('customers', 'customers.id', '=', 'delivery_note.buyers_id')
            ->where('delivery_note.status', 1)
            ->where('delivery_note.sales_tax_invoice', 0)
            ->whereIn('customers.territory_id', $territory_ids)
            ->when(isset($customer_id), function($query) use ($customer_id) {
                $query->where("delivery_note.buyers_id", $customer_id);
            })
            ->when(isset($so_no), function($query) use($so_no) {
                $query->where("delivery_note.so_no", $so_no);
            })
            ->select('delivery_note.*', 'customers.territory_id') // <-- fix ambiguity
            ->get();


        return view('Sales.AjaxPages.CreateSalesTaxInvoiceBySO', compact('delivery_note', "customer_id", "so_no"));
    }

    public function dn_without_Sales(Request $request)

    {


        return view('Sales.dn_without_Sales');
    }
    public function salesTaxInvoiceReportPage()
    {
        return view('Sales.salesTaxInvoiceReportPage');
    }

    public function cogs_si(Request $request)
    {
        return view('Sales.cogs_si');
    }
    public function pos_list(Request $request)
    {

        return view('Sales.pos_list');
    }

    public function po_detail(Request $request)
    {
        $id = $request->id;
        return view('Sales.AjaxPages.po_detail', compact('id'));
    }
    public function view_convert_grn(Request $request)
    {
        $id = $request->id;
        return view('Sales.view_convert_grn', compact('id'));
    }


    public function approve_so(Request $request)
    {
        $id = $request->id;
        $so_status = 0;
        $approve_user = '';
        $approve = '';
        $send_behavior = '';
        $so_data =   DB::Connection('mysql2')->table('sales_order')->where('id', $id)->first(); // It is giving me data of sales
        $so_no = $so_data->so_no;
        $dept_id = $so_data->department;
        $p_type = $so_data->p_type;

        if ($so_data->approve_user_1 == ''):
            $so_status = 1;
            $approve_user = 'approve_user_1';
            $approve = 'Approved';
            $send_behavior = 'Approve 1';
        elseif ($so_data->approve_user_2 == ''):
            $so_status = 3;
            $approve_user = 'approve_user_2';
            $approve = '2nd Approved';
            $send_behavior = 'Approve 2';
        elseif ($so_data->approve_user_3 == ''):
            $so_status = 4;
            $approve_user = 'approve_user_3';
            $approve = 'Approved';
            $send_behavior = 'Approve 3';
        endif;

        DB::Connection('mysql2')->table('sales_order')
            ->where('id', $id)
            ->update([$approve_user => Auth::user()->name, 'so_status' =>  $so_status]);



        $voucher_no = $so_no;
        $subject = 'Sales Order Approve For ' . $so_no;
        NotificationHelper::send_email('Sales Order', $send_behavior, $dept_id, $voucher_no, $subject, $p_type);

        echo $approve;
    }


    public static function si_approve(Request $request)
    {
        DB::Connection('mysql2')->beginTransaction();
        try {
            $id =  $request->id;
            $approve = '';
            $behavior = '';
            $si_data =   DB::Connection('mysql2')->table('sales_tax_invoice')->where('id', $id)->first();

            $so_type = $si_data->si_status;
            $gi_no = $si_data->gi_no;
            $so_id = $si_data->so_id;
            $so_no = $si_data->so_no;

            if ($so_type == 0):
                DB::Connection('mysql2')->table('sales_tax_invoice')
                    ->where('id', $id)
                    ->update(['approve_user_1' => Auth::user()->name, 'si_status' =>  2]);
                $approve = '1st Approved';
                $behavior = 'Approve 1';
            else :
                DB::Connection('mysql2')->table('sales_tax_invoice')
                    ->where('id', $id)
                    ->update(['approve_user_2' => Auth::user()->name, 'si_status' =>  3]);


                DB::Connection('mysql2')->table('transactions')
                    ->where('voucher_no', $gi_no)
                    ->where('status', 100)
                    ->update(['status' => 1]);
                $approve = 'Approved';
                $behavior = 'Approve 2';

                $sales_tax_invoice_data = new SalesTaxInvoiceData();
                $sales_tax_invoice_data = $sales_tax_invoice_data->SetConnection('mysql2');
                $sales_tax_invoice_data = $sales_tax_invoice_data->where('status', 1)->where('master_id', $id)->get();
                if ($si_data->so_no == ''):
                    foreach ($sales_tax_invoice_data as $key => $row):

                        $qty =  ReuseableCode::get_stock($row->item_id, $row->warehouse_id, $row->qty, 0);
                        if ($qty < 0):
                            DB::rollBack();
                            return 0;
                        endif;

                        $average_cost = ReuseableCode::average_cost_sales(
                            $row->item_id,
                            $row->warehouse_id,
                            0
                        );



                        $stock = array(
                            'main_id' => $row->master_id,
                            'master_id' => $row->id,
                            'voucher_no' => $row->gi_no,
                            'voucher_date' => $si_data->gi_date,
                            'supplier_id' => 0,
                            'customer_id' => $row->buyers_id,
                            'voucher_type' => 5,
                            'rate' => $row->rate,
                            'sub_item_id' => $row->item_id,
                            'batch_code' => 0,
                            'qty' => $row->qty,
                            'discount_percent' => '',
                            'discount_amount' => '',
                            'amount' => $row->qty * $average_cost,
                            'status' => 1,
                            'warehouse_id' => $row->warehouse_id,
                            'username' => Auth::user()->username,
                            'created_date' => date('Y-m-d'),
                            'created_date' => date('Y-m-d'),
                            'opening' => 0,
                            'so_data_id' => '',
                        );
                        DB::Connection('mysql2')->table('stock')->insert($stock);
                    endforeach;
                endif;
            endif;
            if ($so_id != 0):
                $voucher_no = $gi_no;
                $dept_and_type = NotificationHelper::get_dept_id('sales_order', 'id', $so_id)->select('department', 'p_type')->first();
                $dept_id = $dept_and_type->department;
                $p_type = $dept_and_type->p_type;
                $subject = 'Sales Tax Invoice Approved For ' . $so_no;
                NotificationHelper::send_email('Sales tax Invoice', $behavior, $dept_id, $voucher_no, $subject, $p_type);
            endif;
            DB::Connection('mysql2')->commit();
        } catch (Exception $ex) {

            DB::rollBack();
            echo $ex->getLine();
        }
        return $approve;
    }

    public function editDirectSalesTaxInvoice($id)
    {


        $sale_tax_invoice = DB::Connection('mysql2')->table('sales_tax_invoice')
            ->where('id', $id)->first();

        // get sales Order Data

        $sales_order = new Sales_Order();
        $sales_order = $sales_order->SetConnection('mysql2');
        $sales_order = $sales_order->where('id', $sale_tax_invoice->so_id)->first();


        $so_no = $sales_order->so_no;
        $delivery_note = new DeliveryNote();
        $delivery_note = $delivery_note->SetConnection('mysql2');
        $delivery_note = $delivery_note->where('status', 1)->where('sales_tax_invoice', 1)->where('so_no', $so_no)->get();


        if ($sale_tax_invoice->si_status == 3):
            dd('Approved Voucher Can not be Edit');
        endif;
        $sale_tax_invoice_data =  DB::Connection('mysql2')->table('sales_tax_invoice_data')
            ->where('master_id', $id)->get();
        return view('Sales.editDirectSalesTaxInvoice', compact('sale_tax_invoice', 'sale_tax_invoice_data', 'sales_order'));
    }
    public function createStoresCategory(Request $request)
    {
        if (!$_POST) {

            return view('Sales.StoresCategory.create');
        } else {

            $storesCategory = new  StoresCategory();
            $storesCategory = $storesCategory->SetConnection('mysql2');
            $storesCategory->name        = $request->name;
            $storesCategory->status      = 1;
            $storesCategory->save();

            return Redirect::to('sales/storesCategoryList?pageType=&&parentCode=105&&m=' . Input::get('m') . '#SFR');
        }
    }
    public function storesCategoryList()
    {
        $StoresCategory = new  StoresCategory();
        $StoresCategory = $StoresCategory->SetConnection('mysql2');
        $StoresCategory = $StoresCategory->where('status', 1)->get();
        return view('Sales.StoresCategory.List', compact('StoresCategory'));
    }
    function editStoresCategoryForm(Request $request)
    {
        if (!$_POST) {
            $id = $_GET['id'];
            $StoresCategory = new StoresCategory();
            $StoresCategory = $StoresCategory->SetConnection('mysql2');
            $StoresCategory = $StoresCategory->find($id);
            return view('Sales.AjaxPages.editStoresCategoryForm', compact('StoresCategory'));
        } else {
            $storesCategory = StoresCategory::find($request->id);
            $storesCategory = $storesCategory->SetConnection('mysql2');
            $storesCategory->name = $request->name;
            $storesCategory->save();

            return Redirect::to('sales/storesCategoryList?pageType=&&parentCode=105&&m=' . Input::get('m') . '#SFR');
        }
    }
    public static function deleteStoresCategory(Request $request)
    {
        $id = $request->id;
        if ($id != "") {
            $StoresCategory = StoresCategory::find($id);
            $StoresCategory->delete();
            // $data['status'] = 0;
            // $StoresCategory->where('id', $id)->update($data);
        }
    }

    public function createTerritory(Request $request)
    {
        if (!$_POST) {

            return view('Sales.Territory.create');
        } else {

            $Territory = new Territory();
            $Territory = $Territory->SetConnection('mysql2');
            $Territory->name        = $request->name;
            $Territory->status      = 1;
            $Territory->save();

            return Redirect::to('sales/territoryList?pageType=&&parentCode=105&&m=' . Input::get('m') . '#SFR');
        }
    }
    public function TerritoryList()
    {
        $Territory = new  Territory();
        $Territory = $Territory->SetConnection('mysql2');
        $Territory = $Territory->where('status', 1)->get();
        return view('Sales.Territory.List', compact('Territory'));
    }
    function editTerritoryForm(Request $request)
    {
        if (!$_POST) {
            $id = $_GET['id'];
            $Territory = new Territory();
            $Territory = $Territory->SetConnection('mysql2');
            $Territory = $Territory->find($id);
            return view('Sales.AjaxPages.editTerritoryForm', compact('Territory'));
        } else {
            $Territory = Territory::find($request->id);
            $Territory = $Territory->SetConnection('mysql2');
            $Territory->name = $request->name;
            $Territory->save();

            return Redirect::to('sales/territoryList?pageType=&&parentCode=105&&m=' . Input::get('m') . '#SFR');
        }
    }
    public static function deleteTerritory(Request $request)
    {
        $id = $request->id;
        if ($id != "") {
            $territory = Territory::find($id);
            $territory->delete();
            // $data['status'] = 0;
            // $StoresCategory->where('id', $id)->update($data);
        }
    }
    public function createCustomerType(Request $request)
    {
        if (!$_POST) {

            return view('Sales.CustomerType.create');
        } else {

            $CustomerType = new CustomerType();
            $CustomerType = $CustomerType->SetConnection('mysql2');
            $CustomerType->name        = $request->name;
            $CustomerType->status      = 1;
            $CustomerType->save();

            return Redirect::to('sales/customerTypeList?pageType=&&parentCode=105&&m=' . Input::get('m') . '#SFR');
        }
    }
    public function customerTypeList()
    {
        $CustomerType = new  CustomerType();
        $CustomerType = $CustomerType->SetConnection('mysql2');
        $CustomerType = $CustomerType->where('status', 1)->get();
        return view('Sales.CustomerType.List', compact('CustomerType'));
    }
    function editCustomerTypeForm(Request $request)
    {
        if (!$_POST) {
            $id = $_GET['id'];
            $CustomerType = new CustomerType();
            $CustomerType = $CustomerType->SetConnection('mysql2');
            $CustomerType = $CustomerType->find($id);
            return view('Sales.AjaxPages.editCustomerTypeForm', compact('CustomerType'));
        } else {
            $CustomerType = CustomerType::find($request->id);
            $CustomerType = $CustomerType->SetConnection('mysql2');
            $CustomerType->name = $request->name;
            $CustomerType->save();

            return Redirect::to('sales/customerTypeList?pageType=&&parentCode=105&&m=' . Input::get('m') . '#SFR');
        }
    }
    public static function deleteCustomerType(Request $request)
    {
        $id = $request->id;
        if ($id != "") {
            $CustomerType = CustomerType::find($id);
            $CustomerType->delete();
            // $data['status'] = 0;
            // $StoresCategory->where('id', $id)->update($data);
        }
    }
    public function assignDicount(Request $request)
    {
       if (!$_POST) {
            $stores = DB::connection('mysql2')->table('customers')->where('status', 1)->get();
            $Brands = DB::connection('mysql2')->table('brands')->where('status', 1)->get();

            // Fetch existing discounts
           $discounts = DB::connection('mysql2')->table('special_prices')->get()->keyBy(function($item) {
    return $item->customer_id.'_'.$item->brand_id;
});

            return view('Sales.BrandDiscount.create', compact('stores', 'Brands', 'discounts'));
        }else {

         
            // $SpecialPrice = new SpecialPrice();
            // $SpecialPrice->customer_id        = $request->store_id;
            // $SpecialPrice->brand_id        = implode(',', $request->brand);
            // $SpecialPrice->discount      = $request->discount;
            // $SpecialPrice->save();

            $stores = $request->stores ?? [];
        $discounts = $request->discounts ?? [];

        foreach ($stores as $storeId) {
            if (!isset($discounts[$storeId])) {
                continue;
            }

            foreach ($discounts[$storeId] as $brandId => $discountValue) {
                SpecialPrice::updateOrCreate(
                    [
                        'customer_id' => $storeId,
                        'brand_id'    => $brandId,
                    ],
                    [
                        'discount'    => $discountValue,
                    ]
                );
            }
        }

            return Redirect::to('sales/assignDicountList?pageType=&&parentCode=105&&m=' . Input::get('m') . '#SFR');
        }
    }
    // public function assignDicountList()
    // {
    //     $SpecialPrice = SpecialPrice::all();
    //     return view('Sales.BrandDiscount.List', compact('SpecialPrice'));
    // }


public function getStoresByTerritory(Request $request)
{
    $territoryIds = $request->input('territory_id', []);

    $storesQuery = DB::connection('mysql2')->table('customers')->where('status', 1);

    if (!empty($territoryIds) && !in_array('all', $territoryIds)) {
        $storesQuery->whereIn('territory_id', $territoryIds);
    }

    $stores = $storesQuery->get();

    // Return JSON for AJAX
    return response()->json($stores);
}

public function assignDicountList(Request $request)
{
    // Territories list
    $territories = DB::connection('mysql2')->table('territories')->get();

    // Selected territories and stores from request
    $selectedTerritories = $request->input('territory_id', []);
    $selectedStores = $request->input('store_id', []);

    // Filter stores based on selected territories
    $storesQuery = DB::connection('mysql2')->table('customers')
        ->where('status', 1);

    if (!empty($selectedTerritories) && !in_array('all', $selectedTerritories)) {
        $storesQuery->whereIn('territory_id', $selectedTerritories);
    }

    // Filter stores if some stores are specifically selected
    if (!empty($selectedStores) && !in_array('all', $selectedStores)) {
        $storesQuery->whereIn('id', $selectedStores);
    }

    $stores = $storesQuery->get();

    // Brands list
    $brandsQuery = DB::connection('mysql2')->table('brands')->where('status', 1);
    $selectedBrands = $request->input('brand_id', []);
    if (!empty($selectedBrands) && !in_array('all', $selectedBrands)) {
        $brandsQuery->whereIn('id', $selectedBrands);
    }
    $brands = $brandsQuery->get();

    // Special prices (discounts)
    $specialPrices = DB::connection('mysql2')->table('special_prices')
        ->join('brands', 'special_prices.brand_id', '=', 'brands.id')
        ->select('special_prices.customer_id', 'special_prices.brand_id', 'special_prices.discount', 'brands.name as brand_name')
        ->get();

    // Build matrix: [store_id][brand_id] => discount
    $matrix = [];
    foreach ($specialPrices as $price) {
        $matrix[$price->customer_id][$price->brand_id] = $price->discount;
    }

    return view('Sales.BrandDiscount.List', compact(
        'territories',
        'stores',
        'brands',
        'specialPrices',
        'matrix',
        'selectedTerritories',
        'selectedStores'
    ));
}



    function editAssignDicount(Request $request)
    {
        if (!$_POST) {
            $id = $_GET['id'];
            $SpecialPrice = new SpecialPrice();
            $SpecialPrice = $SpecialPrice->SetConnection('mysql2');
            $SpecialPrice = $SpecialPrice->find($id);


            $stores = DB::connection('mysql2')->table('customers')->where('status', 1)->get();
            $Brands = DB::connection('mysql2')->table('brands')->where('status', 1)->get();
            return view('Sales.AjaxPages.editSpecialPriceForm', compact('SpecialPrice', 'stores', 'Brands'));
        } else {
            $SpecialPrice = SpecialPrice::find($request->id);
            $SpecialPrice->customer_id        = $request->store_id;
            $SpecialPrice->brand_id        = implode(',', $request->brand);
            $SpecialPrice->discount      = $request->discount;
            $SpecialPrice->save();

            return Redirect::to('sales/assignDicountList?pageType=&&parentCode=105&&m=' . Input::get('m') . '#SFR');
        }
    }
    public static function deleteAssignDicount(Request $request)
    {
        $id = $request->id;
        if ($id != "") {
            $SpecialPrice = SpecialPrice::find($id);
            $SpecialPrice->delete();
            // $data['status'] = 0;
            // $StoresCategory->where('id', $id)->update($data);
        }
    }
    public function assignProductDiscount(Request $request)
    {
        if (!$_POST) {
            $stores = DB::connection('mysql2')->table('customers')->where('status', 1)->get();

            return view('Sales.ProductDiscount.create', compact('stores', 'Brands'));
        } else {
            $sub_item_detail = CommonHelper::get_subitem_detail($request->product_id);
            $sub_item_detail = explode(',', $sub_item_detail);

            $CustomerSpecialPrice = new CustomerSpecialPrice();
            $CustomerSpecialPrice->customer_id        = $request->store_id;
            $CustomerSpecialPrice->product_id        = $request->product_id;
            $CustomerSpecialPrice->product_code        = $sub_item_detail[3];
            $CustomerSpecialPrice->mrp_price        = $sub_item_detail[2];
            $CustomerSpecialPrice->sale_price      = $request->sale_price;
            $CustomerSpecialPrice->save();

            return Redirect::to('sales/assignProductDiscountList?pageType=&&parentCode=105&&m=' . Input::get('m') . '#SFR');
        }
    }
    public function assignProductDiscountList()
    {
        $CustomerSpecialPrice = CustomerSpecialPrice::all();
        return view('Sales.ProductDiscount.List', compact('CustomerSpecialPrice'));
    }
    function editAssignProductDiscount(Request $request)
    {
        if (!$_POST) {
            $id = $_GET['id'];
            $CustomerSpecialPrice = new CustomerCustomerSpecialPrice();
            $CustomerSpecialPrice = $CustomerSpecialPrice->SetConnection('mysql2');
            $CustomerSpecialPrice = $CustomerSpecialPrice->find($id);

            $stores = DB::connection('mysql2')->table('customers')->where('status', 1)->get();

            return view('Sales.AjaxPages.editSpecialPriceForm', compact('SpecialPrice', 'stores'));
        } else {
            $CustomerSpecialPrice = CustomerSpecialPrice::find($request->id);
            $sub_item_detail = CommonHelper::get_subitem_detail($request->product_id);
            $sub_item_detail = explode(',', $sub_item_detail);

            $CustomerSpecialPrice->customer_id        = $request->store_id;
            $CustomerSpecialPrice->product_id        = $request->product_id;
            $CustomerSpecialPrice->product_code        = $sub_item_detail[3];
            $CustomerSpecialPrice->mrp_price        = $sub_item_detail[2];
            $CustomerSpecialPrice->sale_price      = $request->sale_price;
            $CustomerSpecialPrice->save();

            return Redirect::to('sales/assignProductDiscountList?pageType=&&parentCode=105&&m=' . Input::get('m') . '#SFR');
        }
    }
    public static function deleteAssignProductDiscount(Request $request)
    {
        $id = $request->id;
        if ($id != "") {
            $CustomerSpecialPrice = CustomerSpecialPrice::find($id);
            $CustomerSpecialPrice->delete();
            // $data['status'] = 0;
            // $StoresCategory->where('id', $id)->update($data);
        }
    }



      public function showImportForm()
    {
        return view('Sales.BrandDiscount.special_prices_import');
    }


// public function import(Request $request)
// {
//     $request->validate([
//         'csv_file' => 'required|file|mimes:csv,txt',
//     ]);

//     $file = $request->file('csv_file');
//     $csvData = array_map('str_getcsv', file($file->getRealPath()));

//     // Extract brand names (excluding customer name column)
//     $brandNames = array_slice(array_shift($csvData), 1);

//     // Get all active brands (name => id map)
//     $brandMap = DB::connection('mysql2')
//         ->table('brands')
//         ->where('status', 1)
//         ->whereIn('name', $brandNames)
//         ->pluck('id', 'name')
//         ->toArray();

//     $imported = 0;
//     $errors = collect();
//     $missingBrands = collect();
//     $missingCustomers = collect();

//     foreach ($csvData as $rowIndex => $row) {
//         if (empty(array_filter($row))) continue;

//         $customerName = trim($row[0]);
//         $discounts = array_slice($row, 1);

//         $customer = DB::connection('mysql2')
//             ->table('customers')
//             ->where('status', 1)
//             ->where(function ($query) use ($customerName) {
//                 $query->where('name', $customerName)
//                       ->orWhere('name', 'LIKE', "%{$customerName}%");
//             })
//             ->first();

//         if (!$customer) {
//             $missingCustomers->push($customerName);
//             continue;
//         }

//         foreach ($discounts as $i => $val) {
//             if (isset($brandNames[$i])) {
//                 $discount = floatval($val);
//                 $brandName = $brandNames[$i];
//                 $brandId = $brandMap[$brandName] ?? null;

//                 if (!$brandId) {
//                     $missingBrands->push($brandName);
//                     continue;
//                 }

//                 try {
//                     $exists = DB::connection('mysql2')->table('special_prices')
//                         ->where('customer_id', $customer->id)
//                         ->where('brand_id', $brandId)
//                         ->first();

//                     if ($exists) {
//                         DB::connection('mysql2')->table('special_prices')
//                             ->where('id', $exists->id)
//                             ->update(['discount' => $discount]);
//                     } else {
//                         DB::connection('mysql2')->table('special_prices')
//                             ->insert([
//                                 'customer_id' => $customer->id,
//                                 'brand_id'    => $brandId,
//                                 'discount'    => $discount,
//                             ]);
//                     }

//                     $imported++;

//                 } catch (\Exception $e) {
//                     $errors->push("Row " . ($rowIndex + 2) . ": Error - " . $e->getMessage());
//                 }
//             }
//         }
//     }

//     return back()->with([
//         'imported' => $imported,
//         'importErrors' => $errors->isNotEmpty() ? $errors : null,
//         'missingBrands' => $missingBrands->unique()->values(),
//         'missingCustomers' => $missingCustomers->unique()->values(),
//     ])->with('success', 'Import completed!');
// }


// public function import(Request $request)
// {
//     $request->validate([
//         'csv_file' => 'required|file|mimes:csv,txt',
//     ]);

//     $file = $request->file('csv_file');
//     $csvData = array_map('str_getcsv', file($file->getRealPath()));

//     // Extract brand names (excluding customer name column)
//     $brandNames = array_slice(array_shift($csvData), 1);

//     // Get all active brands (name => id map)
//     $brandMap = DB::connection('mysql2')
//         ->table('brands')
//         ->where('status', 1)
//         ->whereIn('name', $brandNames)
//         ->pluck('id', 'name')
//         ->toArray();

//     $imported = 0;
//     $errors = collect();
//     $missingBrands = collect();
//     $missingCustomers = collect();

//     foreach ($csvData as $rowIndex => $row) {
//         if (empty(array_filter($row))) continue;

//         $customerName = trim($row[0]);
//         $discounts = array_slice($row, 1);

//         $customer = DB::connection('mysql2')
//             ->table('customers')
//             ->where('status', 1)
//             ->where(function ($query) use ($customerName) {
//                 $query->where('name', $customerName)
//                       ->orWhere('name', 'LIKE', "%{$customerName}%");
//             })
//             ->first();

//         if (!$customer) {
//             $missingCustomers->push($customerName);
//             continue;
//         }

//         foreach ($discounts as $i => $val) {
//             if (isset($brandNames[$i])) {
//                 $discount = floatval($val);

//                 // Skip if discount is 0
//                 if ($discount == 0) continue;

//                 $brandName = $brandNames[$i];
//                 $brandId = $brandMap[$brandName] ?? null;

//                 if (!$brandId) {
//                     $missingBrands->push($brandName);
//                     continue;
//                 }

//                 try {
//                     $exists = DB::connection('mysql2')->table('special_prices')
//                         ->where('customer_id', $customer->id)
//                         ->where('brand_id', $brandId)
//                         ->first();

//                     if ($exists) {
//                         DB::connection('mysql2')->table('special_prices')
//                             ->where('id', $exists->id)
//                             ->update(['discount' => $discount]);
//                     } else {
//                         DB::connection('mysql2')->table('special_prices')
//                             ->insert([
//                                 'customer_id' => $customer->id,
//                                 'brand_id'    => $brandId,
//                                 'discount'    => $discount,
//                             ]);
//                     }

//                     $imported++;

//                 } catch (\Exception $e) {
//                     $errors->push("Row " . ($rowIndex + 2) . ": Error - " . $e->getMessage());
//                 }
//             }
//         }
//     }

//     return back()->with([
//         'imported' => $imported,
//         'importErrors' => $errors->isNotEmpty() ? $errors : null,
//         'missingBrands' => $missingBrands->unique()->values(),
//         'missingCustomers' => $missingCustomers->unique()->values(),
//     ])->with('success', 'Import completed!');
// }

public function import(Request $request)
{
    $request->validate([
        'csv_file' => 'required|file|mimes:csv,txt',
    ]);

    $file = $request->file('csv_file');
    $csvData = array_map('str_getcsv', file($file->getRealPath()));

    // Get original header (brand names), remove first column (customer name)
    $brandNamesRaw = array_slice(array_shift($csvData), 1);
    $brandNames = array_map('strtolower', $brandNamesRaw); // Lowercase brand names from CSV

    // Get all active brands from DB and build lowercase name => id map
    $brandMapRaw = DB::connection('mysql2')
        ->table('brands')
        ->where('status', 1)
        ->get(['id', 'name']);

    $brandMap = [];
    foreach ($brandMapRaw as $brand) {
        $brandMap[strtolower($brand->name)] = $brand->id;
    }

    $imported = 0;
    $errors = collect();
    $missingBrands = collect();
    $missingCustomers = collect();

    foreach ($csvData as $rowIndex => $row) {
        if (empty(array_filter($row))) continue;

        $customerName = trim($row[0]);
        $discounts = array_slice($row, 1);

        $customer = DB::connection('mysql2')
            ->table('customers')
            ->where('status', 1)
            ->where(function ($query) use ($customerName) {
                $query->where('name', $customerName)
                      ->orWhere('name', 'LIKE', "%{$customerName}%");
            })
            ->first();

        if (!$customer) {
            $missingCustomers->push($customerName);
            continue;
        }

        foreach ($discounts as $i => $val) {
            if (isset($brandNames[$i])) {
                $discount = floatval($val);

                // Skip if discount is 0
                if ($discount == 0) continue;

                $brandKey = strtolower($brandNames[$i]);
                $brandId = $brandMap[$brandKey] ?? null;

                if (!$brandId) {
                    $missingBrands->push($brandNamesRaw[$i]); // Show original name for clarity
                    continue;
                }

                try {
                    $exists = DB::connection('mysql2')->table('special_prices')
                        ->where('customer_id', $customer->id)
                        ->where('brand_id', $brandId)
                        ->first();

                    if ($exists) {
                        DB::connection('mysql2')->table('special_prices')
                            ->where('id', $exists->id)
                            ->update(['discount' => $discount]);
                    } else {
                        DB::connection('mysql2')->table('special_prices')
                            ->insert([
                                'customer_id' => $customer->id,
                                'brand_id'    => $brandId,
                                'discount'    => $discount,
                            ]);
                    }

                    $imported++;

                } catch (\Exception $e) {
                    $errors->push("Row " . ($rowIndex + 2) . ": Error - " . $e->getMessage());
                }
            }
        }
    }

    return back()->with([
        'imported' => $imported,
        'importErrors' => $errors->isNotEmpty() ? $errors : null,
        'missingBrands' => $missingBrands->unique()->values(),
        'missingCustomers' => $missingCustomers->unique()->values(),
    ])->with('success', 'Import completed!');
}

}