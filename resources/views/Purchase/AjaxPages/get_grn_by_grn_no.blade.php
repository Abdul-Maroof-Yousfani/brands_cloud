<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Helpers\PurchaseHelper;
use App\Helpers\CommonHelper;
use App\Helpers\ReuseableCode;
use Auth;
use DB;
use Config;
use Session;

$view=ReuseableCode::check_rights(20);
$edit=ReuseableCode::check_rights(21);
$delete=ReuseableCode::check_rights(22);

$counter = 1;
$m = $_GET['m'];
$data ='';

$OverAllTot = 0;
$total_pi=0;
$total_return_grn=0;
$total_return_pi=0;


foreach ($goodsReceiptNoteDetail as $row)
{
    $addAmount = DB::Connection('mysql2')->table('addional_expense')->where('main_id',$row->id)->select('amount')->sum('amount');
    $ntAmount = DB::Connection('mysql2')->table('grn_data')->where('master_id',$row->id)->select('net_amount')->sum('net_amount');
    $pi_no=DB::Connection('mysql2')->table('new_purchase_voucher')->where('status',1)->where('grn_id',$row->id)->select('pv_no')->value('pv_no');
    $pi_date=DB::Connection('mysql2')->table('new_purchase_voucher')->where('status',1)->where('grn_id',$row->id)->select('pv_date')->value('pv_date');
    $net_amount = $ntAmount+$addAmount;
    //  $pi_amount= ReuseableCode::pi_get_net_amount($row->id);
    // $pi_date=$pi_amount->pv_date;
    // $pi_amount=$pi_amount->net_amount;
    //   $return_amount_grn= ReuseableCode::return_amount($row->id,1);
    //   $return_amount_pi= ReuseableCode::return_amount($row->id,2);
    //   $total_pi+=$pi_amount;
    //   $total_return_grn+=$return_amount_grn;
    //   $total_return_pi+=$return_amount_pi;
    $wrong='';
    //  if ($net_amount!=$pi_amount):
    //   $wrong='';
    //   endif;

    $OverAllTot+=$net_amount;

    if($row->type==0 || $row->type == 5):
        $paramOne = "pdc/viewGoodsReceiptNoteDetail";
        $edit_url= url('/purchase/editGoodsReceiptNoteVoucherForm/'.$row->id.'/'.$row->grn_no.'?m='.$m);
    else:
        $paramOne = "pdc/viewGoodsReceiptNoteWPODetail";
        $edit_url= url('/purchase/editGoodsReceiptNoteWithoutPOForm/'.$row->id.'?m='.$m);
    endif;
    $paramTwo = $row['grn_no'];
    $paramThree = "View Goods Receipt Note Voucher Detail";
    $paramFour = "purchase/editGoodsReceiptNoteVoucherForm";

    if($row->po_no!=""){
        $po_no = $row->po_no;
        $po_date = CommonHelper::changeDateFormat($row->po_date);
    } else{
        $po_no = "Direct";
        $po_date = '---';
    }

    if($row->type==0){ $type = "Purchase"; } elseif($row->type==2){ $type = "Direct"; }elseif($row->type==5){ $type = "Import"; } else{ $type = "Transfer"; }

    $data.='<tr  id="RemoveTr'.$row->id.'"><td class="text-center">'.$counter++.'</td><td class="text-center">'.strtoupper($row->grn_no).'</td><td class="text-center">'.strtoupper($pi_no).'<br>'.CommonHelper::changeDateFormat($pi_date).'</td><td class="text-center">'.CommonHelper::changeDateFormat($row->grn_date).'</td><td class="text-center">'.strtoupper($po_no).'</td><td class="text-center">'.$row->supplier_invoice_no.'</td><td class="text-center">'.CommonHelper::getCompanyDatabaseTableValueById($m,'supplier','name',$row->supplier_id).'</td><td class="text-right">'.number_format($net_amount,2).'</td><td class="text-center '.$row->id.'">'.PurchaseHelper::checkVoucherStatus($row->grn_status,$row->status,$row->id).'</td><td class="text-center">'.strtoupper($row->username.' '.$wrong).'</td><td class="text-center">';
    //$data.='<a onclick="showDetailModelOneParamerter(\''.$paramOne.'\',\''.$paramTwo.'\',\''.$paramThree.'\')" class="btn btn-xs btn-success"><span class="glyphicon glyphicon-eye-open"></span></a>';

    if ($view==true)
        if($row->type==0):
            $data.='<button onclick="showDetailModelOneParamerter(\''.$paramOne.'\',\''.$paramTwo.'\',\''.$paramThree.'\')"   type="button" class="btn btn-success btn-xs">View</button>';
        else:
            $data.='<button onclick="showDetailModelOneParamerter(\''.$paramOne.'\',\''.$paramTwo.'\',\''.$paramThree.'\')"   type="button" class="btn btn-success btn-xs">View</button>';
        endif;

    //$data.='.<a href='.$edit_url.' type="button" class="btn btn-primary btn-xs">Edit</a>';

    if ($edit==true):
        if($row->grn_status==1 && $row->status == 1 && $row->type!=3 && $row->type !=5):
            $data.='.<a href='.$edit_url.' type="button" class="btn btn-primary btn-xs">Edit</a>';
        endif;
    endif;

    if ($delete==true):
        if($row->grn_status==1 && $row->status == 1 && $row->type!=3 && $row->type !=5):
            $data.='<button id="'.$row->id.'" type="button" onclick="MasterDeleteGrn('.$row->id.')" class="btn btn-danger btn-xs">Delete</button>';


            $data.'</td></tr>';



        elseif($row->grn_status==2 && $row->status == 1 && $row->type!=3):
            $data.='.<button id="'.$row->id.'" type="button" onclick="MasterDeleteGrn('.$row->id.')" class="btn btn-danger btn-xs">Delete</button>';
            $data.'</td></tr>';
        elseif($row->type==5):
            $data.='.<button id="'.$row->id.'" type="button" onclick="MasterDeleteGrn('.$row->id.')" class="btn btn-danger btn-xs">Delete</button>';
            $data.'</td></tr>';
        elseif($row->type==3):
            $data.='.<button id="'.$row->id.'" type="button" onclick="alert(`Transfer Entry Can not Be Deleted`)" class="btn btn-danger btn-xs">Delete</button>';
            $data.'</td></tr>';

        else:
            $data.='</td></tr>';
        endif;
    else:
        $data.='</td></tr>';
    endif;

}
$data.= '<tr><td colspan="6"></td><td>'.number_format($OverAllTot,2).'</td><td>'.number_format($total_return_grn,2).'</td><td>'.number_format($total_pi,2).'</td><td>'.number_format($total_return_pi,2).'</td></tr>';
?>

<?php
echo $data;
?>