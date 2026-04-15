<?php
use App\Helpers\CommonHelper;
use App\Helpers\ReuseableCode;

$export=ReuseableCode::check_rights(253);

$accType = Auth::user()->acc_type;
if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}
$current_date = date('Y-m-d');
$currentMonthStartDate = date('Y-m-01');
$currentMonthEndDate   = date('Y-m-t');
?>

@extends('layouts.default')

@section('content')
    @include('select2')
    <div class="well_N">
    <div class="dp_sdw">    
        <div class="panel">
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="well">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <span class="subHeadingLabelClass">Customer/Store balance summary Report</span>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
                                        <button class="btn btn-primary" onclick="printViewTwo('PrintEmpExitInterviewList','linkRem','1')" style="">
                                            <span class="glyphicon glyphicon-print"></span> Print
                                        </button>
                                        <?php if($export == true):?>
                                            <a id="dlink" style="display:none;"></a>
                                            <button type="button" class="btn btn-warning" onclick="ExportToExcel('xlsx')">Export <b>(xlsx)</b></button>
                                        <?php endif;?>
                                    </div>
                                </div>

                                <div class="lineHeight">&nbsp;</div>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="panel">
                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <div class="row">
                                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                            <label>As On</label>
                                                            <input type="Date" name="ToDate" id="ToDate" max="<?php echo $current_date;?>" value="<?php echo date('Y-m-d');?>" class="form-control" />
                                                        </div>
                                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                            <label>Customer Group</label>
                                                            <select name="customer_group_id" id="customer_group_id" class="form-control select2" onchange="getCustomersByGroup(this.value)">
                                                                <option value="">All Groups</option>
                                                                @foreach($customer_groups as $group)
                                                                    <option value="{{ $group->id }}">{{ $group->customer_group }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                            <label>Customer/Store</label>
                                                            <select name="customer_id" id="customer_id" class="form-control select2">
                                                                <option value="">All Customers</option>
                                                                @foreach($customers as $customer)
                                                                    <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                            <label>Region</label>
                                                            <select name="region_id" id="region_id" class="form-control select2">
                                                                <option value="">All Regions</option>
                                                                @foreach($regions as $region)
                                                                    <option value="{{ $region->id }}">{{ $region->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                            <label>Territory</label>
                                                            <select name="territory_id" id="territory_id" class="form-control select2">
                                                                <option value="">All Territories</option>
                                                                @foreach($territories as $territory)
                                                                    <option value="{{ $territory->id }}">{{ $territory->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                            <label>Format</label>
                                                            <select name="Format" id="Format" class="form-control" >
                                                                <option value="1">Summary</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                                                            <input type="button" value="Submit" class="btn btn-sm btn-primary" onclick="ReceivablSummaryReport();" style="margin-top: 10px;" />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="lineHeight">&nbsp;</div>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="receivablSummaryReport"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ URL::asset('assets/custom/js/exportToExcelXlsx.js') }}"></script>
    <script !src="">
        function ExportToExcel(type, fn, dl) {
            var elt = document.getElementById('receivablSummaryReport');
            var wb = XLSX.utils.table_to_book(elt, { sheet: "sheet1" });
            return dl ?
                    XLSX.write(wb, { bookType: type, bookSST: true, type: 'base64' }) :
                    XLSX.writeFile(wb, fn || ('Debtor Summary <?php echo date('d-m-Y')?>.' + (type || 'xlsx')));
        }
    </script>
    <script type="text/javascript">
        $(document).ready(function(){
            $('#account_id').select2();
        });


        function ReceivablSummaryReport() {
            var ToDate = $('#ToDate').val();
            var Format = $('#Format').val();
            var customer_group_id = $('#customer_group_id').val();
            var customer_id = $('#customer_id').val();
            var region_id = $('#region_id').val();
            var territory_id = $('#territory_id').val();
            var m = '<?php echo $_GET['m'];?>';
            
            $('#receivablSummaryReport').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
            $.ajax({
                url: '<?php echo url('/');?>/fdc/receivablSummaryReport',
                method:'GET',
                data:{
                    ToDate: ToDate,
                    Format: Format,
                    customer_group_id: customer_group_id,
                    customer_id: customer_id,
                    region_id: region_id,
                    territory_id: territory_id,
                    m: m
                },
                error: function(){
                    alert('error');
                },
                success: function(response)
                {
                    $('#receivablSummaryReport').html(response);
                }
            });
        }

        function getCustomersByGroup(group_id) {
            var m = '<?php echo $_GET['m'];?>';
            $.ajax({
                url: '<?php echo url('/');?>/fdc/getCustomersByGroup',
                method:'GET',
                data: { group_id: group_id, m: m },
                success: function(data) {
                    var options = '<option value="">All Customers</option>';
                    $.each(data, function(index, customer) {
                        options += '<option value="' + customer.id + '">' + customer.name + '</option>';
                    });
                    $('#customer_id').html(options).select2();
                }
            });
        }


    </script>
@endsection