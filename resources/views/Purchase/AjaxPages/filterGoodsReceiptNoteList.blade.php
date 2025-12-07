<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Helpers\PurchaseHelper;
use App\Helpers\CommonHelper;
use App\Helpers\ReuseableCode;
use App\Models\PurchaseRequest;

use Auth;
use DB;
use Config;
use Session;

$counter = 1;
$m = $_GET['m'];
$data ='';
$selectSubDepartment = $_GET['selectSubDepartment'];
$selectVoucherStatus = $_GET['selectVoucherStatus'];
$selectSupplier = $_GET['selectSupplier'];


$close=ReuseableCode::check_rights(524);

$view=ReuseableCode::check_rights(20);
$edit=ReuseableCode::check_rights(21);
$delete=ReuseableCode::check_rights(22);
$inspection=ReuseableCode::check_rights(23);


if(!empty($selectSubDepartment)){
    $selectSubDepartmentTitle = $selectSubDepartment;
}else{
    $selectSubDepartmentTitle = 'All Department';
}

if(!empty($selectSupplier)){
    $selectSupplierTitle = $selectSupplier;
}else{
    $selectSupplierTitle = 'All Suppliers';
}
$voucherStatusTitle='';
if($selectVoucherStatus == '0'){
    $voucherStatusTitle = 'All Vouchers';
}else if($selectVoucherStatus == '1'){
    $voucherStatusTitle = 'Pending Vouchers';
}else if($selectVoucherStatus == '2'){
    $voucherStatusTitle = 'Approve Vouchers';
}else if($selectVoucherStatus == '3'){
    $voucherStatusTitle = 'Deleted Vouchers';
}
else if($selectVoucherStatus == '4'){
    $voucherStatusTitle = 'Invoice Created';
}

$fromDate = $_GET['fromDate'];
$toDate = $_GET['toDate'];

$OverAllTot = 0;
$total_pi=0;
$total_return_grn=0;
$total_return_pi=0;


foreach ($goodsReceiptNoteDetail as $row)
{
    $addAmount = DB::Connection('mysql2')->table('addional_expense')->where('main_id',$row->id)->select('amount')->sum('amount');
    $ntAmount = DB::Connection('mysql2')->table('grn_data')->where('master_id',$row->id)->select('net_amount','rate','')->sum('net_amount');
    $checkifbarcodescanningproduct = DB::connection('mysql2')->table('grn_data')
        ->join('subitem', 'grn_data.sub_item_id', '=', 'subitem.id')
        ->where('subitem.is_barcode_scanning', 1)
        ->where('grn_data.master_id', $row->id)
        ->select('grn_data.net_amount', 'grn_data.rate') // Add required columns to select
        ->count();


    $grn_data = DB::Connection('mysql2')->table('grn_data')->where('master_id',$row->id)->first();
    $pi_no=DB::Connection('mysql2')->table('new_purchase_voucher')->where('status',1)->where('grn_id',$row->id)->select('pv_no')->value('pv_no');
    $pi_date=DB::Connection('mysql2')->table('new_purchase_voucher')->where('status',1)->where('grn_id',$row->id)->select('pv_date')->value('pv_date');
    $net_amount = $ntAmount;
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
    $qc='pdc/qc';
    $paramTwo = $row['grn_no'];
    $paramThree = "View Goods Receipt Note Voucher Detail";
    $paramFour = "purchase/editGoodsReceiptNoteVoucherForm";
    $stockBarcodeurl = route('stockBarcode.show',$row->grn_no).'?type=grn';

    if($row->po_no!=""){
        $po_no = $row->po_no;
        $po_date = CommonHelper::changeDateFormat($row->po_date);
    } else{
        $po_no = "Direct";
        $po_date = '---';
    }

    if($row->type==0){ $type = "Purchase"; } elseif($row->type==2){ $type = "Direct"; }elseif($row->type==5){ $type = "Import"; } else{ $type = "Transfer"; }


    // $data.='<tr  id="RemoveTr'.$row->id.'"><td class="text-center">'.$counter++.'</td><td class="text-center">'.strtoupper($row->grn_no).'</td><td class="text-center">'.strtoupper($pi_no).'<br>'.CommonHelper::changeDateFormat($pi_date).'</td><td class="text-center">'.CommonHelper::changeDateFormat($row->grn_date).'</td><td class="text-center">'.strtoupper($po_no).'</td><td class="text-center">'.$row->supplier_invoice_no.'</td><td class="text-center">'.CommonHelper::getCompanyDatabaseTableValueById($m,'supplier','name',$row->supplier_id).'</td><td class="text-right">'.number_format($net_amount,2).'</td><td class="text-center '.$row->id.'">'.PurchaseHelper::checkVoucherStatus($row->grn_status,$row->status,$row->id).'</td><td class="text-center '.$row->id.'">'.$grn_data->received_type.'</td><td class="text-center">'.strtoupper($row->username.' '.$wrong).'</td><td class="text-center">';
    $data .= '<tr id="RemoveTr' . $row->id . '">
    <td class="text-center">' . $counter++ . '</td>
    <td class="text-center">' . strtoupper($row->grn_no) . '</td>
    <td class="text-center">' . strtoupper($pi_no) . '<br>(' . CommonHelper::changeDateFormat($pi_date) . ')</td>
    <td class="text-center">' . CommonHelper::changeDateFormat($row->grn_date) . '</td>
    <td class="text-center">' . strtoupper($po_no) . '</td>
    <td class="text-center">' . $row->supplier_invoice_no . '</td>
    <td class="text-center">' . CommonHelper::getCompanyDatabaseTableValueById($m, 'supplier', 'name', $row->supplier_id) . '</td>
    <td class="text-right">' . number_format($net_amount, 2) . '</td>
    <td class="text-center ' . $row->id . '">' . PurchaseHelper::checkVoucherStatus($row->grn_status, $row->status, $row->id) . '</td>
    <td class="text-center ' . $row->id . '">' .strtoupper($row->grn_data_status). '</td>
    <td class="text-center">' . strtoupper($row->username . ' ' . $wrong) . '</td>
    <td class="text-center">';
  
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
            $data.='<a href='.$edit_url.' type="button" class="btn btn-primary btn-xs">Edit</a>';
        endif;
        if($checkifbarcodescanningproduct > 0):
            $data.='<a href='.$stockBarcodeurl.' type="button" class="btn btn-warning btn-xs" target="_blank">Barcodes</a>';
        endif;
    endif;

    if ($inspection==true && $row->grn_status==1):
    $data.='<button onclick="showDetailModelOneParamerter(\''.$qc.'\',\''.$paramTwo.'\',\''.$paramThree.'\')"   type="button" class="btn btn-success btn-xs">Approve</button>';
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

    $purchaseRequest = PurchaseRequest::where(['purchase_request_no'=> $row->po_no ], ['status'=>1])->first();
    if(isset($purchaseRequest->purchase_request_status) && $purchaseRequest->purchase_request_status != 3 && $grn_data->received_type == "Partial"){
        if($close){
            $data .= '<a href="' . url("pdc/closeGrn/$row->id") . '" type="button" class="btn btn-primary btn-xs">Close</a>';
        }
    }

}
$data.= '<tr><td colspan="7"></td><td>'.number_format($OverAllTot,2).'</td><td>'.number_format($total_return_grn,2).'</td><td>'.number_format($total_pi,2).'</td><td>'.number_format($total_return_pi,2).'</td></tr>';
?>

<?php
echo json_encode(array('data' => $data));
?>
