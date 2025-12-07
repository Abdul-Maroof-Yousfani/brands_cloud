<?php
use App\Helpers\CommonHelper;
use App\Helpers\ReuseableCode;
// echo "<pre>";
// print_r($category);
// exit();
?>

    <h2 style="text-align: center">Stock Summary Report</h2>




<table id="data" class="table table-bordered table-responsive">
            <h4>{{$category_heading}}</h4>
            <h4>{{$item_heading}}</h4>
            <h4>{{$location_heading}}</h4>
            <h4>{{$sub_item_heading}}</h4>
            <h4>{{$sub_item_des_heading}}</h4>
<label for="">Show Detail</label>
<input type="checkbox" id="CheckUnCheck" onclick="ShowHideDetail()">

            <thead>
            <th class="text-center">S.No</th>
            <th class="text-center">Item</th>
            <th class="text-center">UOM</th>
            <th class="text-center">Pack size</th>
            <th class="text-center">Pack size UOM</th>
            <th class="text-center">Number of Bag</th>
            <th class="text-center">Location</th>
            <th class="text-center ShowHideTd" style="display: none">Average Cost</th>

            <th class="text-center ShowHideTd" style="display: none">Purchase QTY.</th>
            <th class="text-center ShowHideTd" style="display: none">Produce QTY By Production</th>
            <th class="text-center ShowHideTd" style="display: none">Purchase Return.</th>
            <th class="text-center ShowHideTd" style="display: none">Transferd QTY.</th>
            <th class="text-center ShowHideTd" style="display: none">Consumption  QTY.</th>
            <th class="text-center ShowHideTd" style="display: none">Issuence Against Production Plan</th>
            <th class="text-center ShowHideTd" style="display: none">Return Against Production Plan</th>

            <th class="text-center ShowHideTd" style="display: none">Sales QTY.</th>
            <th class="text-center ShowHideTd" style="display: none">Sales Return QTY.</th>
            <th class="text-center">In Stock</th>

            </thead>
            <tbody id="filterDemandVoucherList">
            <?php
            $counter=1;
            $item_id  =[];
            ?>
            @foreach($category as $data)
                <?php
                      

                    
                $sub_ic_data=CommonHelper::get_subitem_detail($data->sub_item_id);
                $sub_ic_data=explode(',',$sub_ic_data);
                $uom=$sub_ic_data[0];
                $sub_item_id=$sub_ic_data[4];


                $purchase_amount= CommonHelper::get_amount_from_stock(1,$data->sub_item_id,$data->warehouse_id);
                $produce_qty= CommonHelper::get_amount_from_stock(11,$data->sub_item_id,$data->warehouse_id);
                $purchase_return= CommonHelper::get_amount_from_stock(2,$data->sub_item_id,$data->warehouse_id);
                $stock_tarnsfer= CommonHelper::get_amount_from_stock(3,$data->sub_item_id,$data->warehouse_id);
                $stock_consumption= CommonHelper::get_amount_from_stock(8,$data->sub_item_id,$data->warehouse_id);
                $stock_received= CommonHelper::get_amount_from_stock(4,$data->sub_item_id,$data->warehouse_id);
                $sales_qty= CommonHelper::get_amount_from_stock(5,$data->sub_item_id,$data->warehouse_id);
                $sales_return_qty= CommonHelper::get_amount_from_stock(6,$data->sub_item_id,$data->warehouse_id);
                $issuence_against_plan_qty= CommonHelper::get_amount_from_stock(9,$data->sub_item_id,$data->warehouse_id);
                $return_against_plan_qty= CommonHelper::get_amount_from_stock(10,$data->sub_item_id,$data->warehouse_id);


                        // for amount
                $purchase_value= CommonHelper::get_value_stock(1,$data->sub_item_id,$data->warehouse_id);
                $produce_amount= CommonHelper::get_value_stock(11,$data->sub_item_id,$data->warehouse_id);
                $purchase_value_return= CommonHelper::get_value_stock(2,$data->sub_item_id,$data->warehouse_id);
                $stock_value_transfer= CommonHelper::get_value_stock(3,$data->sub_item_id,$data->warehouse_id);
                $stock_value_received= CommonHelper::get_value_stock(4,$data->sub_item_id,$data->warehouse_id);
                $sales_value= CommonHelper::get_value_stock(5,$data->sub_item_id,$data->warehouse_id);
                $sales_value_return= CommonHelper::get_value_stock(6,$data->sub_item_id,$data->warehouse_id);
                $issuence_against_plan_amount= CommonHelper::get_value_stock(9,$data->sub_item_id,$data->warehouse_id);
                $return_against_plan_amount= CommonHelper::get_value_stock(10,$data->sub_item_id,$data->warehouse_id);
                        //end

                 

                if ($purchase_amount >0 || $purchase_return>0 || $stock_tarnsfer>0 || $sales_qty>0 || $issuence_against_plan_amount >0 || $return_against_plan_qty>0 || $produce_qty>0):
                   $actual_qtyt= $purchase_amount-$purchase_return-$stock_tarnsfer+$stock_received-$sales_qty+$sales_return_qty-$issuence_against_plan_qty+$return_against_plan_qty+$produce_qty;
                   $actual_amount=$purchase_value-$purchase_value_return-$stock_value_transfer+$stock_value_received-$sales_value-$issuence_against_plan_amount+$return_against_plan_amount+$produce_amount;
                    if ($actual_amount>0):
                        if ($actual_qtyt==0):
                            $actual_qtyt=1;
                            endif;
                     $average=$actual_amount / $actual_qtyt;
                        else:
                        $average=0;
                        endif;
                ?>
                <tr>
                <td class="text-center">{{$counter++}}</td>
                <td class="text-center">
                    <a class="" href="<?php echo url('/') ?>/store/fullstockReportView?pageType=&&parentCode=97&&m=<?php echo $_GET['m'] ?>&&sub_item_id=<?php echo $data->sub_item_id ?>&&warehouse_id=<?php echo $data->warehouse_id ?>#SFR" target="_blank">
                        {{ CommonHelper::get_item_name($data->sub_item_id) }}</a>
                </td>

                <?php $in_stock=$purchase_amount+$produce_qty-$purchase_return-$stock_tarnsfer-$stock_consumption-$sales_qty+$sales_return_qty-$issuence_against_plan_qty+$return_against_plan_qty; ?>
                <td class="text-center">{{CommonHelper::get_uom_name($uom)}}</td>
                <td class="text-center">{{ $data->pack_size }}</td>
                <td class="text-center">{{ ($data->uom2) ? CommonHelper::get_uom_name($data->uom2) : '' }}</td>
                <td class="text-center">{{number_format( ( $in_stock / ($data->pack_size ?? 1) ) ,2)}}</td>
                <td class="text-center">{{CommonHelper::get_name_warehouse($data->warehouse_id)}} <?php  ?></td>
                <td class="text-center ShowHideTd" style="display: none;">{{number_format($average,2)}}</td>
                <td class="text-center ShowHideTd" style="display: none;">{{number_format($purchase_amount,2)}}</td>
                <td class="text-center ShowHideTd" style="display: none;">{{number_format($produce_qty,2)}}</td>


                <td class="text-center ShowHideTd" style="display: none;">  <?php echo  number_format($purchase_return,2); ?></td>
                    <td class="text-center ShowHideTd" style="display: none;">  <?php echo  number_format($stock_tarnsfer,2); ?></td>
                    <td class="text-center ShowHideTd" style="display: none;">  <?php echo  number_format($stock_consumption,2); ?></td>
                    <td class="text-center ShowHideTd" style="display: none;">  <?php echo  number_format($issuence_against_plan_qty,2); ?></td>
                    <td class="text-center ShowHideTd" style="display: none;">  <?php echo  number_format($return_against_plan_qty,2); ?></td>
                    <td class="text-center ShowHideTd" style="display: none;">  <?php echo  number_format($sales_qty,2); ?></td>
                    <td class="text-center ShowHideTd" style="display: none;">  <?php echo  number_format($sales_return_qty,2); ?></td>

                <td class="text-center"> <?php
                    echo number_format($in_stock,2);
                    ?></td>
                </tr>
                <?php endif; ?>
            @endforeach
    
            </tbody>
        </table>
        @include('Store.AjaxPages.quarintine_stock')
<script !src="">
    function ShowHideDetail()
    {
        //alert(); return false;
        if($('#CheckUnCheck').is(":checked"))
        {
            $('.ShowHideTd').fadeIn();
        }
        else{
            $('.ShowHideTd').fadeOut();
        }
    }
</script>

