<?php

namespace App\Http\Controllers;

use App\Models\DeliveryNoteData;
use App\Models\GRNData;
use App\StockBarcode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockBarcodeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
//        dd($request);
//        return view('StockBarcode.getBarcodeListAgainstProduct');

    }
    public function getBarcodeListAgainstProduct(Request $request)
    {

//        if($request->product == null){
//            return '<div class="col-12 text-center"><h4>Please Select Item</h4></div>';
//        }

        if ($request->type == 'grn') {


            $data['voucherItemCount'] = GRNData::where('grn_no', $request->voucher_no)
                ->where('sub_item_id', $request->product)
                ->value('purchase_recived_qty');
        } elseif ($request->type == 'gdn'){
            $data['voucherItemCount'] = DeliveryNoteData::where('gd_no', $request->voucher_no)
                ->where('item_id', $request->product)
                ->value('qty');
            }



        $stockbar =  StockBarcode::where('product_id',$request->product)->where('voucher_no', $request->voucher_no)
            ->leftJoin('subitem','subitem.id','=','stock_barcodes.product_id')
            ->select('subitem.product_name','stock_barcodes.*');
        $data['barcode']  = $stockbar->pluck('barcode')->toArray();

        $data['stockbarcode']  = $stockbar->paginate(10);

        $data['product_id'] = $request->product;
        $data['voucher_no'] = $request->voucher_no;
//dd($data['barcode']);
        return view('StockBarcode.getBarcodeListAgainstProduct',$data);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeX(Request $request)
    {
        //
    }


    public function store(Request $request)
    {
        // Validate the inputs
        $request->validate([
            'product_id' => 'required', // Ensure product_id exists in the products table
            'voucher_no' => 'required|string', // Adjust type according to your database
        ], [
            'product_id.required' => 'Please select an item first.',
            'voucher_no.required' => 'Voucher number is required.',
        ]);


        if($request->voucher_type == 'grn'){
            $voucher_type = 1;
            $type = 1; //1= Stock-in
        }elseif($request->voucher_type == 'gdn'){
            $voucher_type = 2;
            $type = 0; //0= Stock-out
        }elseif($request->voucher_type == 'salereturn'){
            $voucher_type = 3;
            $type =1; //1= Stock-in
        }

        try {
            StockBarcode::create([
                'product_id' => $request->product_id,
                'voucher_no' => $request->voucher_no,
                'voucher_type' => $voucher_type,
                'type' => $type,
                'barcode' => $request->barcode,
            ]);

            return response()->json([
                'message' => 'Barcode submitted successfully!'
            ], 200);
        } catch (\Exception $e) {
            // Handle unexpected server errors
            return response()->json([
                'message' => $e->getMessage(),
                'error' => 'An error occurred while saving the barcode. Please try again later.'
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\StockBarcode  $stockBarcode
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {

        if($request->type == 'grn'){
            $data['voucherItem'] = GRNData::where('grn_data.grn_no',$id)
                ->leftJoin('subitem','grn_data.sub_item_id','=','subitem.id')
                ->where('subitem.is_barcode_scanning',1)
                ->select('grn_data.grn_no as voucher_no', 'subitem.product_name', 'subitem.id as product_id')
                ->get();
        }elseif($request->type == 'gdn'){
            $data['voucherItem'] = DeliveryNoteData::where('delivery_note_data.gd_no',$id)
                ->leftJoin('subitem','delivery_note_data.item_id','=','subitem.id')
                ->where('subitem.is_barcode_scanning',1)
                ->select('delivery_note_data.gd_no as voucher_no', 'subitem.product_name', 'subitem.id as product_id')
                ->get();
        }
        $data['voucher_no'] = $id;
        $data['type'] = $request->type;

        return view('StockBarcode.index',$data);
        dd($request,$id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\StockBarcode  $stockBarcode
     * @return \Illuminate\Http\Response
     */
    public function edit(StockBarcode $stockBarcode)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\StockBarcode  $stockBarcode
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StockBarcode $stockBarcode)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\StockBarcode  $stockBarcode
     * @return \Illuminate\Http\Response
     */
    public function destroy(StockBarcode $stockBarcode)
    {
        $stock_voucher_no = $stockBarcode->voucher_no;
        $gdn = DB::connection("mysql2")->table("delivery_note")->where("gd_no", $stock_voucher_no)->first();
        if($gdn->status == 1) {
            return response()->json("Gdn has already approved", 404);
        }

        $stockBarcode->delete();
        return response()->json("deleted");
    }
}
