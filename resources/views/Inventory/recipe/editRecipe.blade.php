<?php

use App\Helpers\CommonHelper;
use App\Helpers\StoreHelper;
use App\Helpers\FinanceHelper;
use App\Helpers\ReuseableCode;

$MenuPermission = true;
$accType = Auth::user()->acc_type; if ($accType == 'client') 
{ $m = $_GET['m'];
} else 
{ $m = Auth::user()->company_id; } 
$current_date = date('Y-m-d');
$currentMonthStartDate = date('Y-m-01'); $currentMonthEndDate = date('Y-m-t');
if ($accType == 'user') : $user_rights =
DB::table('menu_privileges')->where([['emp_code', '=', Auth::user()->emp_code],
['compnay_id', '=', Session::get('run_company')]]); 
$submenu_ids = explode(",",$user_rights->value('submenu_id')); 
if (in_array(410, $submenu_ids)) {
$MenuPermission = true; } 
else { $MenuPermission = false; }
 $workName =
 DB::connection('mysql2')->table('work_station')->get(); endif; 
 $count = 1;
?>
@extends('layouts.default') 

@section('content')
 @include('select2')
@include('modal')
 @include('number_formate')

<script>
  var counter = 1;
</script>

<div class="container-fluid">
  <div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
      <div class="well_N">
        <div class="dp_sdw">
          <div class="headquid">
            <h2 class="subHeadingLabelClass">Edit Recipe</h2>
          </div>
          <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
              <?php
                            if ($MenuPermission == true) : ?>
              <?php else : ?>
              <span
                class="subHeadingLabelClass text-danger text-center"
                style="float: right"
                >Permission Denied
                <span style="font-size: 45px !important">&#128546;</span></span
              >
              <?php endif;

                            ?>
            </div>
          </div>
          <?php if ($MenuPermission == true) : ?>
          <div class="lineHeight">&nbsp;</div>

          <?php echo Form::open(array('url' => 'recipe/UpdateRecipe?m=' . $m . '', 'id' => 'saveRecipe')); ?>

            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="recordId" value="{{ $recipe->id }}">


         
          <div class="panel">
            <div class="panel-body">
              <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                  <div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                      <label class="sf-label">Item Finish Goods</label>
                      <span class="rflabelsteric"><strong>*</strong></span>
                      <select
                        class="form-control select2"
                        name="product_id"
                        id="product_id"
                        onchange="get_uom_name_by_item_id(this.value)"
                      >
                        <option>Select Finish Goods</option>
                        @foreach (CommonHelper::get_all_subitem() as $key =>
                        $value)
                        <option @if( $value->id  == $recipe->finish_goods) selected @endif value="{{ $value->id }}">
                          {{ $value->sub_ic }}
                        </option>
                        @endforeach
                      </select>
                    </div>

                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                      <label class="sf-label">uom</label>
                      <input
                        class="form-control"
                        type="text"
                        name="uom"
                        id="uom"
                        value="{{ CommonHelper::get_uom($recipe->finish_goods) }}"
                        readonly
                      />
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                      <label class="sf-label">Quantity</label>
                      <span class="rflabelsteric"><strong>*</strong></span>
                      <input
                        class="form-control"
                        type="text"
                        name="qty"
                        id="qty"
                        value=" {{ $recipe->qty }}"
                      />
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                      <label for="">Recipe Name</label>
                      <input type="text" class="form-control" name="receipe_name" id=""  value=" {{ $recipe->receipe_name }}">

                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                      <label class="sf-label">Description</label>
                      <span class="rflabelsteric"><strong>*</strong></span>
                      <textarea
                        name="main_description"
                        id="main_description"
                        rows="4"
                        cols="50"
                        style="resize: none; font-size: 11px"
                        class="form-control requiredField"
                        value=" {{ $recipe->description }}"
                      >{{ $recipe->description }}</textarea>
                    </div>
                  </div>
                </div>
              </div>
              <div class="lineHeight">&nbsp;</div>
              <div class="row">
                @foreach($recipeData as $key => $value)
                @if($count == 1)
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                        <label for="">Category</label>
                        <select name="category[]" onchange="get_sub_item_by_id(this,1,{{$value->item_id}})" class="form-control category select2">
                            <option  value="">Select Category</option>
                            @foreach(CommonHelper::get_sub_category()->get() as $sub_category)
                            <option @if($sub_category->id == $value->category_id) selected @endif value="{{$sub_category->id}}">{{$sub_category->sub_category_name}}</option>
                            @endforeach
                        </select>        
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                      <label class="dis" for="">Item</label>
                      <select  class="form-control select2 item_id" name="item_id[]" id="item_id1">
                        <option  value="">Select Item</option>

                      </select>    
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                        <label for="">Required QTY</label>
                        <input type="text"  class="form-control" name="required_qty[]" value="{{$value->category_total_qty}}" >
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                        <label for="">&nbsp;</label>
                        <button style="margin-top: 35px;" type="button" onclick="AddMoreDetails()"  class="btn btn-sm btn-success">+</button>
                    </div>

                    </div>
                    @endif

                    <div class="add_morew">
                     @if($count > 1)
                       
                        <div class="row" id="RemoveRows{{$count}}"> 
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                    <label for="">Category</label>
                                    <select name="category[]" onchange="get_sub_item_by_id(this,{{$count}},{{$value->item_id}})" class="form-control category" id="">
                                        <option  value="">Select Category</option>
                                        @foreach(CommonHelper::get_sub_category()->get() as $sub_category)
                                        <option @if($sub_category->id == $value->category_id) selected @endif value="{{$sub_category->id}}">{{$sub_category->sub_category_name}}</option>
                                        @endforeach
                                    </select>        
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                  <label class="dis" for="">Item</label>
                                  <select  class="form-control select2 item_id" name="item_id[]" id="item_id{{$count}}">
                                    <option  value="">Select Item</option>

                                  </select>    
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                    <label for="">Required QTY</label>
                                    <input type="text"  class="form-control" name="required_qty[]" id="" value="{{$value->category_total_qty}}" >
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                    <label for="">&nbsp;</label>
                                    <button style="margin-top: 35px;" onclick="RemoveSection({{$count}})"  class="btn btn-danger">-</button>
                                </div>
                        </div>
                    @endif
                        
                      </div>
                <?php $count++ ; ?>
                @endforeach
               </div>
              <div class="lineHeight">&nbsp;</div>
              <div class="row mb-20">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">

                    <button type="submit" id="reset" class="btn btn-success">
                        Submit
                      </button>
                  
                  <button type="reset" id="reset" class="btn btn-primary">
                    Clear Form
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
        <?php echo Form::close(); ?>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>
<script>
  var Counter = {{$count}};

  function AddMoreDetails() {
    Counter++;
    $(".add_morew").append(`
    <div class="row" id="RemoveRows${Counter}"> 
              <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                 <label for="">Category</label>
                  <select name="category[]" onchange="get_sub_item_by_id(this,${Counter},0)" class="form-control category" id="">
                    <option  value="">Select Category</option>
                    @foreach(CommonHelper::get_sub_category()->get() as $sub_category)
                    <option value="{{$sub_category->id}}">{{$sub_category->sub_category_name}}</option>
                    @endforeach
                  </select>        
                 </div>
                 <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                    <label class="dis" for="">Item</label>
                    <select  class="form-control select2 item_id" name="item_id[]" id="item_id${Counter}">
                        
                    </select>    
                </div> 
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                  <label for="">Required QTY</label>
                  <input type="text"  class="form-control" name="required_qty[]" id="">
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                  <label for="">&nbsp;</label>
                  <button style="margin-top: 35px;" onclick="RemoveSection(${Counter})"  class="btn btn-danger">-</button>
                </div>
              </div>
    `

    
    );
    $(".categorys").select2();
  }



  function RemoveSection(Row) {
    $("#RemoveRows" + Row).remove();
    Counter--;
  }
</script>


<script type="text/javascript">
  $(".select2").select2();

  function  get_sub_item_by_id(instance,num,value)
	{

    $('#item_id'+num).empty();

		var category= instance.value;
	
        $(instance).closest('.main').find('#item_id').empty();
		$.ajax({
			url: '{{ url("/getSubItemByCategory") }}',
			type: 'Get',
			data: {category: category},
			success: function (response) {
                $('#item_id'+num).append(response);

                if(value != 0)
                {
                  console.log(value)
                  setTimeout(() => {
                    $('#item_id'+num).val(value).select2();
                  }, 2000);

                }
			}
		});
	}

  $(document).ready(function (){
    $('.category').trigger('change')
  });

  function  get_uom_name_by_item_id(ItemId,num)
	{

    $('#item_id'+num).empty();

		$.ajax({
			url: '{{ url("pdc/get_uom_name_by_item_id") }}',
			type: 'Get',
			data: {ItemId: ItemId},
			success: function (response) {

        $('#uom').val(response)
			}
		});
	}
</script>

<script src="{{ URL::asset('assets/js/select2/js_tabindex.js') }}"></script>

@endsection
