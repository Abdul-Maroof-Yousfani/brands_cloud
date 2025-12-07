@extends('layouts.default')

@section('content')
@include('select2')
<?php
    use App\Helpers\CommonHelper;
    $i=1;
?>
<style>
    .my-lab label {
    padding-top:0px; 
}
</style>
    <div class="row well_N align-items-center">
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
            <ul class="cus-ul">
                <li>
                    <h1>Production</h1>
                </li>
                <li>
                    <h3><span class="glyphicon glyphicon-chevron-right"></span> &nbsp;Qc of Packing</h3>
                </li>
            </ul>
        </div>
        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 text-right">
        
        </div>
    </div>
    <div class="row">
        
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="well_N">
            <div class="dp_sdw2">    
                <div class="row">

                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="panel">
                            <div class="panel-body">
                                <form action="{{route('QaPacking.store')}}" method="post">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 cus-tab">
                                        <div class=" qout-h">
                                            <div class="col-md-12 bor-bo">
                                                <h1>Make Qc of Packing</h1>
                                            </div>
                                            
                                            <div class="col-md-12 padt pos-r">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <div class="col-md-4">
                                                            <label>Sales Order</label>
                                                            <!-- <label>Purchase Request No.</label> -->
                                                        </div>
                                                        <div class="col-sm-8">
                                                            <select name="so_id" onchange="productionPlanAgainstSo(this)" class="form-control" id="so_id">
                                                                <option value="">Select Sales Order</option>
                                                                
                                                                @foreach($Sales_Order as $key => $value )
                                                                    <option value="{{ $value->id}}">{{ $value->so_no }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="col-md-4">
                                                            <label>Production Plan</label>
                                                        </div>
                                                        <div class="col-sm-8">
                                                            <select name="material_requisition_id" onchange="getPackingListNo(this)" class="form-control" id="material_requisition_id">
                                                                 
                                                            </select>
                                                            <input readonly name="pp_id" id="pp_id"  class="form-control" type="hidden">
                                                            
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="col-md-4">
                                                            <label>Packing List No</label>
                                                        </div>
                                                        <div class="col-sm-8">
                                                            <select name="packing_list_id"  class="form-control" id="packing_list_id">
                                                                 
                                                            </select>
                                                            
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="form-group">
                                                        <div class="col-md-4">
                                                            <label>Customer Name</label>
                                                            <!-- <label>Purchase Request No.</label> -->
                                                        </div>
                                                        <div class="col-sm-8">
                                                            <input readonly name="customer_name" id="customer_name" value="" class="form-control" type="text">
                                                            <input readonly name="customer_id" id="customer_id" value="" class="form-control" type="hidden">
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="col-sm-4 control-label">Qc Packing Date</label>
                                                        <div class="col-sm-8">
                                                            <input name="qc_packing_date" value="{{date('Y-m-d')}}" class="form-control" type="date">
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="form-group">
                                                        <div class="col-md-4">
                                                            <label>QC by</label>
                                                            <!-- <label>Purchase Request No.</label> -->
                                                        </div>
                                                        <div class="col-sm-8">
                                                            <input name="qc_by" value="" class="form-control" type="text">
                                                        </div>
                                                    </div>
                                                    
                                                    
                                                </div>
 
                                                <div class="col-md-2"></div>
                                                <div class="col-md-4">
                                                    
                                               
                                                </div>
                                            </div>
                                            <div class="col-md-12 padt">
                                                <div class="col-md-12 padt">
                                                    <div class="col-md-12">
                                                        <table class="table">
                                                            <tr>
                                                                <th><input type="checkbox" onclick="checkedCheckBox(this)" id="check_all"></th>
                                                                <th>S.No</th>
                                                                <th>Test Name</th>
                                                                <th>Test type</th>
                                                                <th>Value</th>
                                                            </tr>
                                                            <tbody id="more_details">
                                                            

                                                                @foreach($QaTest as $test)

                                                                <tr>
                                                                    <td>
                                                                        <input type="hidden" name="qc_test_id[]" id="" value="{{$test->id}}">
                                                                        <input type="checkbox" class="checkbox" onclick="setValueOnCheckBox(this,{{$test->id}})" name="checkBox{{$test->id}}" value="{{$test->id}}">
                                                                        <input type="hidden" class="checkbox" id="checkBox{{$test->id}}" name="checkBox{{$test->id}}" value="0">
                                                                    </td>
                                                                    <td>{{$i}}</td>
                                                                    <td>
                                                                        {{$test->name}}
                                                                        
                                                                    </td>
                                                                    <td>
                                                                        <select name="test_type{{$test->id}}" id="">
                                                                            <option value="Mechanical">Mechanical</option>
                                                                            <option value="Physical">Physical</option>
                                                                        </select>
                                                                    </td>
                                                                    <td>

                                                                        <input type="text" name="test_value{{$test->id}}" id="test_value{{$test->id}}" />

                                                                    </td>
                                                                </tr>

                                                                @php
                                                                $i++;   
                                                                @endphp
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-12 padtb text-right">
                                                <div class="col-md-9"></div>    
                                                <div class="col-md-3 my-lab">
                                                    <button type="submit" disabled class="btn btn-primary mr-1" id="btn" data-dismiss="modal">Save</button>
                                                </div>    
                                            </div>
                                        </div>        
                                    </div>
                                </div>
                            </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>


<script>

        
    function productionPlanAgainstSo(datas)
    {
        $('#material_requisition_id').empty();
        var id = datas.value;

        $.ajax({
            // url: '<?php echo url('/')?>/selling/getMrData',
                url: '<?php echo url('/')?>/Production/QaPacking/productionPlanAndCustomerAgainstSo',
                type: 'Get',
                data: {id:id},
                success: function (data) {
                    
                    var select = document.getElementById("material_requisition_id");

                    // Clear existing options
                    select.innerHTML = "";

                    // Add default option
                    var defaultOption = document.createElement("option");
                    defaultOption.text = "Select Production Plan";
                    defaultOption.value = "";
                    select.appendChild(defaultOption);

                    // Loop through the array and create options
                    data?.material_requisition.forEach(function(mr) {
                        var option = document.createElement("option");
                        option.value = mr.id;
                        option.setAttribute('data-value', mr.pp_id);
                        option.text = mr.order_no + " - " + mr.order_date;
                        select.appendChild(option);
                    });

                    $('#customer_id').val(data?.customerDetails?.customer_id)
                    $('#customer_name').val(data?.customerDetails?.name)
                    $('#po_no').val(data?.customerDetails?.purchase_order_no)
                   
             }
            });
    }

    function getPackingListNo(datas)
    {
        var pp_id =  $('#material_requisition_id option:selected').attr('data-value');
        $('#pp_id').val(pp_id)

        document.getElementById("btn").disabled = true;
        var id = datas.value;

        if(id)
        {

            $.ajax({
                // url: '<?php echo url('/')?>/selling/getMrData',
                url: '<?php echo url('/')?>/Production/QaPacking/getPackingListNo',
                type: 'Get',
                data: {id:id},
                success: function (data) {

                    var select = document.getElementById("packing_list_id");

                    // Clear existing options
                    select.innerHTML = "";

                    // Add default option
                    var defaultOption = document.createElement("option");
                    defaultOption.text = "Select Packing ";
                    defaultOption.value = "";
                    select.appendChild(defaultOption);

                    // Loop through the array and create options
                    data?.packing.forEach(function(pk) {
                        var option = document.createElement("option");
                        option.value = pk.id;
                        option.text = pk.packing_list_no;
                        select.appendChild(option);
                    });

                    document.getElementById("btn").disabled = false;
                    
                }
            });
        }
    }

    function checkedCheckBox(e)
    {
        let allCheckBox = document.querySelectorAll('.checkbox');

        if(allCheckBox.length > 0)
        {

            if(e.checked)
            {
                allCheckBox.forEach(function(e){
                    if(e.type == 'checkbox' ){
                        e.checked = true;
                        // $('#test_value'+ e.value).attr('required',true)    

                    }else
                    {
                        e.value = 1;

                    }
                })

            }
            else
            {
                allCheckBox.forEach(function(e){
                    if(e.type == 'checkbox' ){
                        e.checked = false;
                        // $('#test_value'+ e.value).removeAttr('required')    
                           

                    }else
                    {
                        e.value = 0;

                    }
                })
            }
        }
    }

    function setValueOnCheckBox(e,count)
    {
        if(e.checked)
        { 
            $('#checkBox'+count).val(1);
            // $('#test_value'+count).attr('required',true)    
        }
        else
        {
            $('#checkBox'+count).val(0);
            // $('#test_value'+count).removeAttr('required')    
        }
    }

</script>

@endsection