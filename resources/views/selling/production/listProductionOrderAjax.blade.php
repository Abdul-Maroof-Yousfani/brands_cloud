<?php
use App\Helpers\CommonHelper;
use Illuminate\Support\Facades\DB;
$i=1;
?>
@foreach($productions as $production)
<?php

$customer = CommonHelper::byers_name($production->customer);

$customer = (!empty($customer)) ? $customer->name : '' ;

?>
<tr>
    <td>{{$i}}</td>
    <td>{{$production->sale_order_no}}</td>
    <td>{{$production->order_no}}</td>
    <td>{{$customer}}</td>
    <td>{{ CommonHelper::get_item_by_id($production->finish_goods_id)->sub_ic}}</td>
    <td>{{ $production->planned_qty}}</td>
    <td>{{ $production->start_date}}</td>
    <td>{{ $production->delivery_date}}</td>
  
    <td>
        <div class="dropdown">
            <button class="drop-bt dropdown-toggle"
                type="button" data-toggle="dropdown"
                aria-expanded="false">
                ...
            </button>
            <ul class="dropdown-menu">
                <li>

                    <a href="editProductionOrder?id=<?php echo $production->pp_id?>" class="btn btn-sm btn-warning "><i class="fa fa-pencil" aria-hidden="true"></i>  Edit</a>
                    
                    <a href="#" class="btn btn-sm btn-danger"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</a>
                    {{-- @if($production->ppc_status == '0')
                    <a  class="btn btn-infoo" href="{{route('createMaterialRequisition',$production->p_p_d_id)}}"> <i class="fa fa-plus" aria-hidden="true"></i> Create MR</a>
                    @endif --}}
                          <a  class="btn btn-sm btn-success" onclick="showDetailModelTwoParamerter('selling/viewProductionOrder/','<?php echo $production->pp_id?>','view Production Order','')">
                    <i class="fa fa-eye" aria-hidden="true"></i>  View</a>  
                          <a  class="btn btn-sm btn-success" onclick="showDetailModelTwoParamerter('selling/viewProductionOrderPrint/','<?php echo $production->pp_id?>','view Production Order','')">
                    <i class="fa fa-eye" aria-hidden="true"></i>  Print</a>  
                        



                    <a  class="btn btn-sm btn-success" onclick="approveProductionPlanMr( {{$production->pp_id}} , {{$production->p_p_d_id}} , {{$production->finish_goods_id}} )">Approved </a>
                    
                  
                    
                </li>
            </ul>
        </div>
    </td>
</tr>
@php
$i++;
@endphp
@endforeach


<script>
   function approveProductionPlanMr(production_id,production_plan_data_id,finish_good_id)
    {
        $.ajax({
                url: '/selling/approveProductionPlanMr',
                type: 'Get',
                data: {
                    production_id:production_id,
                    production_plan_data_id:production_plan_data_id,
                    finish_good_id:finish_good_id,
                    },
                success: function (response) {
                    if(response == 1)
                    {
                        window.location.reload();

                    }
                }   
            });
    }
</script>