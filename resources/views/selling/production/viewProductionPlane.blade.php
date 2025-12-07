@extends('layouts.default')
@section('content')
@include('select2')

<?php
use App\Models\MaterialRequisitionData;
use App\Models\ProductionPlaneRecipe;
use App\Helpers\CommonHelper;
$count = 0;
?>
<div class=" well_N ">
  <form action="{{route('issueMaterial')}}" method="post">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <div class="row">
      <div class="col-md-12 ">
        <input type="hidden" value="{{$material_requisitions->id}}" name="mr_id" id="mr_id">
        <table class="table">
        <thead>
            <th>MR NO</th>
            <th>Finish Good  </th>
            <th>Order Qty/Length</th>
        </thead>
        <tr>
            @php 
            $item =CommonHelper::get_item_by_id($material_requisitions->finish_good_id);
            @endphp
            <td>{{$material_requisitions->mr_no}}</td>
            <td>{{$item->sub_ic}}</td>
            <td>{{$material_requisitions->finish_good_qty}}</td>
        </tr>
        </table>
      </div>
        @php
        $production_plane_receipe = ProductionPlaneRecipe::where('master_id',$material_requisitions->id)->where('receipe_id',$material_requisitions->receipt_id)->where('status',1)->get();
        @endphp
        @foreach($production_plane_receipe as $key_recipe => $recipe)
        @php
        $issuance = MaterialRequisitionData::where('mr_id',$material_requisitions->id)->where('category_id',$recipe->bom_data_id)->where('status',1)->select(DB::raw('sum(issuance_qty) as issuance_qty'))
        ->first();
        @endphp
          <div class="main_category">
            <div class="col-md-12"> 
              <table class="table"> 
                <tbody>
                  <tr>
                    <td>
                      <label for="">Category</label>
                      <select name="category[]" onchange="getOverAllstock(this)" class="form-control category select2" id="category">
                        @foreach(CommonHelper::get_sub_category()->get() as $sub_category)
                          @if($recipe->bom_data_id == $sub_category->id)
                            <option    selected   value="{{$sub_category->id}}">{{$sub_category->sub_category_name}}</option>
                          @endif
                        @endforeach
                      </select>
                    </td>
                      <td>
                        <label for="">Request Qty</label>
                        <input type="text" value="{{$recipe->request_qty}}" readonly class="form-control" name="recipe_qty[]" id="required_qty">
                      </td>
                        <td>
                            <label for="">Issued Qty</label>
                            <input type="text" readonly class="form-control" value="{{isset($issuance->issuance_qty)? $issuance->issuance_qty : 0}}" name="Issued_qty[]" id="Issued_qty">
                        </td>
                        <td>
                            <label for="">Remaining Qty</label>
                            <input type="text" readonly class="form-control" value="{{isset($issuance->issuance_qty)? $recipe->request_qty - $issuance->issuance_qty  : $recipe->request_qty - 0}}"  id="">
                        </td>
                        <td></td>
                        <td></td>
                  </tr>
                  <tr>
                      <th>Product Name</th>
                      <th>warehouse</th>
                      <th>batch code</th>
                      <th>In Stock </th>
                      <th>Total Qty issue</th>
                      <th>issue date</th>
                  </tr>
                </tbody>
                <tbody class="add">
                  <tr>
                    <input type="button" style="background-color: limegreen; color: white; text-align: center;" onclick="addRow(this,{{ $recipe->bom_data_id }},'{{  $recipe->item_id }}',{{ $count }})" class="btn" value="+" id="">
                  </tr>

                  @php 
                    $count++;
                  @endphp
                  @if($recipe->bom_data_item_id != 0 )
                    <tr class="main" id="remove{{$count}}">
                      <td>
                        <select id="item{{$count}}"  onchange="getStock1(this)" class="form-control item select2 " name="item[]">
                          
                          @foreach(CommonHelper::get_all_subitem() as $subitem)
                            @if($subitem->id == $recipe->bom_data_item_id )
                              <option selected value="{{$subitem->id}}">{{$subitem->sub_ic}}</option>
                            @endif
                          @endforeach
                        </select>
                      </td>
                      <td>
                        <select  onchange="getStock1(this)" class="form-control warehouse_id select2" name="warehouse_id[]" id="">
                          <option value="">Select Warehouse</option>
                          @foreach(CommonHelper::get_all_warehouse() as $warehouse)
                          <option value="{{$warehouse->id}}">{{$warehouse->name}}</option>
                          @endforeach
                        </select>
                      </td>
                      <td>
                        <input type="number" readonly class="form-control in_stock" name="in_stock[]" id="">
                      </td>
                      <td>
                        <input type="number" class="form-control" name="required_qty[]" id="">
                      </td>
                      <td>
                        @if($count > 1)
                        {
                          <input type="button" onclick="removes('{{$count}}')" class="btn btn-danger" value="-"  >
                        }
                        @endif  
                      </td>
                    </tr>
                  @endif    
                </tbody>
              </table>
            </div>
          </div>

          @if((count($production_plane_receipe) - 1) == $key_recipe)
             <div class="main_category">
            <div class="col-md-12"> 
              <table class="table"> 
                <tbody>
                  <tr>
                    <td>
                      <label for="">Category</label>
                      <select name="category[]" onchange="getItemByCategoryRaw(this,this.value)" class="form-control category select2" id="category_raw">
                        @foreach(CommonHelper::get_sub_category()->get() as $sub_category)
                            <option  value="{{$sub_category->id}}">{{$sub_category->sub_category_name}}</option>
                        @endforeach
                      </select>
                    </td>
                      <td>
                        <label for="">Request Qty</label>
                        <input type="text" value="0" readonly class="form-control" name="recipe_qty[]" id="required_qty">
                      </td>
                        <td>
                            <label for="">Issued Qty</label>
                            <input type="text" readonly class="form-control" value="{{isset($issuance->issuance_qty)? $issuance->issuance_qty : 0}}" name="Issued_qty[]" id="Issued_qty">
                        </td>
                        <td>
                            <label for="">Remaining Qty</label>
                            <input type="text" readonly class="form-control" value="0"  id="">
                        </td>
                        <td></td>
                        <td></td>
                  </tr>
                  <tr>
                      <th>Product Name</th>
                      <th>warehouse</th>
                      <th>batch code</th>
                      <th>In Stock </th>
                      <th>Total Qty issue</th>
                      <th>issue date</th>
                  </tr>
                </tbody>
                <tbody class="add">
                  <tr>
                    <input type="button" style="background-color: limegreen; color: white; text-align: center;" onclick="addRowForRawMaterial(this,'','',{{ $count }})" class="btn" value="add raw material" id="">
                  </tr>

                  @php 
                    $count++;
                  @endphp
                  @if($recipe->bom_data_item_id != 0 )
                    <tr class="main" id="remove{{$count}}">
                      <td>
                        <select id="item{{$count}}"  onchange="getStock1(this)" class="form-control item select2 " name="item[]">
                          
                          @foreach(CommonHelper::get_all_subitem() as $subitem)
                            @if($subitem->id == $recipe->bom_data_item_id )
                              <option selected value="{{$subitem->id}}">{{$subitem->sub_ic}}</option>
                            @endif
                          @endforeach
                        </select>
                      </td>
                      <td>
                        <select  onchange="getStock1(this)" class="form-control warehouse_id select2" name="warehouse_id[]" id="">
                          <option value="">Select Warehouse</option>
                          @foreach(CommonHelper::get_all_warehouse() as $warehouse)
                          <option value="{{$warehouse->id}}">{{$warehouse->name}}</option>
                          @endforeach
                        </select>
                      </td>
                      <td>
                        <input type="number" readonly class="form-control in_stock" name="in_stock[]" id="">
                      </td>
                      <td>
                        <input type="number" class="form-control" name="required_qty[]" id="">
                      </td>
                      <td>
                        @if($count > 1)
                        {
                          <input type="button" onclick="removes('{{$count}}')" class="btn btn-danger" value="-"  >
                        }
                        @endif  
                      </td>
                    </tr>
                  @endif    
                </tbody>
              </table>
            </div>
          </div>
                  @endif
        @endforeach
    </div>
    <div class="col-md-12 padtb text-right">
      <div class="col-md-9"></div>    
      <div class="col-md-3 my-lab">
          <button type="submit" class="btn btn-primary mr-1" data-dismiss="modal">Save</button>
          <button type="button" class="btnn btn-secondary " data-dismiss="modal">Cancel</button>
      </div>    
    </div>
  </form>
</div>

<script>

  function getStock1(datas)
  {
      var ids =   $(datas).closest('.main').find('.item').val();  
      var warehouse_id =   $(datas).closest('.main').find('.warehouse_id').val();  
      var batch_code =   $(datas).closest('.main').find('.batch_code').val();  

      // return
      $.ajax({
                  url: '<?php echo url('/')?>/selling/getStockForProduction',
                  type: 'Get',
                  data: {
                          id:ids,
                          warehouse_id:warehouse_id,
                          batch_code:batch_code
                        },
              success: function (data) {
                  $(datas).closest('.main').find('.in_stock').val(data);
              }
              });
  }

  function get_stock(datas,number)
  {
    var item =   $(datas).closest('.main').find('.item').val();  
    var warehouse =   $(datas).closest('.main').find('.warehouse_id').val();  
    $(datas).closest('.main').find('.batch_code').empty();
    $(datas).closest('.main').find('.in_stock').val(0);

      $.ajax({
          url: '<?php echo url('/')?>/pdc/get_stock_location_wise',
          type: "GET",
          data: {warehouse:warehouse,item:item},
          success:function(data)
          {

            if(data)
            {
              // $('#batch_code'+number).html(data);
              $(datas).closest('.main').find('.batch_code').html(data);
              
              // console.log(data.lenght)
              // console.log(data)
            }
            else
            {
              getStock1(datas)
            }
              //   data=data.split('/');
              // $('#batch_code'+number).html(data);
              // $('#rate'+number).val(data[1]);

              // $('#warehouse_to'+number+' option[value="'+warehouse+'"]').prop('disabled', true)
              // check_qty('qty'+number,number);
          }
      });

  }

  var option = '';

  // var abc = "";
  function getItemByCategory(instance,category_id,item_id,counter)
  {

    // abc  = instance;
    // console.log(instance+" , "+ category_id +" , "+" , "+ item_id +" , "+" , "+ counter +" , ");

      $.ajax({
          url: '<?php echo url('/')?>/selling/getItemByCategory',
          type: 'Get',
          data: {
                sub_category_id: category_id,
                item_id: item_id,
                },
          success: function (data) {
            console.log(data)
              // Use the captured 'this' to find the closest '.main_category'
                  $('#item'+counter).append(data);
                  $('#item'+counter).val(item_id)
              
          }
      });
  }
  
  function getItemByCategoryRaw(instance,category_id,item_id,counter)
  {

    // abc  = instance;
    // console.log(instance+" , "+ category_id +" , "+" , "+ item_id +" , "+" , "+ counter +" , ");

    $('.item_raw').empty();
    console.log(category_id)
      $.ajax({
          url: '<?php echo url('/')?>/selling/getItemByCategory',
          type: 'Get',
          data: {
                sub_category_id: category_id,
                item_id: item_id,
                },
          success: function (data) {
            console.log(data)
              // Use the captured 'this' to find the closest '.main_category'
                  $('.item_raw').append(data);
                  $('.item_raw').val(item_id)
              
          }
      });
  }

  // function getItemByCategory(instance,category_id,counter)
  // {

  //   $('.category').each(function () {
  //     // Capture 'this' in a variable for use inside the AJAX success callback
  //     var currentCategory = $(this);

  //     let id = currentCategory.find(':selected').val();
  //     $.ajax({
  //         url: '<?php echo url('/')?>/selling/getItemByCategory',
  //         type: 'Get',
  //         data: { id: id },
  //         success: function (data) {
  //             // Use the captured 'this' to find the closest '.main_category'
  //             currentCategory.closest('.main_category').find('.add .item').each(function () {
  //                 $(this).append(data);
  //             });
  //         }
  //     });
  // });
  // }

  function removes(count)
  {
      console.log(count);
      $('#remove'+count).remove();
      // counter--;
  }
  var counter = {{$count}} + 1;

  function addRow(instance,category_id,item_id,count)
  {
    
      var html = '';
      $(instance).closest('.main_category').find('.add').append(`
      <tr class="main" id="remove${counter}">
      <td>
        <select id="item${counter}" onchange="get_stock(this)" class="form-control item" name="item_${count}_${category_id}[]">
          <option  value="">Select Item</option>
          ${option}
          </select>
      </td>
      <td>
        <select  onchange="get_stock(this)" class="form-control warehouse_id" name="warehouse_id_${count}_${category_id}[]" id="">
          <option value="">Select Warehouse</option>
          @foreach(CommonHelper::get_all_warehouse() as $warehouse)
          <option value="{{$warehouse->id}}">{{$warehouse->name}}</option>
          @endforeach
        </select>
      </td>
      <td>
        <select  onchange="getStock1(this)" class="form-control batch_code" name="batch_code_${count}_${category_id}[]" id="">
        
          </select>
      </td>
      <td>
        <input type="number" readonly class="form-control in_stock" name="in_stock_${count}_${category_id}[]" id="">
      </td>
      <td>
        <input type="number" step="any" class="form-control" name="required_qty_${count}_${category_id}[]" id="">
      </td>
      <td>
        <input type="date" class="form-control" name="issuance_date_${count}_${category_id}[]" id="">
      </td>
      <td>
      <input type="button" onclick="removes('${counter}')" class="btn btn-danger" value="-"  >
      
          </td>
    </tr>
      `);
      getItemByCategory(instance,category_id,item_id,counter);
      
      // $('#category'+counter).select2();
      // $('#item_id'+counter).select2();
      // $('#category' + counter).select2({
      //   width: '100%' // Set the desired width
      // });
      counter++;
      
  //     $(document).ready(function () {
  // })

    }


    function addRowForRawMaterial(instance,category_id,item_id,count)
  {
    
    category_id = $('#category_raw').val();

    console.log(category_id)


      var html = '';
      $(instance).closest('.main_category').find('.add').append(`
      <tr class="main" id="remove${counter}">
      <td>
        <select id="item_raw_${counter}" onchange="get_stock(this)" class="form-control item item_raw" name="item_${count}_${category_id}[]">
          <option  value="">Select Item</option>
          ${option}
          </select>
      </td>
      <td>
        <select  onchange="get_stock(this)" class="form-control warehouse_id" name="warehouse_id_${count}_${category_id}[]" id="">
          <option value="">Select Warehouse</option>
          @foreach(CommonHelper::get_all_warehouse() as $warehouse)
          <option value="{{$warehouse->id}}">{{$warehouse->name}}</option>
          @endforeach
        </select>
      </td>
      <td>
        <select  onchange="getStock1(this)" class="form-control batch_code" name="batch_code_${count}_${category_id}[]" id="">
        
          </select>
      </td>
      <td>
        <input type="number" readonly class="form-control in_stock" name="in_stock_${count}_${category_id}[]" id="">
      </td>
      <td>
        <input type="number" step="any" class="form-control" name="required_qty_${count}_${category_id}[]" id="">
      </td>
      <td>
        <input type="date" class="form-control" name="issuance_date_${count}_${category_id}[]" id="">
      </td>
      <td>
      <input type="button" onclick="removes('${counter}')" class="btn btn-danger" value="-"  >
      
          </td>
    </tr>
      `);
      getItemByCategoryRaw(instance,category_id,item_id,counter);
      
      $('#category'+counter).select2();
      $('#item_raw_'+counter).select2();
      $('#category' + counter).select2({
        width: '100%' // Set the desired width
      });
      counter++;
      
  //     $(document).ready(function () {
  // })

    }

    $(document).ready(function (){

      $('.category').select2();

      // category
    })
 </script>
@endsection

