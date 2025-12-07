<?php

namespace App\Http\Controllers;

use App\Helpers\CommonHelper;
use App\Models\MachineProccess;
use App\Models\MachineProccessData;
use App\Models\MaterialRequisition;
use App\Models\MaterialRequisitionData;
use App\Models\ProductionPlane;
use App\Models\Sales_Order;
use App\Models\InventoryMaster\Machine;
use App\Models\InventoryMaster\Operator;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class MachineProccessController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createMachineProccess()
    {
    //    $material_requisition = MaterialRequisition::
    //    where('mr_status',2)->
    //    where('status',1)->get();

       $material_requisition = ProductionPlane::where('status',1)->get();
       $Sales_Order = Sales_Order::where('status',1)->get();
       $Machine = Machine::where('status',1)->get();
       $Operator = Operator::where('status',1)->get();
       


        return view('selling.machineproccess.createMachineProccess',compact('material_requisition','Machine','Sales_Order','Operator'));
    }

    public function productionPlanAgainstSo(Request $request)
    {

    //    $material_requisition = ProductionPlane::where('status',1)->where('sales_order_id',$request->id)->get();

       $material_requisition = DB::Connection('mysql2')->table('production_plane as pp')
            ->join('material_requisitions as mr', 'pp.id', '=', 'mr.production_id')
            ->where('mr.status', 1)
            ->where('mr.approval_status', 2)
            ->where('pp.status', 1)
            ->where('pp.sales_order_id',$request->id)
            ->groupBy('mr.id')
            ->select('mr.id', 'pp.order_no', 'pp.order_date','pp.id as pp_id')
            ->get();

       ?>
        <option value="">Select Production Plan</option>
        <?php foreach ($material_requisition as $item): ?>
            <option value="<?php echo $item->id ?>" data-value="<?php echo  $item->pp_id ?>"> <?php echo $item->order_no." -- ".$item->order_date ?>  </option>
        <?php
        endforeach;

    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeMachineProccess(Request $request)
    {
        // echo $request->serial_no;
        // echo "<pre>";
        // print_r($request->all());
        // exit();
        DB::Connection('mysql2')->beginTransaction();
        try {
                if(empty($request->cmp))
                {

                    $mrData =  MaterialRequisition::where('id',$request->mr_id)->where('status',1)->get();
                    // echo "<pre>";
                    // print_r($mrData);
                    // exit();
                    foreach ($mrData as $key => $value) {
                        # code...
                        $mr =  MaterialRequisition::where('status',1)->where('id',$value->id)->first();

                        $machine_no =CommonHelper::generateUniquePosNo('machine_proccesses','machine_no','MRP');
                        $machine_process = new MachineProccess;
                        $machine_process->mr_id = $mr->id;
                        $machine_process->production_plane_id = $mr->production_id;
                        $machine_process->finish_good_id =$mr->finish_good_id;
                        $machine_process->finish_good_qty =$mr->finish_good_qty;
                        $machine_process->so_id = $request->so_id;
                        $machine_process->serial_no = $request->serial_no;
                        $machine_process->machine_process_date = $request->machine_process_date;


                        // echo "<pre>";
                        // print_r($machine_process);
                        // exit();
                        $machine_process->save();
                        $mr->mr_status = 3;
                        $mr->save();

                        $machine_process_id = $machine_process->id;
                    }
                    foreach ($request->mr_data_id as $key => $value) {
                        $MaterialRequisitionData =  MaterialRequisitionData::find($value);
                        $MaterialRequisitionData->material_stage = 2 ;
                        $MaterialRequisitionData->save();
                    }

                }
                else
                {
                    $machine_process_id = $request->cmp;
                }



                //direct creating machine process

                $Bundle = ($request->Bundle) ? $request->Bundle : 1 ;

                for ($i=0; $i < $Bundle ; $i++) { 
                    # code...
                    $mp =  MachineProccess::find($machine_process_id);
        
                    if(!$mp)
                    { 
                        return 2;
                    }
                    else
                    {
                        $code = ($mp->serial_no) ? $mp->serial_no : 'PKG';
                    }
        
                    $batch_no =CommonHelper::generateUniquePosNoForMachine('machine_proccess_datas','batch_no',$code);
                 
                    if($mp->finish_good_qty == $request->received_length)
                    {
                        $mp->proccess_status =  3;
                    }else{
                        $mp->proccess_status =  2;
                    }
                    $mchaine_data =  new MachineProccessData;
                    $mchaine_data->machine_proccess_id = $machine_process_id;
                    $mchaine_data->mr_data_id = $mp->mr_id;
                    $mchaine_data->finish_good_id = $mp->finish_good_id;
                    $mchaine_data->request_qty = $request->received_length;
                    $mchaine_data->color_line = $request->color;
                    $mchaine_data->remarks = $request->remarks;
                    $mchaine_data->batch_no = $batch_no;
                    
                    $mchaine_data->machine_id = $request->machine_id;
                    $mchaine_data->operator_id = $request->operator_id;
                    $mchaine_data->shift = $request->shift;
        
                    $mchaine_data->recieved_date = date('Y-m-d');
                    if(!$mchaine_data->save()){
                        return 2;
                    }
                }





                DB::Connection('mysql2')->commit();
            }
            catch (Exception $e) {
                DB::Connection('mysql2')->rollBack();
                return redirect()->route('createMachineProccess')->with('error', $e->getMessage());
            }
        return redirect()->route('createMachineProccess')->with('dataInsert','MR goining to procees SuccessFully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\MachineProccess  $machineProccess
     * @return \Illuminate\Http\Response
     */
    public function show(MachineProccess $machineProccess)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\MachineProccess  $machineProccess
     * @return \Illuminate\Http\Response
     */
    public function edit(MachineProccess $machineProccess)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\MachineProccess  $machineProccess
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MachineProccess $machineProccess)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\MachineProccess  $machineProccess
     * @return \Illuminate\Http\Response
     */
    public function destroy(MachineProccess $machineProccess)
    {
        //
    }

    public function getMrData(Request $request)
    {
      $mr_datas =   MaterialRequisitionData::where('mr_id',$request->id)->get();
      return view('selling.machineproccess.getMrData',compact('mr_datas'));
    }

    public function getMrDataWithProductionPlanId(Request $request)
    {
      
      $mr_datas = DB::Connection('mysql2')->table('production_plane as pp')
                        ->join('material_requisitions as mr', 'pp.id', '=', 'mr.production_id')
                        ->join('material_requisition_datas as mrd', 'mr.id', '=', 'mrd.mr_id')
                        ->where('mr.status', 1)
                        ->where('mrd.status', 1)
                        ->where('mrd.material_stage', 1)
                        ->where('pp.status', 1)
                        ->where('mrd.mr_id',$request->id)
                        // ->where('pp.id',$request->id)
                        ->select('mrd.*')
                        ->get();
      return view('selling.machineproccess.getMrData',compact('mr_datas'));
    }

    public function pipeMachineList()
    {
    
       return view('selling.production.pipeMachineList');
    }

    public function viewProductInProccess()
    {
       $machine_process =  MachineProccess::
       join('production_plane','production_plane.id','machine_proccesses.production_plane_id')
       ->join('subitem','subitem.id','machine_proccesses.finish_good_id')
       ->leftjoin('machine_proccess_datas','machine_proccess_datas.machine_proccess_id','machine_proccesses.id')
       ->select('machine_proccesses.machine_process_date','production_plane.sale_order_no','subitem.item_code','machine_proccesses.machine_no','production_plane.order_no','machine_proccesses.finish_good_qty','machine_proccesses.id'
        ,DB::raw('sum(machine_proccess_datas.request_qty) as received_qty'),'machine_proccess_datas.remarks','machine_proccess_datas.color_line'
       )
       ->where(['machine_proccesses.status'=>1])
       ->whereIn('machine_proccesses.proccess_status',[1,2])
       ->groupBy('machine_proccesses.id','machine_proccess_datas.machine_proccess_id')
       ->get();

       $Machine = Machine::where('status',1)->get();
       $Operator = Operator::where('status',1)->get();
     
    //    echo "<pre>";
    //    print_r($machine_process);
    //    exit();
        return view('selling.machineproccess.viewProductInProccess',compact('machine_process','Machine','Operator'));  
    }
    public function viewProductProccessComplete(Request $request)
    {
  
        $machine_process =  MachineProccess::join('machine_proccess_datas as mpd', 'machine_proccesses.id', '=', 'mpd.machine_proccess_id')
                            ->join('machine as m', 'mpd.machine_id', '=', 'm.id')
                            ->join('operators as o', 'mpd.operator_id', '=', 'o.id')
                            ->select('mpd.id', 'mpd.batch_no', 'm.name as machine_name', 'o.name as operator_name', 'mpd.shift', 'machine_proccesses.machine_process_date', 'mpd.request_qty', 'mpd.machine_process_stage')
                            ->where('mpd.status', '=', 1);
                            if(!empty($request->machine_proccess_id))
                            {
                                $machine_process = $machine_process->where('mpd.machine_proccess_id', '=', $request->machine_proccess_id);
                            }
                            else
                            {
                                $machine_process = $machine_process->take(10);

                            }
                            

                      $machine_process = $machine_process->get();
        return view('selling.machineproccess.viewProductProccessComplete',compact('machine_process'));  
    }

    public function received_length(Request $request)
    {
        // echo "<pre>";
        // print_r($request->all());
        // exit();
        $Bundle = ($request->Bundle) ? $request->Bundle : 1 ;

        for ($i=0; $i < $Bundle ; $i++) { 
            # code...
            $mp =  MachineProccess::find($request->machine_proccess_id);

            if(!$mp)
            { 
                return 2;
            }
            else
            {
                $code = ($mp->serial_no) ? $mp->serial_no : 'PKG';
            }

            $batch_no =CommonHelper::generateUniquePosNoForMachine('machine_proccess_datas','batch_no',$code);
         
            if($mp->finish_good_qty == $request->received_length)
            {
                $mp->proccess_status =  3;
            }else{
                $mp->proccess_status =  2;
            }

            $mchaine_data =  new MachineProccessData;
            $mchaine_data->machine_proccess_id = $request->machine_proccess_id;
            $mchaine_data->mr_data_id = $mp->mr_id;
            $mchaine_data->finish_good_id = $mp->finish_good_id;
            $mchaine_data->request_qty = $request->received_length;
            $mchaine_data->color_line = $request->color;
            $mchaine_data->remarks = $request->remarks;
            $mchaine_data->batch_no = $batch_no;
            
            $mchaine_data->machine_id = $request->machine_id;
            $mchaine_data->operator_id = $request->operator_id;
            $mchaine_data->shift = $request->shift;

            $mchaine_data->recieved_date = date('Y-m-d');
            if(!$mchaine_data->save()){
                return 2;
            }
        }

        return 1;

    }

    public function RemainingQtyOfSaleOrder(Request $request)
    {

        $result = DB::Connection('mysql2')->table('machine_proccesses as mp')
                ->leftJoin('machine_proccess_datas as mpd', 'mp.id', '=', 'mpd.machine_proccess_id')
                // ->where('mp.mr_id', '=', $request->mr_id)
                ->where('mp.production_plane_id', '=', $request->pp_id)
                ->groupBy('mp.production_plane_id')
                ->selectRaw('IFNULL(SUM(mpd.request_qty), 0) as total_request_qty')
                ->get();

                // echo "<pre>";
                // print_r($result     );
                // exit();
        $readyQty = $result[0]->total_request_qty ?? 0;
        $saleOrderQty = DB::Connection('mysql2')->table('sales_order_data as sod')
                    ->join('material_requisitions as mr', 'sod.item_id', '=', 'mr.finish_good_id')
                    ->where('sod.master_id', '=', $request->so_id)
                    // ->where('mr.id', '=', $request->mr_id)
                    ->where('mr.production_id', '=', $request->pp_id)
                    ->sum('sod.qty');
        $saleOrderQty = $saleOrderQty ?? 0 ;

        $remaining= $saleOrderQty - $readyQty ; 
       return [$readyQty,$saleOrderQty,$remaining];
    }

    public function getMachineProcessAgainstPP(Request $request)
    {
        $data = MachineProccess::where('status',1)->where('production_plane_id', $request->pp_id )->get();


        return $data;
        
        ?>
        <option value="">Select Machine Process </option>
        <?php foreach ($data as $value): ?>
            <option value="<?php echo $value->id ?>" > <?php echo $value->serial_no." -- ".$value->machine_process_date ?>  </option>
        <?php
        endforeach;
    }
}
