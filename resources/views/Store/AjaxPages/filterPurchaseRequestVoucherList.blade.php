<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Helpers\StoreHelper;
use App\Helpers\CommonHelper;
use App\Helpers\ReuseableCode;
use Auth;
use DB;
use Config;
use Session;


//$view=ReuseableCode::check_rights(3);
$counter = 1;
$m = $_GET['m'];
$selectSubDepartment = $_GET['selectSubDepartment'];
$selectVoucherStatus = $_GET['selectVoucherStatus'];
$selectSupplier = $_GET['selectSupplier'];

$view=ReuseableCode::check_rights(13);
$edit=ReuseableCode::check_rights(14);
$delete=ReuseableCode::check_rights(15);
$reject=ReuseableCode::check_rights(210);
$total=0;

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
    $voucherStatusTitle = 'Approve Vouchers';
}
$fromDate = $_GET['fromDate'];
$toDate = $_GET['toDate'];
$data ='';
$data .='<tr><td colspan="10" class="text-center"><strong>Filter By : (Suppliers => '.$selectSupplierTitle.')&nbsp;&nbsp;,&nbsp;&nbsp;(From Date => '.CommonHelper::changeDateFormat($fromDate).')&nbsp;&nbsp;,&nbsp;&nbsp;(To Date => '.CommonHelper::changeDateFormat($toDate).')&nbsp;&nbsp;,&nbsp;&nbsp;(Voucher Status => '.$voucherStatusTitle.')</strong></td></tr>';
foreach ($purchaseRequestDetail as $row){
    $edit_url= url('/store/editPurchaseRequestVoucherForm/'.$row->id.'?m='.$m);
    $edit_url_direct= url('/purchase/editDirectPurchaseOrder/'.$row->id.'?m='.$m);
    $net_amount= ReuseableCode::get_po_total_amount($row->id);
    $total+=$net_amount;
    $paramOne = "stdc/viewPurchaseRequestVoucherDetail";
    $paramTwo = $row->purchase_request_no;
    $paramThree = "View Purchase Order Detail";
    $Tstatus = '';
    if($row->purchase_request_status == 3)
        {
            if(CommonHelper::CheckGrnCount($row->purchase_request_no) == 0)
            {
                $Tstatus = 2;
            }
            else
            {
                $Tstatus = $row->purchase_request_status;
            }
        }
    else
        {
            $Tstatus = $row->purchase_request_status;
        }

    $data.='<tr id="tr'.$row->id.'" class="'.$row->id.'"><td class="text-center">'.$counter++.'</td><td class="text-center">'.strtoupper($row->purchase_request_no).'</td><td class="text-center">'.CommonHelper::changeDateFormat($row->purchase_request_date).'</td><td class="text-center">'.$row->trn.'</td><td class="text-center">'.$row->remarks.'</td><td>'.CommonHelper::getCompanyDatabaseTableValueById($m,'supplier','name',$row->supplier_id).'</td><td class="text-center">'.StoreHelper::checkVoucherStatus($Tstatus,$row->status).'</td><td class="text-center">'.strtoupper($row->grn_data_status).'</td><td class="text-center">'.strtoupper($row->username).'</td>'.'</td><td class="text-center">'.number_format($net_amount,2).'</td><td class="text-center hidden-print">
            ';

    if ($view==true):
        $data.='<button onclick="showDetailModelOneParamerter(\''.$paramOne.'\',\''.$paramTwo.'\',\''.$paramThree.'\')"   type="button" class="btn btn-success btn-xs">View</button>';
         $data .= '&nbsp<a class="btn btn-infoo btn-xs" href="' . route('purchase_order') . '">Print</a>';

        endif;
    if ($row->status==1 && $row->purchase_request_status!=3):
     
    endif;
    if ($edit==true)
    if ($row->purchase_request_status==1 || CommonHelper::CheckGrnCount($row->purchase_request_no) == 0):
        if($row->type == 2):
            $data.='.<a href='.$edit_url_direct.' type="button" class="btn btn-primary btn-xs">Edit</a>';
            else:
            $data.='.<a href='.$edit_url.' type="button" class="btn btn-primary btn-xs">Edit</a>';
        endif;
        if ($delete)
    $data.= '.<button id="'.$row->id.'" type="button" onclick="delete_records('.$row->id.','.'2)" class="btn btn-danger btn-xs">Delete</button>';
        endif;
        

if($row->purchase_request_status == 2):
    if($reject == true):
        $data.='.<button type="button" class="btn btn-xs btn-danger" id="BtnReject'.$row->id.'" onclick="RejectPo('.$row->id.')">Reject</button>';
    endif;
endif;
    $data.='</td>
</tr>';
}
$data.='<tr style="font-size: large;font-weight: bold"><td colspan="9">Total</td><td>'.number_format($total,2).'</td></tr>'
?>

<?php
echo json_encode(array('data' => $data));
?>
