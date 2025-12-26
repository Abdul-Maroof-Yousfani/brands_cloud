<?php

namespace App\Http\Controllers;

use App\Credit;
use App\Debit;
use App\Helpers\CommonHelper;
use App\Helpers\SalesHelper;
use App\Models\Account;
use App\Models\Transactions;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use VoucherType;

class CreditNoteController extends Controller
{
    // public function show() {
    //     $credits = Credit::where("status", 1)->get();

    //     if(request()->ajax()) {
    //         return view("creditNote.listAjax", compact("credits"));
    //     }

    //     return view("creditNote.list", compact("credits"));
    // }
    // public function create() {
    //     $vouchers = DB::connection("mysql2")->table("voucher_type")->where("status", 1)->get();
    //     $branches = DB::connection("mysql2")->table("branch")->where("status", 1)->get();
    //     return view("creditNote.create", compact("vouchers", "branches"));
    // }
    // public function update(Credit $credit) {
    //     $vouchers = DB::connection("mysql2")->table("voucher_type")->where("status", 1)->get();
    //     $branches = DB::connection("mysql2")->table("branch")->where("status", 1)->get();
    
    //     return view("creditNote.update", compact("credit", "vouchers", "branches"));
    // }
    // public function edit(Request $request, Credit $credit) {
    //     $credit = $credit->update([
    //         "store" => $request->store,
    //         "delivery_man" => $request->delivery_man,
    //         "date" => $request->date_and_time,
    //         "amount" => $request->amount,
    //         "details" => $request->details,
    //         "credit" => $request->credit,
    //         "debit" => $request->debit,
    //         "on_record" => $request->on_record == "on" ? 1 : 0,
    //         "voucher_type" => $request->voucher_type,
    //         "branch" => $request->branch
    //     ]);

    //     return redirect()->route("creditNote.list");
    // }
    // public function approve(Credit $credit) {
    //     $credit->is_approved = true;
    //     $credit->save();

    //     return back()->with("success", "Credit Note is approved");
    // }
    // public function destroy(Credit $credit) {
    //     $credit->status = 0;
    //     $credit->save();

    //     return back()->with("success","Deleted");
    // }
    // public function store(Request $request) {
    //     $credit = Credit::create([
    //         "store" => $request->store,
    //         "delivery_man" => $request->delivery_man,
    //         "date" => $request->date_and_time,
    //         "amount" => $request->amount,
    //         "details" => $request->details,
    //         "credit" => $request->credit,
    //         "debit" => $request->debit,
    //         "on_record" => $request->on_record == "on" ? 1 : 0,
    //         "voucher_type" => $request->voucher_type,
    //         "branch" => $request->branch
    //     ]);

    //     return redirect()->route("creditNote.list");
    // }


     public function show() {
		$m = request()->get("m");
        $credits = Credit::where("status", 1)
							->orderBy("id", "desc")
							->where("company_id", $m)
							->get();
        if(request()->ajax()) {
			return view("creditNote.listAjax", compact("credits"));
        }

        return view("creditNote.list", compact("credits"));
    }
    public function create() {
        $vouchers = DB::connection("mysql2")->table("voucher_type")->where("status", 1)->get();
        $branches = DB::connection("mysql2")->table("branch")->where("status", 1)->get();
        $customers = DB::connection("mysql2")->table("customers")->where("status", 1)->get();
        $accounts = DB::connection("mysql2")
        ->table("accounts")
        ->where("status", 1)
        ->where("operational", 1)
        ->select("id", "name", "code", "type")
        ->orderBy("level1", "ASC")
        ->orderBy("level2", "ASC")
        ->orderBy("level3", "ASC")
        ->orderBy("level4", "ASC")
        ->orderBy("level5", "ASC")
        ->orderBy("level6", "ASC")
        ->orderBy("level7", "ASC")
        ->get();


        return view("creditNote.create", compact("vouchers", "branches", "customers", "accounts"));
    }
    public function submitReceiptData(Request $request) {
        DB::Connection('mysql2')->beginTransaction();
		try
		{
			// $rv_type=0;
			// $bank=0;
            $rv_no = CommonHelper::uniqe_no_for_rvs(date('y'),date('m'),1);

			// if ($request->pay_mode=='1,1'):
			// 	$rv_no = CommonHelper::uniqe_no_for_rvs(date('y'),date('m'),1);
			// 	$rv_type=2;
			// 	$pay_mode=1;
			// 	$rv_type=1;
			// 	$bank=$request->bank;
			// 	elseif ($request->pay_mode=='2,2'):
			// 		$rv_no = CommonHelper::uniqe_no_for_rvs(date('y'),date('m'),2);
			// 		$pay_mode=2;
			// 		$rv_type=2;
			// 		elseif ($request->pay_mode=='3,1'):
			// 			$rv_no = CommonHelper::uniqe_no_for_rvs(date('y'),date('m'),1);
			// 			$rv_type=1;
			// 			$pay_mode=3;
			// 			$bank=$request->bank;
            // endif;



			// $data=array
			// (
			// 	'rv_no'=>$rv_no,
			// 	'rv_date'=>$request->v_date,
			// 	'ref_bill_no'=>$request->ref_bill_no,
			// 	'cheque_no'=>$request->cheque,
			// 	'cheque_date'=>$request->cheque_date,
			// 	'rv_type'=>$rv_type,
			// 	//'description'=>$request->ref_bill_no,
			// 	'rv_status'=>1,
			// 	'username'=>Auth::user()->name,
			// 	'date'=>date('Y-m-d'),
			// 	'sales'=>1,
			// 	'status'=>1,
			// 	'description'=>$request->desc,
			// 	'bank'=>0,
			// 	'pay_mode'=>0


			// );

			// $master_id=DB::Connection('mysql2')->table('new_rvs')->insertGetId($data);

			// $brig=$request->si_id;
			$net_amount=0;
			$tax_amount=0;
			$tax_acc_id=0;
			$total_amount=0;
			$discount_amount=0;

            $brig=$request->si_id;
            $master_id = $request->id;

			foreach($brig as $key=>$row):
				$data1=array
				(
					'si_id'=>$row,
					'so_id'=>$request->input('so_id')[$key],
					'rv_id'=>$master_id,
					'rv_no'=>$rv_no,
					'received_amount'=>CommonHelper::check_str_replace($request->input('receive_amount')[$key]),
					'tax_percent'=>$request->input('percent')[$key],
					'tax_amount'=>$request->input('tax_amount')[$key],
					'discount_amount'=>$request->input('discount')[$key],
					'net_amount'=>CommonHelper::check_str_replace($request->input('net_amount')[$key]),
				);
				if ($request->input('percent')[$key]!=0):
				$tax_acc_id=	CommonHelper::generic('invoice_tax',array('name'=>$request->input('percent')[$key]),'acc_id')->first()->acc_id;

					endif;

				$net_amount+=CommonHelper::check_str_replace($request->input('receive_amount')[$key]);
				$discount_amount+=$request->input('discount')[$key];

				if ($request->input('percent')[$key]!=0):
				$tax_amount+=$request->input('tax_amount')[$key];
					else:
						$tax_amount+=0;
					endif;
				$total_amount+=CommonHelper::check_str_replace($request->input('net_amount')[$key]);
				DB::Connection('mysql2')->table('brige_table_sales_receipt')->insert($data1);



				$received_paymet=array
				(
					'sales_tax_invoice_id'=>$row,
					'receipt_id'=>$master_id,
					'receipt_no'=>$rv_no,
					'received_amount'=>CommonHelper::check_str_replace($request->input('receive_amount')[$key]),
					'slip_no'=>$request->cheque,
					'status'=>1,
				);
				DB::Connection('mysql2')->table('received_paymet')->insert($received_paymet);
			endforeach;

			$transaction=new Transactions();
			$transaction=$transaction->SetConnection('mysql2');
			//   $count=$transaction->where('acc_id',$row->acc_id)->where('opening_bal',1)->count();





			$data2=array
			(
				'master_id'=>$master_id,
				'rv_no'=>$rv_no,
				'acc_id'=>$request->acc_id,
				'amount'=>$total_amount,
				'debit_credit'=>1,
				'status'=>1,
				'rv_status'=>1,
				'description'=>$request->desc,

				);
				DB::Connection('mysql2')->table('new_rv_data')->insert($data2);

		if ($tax_amount>0):

			$data3=array
			(
				'master_id'=>$master_id,
				'rv_no'=>$rv_no,
				'acc_id'=>$tax_acc_id,
				'amount'=>$tax_amount,
				'debit_credit'=>1,
				'description'=>'',
				'status'=>1,
				'rv_status'=>1,
			);
					DB::Connection('mysql2')->table('new_rv_data')->insert($data3);

			endif;


			if ($discount_amount>0):
			$disc_acc_id=DB::Connection('mysql2')->table('accounts')->where('status',1)->where('name','Sales Discount')->first()->id;
				$data6=array
				(
					'master_id'=>$master_id,
					'rv_no'=>$rv_no,
					'acc_id'=>$disc_acc_id,
					'amount'=>$discount_amount,
					'debit_credit'=>1,
					'description'=>'',
					'status'=>1,
					'rv_status'=>1,
				);
				DB::Connection('mysql2')->table('new_rv_data')->insert($data6);
				endif;



		$customer_acc_id=	SalesHelper::get_customer_acc_id($request->buyers_id);


			$data4=array
			(
				'master_id'=>$master_id,
				'rv_no'=>$rv_no,
				'acc_id'=>$customer_acc_id,
				'amount'=>$net_amount,
				'debit_credit'=>0,
				'status'=>1,
				'rv_status'=>1,
				'description'=>$request->desc,
                
			);
			DB::Connection('mysql2')->table('new_rv_data')->insert($data4);
			// lala


			SalesHelper::sales_activity($rv_no,now(),$total_amount,5,'Insert');

			DB::Connection('mysql2')->commit();

		}
		catch(\Exception $e)
		{
			DB::Connection('mysql2')->rollback();
			echo "EROOR"; //die();
			dd($e->getFile());

		}

		$SavePrintVal = Input::get('SavePrintVal');
        return redirect()->back();
		//Testing Redirect
		// if($SavePrintVal == 1)
		// {
		// 	$Url = url('sdc/viewReceiptVoucherDirect?id='.$master_id.'&&pageType='.Input::get('pageType').'&&parentCode='.Input::get('parentCode').'&&m='.Session::get('run_company').'#Murtaza Corp');
		// 	return Redirect::to($Url);
		// }
		// else
		// {
		// 	return Redirect::to('sales/receiptVoucherList?m='.$request->m);
		// }
    } 
    public function showReceipt(Request $request) {
    	$val=$request->checkbox;
        
		// if (empty($val)):
		// return redirect()->back();
		// endif;

		$accounts = new Account();
		$accounts=$accounts->SetConnection('mysql2');
		$accounts = $accounts->where('status',1)->select('id','name','code')->orderBy('level1', 'ASC')
			->orderBy('level2', 'ASC')
			->orderBy('level3', 'ASC')
			->orderBy('level4', 'ASC')
			->orderBy('level5', 'ASC')
			->orderBy('level6', 'ASC')
			->orderBy('level7', 'ASC')
			->get();

        return view("creditNote.ajax.creditNoteCustomer", compact("accounts", "val"));
    } 
    public function update(Debit $debit) {
        $vouchers = DB::connection("mysql2")->table("voucher_type")->where("status", 1)->get();
        $branches = DB::connection("mysql2")->table("branch")->where("status", 1)->get();
        return view("creditNote.update", compact("debit", "vouchers", "branches"));
    }
    public function edit(Request $request, Debit $debit) {
        $debit = $debit->update([
            "store" => $request->store,
            "delivery_man" => $request->delivery_man,
            "date" => $request->date_and_time,
            "amount" => $request->amount,
            "details" => $request->details,
            "credit" => $request->credit,
            "debit" => $request->debit,
            "on_record" => $request->on_record == "on" ? 1 : 0,
            "voucher_type" => $request->voucher_type,
            "branch" => $request->branch
        ]);

        return redirect()->route("creditNote.list");
    }
    public function approve(Debit $debit) {
        $debit->is_approved = true;
        $debit->save();

        return back()->with("success", "Credit Note is approved");
    }
    public function destroy(Debit $debit) {
        $debit->status = 0;
        $debit->save();

        return back()->with("success","Deleted");
    }
    public function store(Request $request) {
		
		$rules = [
			"store" => "required",
			"delivery_man" => "required",
			"date_and_time" => "required",
			"details" => "required",
			"debit" => "required",
			"voucher_type" => "required",
			"branch" => "required",
			'type' => "required"
		];

		if($request->type === 'without-invoice') {
			array_merge($rules, [
				'amount' => "required"
			]);
		}

		$request->validate($rules);
	
		$type = $request->type;
		

		$rv_no = \App\Helpers\CommonHelper::generateUniquePosNo("credits_data", "rv_no", "cvv");
        $credit = Credit::create([
			"rv_no" => $rv_no,
            "store" => $request->store,
            "delivery_man" => $request->delivery_man,
            "date" => $request->date_and_time,
            "amount" => $request->amount ?? "",
            "details" => $request->details,
            "credit" => "-",
            "debit" => $request->debit,
            "on_record" => $request->on_record == "on" ? 1 : 0,
            "voucher_type" => $request->voucher_type,
            "branch" => $request->branch,
			"company_id" => auth()->user()->company_id
        ]);


        DB::Connection('mysql2')->beginTransaction();
		try
		{
	

			$data=array
			(
				'rv_no'=>$rv_no,
				'rv_date'=> $request->date_and_time, 
				'ref_bill_no'=> "-",
				'cheque_no'=> "-",
				'cheque_date'=> "-",
				'rv_type'=>1,
				'rv_status'=>1,
				'username'=>Auth::user()->name,
				'date'=>date('Y-m-d'),
				'sales'=>1,
				'status'=>1,
				'description'=>"-",
				'bank'=>0,
				'pay_mode'=>0
            );

			$master_id=DB::Connection('mysql2')->table('credits_data')->insertGetId($data);
            
            
			// $brig=$request->si_id;
			$net_amount=0;
			$tax_amount=0;
			$tax_acc_id=0;
			$total_amount=0;
			$discount_amount=0;

            $brig=$request->si_id;
            $master_id = $credit->id;

			if($type == 'against-invoice'):
				foreach($brig as $key=>$row):
					$data1=array
					(
						'si_id'=>$row,
						'so_id'=>$request->input('so_id')[$key],
						'rv_id'=>$master_id,
						'rv_no'=>$rv_no,
						'received_amount'=>CommonHelper::check_str_replace($request->input('receive_amount')[$key]),
						'tax_percent'=>$request->input('percent')[$key],
						'tax_amount'=>$request->input('tax_amount')[$key],
						'discount_amount'=>$request->input('discount')[$key],
						'net_amount'=>CommonHelper::check_str_replace($request->input('net_amount')[$key]),
					);
					if ($request->input('percent')[$key]!=0):
					$tax_acc_id=	CommonHelper::generic('invoice_tax',array('name'=>$request->input('percent')[$key]),'acc_id')->first()->acc_id;

						endif;

					$net_amount+=CommonHelper::check_str_replace($request->input('receive_amount')[$key]);
					$discount_amount+=$request->input('discount')[$key];

					if ($request->input('percent')[$key]!=0):
					$tax_amount+=$request->input('tax_amount')[$key];
						else:
							$tax_amount+=0;
						endif;
					$total_amount+=CommonHelper::check_str_replace($request->input('net_amount')[$key]);
					DB::Connection('mysql2')->table('brige_table_sales_receipt')->insert($data1);



					$received_paymet=array
					(
						'sales_tax_invoice_id'=>$row,
						'receipt_id'=>$master_id,
						'receipt_no'=>$rv_no,
						'received_amount'=>CommonHelper::check_str_replace($request->input('receive_amount')[$key]),
						'slip_no'=>"-",
						'status'=>1,
					);
					DB::Connection('mysql2')->table('received_paymet')->insert($received_paymet);
				endforeach;
		

			endif;

			$transaction=new Transactions();
			$transaction=$transaction->SetConnection('mysql2');
			//   $count=$transaction->where('acc_id',$row->acc_id)->where('opening_bal',1)->count();




			
			if($type === 'against-invoice'):
				foreach($brig as $key=>$row):
					$data2=array
					(
						'master_id' => $master_id,
						'rv_no'=>$rv_no,
						'acc_id' => $request->debit,
						'amount' => CommonHelper::check_str_replace($request->input('net_amount')[$key]),
						'debit_credit' => 1,
						'status' => 1,
						'rv_status' => 1,
						'description' => $request->desc

					);
					DB::Connection('mysql2')->table('credits_item_data')->insert($data2);
				endforeach;

			else:
				$data2=array
				(
					'master_id' => $master_id,
					'rv_no'=>$rv_no,
					'acc_id' => $request->debit,
					'amount' => $request->amount,
					'debit_credit' => 1,
					'status' => 1,
					'rv_status' => 1,
					'description' => $request->desc

				);
				DB::Connection('mysql2')->table('credits_item_data')->insert($data2);
			endif;
		

		if ($tax_amount>0):

			$data3=array
			(
				'master_id'=>$master_id,
				'rv_no'=>$rv_no,
				'acc_id'=>$tax_acc_id,
				'amount'=>$tax_amount,
				'debit_credit'=>0,
				'description'=>'',
				'status'=>1,
				'rv_status'=>1,
			);
					DB::Connection('mysql2')->table('credits_item_data')->insert($data3);

			foreach($brig as $key=>$row):
				$data3=array
				(
					'master_id' => $master_id,
					'rv_no'=>$rv_no,
					'acc_id' => $tax_acc_id,
					'amount' => $tax_amount,
					'debit_credit' => 0,
					'status' => 1,
					'rv_status' => 1,
					'description' => ''

				);
				DB::Connection('mysql2')->table('credits_item_data')->insert($data3);
			endforeach;

			endif;


			if ($discount_amount>0):
			$disc_acc_id=DB::Connection('mysql2')->table('accounts')->where('status',1)->where('name','Sales Discount')->first()->id;
        		
			if($type === 'against-invoice'):
				foreach($brig as $key=>$row):
					$data6=array
					(
						'master_id' => $master_id,
						'rv_no'=>$rv_no,
						'acc_id' => $disc_acc_id,
						'amount' => $discount_amount,
						'debit_credit' => 0,
						'status' => 1,
						'rv_status' => 1,
						'description' => ''

					);
					DB::Connection('mysql2')->table('credits_item_data')->insert($data6);
					
				endforeach;
			endif;
				endif;


		$customer_acc_id=	SalesHelper::get_customer_acc_id($request->store);
       

		if($type === 'against-invoice'):
			foreach($brig as $key=>$row):
				$data6=array
				(
					'master_id' => $master_id,
					'rv_no'=>$rv_no,
					'acc_id' => $customer_acc_id,
					'amount' => CommonHelper::check_str_replace($request->input('net_amount')[$key]),
					'debit_credit' => 0,
					'status' => 1,
					'rv_status' => 1,
					'description' => $request->desc

				);
				DB::Connection('mysql2')->table('credits_item_data')->insert($data6);

			endforeach;
		else:
			$data6=array
				(
					'master_id' => $master_id,
					'rv_no'=>$rv_no,
					'acc_id' => $customer_acc_id,
					'amount' => $request->amount,
					'debit_credit' => 0,
					'status' => 1,
					'rv_status' => 1,
					'description' => $request->desc

				);
				DB::Connection('mysql2')->table('credits_item_data')->insert($data6);
		endif;

			// lala



			SalesHelper::sales_activity($rv_no,"",$total_amount,5,'Insert');

			DB::Connection('mysql2')->commit();

		}
		catch(\Exception $e)
		{
			DB::Connection('mysql2')->rollback();
			echo "EROOR"; //die();
			dd($e);

		}
        
        return redirect()->route("creditNote.list");

		$SavePrintVal = Input::get('SavePrintVal');

		//Testing Redirect
		if($SavePrintVal == 1)
		{
			$Url = url('sdc/viewReceiptVoucherDirect?id='.$master_id.'&&pageType='.Input::get('pageType').'&&parentCode='.Input::get('parentCode').'&&m='.Session::get('run_company').'#Murtaza Corp');
			return Redirect::to($Url);
		}
		else
		{
			return Redirect::to('sales/receiptVoucherList?m='.$request->m);
		}

    }
}
