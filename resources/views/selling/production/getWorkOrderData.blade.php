<?php
use App\Helpers\CommonHelper;
use Illuminate\Support\Facades\DB;
$receipe='';
?>

@foreach($prodtion_datas as $prodtion_data)

<?php      
    
  $production_bom =  DB::connection('mysql2')->table('production_bom')
->where('finish_goods',$prodtion_data->item_id)->get(); 

?>
<tbody id="row_of_data" class="row_of_data receipe_main recipe_details">
        <tr style="background-color: darkgray">
              <th><input type="checkbox" onchange="toggleRow(this)" /></th>
              <th>
                Product
              </th>
              <th>Order Qty</th>
              <th>Start Date</th>
              <th>Delivery Date</th>
           
        </tr>

      @php 
      $item = CommonHelper::get_item_by_id($prodtion_data->item_id);
      @endphp

  <tr>
    <td></td>
    <td>{{$item->sub_ic}}</td>
    <td>
      {{$prodtion_data->qty}}
      <input
        type="hidden"
        name="work_data_id[]"
        value="{{ $prodtion_data->id}}"
      />
      <input
        type="hidden"
        value="{{$prodtion_data->item_id}}"
        name="finish_good[]"
      />
      <input
        type="hidden"
        value="{{$prodtion_data->id}}"
        name="sale_data_id[]"
      />
      <input
        type="hidden" class="order_qty order_qty"
        value="{{$prodtion_data->qty}}"
        name="order_qty[]"
      />
      @php
      $item_id = $prodtion_data->item_id;
      @endphp
    </td>
    <td>
      <input type="date" name="start_date[]" class="form-control" />
    </td>
    <td>
      <input type="date" name="delivery_date[]" class="form-control" />
    </td>
  </tr>

    <tr>
            <td>
              <label for="">Receipe</label>
              <select class="form-control receip_id" onchange="getReceipeData(this);" name="receipt_id[]" id="">
                @foreach($production_bom as $bom)
              <option value="{{$bom->id}}">{{$bom->receipe_name}}</option>
            @endforeach
            
          </select>
        </td>
    </tr>
    <tr class="receipe1">
  
    </tr>
    
</tbody>

@endforeach
<input
  type="hidden"
  name="customer_name"
  value="{{$prodtions->name}}"
  id="customer_name"
/>
<input
  type="hidden"
  name="customer_id"
  value="{{$prodtions->customer_id}}"
  id="customer_id"
/>
<input
  type="hidden"
  name="order_no"
  value="{{$prodtions->so_no}}"
  id="order_no"
/>

