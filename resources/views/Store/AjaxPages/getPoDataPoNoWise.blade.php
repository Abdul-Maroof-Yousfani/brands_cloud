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


$view=ReuseableCode::check_rights(13);
$edit=ReuseableCode::check_rights(14);
$delete=ReuseableCode::check_rights(15);
$reject=ReuseableCode::check_rights(210);
$counter = 1;

$data ='';

foreach ($purchaseRequestDetail as $row){
    $edit_url= url('/store/editPurchaseRequestVoucherForm/'.$row->id.'?m='.$m);
    $edit_url_direct= url('/store/editDirectPurchaseRequestVoucherForm/'.$row->id.'?m='.$m);

    $paramOne = "stdc/viewPurchaseRequestVoucherDetail";
    $paramTwo = $row->id;
    $paramThree = "d";
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

    $data.='<tr id="tr'.$row->id.'" class="'.$row->id.'"><td class="text-center">'.$counter++.'</td><td class="text-center">'.strtoupper($row->purchase_request_no).'</td><td class="text-center">'.CommonHelper::changeDateFormat($row->purchase_request_date).'</td><td class="text-center">'.$row->trn.'</td><td class="text-center">'.CommonHelper::getMasterTableValueById($m,'sub_department','sub_department_name',$row->sub_department_id).'</td><td>'.CommonHelper::getCompanyDatabaseTableValueById($m,'supplier','name',$row->supplier_id).'</td><td class="text-center">'.StoreHelper::checkVoucherStatus($Tstatus,$row->status).'</td><td class="text-center">'.strtoupper($row->grn_data_status).'</td><td class="text-center">'.strtoupper($row->username).'</td><td class="text-center hidden-print">
            ';

    if ($view==true):
        $data.='<button onclick="showDetailModelOneParamerter(\''.$paramOne.'\',\''.$paramTwo.'\',\''.$paramThree.'\')"   type="button" class="btn btn-success btn-xs">View</button>';
    endif;
    if ($row->status==1 && $row->purchase_request_status!=3):

    endif;
    if ($edit==true)
        if ($row->purchase_request_status==1 || CommonHelper::CheckGrnCount($row->purchase_request_no) == 0):
            if($row->type == 2):
                // $data.='.<a href='.$edit_url_direct.' type="button" class="btn btn-primary btn-xs">Edit</a>';
                $data.='.<a href='.$edit_url.' type="button" class="btn btn-primary btn-xs">Edit</a>';
            else:
                $data.='.<a href='.$edit_url.' type="button" class="btn btn-primary btn-xs">Edit</a>';
            endif;
            if ($delete)
                $data.= '.<button id="'.$row->id.'" type="button" onclick="delete_records(this.id)" class="btn btn-danger btn-xs">Delete</button>';
        endif;

    if($row->purchase_request_status == 2):
        if($reject == true):
            $data.='.<button type="button" class="btn btn-xs btn-danger" id="BtnReject'.$row->id.'" onclick="RejectPo('.$row->id.')">Reject</button>';
        endif;
    endif;
    $data.='</td>
</tr>';
}
?>

<?php
echo $data;
?>