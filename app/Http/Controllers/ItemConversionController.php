<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\CommonHelper;
use App\Helpers\PurchaseHelper;
use DB;
use Auth;
use Carbon\Carbon;

class ItemConversionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $m = request()->get('m');
        CommonHelper::companyDatabaseConnection($m);
        $brands = CommonHelper::get_all_brand();
        $warehouses = CommonHelper::get_all_warehouse();
        $items = CommonHelper::get_all_subitem_get();
        $conversion_no = 'IC-' . date('ymdHis');

        CommonHelper::reconnectMasterDatabase();
        return view('Store.item_conversion_form', compact('brands', 'warehouses', 'items', 'conversion_no', 'm'));
    }

    public function list()
    {
        $m = request()->get('m');
        CommonHelper::companyDatabaseConnection($m);

        // Conversions can be identified by voucher_type 12 (new type for conversion)
        // Or we just fetch from stock where voucher_no starts with IC-
        $conversions = DB::connection('mysql2')->table('stock')
            ->where('voucher_no', 'like', 'IC-%')
            ->orderBy('id', 'desc')
            ->get();

        CommonHelper::reconnectMasterDatabase();
        return view('Store.item_conversion_list', compact('conversions', 'm'));
    }

    public function save(Request $request)
    {
        $m = $request->get('m');
        CommonHelper::companyDatabaseConnection($m);

        try {
            DB::connection('mysql2')->beginTransaction();

            $conversion_no = 'IC-' . date('ymdHis');
            $date = $request->conversion_date ?? date('Y-m-d');
            $remarks = $request->remarks;
            $warehouse_id = $request->warehouse_id;

            $total_out_amount = 0;
            $total_in_amount = 0;

            // Existing Inventory (Stock OUT)
            if (isset($request->existing_items)) {
                foreach ($request->existing_items as $item) {
                    if (empty($item['item_id']) || empty($item['qty']))
                        continue;

                    $item_amount = $item['qty'] * $item['rate'];
                    $total_out_amount += $item_amount;

                    DB::connection('mysql2')->table('stock')->insert([
                        'voucher_no' => $conversion_no,
                        'voucher_date' => $date,
                        'voucher_type' => 2, // Stock OUT
                        'sub_item_id' => $item['item_id'],
                        'qty' => $item['qty'],
                        'rate' => $item['rate'],
                        'amount' => $item_amount,
                        'warehouse_id' => $warehouse_id,
                        'status' => 1,
                        'description' => 'Conversion OUT: ' . $remarks,
                        'username' => Auth::user()->name,
                        'created_date' => date('Y-m-d')
                    ]);

                    // Financial Transaction (Credit Inventory)
                    $product_name = DB::connection('mysql2')->table('subitem')->where('id', $item['item_id'])->value('product_name');
                    DB::connection('mysql2')->table('transactions')->insert([
                        'acc_id' => config('accounts.item_conversion.inventory.id'),
                        'acc_code' => config('accounts.item_conversion.inventory.code'),
                        'particulars' => $conversion_no . ' - ' . $product_name,
                        'debit_credit' => 0, // Credit
                        'amount' => $item_amount,
                        'voucher_no' => $conversion_no,
                        'voucher_type' => 53,
                        'v_date' => $date,
                        'date' => date('Y-m-d'),
                        'time' => date('H:i:s'),
                        'username' => Auth::user()->name,
                        'status' => 1,
                        'action' => 'create'
                    ]);
                }
            }

            // Conversion (Stock IN)
            if (isset($request->conversion_items)) {
                foreach ($request->conversion_items as $item) {
                    if (empty($item['item_id']) || empty($item['qty']))
                        continue;

                    $item_amount = $item['qty'] * $item['rate'];
                    $total_in_amount += $item_amount;

                    DB::connection('mysql2')->table('stock')->insert([
                        'voucher_no' => $conversion_no,
                        'voucher_date' => $date,
                        'voucher_type' => 1, // Stock IN
                        'sub_item_id' => $item['item_id'],
                        'qty' => $item['qty'],
                        'rate' => $item['rate'],
                        'amount' => $item_amount,
                        'warehouse_id' => $warehouse_id,
                        'status' => 1,
                        'description' => 'Conversion IN: ' . $remarks,
                        'username' => Auth::user()->name,
                        'created_date' => date('Y-m-d')
                    ]);

                    // Financial Transaction (Debit Inventory)
                    $product_name = DB::connection('mysql2')->table('subitem')->where('id', $item['item_id'])->value('product_name');
                    DB::connection('mysql2')->table('transactions')->insert([
                        'acc_id' => config('accounts.item_conversion.inventory.id'),
                        'acc_code' => config('accounts.item_conversion.inventory.code'),
                        'particulars' => $conversion_no . ' - ' . $product_name,
                        'debit_credit' => 1, // Debit
                        'amount' => $item_amount,
                        'voucher_no' => $conversion_no,
                        'voucher_type' => 53,
                        'v_date' => $date,
                        'date' => date('Y-m-d'),
                        'time' => date('H:i:s'),
                        'username' => Auth::user()->name,
                        'status' => 1,
                        'action' => 'create'
                    ]);
                }
            }

            // Record Conversion Variance/Expense to balance the entry
            $diff = $total_in_amount - $total_out_amount;
            if ($diff != 0) {
                DB::connection('mysql2')->table('transactions')->insert([
                    'acc_id' => config('accounts.item_conversion.conversion_account.id'),
                    'acc_code' => config('accounts.item_conversion.conversion_account.code'),
                    'particulars' => 'Item Conversion Adjustment: ' . $conversion_no,
                    'debit_credit' => ($diff > 0) ? 0 : 1, // Credit (0) if Gain, Debit (1) if Loss
                    'amount' => abs($diff),
                    'voucher_no' => $conversion_no,
                    'voucher_type' => 53,
                    'v_date' => $date,
                    'date' => date('Y-m-d'),
                    'time' => date('H:i:s'),
                    'username' => Auth::user()->name,
                    'status' => 1,
                    'action' => 'create'
                ]);
            }

            DB::connection('mysql2')->commit();
            
            // Log Inventory Activity
            $total_amount = 0;
            if (isset($request->conversion_items)) {
                foreach ($request->conversion_items as $item) {
                    $total_amount += ($item['qty'] * $item['rate']);
                }
            }
            CommonHelper::inventory_activity($conversion_no, $date, $total_amount, 12, 'Insert'); // Using 12 as table code for conversion

            CommonHelper::reconnectMasterDatabase();
            return redirect()->back()->with('message', 'Item Conversion saved successfully! No: ' . $conversion_no);

        } catch (\Exception $e) {
            DB::connection('mysql2')->rollBack();
            CommonHelper::reconnectMasterDatabase();
            return redirect()->back()->with('error', 'Error saving conversion: ' . $e->getMessage());
        }
    }

    public function getStock(Request $request)
    {
        $warehouse = $request->warehouse;
        $item = $request->item;

        $in = DB::connection('mysql2')->table('stock')
            ->where('status', 1)
            ->whereIn('voucher_type', [1, 4, 6, 10, 11])
            ->where('sub_item_id', $item)
            ->where('warehouse_id', $warehouse)
            ->select(DB::raw('SUM(qty) As qty'), DB::raw('SUM(amount) As amount'))
            ->first();

        $out = DB::connection('mysql2')->table('stock')
            ->where('status', 1)
            ->whereIn('voucher_type', [2, 5, 3, 9, 8])
            ->where('sub_item_id', $item)
            ->where('warehouse_id', $warehouse)
            ->select(DB::raw('SUM(qty) As qty'), DB::raw('SUM(amount) As amount'))
            ->first();

        $qty = ($in->qty ?? 0) - ($out->qty ?? 0);
        $amount = $in->amount ?? 0;
        $rate = 0;

        if ($qty > 0 && ($in->qty ?? 0) > 0) {
            $rate = number_format($amount / $in->qty, 2, '.', '');
        }

        return response()->json([
            'qty' => number_format((float) $qty, 2, '.', ''),
            'rate' => $rate
        ]);
    }
}
