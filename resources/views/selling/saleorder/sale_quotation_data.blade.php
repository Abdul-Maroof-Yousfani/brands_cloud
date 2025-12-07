<?php 
use App\Helpers\CommonHelper;
$i =1;
?>
@foreach($quotation_data as $q_data)
    
            <tr class="m-tab main">
                        <td> <select onchange="get_sub_item_by_id(this);"  name="category[]" class="form-control select2 category">
                            <option value="">Select</option>
                            @foreach(CommonHelper::get_sub_category()->get() as $category )
                                <option @if($q_data->sub_category_id == $category->id ) selected @endif value="{{$category->id}}">{{$category->sub_category_name}} </option>
                            @endforeach                                                                </select>                                                            
                    </td>
                <td>
                    <select onchange="item_change(this); get_discount();" class="form-control item_id itemsclass" name="item_id[]" id="">
                            @foreach(CommonHelper::get_all_subitem() as $item)
                            <option @if($q_data->item_id == $item->id) selected @endif value="{{$item->id}}">{{$item->sub_ic}}</option> 
                            @endforeach
                    </select>             
                </td>

                <input style="display: none" class="form-control" type="text" name="item_code[]" id="item_code" value="{{$q_data->item_code}}">
                <input style="display: none" class="form-control" type="text" name="thickness[]" id="">
                <input style="display: none" class="form-control"  type="text" name="diameter[]" id="">
                <td><input class="form-control" onkeyup="calculation_amount()" type="text" name="qty[]" id="qty" value="{{$q_data->qty}}"></td>
                <td><input class="form-control" onkeyup="calculation_amount()" type="text" name="rate[]" id="rate" value="{{$q_data->unit_price}}"></td>
                <td><input class="form-control" readonly type="text" name="uom[]" id="" value="{{$q_data->uom_name}}"></td>
                <td style="width:10%;"><input class="form-control discount_percent" onkeyup="calculation_amount()" type="text" name="discount_percent[]" id="discount_percent" value="0"></td>
                        <td style="width:10%;"><input class="form-control discount_amount" onkeyup="calculation_amount()" type="text" name="discount_amount[]" id="discount_amount" value="0"></td>
                <td><input readonly class="form-control" type="text" name="total[]" id="total" value="{{$q_data->total_amount}}">
            </tr>
            <tr>
                <td colspan="2">
                    <label>Description</label>
                    <textarea class="form-control" name="item_description[]" cols="4" rows="3"> 
                    </textarea>
                </td>
                <td>
                    <label for="">Printing</label>
                    <textarea class="form-control"  cols="4" rows="3" type="text" name="printing[]" id=""></textarea>
                <td>
                    <label for="">Special Instruction</label>
                    <textarea class="form-control"  cols="4" rows="3" type="text" name="special_ins[]" id="item_description"></textarea>

                <td><label for="">Delivery Date</label>
                    <input class="form-control" type="date" name="delivery_date[]" id=""></td>

                @if($i > 1)    
                    <td>
                        <label for="">Action</label>
                        <button type="button" class="btn btn-sm btn-danger" onclick="RemoveSection({{$i}})"> <span class="glyphicon glyphicon-trash"></span> </button>
                    </td>
                @endif
            </tr>
            @php
            $i++;   
            @endphp
@endforeach
<input type="hidden" name="customer_id" value="{{$saleQuot->customer_id}}" id="customer_id">
<input type="hidden" name="sale_tax_group" value="{{$saleQuot->sale_tax_group}}" id="sale_tax_group">
<input type="hidden" name="sale_tax_rate" value="{{$saleQuot->sales_tax_rate}}" id="sale_tax_rate">
<input type="hidden" name="customer_type" value="{{$saleQuot->customer_type}}" id="customer_type">
<input type="hidden" name="prospect_id" value="{{$saleQuot->prospect_id}}" id="prospect_id">

