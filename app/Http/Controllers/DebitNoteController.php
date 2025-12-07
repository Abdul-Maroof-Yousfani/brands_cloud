<?php

namespace App\Http\Controllers;

use App\Debit;
use App\Helpers\CommonHelper;
use App\Helpers\SalesHelper;
use App\Models\Account;
use App\Models\Subitem;
use App\Models\Transactions;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Redirect;

class DebitNoteController extends Controller
{
	 public function show() {
        $debits = Debit::where("status", 1)->get();

        if(request()->ajax()) {
            return view("debitNote.listAjax", compact("debits"));
        }

        return view("debitNote.list", compact("debits"));
    }
     public function view(Request $request){
        $id = $request->id;
        $debit = Debit::find($id);
       
        return view('debitNote.ajax.viewDebitNoteAjax', compact('debit'));
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

        return view("debitNote.create", compact("vouchers", "branches", "accounts"));
    }
    public function update(Debit $debit) {
        $vouchers = DB::connection("mysql2")->table("voucher_type")->where("status", 1)->get();
        $branches = DB::connection("mysql2")->table("branch")->where("status", 1)->get();
    
        return view("debitNote.update", compact("debit", "vouchers", "branches"));
    }
    public function edit(Request $request, Debit $credit) {
        $credit = $credit->update([
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

        return back()->with("success", "Debit Note is approved");
    }
    public function destroy(Debit $debit) {
        $debit->status = 0;
        $debit->save();

        return back()->with("success","Deleted");
    }
    public function store(Request $request) {
        $rv_no = CommonHelper::generateUniqueNumber("DI", "debits", "rv_no");
        $credit = Debit::create([
            "store" => $request->store,
            "delivery_man" => $request->delivery_man,
            "date" => $request->date_and_time,
            "amount" => $request->amount,
            "details" => $request->details,
            "debit" => $request->credit,
            "credit" => "-",
            "on_record" => $request->on_record == "on" ? 1 : 0,
            "voucher_type" => $request->voucher_type,
            "branch" => $request->branch,
            "rv_no" => $rv_no
        ]);

        return redirect()->route("debitnote.list");
    }
    
}
