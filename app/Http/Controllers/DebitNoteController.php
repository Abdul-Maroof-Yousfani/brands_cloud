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
use Illuminate\Support\Facades\Session;
use Redirect;

class DebitNoteController extends Controller
{
    public function show()
    {
        $debits = Debit::where("status", 1)->get();

        if (request()->ajax()) {
            return view("debitNote.listAjax", compact("debits"));
        }

        return view("debitNote.list", compact("debits"));
    }

    public function view(Request $request)
    {
        $m = Session::get('run_company');
        $id = $request->id;
        return view('debitNote.ajax.viewDebitNoteVoucherAjax', compact('id', 'm'));
    }

    public function create()
    {
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

    public function update(Debit $debit)
    {
        $vouchers = DB::connection("mysql2")->table("voucher_type")->where("status", 1)->get();
        $branches = DB::connection("mysql2")->table("branch")->where("status", 1)->get();

        return view("debitNote.update", compact("debit", "vouchers", "branches"));
    }

    public function approve(Debit $debit)
    {
        $debit->is_approved = true;
        $debit->save();

        // 1. Debit Entry (Customer)
        DB::connection('mysql2')->table('transactions')->insert([
            'master_id' => $debit->id,
            'acc_id' => $debit->debit,
            'acc_code' => \App\Helpers\CommonHelper::get_account_code($debit->debit),
            'particulars' => $debit->details,
            'opening_bal' => 0,
            'debit_credit' => 1,
            'amount' => $debit->amount,
            'voucher_no' => $debit->rv_no,
            'voucher_type' => 20,
            'v_date' => $debit->date,
            'date' => date('Y-m-d'),
            'time' => date('H:i:s'),
            'username' => auth()->user()->name,
            'status' => 1
        ]);

        // 2. Credit Entry (Selected Account)
        DB::connection('mysql2')->table('transactions')->insert([
            'master_id' => $debit->id,
            'acc_id' => $debit->credit,
            'acc_code' => \App\Helpers\CommonHelper::get_account_code($debit->credit),
            'particulars' => $debit->details,
            'opening_bal' => 0,
            'debit_credit' => 0,
            'amount' => $debit->amount,
            'voucher_no' => $debit->rv_no,
            'voucher_type' => 20,
            'v_date' => $debit->date,
            'date' => date('Y-m-d'),
            'time' => date('H:i:s'),
            'username' => auth()->user()->name,
            'status' => 1
        ]);

        return back()->with("success", "Debit Note is approved and balanced entries created successfully.");
    }

    public function destroy(Debit $debit)
    {
        $debit->status = 0;
        $debit->save();

        return back()->with("success", "Deleted");
    }

    public function store(Request $request)
    {
        $rv_no = CommonHelper::generateUniqueNumber("DI", "debits", "rv_no");
        $customer_acc_id = SalesHelper::get_customer_acc_id($request->store);

        $master_id = DB::connection('mysql2')->table('debits')->insertGetId([
            "store" => $request->store,
            "date" => $request->date_and_time,
            "amount" => $request->amount,
            "details" => $request->details,
            "debit" => $customer_acc_id,    // Store account (Debit)
            "credit" => $request->credit,   // Selected account (Credit)
            "branch" => $request->branch,
            "rv_no" => $rv_no,
            "voucher_type" => 20,
            "created_at" => now(),
            "updated_at" => now(),
        ]);

        $type = "Debit Note";
        CommonHelper::createNotification($type . " with " . $rv_no . " is created by " . auth()->user()->name, $type);

        return redirect()->route("debitnote.list");
    }
}
