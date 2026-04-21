<?php

namespace App\Http\Controllers;

use App\VendorCredit;
use App\Helpers\CommonHelper;
use App\Helpers\FinanceHelper;
use Illuminate\Http\Request;
use DB;
use Session;
use Auth;

class VendorCreditNoteController extends Controller
{
    public function show()
    {
        $credits = VendorCredit::where("status", 1)->get();

        if (request()->ajax()) {
            return view("vendorCreditNote.listAjax", compact("credits"));
        }

        return view("vendorCreditNote.list", compact("credits"));
    }

    public function view(Request $request)
    {
        $m = Session::get('run_company');
        $id = $request->id;
        return view('vendorCreditNote.ajax.viewVendorCreditNoteVoucherAjax', compact('id', 'm'));
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

        return view("vendorCreditNote.create", compact("vouchers", "branches", "vendors", "accounts"));
    }

    public function store(Request $request)
    {
        $rv_no = CommonHelper::generateUniqueNumber("VCR", "vendor_credits", "rv_no");
        $vendor_acc_id = FinanceHelper::getSupplier($request->vendor_id);

        $vcredit = new VendorCredit();
        $vcredit->vendor_id = $request->vendor_id;
        $vcredit->date = $request->date;
        $vcredit->amount = $request->amount;
        $vcredit->details = $request->details;
        $vcredit->debit = $request->debit;    // Debit Selected Account
        $vcredit->credit = $vendor_acc_id;    // Credit Vendor (Increases Payable)
        $vcredit->branch = $request->branch;
        $vcredit->rv_no = $rv_no;
        $vcredit->voucher_type = 20;
        $vcredit->status = 1;
        $vcredit->is_approved = 0;
        $vcredit->save();

        $type = "Vendor Credit Note";
        CommonHelper::createNotification($type . " with " . $rv_no . " is created by " . auth()->user()->name, $type);

        return redirect()->route("vendorcreditnote.list");
    }

    public function approve(VendorCredit $vcredit)
    {
        $vcredit->is_approved = 1;
        $vcredit->save();

        // Balanced Entries
        // 1. DR Account
        DB::connection('mysql2')->table('transactions')->insert([
            'master_id' => $vcredit->id,
            'acc_id' => $vcredit->debit,
            'acc_code' => CommonHelper::get_account_code($vcredit->debit),
            'particulars' => $vcredit->details,
            'debit_credit' => 1,
            'amount' => $vcredit->amount,
            'voucher_no' => $vcredit->rv_no,
            'voucher_type' => 20,
            'v_date' => $vcredit->date,
            'date' => date('Y-m-d'),
            'time' => date('H:i:s'),
            'username' => auth()->user()->name,
            'status' => 1
        ]);

        // 2. CR Vendor
        DB::connection('mysql2')->table('transactions')->insert([
            'master_id' => $vcredit->id,
            'acc_id' => $vcredit->credit,
            'acc_code' => CommonHelper::get_account_code($vcredit->credit),
            'particulars' => $vcredit->details,
            'debit_credit' => 0,
            'amount' => $vcredit->amount,
            'voucher_no' => $vcredit->rv_no,
            'voucher_type' => 20,
            'v_date' => $vcredit->date,
            'date' => date('Y-m-d'),
            'time' => date('H:i:s'),
            'username' => auth()->user()->name,
            'status' => 1
        ]);

        return back()->with("success", "Approved");
    }

    public function destroy(VendorCredit $vcredit)
    {
        $vcredit->status = 0;
        $vcredit->save();
        return back()->with("success", "Deleted");
    }
}
