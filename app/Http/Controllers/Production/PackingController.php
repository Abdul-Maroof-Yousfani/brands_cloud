<?php

namespace App\Http\Controllers\Production;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Sales_Order;

use App\Models\Production\Packing;
use App\Models\Production\PackingData;
use Hash;
use Input;
use Auth;
use DB;
use Config;
use Session;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class PackingController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::connection('mysql2')->table('packings')->where('status', 1);

            // if ($request->rate_date) {
            //     $data = $data->where('er.rate_date', '>=', $request->rate_date);
            // }
            // if ($request->to_date) {
            //     $data = $data->where('er.rate_date', '<=', $request->to_date);
            // }

            $data = $data->orderBy('id', 'desc')->get();

            return view('Production.Packing.ajax.listPackingAjax', compact('data'));
        }

        return view('Production.Packing.listPacking');
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $Sales_Order = Sales_Order::where('status',1)->get();

        return view('Production.Packing.createPacking', compact('Sales_Order') );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // echo "<pre>";
        // print_r($request->all());
        // exit();
        // $validator = Validator::make($request->all(), [
        //     'name' => 'required',
        // ]);
		DB::Connection('mysql2')->beginTransaction();
    
        $total_qty = 0 ;
        try {
        
            // if ($validator->fails()) {
            //     return redirect()->back()->withErrors($validator)->withInput();
            // }

            $packing = new Packing;
            $packing->so_id = $request->so_id;
            $packing->material_requisition_id = $request->material_requisition_id; //
            $packing->production_plan_id = $request->pp_id ?? 0 ;
            $packing->customer_name = $request->customer_name;
            $packing->customer_id = $request->customer_id;
            $packing->packing_date = $request->packing_date;
            $packing->deliver_to = $request->deliver_to;
            $packing->packing_list_no = $request->packing_list_no;
            $packing->item_id = $request->item_id;
            $packing->item_name = $request->item_name;
            $packing->status = 1;
            $packing->username = Auth::user()->name;
            $packing->save();
            $packing_id = $packing->id;

            foreach ($request->machine_proccess_data_id as $key => $value) {

                if ($request->input('checkBox' . $value) == 1) 
                {

                    db::connection('mysql2')->table('machine_proccess_datas')->where('id',$value)->update([
                        'machine_process_stage' => 2
                    ]);


                    $total_qty += $request->qty[$key];
                    $packing_data = new PackingData;
                    $packing_data->packing_id = $packing_id;
                    $packing_data->machine_proccess_data_id = $value;
                    $packing_data->bundle_no = $request->bundle_no[$key];
                    $packing_data->qty = $request->qty[$key];
                    $packing_data->status = 1;
                    $packing_data->username = Auth::user()->name;
                    $packing_data->save();
                }      
                
            }

            $packing->total_qty = $total_qty;
            $packing->save();
            
			DB::Connection('mysql2')->commit();
    
            return redirect()->back()->with('success', 'Record inserted successfully');
        } catch (QueryException $e) {
            // Log or handle the exception as needed
			DB::Connection('mysql2')->rollback();

            return redirect()->back()->withErrors('Error inserting record. Please try again.')->withInput();
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $packing = DB::connection('mysql2')->table('packings as p')
        ->join('sales_order as so', 'p.so_id', '=', 'so.id')
        ->join('subitem as s', 's.id', '=', 'p.id')
        ->join('sub_category as sc', 's.sub_category_id', '=', 'sc.id')
        ->join('sales_order_data as sod', function($join) {
            $join->on('p.item_id', '=', 'sod.item_id')
                 ->on('sod.master_id', '=', 'p.so_id');
        })
        ->where('p.id', $id)
        ->select('s.sub_ic', 'sc.sub_category_name', 'so.description', 'so.so_no', 'so.purchase_order_no', 'p.packing_list_no', 'p.customer_name', 'p.deliver_to','p.packing_date')
        ->first();

        $packing_data = PackingData::where('packing_id',$id)->where('status', 1)->get();

        return view('Production.Packing.viewPacking', compact('packing','packing_data') );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $Packing = Packing::where('id', $id)->where('status', 1)->first();

        if (!$Packing) {
            return redirect()->back()->withErrors('Record not found')->withInput();
        }

        return view('Production.Packing.updatePacking', compact('Packing'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required'
               ]);

        try {
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $Packing = Packing::find($id);

            if (!$Packing) {
                return redirect()->back()->withErrors('Record not found')->withInput();
            }

            $Packing->update([
                'name' => $request->name,
                'status' => 1,
                'username' => Auth()->user()->name,
            ]);

            return redirect('Production/Packing/')->with('success', 'Record updated successfully');
        } catch (QueryException $e) {
            // Log or handle the exception as needed
            return redirect()->back()->withErrors('Error updating record. Please try again.')->withInput();
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    public function deletePacking($id)
    {
        Packing::find($id)->update([
            'status' => 0
        ]);
    }


    public function productionPlanAndCustomerAgainstSo(Request $request)
    {

        //    $material_requisition = ProductionPlane::where('status',1)->where('sales_order_id',$request->id)->get();

       $material_requisition = DB::Connection('mysql2')->table('production_plane as pp')
            ->join('material_requisitions as mr', 'pp.id', '=', 'mr.production_id')
            ->where('mr.status', 1)
            ->where('mr.approval_status', 2)
            ->where('pp.status', 1)
            ->where('pp.sales_order_id',$request->id)
            ->groupBy('mr.id')
            ->select('mr.id','pp.id as pp_id', 'pp.order_no', 'pp.order_date')
            ->get();


        $customerDetails =   Sales_Order::where('sales_order.id',$request->id)
            ->join('customers','customers.id','sales_order.buyers_id')
            ->select('sales_order.id as so_id','sales_order.purchase_order_no','sales_order.so_no','customers.id as customer_id','customers.name as name')
            ->first();      

       return compact('material_requisition','customerDetails');
    }

    public function getMachineProcessDataByMr(Request $request)
    {

        $machine_data =  DB::connection('mysql2')->table('machine_proccesses AS mp')
                            ->join('subitem AS s', 's.id', '=', 'mp.finish_good_id')
                            ->select('mp.finish_good_id', 's.sub_ic')
                            ->where('mp.status', 1)
                            ->where('s.status', 1)
                            ->where('mp.mr_id', $request->id)
                            ->first();
   
                            // echo "<pre>";
                            // print_r($machine_data);
                            // exit();
        $mr_datas =  DB::connection('mysql2')->table('machine_proccesses AS mp')
                        ->join('machine_proccess_datas AS mpd', 'mp.id', '=', 'mpd.machine_proccess_id')
                        ->select('mpd.id', 'mpd.batch_no', 'mpd.request_qty')
                        ->where('mp.status', 1)
                        ->where('mpd.status', 1)
                        ->where('mpd.machine_process_stage', 1)
                        ->where('mp.mr_id', $request->id)
                        ->whereBetween('mpd.batch_no', [$request->range_1 , $request->range_2])
                        ->get();

      return view('Production.Packing.getMachineProcessDataByMr',compact('mr_datas','machine_data'));

    }
}
