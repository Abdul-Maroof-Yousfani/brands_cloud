<?php

namespace App\Http\Controllers;
use App\Models\Transactions;
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

use App\Models\ProductType;
use App\Models\ResourceAssigned;
use App\Models\Quotation;
use App\Models\Quotation_Data;
use App\Models\Complaint;
use App\Models\ComplaintProduct;
use App\Models\InvDesc;
use App\Helpers\SalesHelper;
use App\Models\Invoice_totals;

use Input;
use Auth;
use DB;
use Config;
use Redirect;
use App\Models\DeliveryNote;
use App\Models\DeliveryNoteData;
use App\Models\SalesTaxInvoice;
use App\Models\SalesTaxInvoiceData;
use App\Models\Invoice;
use App\Models\InvoiceData;
use App\Models\Survey;
use App\Models\ClientJob;
use App\Models\ComplaintDocument;

class SalesEditDetailController extends Controller
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

    function updateDeliveryNote(Request $request)
    {
        DB::Connection('mysql2')->beginTransaction();
        try {
        $delivery_note = new DeliveryNote();
        $delivery_note = $delivery_note->SetConnection('mysql2');
        $delivery_note = $delivery_note->find($request->edit_id);
        $delivery_note->master_id = $request->master_id;
        $delivery_note->gd_no = $request->gd_no;
        $delivery_note->gd_date = $request->gd_date;
        $delivery_note->model_terms_of_payment = $request->model_terms_of_payment;
        $delivery_note->so_no = $request->so_no;
        $delivery_note->so_date = $request->so_date;
        $delivery_note->other_refrence = $request->other_refrence;
        $delivery_note->order_no = $request->order_no;;
        $delivery_note->order_date = $request->order_date;
        $delivery_note->despacth_document_no = $request->despacth_document_no;
        $delivery_note->despacth_document_date = $request->despacth_document_date;
        $delivery_note->despacth_through = $request->despacth_through;
        $delivery_note->destination = $request->destination;
        $delivery_note->terms_of_delivery = $request->terms_of_delivery;
        $delivery_note->buyers_id = $request->buyers_id;
        $delivery_note->due_date = $request->due_date;
        $delivery_note->sales_tax_amount = CommonHelper::check_str_replace($request->sales_tax_apply);
            $SalesTaxAmount = CommonHelper::check_str_replace($request->sales_tax_apply);
        $delivery_note->description = $request->description;
        // $delivery_note->status = 1;
        $delivery_note->date = date('Y-m-d');
        $delivery_note->username = Auth::user()->name;

        $delivery_note->save();

        DB::Connection('mysql2')->table('delivery_note_data')->where('master_id', $request->edit_id)->delete();
        DB::Connection('mysql2')->table('stock')->where('main_id', $request->edit_id)->where('voucher_no',$request->gd_no)->where('voucher_type',5)->delete();
        $count = $request->count;
        $total_amount = 0;
        $actual_qty = 0;
        $Actualsend_qty =0;
        $total_send_qty = 0;
        for ($i = 1; $i <= $count; $i++):
            $delivery_note_data = new DeliveryNoteData();
            $delivery_note_data = $delivery_note_data->SetConnection('mysql2');
            $delivery_note_data->master_id = $request->edit_id;
            $delivery_note_data->so_id = $request->master_id;
            $delivery_note_data->so_data_id = $request->input('data_id' . $i);
            $delivery_note_data->gd_no = $request->gd_no;
            $delivery_note_data->gd_date = $request->input('gd_date');
            $delivery_note_data->item_id = $request->input('item_id' . $i);


            $qty = CommonHelper::check_str_replace($request->input('qty' . $i));
            $actual_qty += DB::Connection('mysql2')->table('sales_order_data')->where('id',$request->input('data_id' . $i))->first()->qty;
            $send_qty = CommonHelper::check_str_replace($request->input('send_qty' . $i));


            $rate = CommonHelper::check_str_replace($request->input('send_rate' . $i));
            $amount = CommonHelper::check_str_replace($request->input('send_amount' . $i));


            $delivery_note_data->qty = $send_qty;

            $delivery_note_data->rate = $rate;
            $delivery_note_data->tax=$request->input('send_discount' . $i);
            $delivery_note_data->tax_amount=$request->input('send_discount_amount' . $i);
            $delivery_note_data->amount = $amount;
            $total_amount+=$amount;


            $delivery_note_data->warehouse_id = $request->input('warehouse' . $i);
            $delivery_note_data->warehouse_to_id=$request->input('warehouse_to' . $i);
            $delivery_note_data->groupby = $request->input('groupby' . $i);
            $delivery_note_data->bundles_id = $request->input('bundles_id' . $i);
            $delivery_note_data->status = 1;
            $delivery_note_data->date = date('Y-m-d');
            $delivery_note_data->username = Auth::user()->name;

            $delivery_note_data->save();
            $master_data_id = $delivery_note_data->id;
            //$Actualsend_qty += DB::Connection('mysql2')->table('delivery_note_data')->where('so_data_id',$request->input('data_id' . $i))->first()->qty;
            $Actualsend_qty = DB::Connection('mysql2')->table('delivery_note_data')->where('so_data_id',$request->input('data_id' . $i))->sum('qty');
            $total_send_qty += $Actualsend_qty;


            $stock = array
            (
                'main_id' => $request->edit_id,
                'master_id' => $master_data_id,
                'voucher_no' => $request->gd_no,
                'voucher_date' => $request->gd_date,
                'supplier_id' => 0,
                'customer_id' => $request->buyers_id,
                'voucher_type' => 5,
                'rate' => $rate,
                'sub_item_id' => $request->input('item_id' . $i),
                'batch_code' => $request->input('batch_code' . $i),
                'qty' => $send_qty,
                'amount' => $rate * $send_qty,
                'status' => 1,
                'warehouse_id' => $request->input('warehouse' . $i),
                'username' => Auth::user()->username,
                'created_date' => date('Y-m-d'),
                'created_date' => date('Y-m-d'),
                'opening' => 0,
                'so_data_id' => $request->input('data_id' . $i)
            );
            DB::Connection('mysql2')->table('stock')->insert($stock);

        endfor;

        if ($total_send_qty == $actual_qty):


            $sale_order = new Sales_Order();
            $sale_order = $sale_order->SetConnection('mysql2');
            $sale_order = $sale_order->find($request->master_id);
            $sale_order->delivery_note_status = 1;
            $sale_order->save();
        else:
            $sale_order = new Sales_Order();
            $sale_order = $sale_order->SetConnection('mysql2');
            $sale_order = $sale_order->find($request->master_id);
            $sale_order->delivery_note_status = 0;
            $sale_order->save();
        endif;
        echo $total_send_qty.' == '.$actual_qty;

            SalesHelper::sales_activity($request->gd_no,$request->gd_date,$total_amount+$SalesTaxAmount,2,'Update');
            DB::Connection('mysql2')->commit();
        }
        catch ( Exception $ex )
        {


            DB::rollBack();

        }
            

        return Redirect::to('sales/viewDeliveryNoteList?pageType=' . Input::get('pageType') . '&&parentCode=' . Input::get('parentCode') . '&&m=' . $_GET['m'] . '#SFR');

    }
}