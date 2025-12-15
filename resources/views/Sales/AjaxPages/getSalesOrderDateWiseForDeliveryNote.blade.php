<?php
use App\Helpers\CommonHelper;
use App\Helpers\SalesHelper;
$counter = 1;$total=0;?>

@foreach($sale_order as $row)
    <?php $data=SalesHelper::get_so_amount($row->id); ?>
    <?php $customer=CommonHelper::byers_name($row->buyers_id); ?>
    <tr @if ($row->so_type==1) style="background-color: lightyellow" @endif title="{{$row->id}}" id="{{$row->id}}">
        <td class="text-center">{{$counter++}}</td>
        <td title="{{$row->id}}" class="text-center">@if ($row->so_type==0) {{strtoupper($row->so_no)}} @else {{strtoupper($row->so_no.' ('.$row->description.')')}}@endif</td>
        <!-- <td class="text-center"><?php  echo \Carbon\Carbon::parse($row->so_date)->format("d-M-Y"); ?></td> -->
        <!-- <td class="text-center">{{$row->model_terms_of_payment}}</td> -->
        <!-- <td class="text-center"><?php echo $row->order_no?></td> -->
        <!-- <td class="text-center"><?php  echo \Carbon\Carbon::parse($row->order_date)->format("d-M-Y"); ?></td> -->
           <td class="text-center">
                                                        
                                                    <?php echo $row->timestamp ? \Carbon\Carbon::parse($row->timestamp)->format("d-M-Y") : "";?> <br>
                                                <?php echo $row->timestamp ? \Carbon\Carbon::parse($row->timestamp)->format("h:i:s A") : "";?> 
                                                
                                                </td>
        <td class="text-center"><strong>{{$customer->name}}</strong></td>
        <!-- @php
        $total_tax_ammount = $data->amount/100*$data->sales_tax_rate;


        @endphp -->

        @php
            $lineTotal = $row->total_amount_after_sale_tax + $row->sale_taxes_amount_rate;
            $total += $lineTotal;
        @endphp
        <td class="text-right">{{ number_format($lineTotal, 0) }}</td>

        <!-- <td class="text-right">{{number_format($row->total_amount_after_sale_tax,2)}}<?php $total+=$row->total_amount_after_sale_tax ?></td> -->
        <!-- <td class="text-right">{{number_format($data->amount+$total_tax_ammount,2)}}
           
        </td> -->
 <td  style="text-align:left;"  class="text-center">
                                                        {{ !empty($sales_order->remark) ? $sales_order->remark : '-' }}
                                                    </td>

        <td class="text-center">
            <div class="dropdown">
                <button class="drop-bt dropdown-toggle"type="button" data-toggle="dropdown" aria-expanded="false"><i class="fa-solid fa-ellipsis-vertical"></i></button>
                <ul class="dropdown-menu">
                    <li>
                        <button onclick="showDetailModelOneParamerter('selling/viewSaleOrderPrint/{{$row->id}}',{{$row->id}},'View Sale Order ')" type="button" style="width:100%;" class="btn btn-success btn-xs">View</button>                    
                        <button onclick="delivery_note('<?php echo $row->id?>','<?php echo $m ?>')"type="button" class="btn btn-success btn-xs">Create Delivery Note</button></td>
                    </li>
                </ul>
            </div>
        </td>


   
    </tr>


@endforeach


<tr>
    <td class="text-center" colspan="4" style="font-size: 13px;"><strong>Total</strong></td>
    <td class="text-right" colspan="1" style="font-size: 13px;color: #333"><strong>{{number_format($total,2)}}</strong></td>
    <td class="text-center" colspan="1" style="font-size: 13px;"></td>
 
</tr>


