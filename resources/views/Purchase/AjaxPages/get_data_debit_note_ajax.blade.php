<?php
use App\Helpers\CommonHelper;
use App\Helpers\ReuseableCode;


$view=ReuseableCode::check_rights(31);
$edit=ReuseableCode::check_rights(32);
$delete=ReuseableCode::check_rights(33);
        $Claus = '';
        if($VoucherType == 'all')
            {
                $Claus = "1".","."2";
            }
        else{
            $Claus = $VoucherType;
        }

CommonHelper::companyDatabaseConnection($m);
        if($SupplierId == 'all'):
            //$MasterData = DB::table('purchase_return')->where('status', '=', 1)->whereIn('type',array($Claus))
              //      ->whereBetween('pr_date', [$fromDate, $to])->orderBy('id', 'desc')->get();
            $MasterData = DB::select('select * from purchase_return where status = 1 and pr_date BETWEEN "'.$fromDate.'" and "'.$to.'" and type in ('.$Claus.') order by id desc');
        else:
            //$MasterData = DB::table('purchase_return')->where('status', '=', 1)
              //      ->whereBetween('pr_date', [$fromDate, $to])->where('supplier_id',$SupplierId)
                //    ->orderBy('id', 'desc')->get();
            $MasterData = DB::select('select * from purchase_return where status = 1 and pr_date BETWEEN "'.$fromDate.'" and "'.$to.'" and type in ('.$Claus.') and supplier_id = '.$SupplierId.' order by id desc');
        endif;
CommonHelper::reconnectMasterDatabase();

$Counter = 1;
$paramOne = "pdc/viewPurchaseReturnDetail?m=".$m;
$paramThree = "View Issuance Detail";
$total_return=0;
        $total_net_stock=0;
foreach($MasterData as $Fil):
$edit_url= url('/purchase/editPurchaseReturnForm/'.$Fil->id.'/'.$Fil->pr_no.'?m='.$m);
$net_stock = DB::Connection('mysql2')->table('stock')->where('voucher_no',$Fil->pr_no)->select('amount')->sum('amount');
$tr = DB::Connection('mysql2')->table('transactions')->where('voucher_no',$Fil->pr_no)->where('status',1)->where('debit_credit',1)->select('amount')->sum('amount');
$total_net_stock+=$net_stock;
    //  $return_amount=  ReuseableCode::return_amount($Fil->grn_id,$Fil->type);
$po_no=     DB::Connection('mysql2')->table('goods_receipt_note')->where('grn_no',$Fil->grn_no)->value('po_no');
?>
<tr class="text-center" id="RemoveTr<?php echo $Fil->id?>">
    <td><?php echo $Counter++;?></td>
    <td><?php echo strtoupper($Fil->pr_no);?></td>
    <td><?php echo CommonHelper::changeDateFormat($Fil->pr_date);?></td>
    <td><?php echo CommonHelper::getCompanyDatabaseTableValueById($m,'supplier','name',$Fil->supplier_id);?></td>
    <td><?php echo strtoupper($Fil->grn_no.'</br>'.$po_no);?></td>
    <td><?php echo CommonHelper::changeDateFormat($Fil->grn_date);?></td>
    <td><?php echo $Fil->remarks;?></td>
    <td class="text-right">{{number_format($tr,2)}}</td>
    <td class="text-right">{{number_format($net_stock,2)}}</td>
    <td><?php if ($Fil->type==1): echo 'GRN'; elseif($Fil->type==2): echo 'Purchase Invoice';   endif;?></td>
    <td>
        @if($view==true)
            <button onclick="showDetailModelOneParamerter('<?php echo $paramOne?>','<?php echo $Fil->pr_no;?>','View Purchase Return Detail')"   type="button" class="btn btn-success btn-xs">View</button>
        @endif
        @if($edit==true)
            <a href='<?php echo $edit_url;?>' type="button" class="btn btn-primary btn-xs">Edit</a>
        @endif
        @if($delete==true)
            <button type="button" class="btn btn-danger btn-xs" id="BtnDelete<?php echo $Fil->id?>" onclick="DeletePurchaseReturn('<?php echo $Fil->id?>','<?php echo $Fil->pr_no?>')">Delete</button>
        @endif
    </td>
    <?php $total_return+=$tr; ?>
</tr>

<?php endforeach;?>
<tr>
    <td colspan="7">Total</td>
    <td >{{number_format($total_return),2}}</td>
    <td >{{number_format($total_net_stock),2}}</td>
</tr>
