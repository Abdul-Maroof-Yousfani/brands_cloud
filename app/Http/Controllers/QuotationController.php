<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Quotation;
use App\Models\Quotation_Data;
use App\Models\Demand;
use Auth;
use Illuminate\Support\Arr;
use App\Helpers\CommonHelper;
use App\Helpers\NotificationHelper;
use DB;
use phpDocumentor\Reflection\Types\Void_;

class QuotationController extends Controller
{
    public function __construct(Request $request)
    {

        $this->middleware('auth');
        $this->page='Purchase.Quotation.'.$request->segment(2);
    }

    public function create_quotation(Request $request)
    {

        return view($this->page);

    }


    public function create_quotation_ajax(Request $request)
    {

        $from = $request->from;
        $to = $request->to;

         $data =   DB::Connection('mysql2')->table('demand')
        ->where('status',1)
        ->whereBetween('demand_date', [$from, $to])
        ->where('demand_status',2)
        ->where('quotation_skip',0)
        ->where('quotation_approve',0);


        return view($this->page,compact('data'));

    }

    public function quotation_no($month , $year)
    {
        $quotation_no = '';
        $variable = 100;
        sprintf("%'03d", $variable);
        $str = DB::Connection('mysql2')->selectOne("select max(convert(substr(`voucher_no`,7,length(substr(`voucher_no`,3))-3),signed integer)) reg
        from `quotation` where substr(`voucher_no`,3,2) = " . $year . " and substr(`voucher_no`,5,2) = " . $month . "")->reg;
        $str = $str + 1;
        $str = sprintf("%'03d", $str);
        return  $job_order_no = 'qo' . $year . $month . $str;
    }

    public function get_pr_for_quotaion($id)
    {
       $data= DB::Connection('mysql2')->table('demand as a')
            ->join('demand_data as b','a.id','=','b.master_id')
            ->join('subitem','b.sub_item_id','=','subitem.id')
            ->select('b.*','subitem.is_tax_apply as is_tax_apply_on_product','subitem.tax as tax_in_percent_on_product')
            ->where('a.status',1)
            ->where('a.id',$id);

       return $data;
    }
    public function quotation_form(Request $request)
    {
      
        $id=$request->id;
        $this->check_pr_status($id);

        if ($this->check_pr_status($id)>0):
        return redirect()->back()->with('error', 'Quotation Againts This PR Alreday Approved');
        endif;

        $voucher_no= $this->quotation_no(date('m'),date('y'));
        $request_data = $this->get_pr_for_quotaion($request->id)->where('quotation_id',0)->get();
        return view($this->page,compact('voucher_no','request_data','id'));



    }

     public  function get_pr_no($id)
     {

         return DB::Connection('mysql2')->table('demand')->where('id', $id)->value('demand_no');
     }


       public function check_pr_status($id)
       {
       return    $quotation = Quotation::where('pr_id',$id)->where('status',1)->where('quotation_status',2)->count();
       }
    public function insert_quotation(Request $request)
    {

//        dd($request);
        if ($this->check_pr_status($request->pr_id)>0):
            return redirect()->back()->with('error', 'Quotation Againts This PR Alreday Approved');
        endif;

        DB::Connection('mysql2')->beginTransaction();
        $voucher_no=$this->quotation_no(date('m'), date('y'));
        try {
            $net_after_tax_value = $request->net_after_tax_value_hidden;
            $sales_tax_amount = $request->sales_tax_amount;
            $sales_tax_percentage = ($sales_tax_amount / $net_after_tax_value) * 100;


            $quotation = new Quotation();
            $quotation = $quotation->SetConnection('mysql2');

            $demand = NotificationHelper::get_dept_id('demand','id',$request->pr_id)->select('sub_department_id','p_type')->first();
            

            $quotation->dept_id = $demand->sub_department_id;
            $quotation->p_type = $demand->p_type;


            $quotation->pr_id = $request->pr_id;
            $quotation->pr_no = $this->get_pr_no($request->pr_id);
            $quotation->voucher_no = $voucher_no;
            $quotation->voucher_date = $request->demand_date_1;
            $quotation->vendor_id = $request->supplier;
            $quotation->ref_no = $request->ref_no;






//            $quotation->gst = $request->sales_taxx;
            $quotation->gst = $sales_tax_percentage;
            $quotation->total_amount = $request->net_after_tax_value_hidden-$request->sales_tax_amount;
            $quotation->total_amount_after_sale_tax = $request->net_after_tax_value_hidden;
//            $quotation->gst = $request->sales_tax_amount;
            $quotation->gst_amount = CommonHelper::check_str_replace($request->sales_tax_amount);
            $quotation->date = date('Y-m-d');
            $quotation->status = 1;
            $quotation->username = Auth::user()->name;



            $quotation->description = $request->description_1;
//            $quotation->description = $request->description_1;
            $quotation->save();
            $master_id=$quotation->id;

            $quotation_data = $request->pr_data_id;

            foreach ($quotation_data  as $key => $row):
            $quotation_data = new Quotation_Data();
            $quotation_data = $quotation_data->SetConnection('mysql2');
            $quotation_data->master_id=$master_id;
            $quotation_data->voucher_no=$voucher_no;
            $quotation_data->pr_id=$request->pr_id;
            $quotation_data->quotation_status=1;
            $quotation_data->pr_data_id=$row;
            $quotation_data->qty =$request->input('qty_each_row')[$key];
            $quotation_data->rate =$request->input('rate')[$key];
            $quotation_data->amount =$request->input('amount')[$key];
            $quotation_data->tax_percent =$request->input('tax_in_percent_on_each_row')[$key];
            $quotation_data->tax_per_item_amount =$request->input('tax_in_amount_on_each_row')[$key];
            $quotation_data->tax_total_amount =$request->input('total_tax_on_each_row')[$key];
            $quotation_data->save();

            endforeach;

            $demand_no= DB::Connection('mysql2')->table('demand')->where('id',$request->pr_id)->value('demand_no');
            $subject = 'Purchase Quotation For '.$demand_no; 
            NotificationHelper::send_email('Purchase Quotation','Create',$demand->sub_department_id,$voucher_no,$subject,$demand->p_type);

            DB::Connection('mysql2')->commit();
        }
        catch ( Exception $ex )
        {
            DB::rollBack();
            return self::index($request)->withErrors($ex->getMessage());
        }
        return redirect('quotation/create_quotation')->with('message', 'Quotation Successfully Saved');
    }

    public function quotation_list()
    {

   
        return view($this->page);
    }

    public function quotation_query()
    {
        $type = request()->type;
     return   DB::Connection('mysql2')->table('quotation as a')
            ->join('quotation_data as b','a.id','=','b.master_id')
            ->join('demand as c','a.pr_id','=','c.id')
            ->select('a.*',DB::Connection('mysql2')->raw('SUM(b.amount) As amount'),'c.demand_date','c.quotation_approve')
            ->where('a.status',1)
            ->groupBy('a.id')
            ->when($type == 'pending', function($query) {
                $query->where("a.quotation_status", 1);
            })
            ->orderBy('a.id','Desc')
            ->orderBy('a.pr_no')
            ->get();

           

            
    }

    public function quotation_list_ajax()
    {
        $data=$this->quotation_query();

        
        return view($this->page,compact('data'));
    }

    public function view_quotation(Request $request)
    {
        
        $id=$request->id;
        $quotation= Quotation::where('id',$id)->first();

        $quotation_data = DB::Connection('mysql2')->table('quotation_data as a')
                         ->join('demand_data as b','a.pr_data_id','=','b.id')
                        ->where('a.master_id',$id)
                         ->get();

        return view($this->page,compact('quotation','quotation_data','id'));
    }

    public function approve(Request $request)
    {
        // if ($this->check_pr_status($request->pr_id)>0):
        //     echo 'no';
        //     die;
        // endif;
        $quotation = Quotation::find($request->id);
        $quotation->quotation_status = 2;
        $quotation->approve_username = Auth::user()->name;
        $quotation->save();
        $pr_id = $quotation->pr_id;

        $demand = new Demand();
        $demand = $demand->SetConnection('mysql2');
        $demand=$demand->find($pr_id);
        $demand->quotation_approve=1;
        $demand->save();


        $subject = 'Purchase Quotation Approved'; 
           
        // NotificationHelper::send_email('Purchase Quotation','Create',$sub_department_id,$voucher_no,$subject);
        echo $request->id;
    }


    public function qutation_summary(Request $request)
    {
        $vendor= DB::Connection('mysql2')
         ->table('quotation as a')
        ->join('supplier as b','a.vendor_id','=','b.id')
        ->join('demand as c','c.id','=','a.pr_id')
        ->select('a.pr_id','a.pr_no','b.name','a.vendor_id','c.id as demand_id','a.dept_id','c.demand_no','a.p_type')
        ->where('a.pr_id',$request->id)
        ->where('a.quotation_status',2)
        ->get();

       $demand_data= DB::Connection('mysql2')->table('demand_data as a')
       ->join('quotation_data as c','c.pr_data_id','=','a.id')
       ->join('subitem as b','a.sub_item_id','=','b.id')
       ->select('b.sku_code','b.product_barcode','b.sub_ic','b.product_name','a.id','a.demand_no','c.quotation_status','a.qty','c.vendor','c.id as quotation_id','a.master_id','c.description')
       ->where('a.master_id',$request->id)
       ->where('c.status',1)
       ->groupBy('a.id')
       ->get();


        return view($this->page,compact('vendor','demand_data'));
    }

   public function approved_quotation_summary(Request $request)
   {
    
    DB::Connection('mysql2')->beginTransaction();

    try {

        $vendor= $request->vendor;
        $desc= $request->desc;
        $pr_no= $request->pr_no;
        $dept_id= $request->dept_id;
        $p_type= $request->p_type;
        $master_ids = [];
        foreach ($request->array as $row):

         $data= explode(',',$row);
         $quotation_id = $data[0];
         $pr_data_id = $data[1];
         $pr_id = $data[2];    
         
          $data = array 
          (
            'quotation_status'    => 1 ,
             'vendor' =>  $vendor,
             'description'=>$desc
          );
          DB::Connection('mysql2')->table('quotation_data')
          ->where('id',$quotation_id)
          ->update($data);

          DB::Connection('mysql2')->table('demand_data')
          ->where('id',$pr_data_id)
          ->update(['quotation_id'=>$quotation_id]);

          $quotation_data = DB::connection('mysql2')->table('quotation_data')
                ->where('id', $quotation_id)
                ->first();

                if ($quotation_data && !in_array($quotation_data->master_id, $master_ids)) {
                    $master_ids[] = $quotation_data->master_id;
                }
        endforeach;
DB::connection('mysql2')->table('quotation')
    ->whereIn('id', $master_ids) // status update
    ->update(['quotation_status' => 2]);
DB::connection('mysql2')->table('quotation')
    ->whereIn('pr_id', (array)$pr_id) // array bana kar pass karo
    ->update([
        'comparative_number' => DB::raw("CONCAT('COMP-', id)")
    ]);

$exists = DB::connection('mysql2')->table('demand')
    ->whereIn('id', (array)$pr_id)
    ->exists();

if ($exists) {
    DB::connection('mysql2')->table('demand')
        ->whereIn('id', (array)$pr_id)
        ->update([
            'comparative_number_new' => DB::raw("CONCAT('COMP-', id)")
        ]);
}

        $voucher_no = 'Quotation Against '.$pr_no;
        $subject = 'Purchase Quotation Approved For '.$pr_no;            
        NotificationHelper::send_email('Purchase Quotation','Approve',$dept_id,$voucher_no,$subject,$p_type);
        echo 'Done';

        $this->check_quotation_update($pr_id);

     
        DB::Connection('mysql2')->commit();

    }
    catch(\Exception $e)
    {
        DB::Connection('mysql2')->rollback();
        echo "EROOR"; //die();
        dd($e->getMessage());

    }

   }

   public function check_quotation_update($id = null)
   {
      $demand=  DB::Connection('mysql2')->table('demand_data')->where('master_id',$id)->where('quotation_id',0)->count();

      if ($demand==0):
        DB::Connection('mysql2')->table('demand')->where('id',$id)->update(['quotation_approve'=>1]);
      endif;
      
   }

    public function edit_quotation($pr_id, $q_id)
    {
        $id = $pr_id;
        $quotation = Quotation::find($q_id);
        $quotationData = Quotation_Data::where('master_id',$q_id)->where('status',1)->where('vendor','!=',0)->count();
           

        if ($quotationData > 0) :
            return redirect()->back()->with('error', 'Quotation Againts This PR Alreday Approved');
        endif;

        $voucher_no = $this->quotation_no(date('m'), date('y'));
        $request_data = $this->get_pr_for_quotaion($pr_id)->where('quotation_id', 0)->get();
        return view($this->page, compact('voucher_no', 'request_data', 'id','quotation'));
    }

    public function update_quotation($id,Request $request)
    {
        // dd($request->all());
        DB::Connection('mysql2')->beginTransaction();
        $voucher_no = $this->quotation_no(date('m'), date('y'));
        try {
            $net_after_tax_value = $request->net_after_tax_value_hidden;
            $sales_tax_amount = $request->sales_tax_amount;
            $sales_tax_percentage = ($sales_tax_amount / $net_after_tax_value) * 100;



            $quotation = Quotation::find($id);
            // dd($quotation);
            // $quotation = $quotation->SetConnection('mysql2');

            $demand = NotificationHelper::get_dept_id('demand', 'id', $request->pr_id)->select('sub_department_id', 'p_type')->first();


            $quotation->dept_id = $demand->sub_department_id;
            $quotation->p_type = $demand->p_type;
            $quotation->description = $request->description_1;

            $quotation->pr_id = $request->pr_id;
            $quotation->pr_no = $this->get_pr_no($request->pr_id);
            $quotation->voucher_no = $request->pr_no;
            $quotation->voucher_date = $request->demand_date_1;
            $quotation->vendor_id = $request->supplier;
            $quotation->ref_no = $request->ref_no;
//            $quotation->gst = $request->sales_taxx;
            $quotation->gst = $sales_tax_percentage;

            $quotation->total_amount = $request->net_after_tax_value_hidden-$request->sales_tax_amount;
            $quotation->total_amount_after_sale_tax = $request->net_after_tax_value_hidden;


            $quotation->gst_amount = CommonHelper::check_str_replace($request->sales_tax_amount);
            $quotation->date = date('Y-m-d');
            $quotation->status = 1;
            $quotation->username = Auth::user()->name;
            // dd($quotation);
            $quotation->update();
            $master_id = $id;

            $quotation_data = $request->quotation_data_id;

            foreach ($quotation_data  as $key => $row) :
                $quotation_data = Quotation_Data::find($row);
                $quotation_data = $quotation_data->SetConnection('mysql2');
                $quotation_data->master_id = $master_id;
                $quotation_data->voucher_no = $request->pr_no;
                $quotation_data->pr_id = $request->pr_id;
                $quotation_data->pr_data_id = $request->pr_data_id[$key];
                $quotation_data->qty =$request->input('qty_each_row')[$key];
                $quotation_data->rate =$request->input('rate')[$key];
                $quotation_data->amount =$request->input('amount')[$key];
                $quotation_data->tax_percent =$request->input('tax_in_percent_on_each_row')[$key];
                $quotation_data->tax_per_item_amount =$request->input('tax_in_amount_on_each_row')[$key];
                $quotation_data->tax_total_amount =$request->input('total_tax_on_each_row')[$key];

//
//                $quotation_data->rate = $request->input('rate')[$key];
//                $quotation_data->amount = $request->input('amount')[$key];
            
                $quotation_data->update();
            endforeach;
            // $demand_no = DB::Connection('mysql2')->table('demand')->where('id', $request->pr_id)->value('demand_no');
            // $subject = 'Purchase Quotation For ' . $demand_no;
            // NotificationHelper::send_email('Purchase Quotation', 'Create', $demand->sub_department_id, $voucher_no, $subject, $demand->p_type);
            DB::Connection('mysql2')->commit();
        } catch (Exception $ex) {
            DB::rollBack();
            // return self::index($request)->withErrors($ex->getMessage());
        }
        return redirect('quotation/quotation_list')->with('message', 'Quotation Successfully Update');
    }

    public function delete_quotation(Request $request)
    {
        try {
            $quotationData = Quotation_Data::where('master_id',$request->id)->where('status',1);
            if ($quotationData->where('vendor','!=',0)->count() > 0) {
                return response()->json(['status'=>'error', "message"=> "Cannot remove approved quotation"]);
            }
            $quotationData->where('vendor',0)->update(['status'=>0]);
            Quotation::find($request->id)->update(['status'=>0]);
            return response()->json(['status'=>'Success', "message"=> "Successfully Deleted"]);
        } catch (Exception $th) {
            return response()->json(['status'=>'error', "message"=> $th->getMessage()]);
        }
    }
}