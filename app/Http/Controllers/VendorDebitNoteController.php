<?php

namespace App\Http\Controllers;

use App\VendorDebit;
use App\Helpers\CommonHelper;
use App\Helpers\FinanceHelper;
use Illuminate\Http\Request;
use DB;
use Session;
use Auth;

class VendorDebitNoteController extends Controller
{
    public function show()
    {
        $debits = VendorDebit::where("status", 1)->get();

        if (request()->ajax()) {
            return view("vendorDebitNote.listAjax", compact("debits"));
        }

        return view("vendorDebitNote.list", compact("debits"));
    }

    public function view(Request $request)
    {
        $m = Session::get('run_company');
        $id = $request->id;
        return view('vendorDebitNote.ajax.viewVendorDebitNoteVoucherAjax', compact('id', 'm'));
    }

    public function create()
    {
        $vouchers = DB::connection("mysql2")->table("voucher_type")->where("status", 1)->get();
        $branches = DB::connection("mysql2")->table("branch")->where("status", 1)->get();
        $vendors = DB::connection("mysql2")->table("supplier")->where("status", 1)->get();
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

        return view("vendorDebitNote.create", compact("vouchers", "branches", "vendors", "accounts"));
    }

    public function store(Request $request)
    {
        $rv_no = CommonHelper::generateUniqueNumber("VDI", "vendor_debits", "rv_no");
        $vendor_acc_id = FinanceHelper::getSupplier($request->vendor_id);

        $vdebit = new VendorDebit();
        $vdebit->vendor_id = $request->vendor_id;
        $vdebit->date = $request->date;
        $vdebit->amount = $request->amount;
        $vdebit->details = $request->details;
        $vdebit->debit = $vendor_acc_id;    // Debit Vendor (Decreases Payable)
        $vdebit->credit = $request->credit;   // Credit Selected Account
        $vdebit->branch = $request->branch;
        $vdebit->rv_no = $rv_no;
        $vdebit->voucher_type = 20;
        $vdebit->status = 1;
        $vdebit->is_approved = 0;
        $vdebit->save();

        $type = "Vendor Debit Note";
        CommonHelper::createNotification($type . " with " . $rv_no . " is created by " . auth()->user()->name, $type);

        return redirect()->route("vendordebitnote.list");
    }

    public function approve(VendorDebit $vdebit)
    {
        $vdebit->is_approved = 1;
        $vdebit->save();

        // Balanced Entries
        // 1. DR Vendor
        DB::connection('mysql2')->table('transactions')->insert([
            'master_id' => $vdebit->id,
            'acc_id' => $vdebit->debit,
            'acc_code' => CommonHelper::get_account_code($vdebit->debit),
            'particulars' => $vdebit->details,
            'debit_credit' => 1,
            'amount' => $vdebit->amount,
            'voucher_no' => $vdebit->rv_no,
            'voucher_type' => 20,
            'v_date' => $vdebit->date,
            'date' => date('Y-m-d'),
            'time' => date('H:i:s'),
            'username' => auth()->user()->name,
            'status' => 1
        ]);

        // 2. CR Account
        DB::connection('mysql2')->table('transactions')->insert([
            'master_id' => $vdebit->id,
            'acc_id' => $vdebit->credit,
            'acc_code' => CommonHelper::get_account_code($vdebit->credit),
            'particulars' => $vdebit->details,
            'debit_credit' => 0,
            'amount' => $vdebit->amount,
            'voucher_no' => $vdebit->rv_no,
            'voucher_type' => 20,
            'v_date' => $vdebit->date,
            'date' => date('Y-m-d'),
            'time' => date('H:i:s'),
            'username' => auth()->user()->name,
            'status' => 1
        ]);

        return back()->with("success", "Approved");
    }

    public function destroy(VendorDebit $vdebit)
    {
        $vdebit->status = 0;
        $vdebit->save();
        return back()->with("success", "Deleted");
    }
}
