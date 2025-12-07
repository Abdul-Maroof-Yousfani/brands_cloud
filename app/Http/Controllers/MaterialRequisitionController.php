<?php

namespace App\Http\Controllers;

use App\Helpers\CommonHelper;
use App\Models\MaterialRequisition;
use App\Models\MaterialRequisitionData;
use App\Models\ProductionPlane;
use App\Models\ProductionPlaneData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use Session;
use Input;
use Auth;
use Config;
use Redirect;
use Illuminate\Support\Facades\Validator;

class MaterialRequisitionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function listMaterialRequisition(Request $request)
    {
        if($request->ajax())
        {
            // $material_requisitions = MaterialRequisition::where('status',1)->where('approval_status',2)->get();
            $material_requisitions = DB::connection('mysql2')->table('material_requisitions as mr')
            ->join('production_plane as pp', 'mr.production_id', '=', 'pp.id')
            ->select('mr.*', 'pp.sale_order_no', 'pp.order_no')
            ->where('mr.status',1)
            ->where('mr.approval_status',2)
            ->get();
        

            return view('selling.materialrequisition.listMaterialRequisitionAjax',compact('material_requisitions'));
        }
        return view('selling.materialrequisition.listMaterialRequisition');

    }

    public function viewProductionPlane(Request $request)
    {
    
        $material_requisitions =   MaterialRequisition::where('id',$request->id)->where('status',1)->first();
        return view('selling.production.viewProductionPlane',compact('material_requisitions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createMaterialRequisition(Request $request)
    {
       $prodtion_data = ProductionPlaneData::find($request->id);
       return view('selling.materialrequisition.createMaterialRequisition',compact('prodtion_data'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeMaterialRequisition(Request $request)
    {
 
        DB::Connection('mysql2')->beginTransaction();
        try {
            $mr_no =CommonHelper::generateUniquePosNo('material_requisitions','mr_no','MR');
            $production =   ProductionPlane::find($request->production_id);
            $mr = new MaterialRequisition;
            $mr->mr_no = $mr_no;
            $mr->mr_date = $request->requisition_date;
            $mr->production_id = $request->production_id;
            $mr->work_id = $production->work_order_id;
            $mr->finish_good_id = $request->finish_goods_id;
            $mr->finish_good_qty = $request->finish_good_qty;
            $mr->category_id = $request->category;
            $mr->over_all_required_qty = $request->qty_for_making_product;
            $mr->mr_status = 1;
            $mr->save();




            foreach($request->item as $key=>$item)
            {
               $mr_data = new MaterialRequisitionData;
               $mr_data->mr_no =  $mr_no;
               $mr_data->mr_id =$mr->id;
               $mr_data->production_data_id = $request->production_data_id;
               $mr_data->receipe_id = $request->receipt_id;
               $mr_data->raw_item_id = $request->item[$key];
               $mr_data->request_qty = $request->required_qty[$key];
               $mr_data->warehouse_id = $request->warehouse_id[$key];
               $mr_data->mr_status = 1;
               $mr_data->save();
               
              $product_plan =  ProductionPlaneData::find($request->production_data_id);
              $product_plan->ppc_status =1;
              $product_plan->save();
            }



            DB::Connection('mysql2')->commit();
            return redirect()->route('listMaterialRequisition')->with('dataInsert','Sale Order Inserted');
       }
       catch ( Exception $e )
       {
           DB::Connection('mysql2')->rollBack();
           return redirect()->route('listMaterialRequisition')->with('error', $e->getMessage());
       }

    }


    public function issueMaterial(Request $request)
    {
       
        $request_qty =  0;
        $issue_qty  =  0;
        $recipt_total = 0;
        DB::Connection('mysql2')->beginTransaction();
        try {
 
            // echo "<pre>";
            // print_r($request->all());
            // print_r($mr);
            // exit();
            
            $mr = MaterialRequisition::find($request->mr_id);
            // foreach($request->item as $key=>$item)
            // {
                
            //    $mr_data = new MaterialRequisitionData;
            //    $mr_data->mr_no =  $mr->mr_no;
            //    $mr_data->mr_id =$mr->id;
            //    $mr_data->raw_item_id = $request->item[$key];
            //    $mr_data->category_id = CommonHelper::get_category_by_itemid($request->item[$key]);
            //    $mr_data->issuance_qty = $request->required_qty[$key];
            //    $mr_data->warehouse_id = $request->warehouse_id[$key];
            //    $mr_data->issuance_date =  date('Y-m-d');
            //    $mr_data->mr_status = 1;
            //    $mr_data->save();  
            
            // }
            // foreach($request->recipe_qty  as $key1=>$qty_receipe)
            // {
            //     $recipt_total +=  $qty_receipe;
            // }

            foreach ($request->category as $key => $value) {
                $itemName = "item_${key}_${value}";
                $requiredQtyName = "required_qty_${key}_${value}";
                $warehouseIdName = "warehouse_id_${key}_${value}";
                $issuanceDateName = "issuance_date_${key}_${value}";
                $batchcodeName = "batch_code_${key}_${value}";
                if (!empty($request->$itemName)) {
                    foreach ($request->$itemName as $itemKey => $itemValue) {

                        $validator = Validator::make(
                            [
                                'issuance_qty' => $request->$requiredQtyName[$itemKey],
                                'warehouse_id' => $request->$warehouseIdName[$itemKey],
                                'issuance_date' => $request->$issuanceDateName[$itemKey],
                            ]
                        , [
                            'issuance_qty' => 'required',
                            'warehouse_id' => 'required',
                            'issuance_date' => 'required',
                        ]);

                        if ($validator->fails())
                        {
                            return redirect()->back()->withErrors($validator)->withInput();
                        }
                        $mrData = new MaterialRequisitionData;
                        $mrData->mr_no = $mr->mr_no;
                        $mrData->mr_id = $mr->id;
                        $mrData->raw_item_id = $itemValue;
                        $mrData->category_id = CommonHelper::get_category_by_itemid($itemValue);
                        $mrData->issuance_qty = $request->$requiredQtyName[$itemKey];
                        $mrData->warehouse_id = $request->$warehouseIdName[$itemKey];
                        $mrData->issuance_date = $request->$issuanceDateName[$itemKey];
                        $mrData->batch_code = ($request->has($batchcodeName) && is_array($request->$batchcodeName))? $request->$batchcodeName[$itemKey]: '';
                        $mrData->mr_status = 1;
                        $mrData->material_stage = 1 ;

                        $mrData->save();

                        $id = $mrData->id;



                        $stock=array
                        (
                            'main_id' => $mr->id,
                            'master_id' => $id,
                            'issuence_for_production_id' => $mr->production_id,
                            'voucher_date' => $request->$issuanceDateName[$itemKey],
                            'voucher_type' => 3,
                            'sub_item_id' => $itemValue,
                            'qty' => $request->$requiredQtyName[$itemKey],
                            'batch_code' => ($request->has($batchcodeName) && is_array($request->$batchcodeName))? $request->$batchcodeName[$itemKey]: '',
                            'status'=> 1,
                            'warehouse_id' => $request->$warehouseIdName[$itemKey],
                            'username'=>Auth::user()->username,
                            'created_date'=>date('Y-m-d'),
                            'created_date'=>date('Y-m-d'),
                            'opening'=>0,
                        );
        
        
                        DB::Connection('mysql2')->table('stock')->insert($stock);
                    }
                }
            }
            foreach ($request->category as $key => $value) 
            {
                foreach($request->recipe_qty  as $key1=>$qty_receipe)
                {
                    $recipt_total +=  $qty_receipe;
                }
            }

            $get_mr_data = MaterialRequisitionData::where('mr_id',$request->mr_id)
            ->select(DB::raw('sum(issuance_qty) as issuance_qty'))
            ->groupBy('mr_id')
            ->first();

            $issue_qty =  $get_mr_data->issuance_qty??'0';
            $request_qty = $recipt_total;

            if( $request_qty <= $issue_qty)
            {
            $mm_r  = MaterialRequisition::find($request->mr_id);
            $mm_r->mr_status =  2;
            $mm_r->save();
            }

            DB::Connection('mysql2')->commit();
        }
        catch(\Exception $e)
        {
            DB::Connection('mysql2')->rollback();
            echo "EROOR"; //die();

          echo   $e->getMessage();
            dd($e->getline());
        }
        Session::flash('dataInsert', 'Purchase Request Successfully Saved.');


            return redirect()->route('listMaterialRequisition');
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\MaterialRequisition  $materialRequisition
     * @return \Illuminate\Http\Response
     */
    public function show(MaterialRequisition $materialRequisition)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\MaterialRequisition  $materialRequisition
     * @return \Illuminate\Http\Response
     */
    public function edit(MaterialRequisition $materialRequisition)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\MaterialRequisition  $materialRequisition
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MaterialRequisition $materialRequisition)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\MaterialRequisition  $materialRequisition
     * @return \Illuminate\Http\Response
     */
    public function destroy(MaterialRequisition $materialRequisition)
    {
        //
    }

    public function  getStock(Request $request)
    {
     return  CommonHelper::in_stock_edit($request->item_id,$request->warehouse_id,0);
    }
}
