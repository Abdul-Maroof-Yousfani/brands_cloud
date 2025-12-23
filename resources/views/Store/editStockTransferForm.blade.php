<?php
$accType = Auth::user()->acc_type;
$currentDate = date('Y-m-d');
if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}
use App\Helpers\PurchaseHelper;
use App\Helpers\CommonHelper;
?>
<style>
.table-responsive{scrollbar-width:thin !important;position:relative !important;height:400px !important;}
.table-responsive thead{position:sticky !important;top:0 !important;padding:5px !important;z-index:1;}

    li[aria-disabled='true'] {
        display: none;
    }
</style>
@extends('layouts.default')
@section('content')
    @include('select2')
    @include('modal')
    <div class="container-fluid"> 
        <div class="well_N">
            <div class="dp_sdw">    
                <div class="panel">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12" style="display: none;">
                                @include('Purchase.'.$accType.'purchaseMenu')
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <span class="subHeadingLabelClass">Stock Transfer Form</span>
                                    </div>
                                </div>
                                <div class="lineHeight">&nbsp;</div>
                                <div class="row">
                                    <?php echo Form::open(array('url' => 'pad/updateStockTransfer','id'=>'addPurchaseReturnDetail','class'=>'stop'));?>
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="hidden" name="m" value="{{ $m }}">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="row">
                                            <?php $uniq=PurchaseHelper::get_unique_no_transfer(date('y'),date('m')) ?>
                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                <?php $uniq=$Master->tr_no?>
                                                <label for="">Transfer No</label>
                                                <input type="text" id="tr_no" name="tr_no" value="{{strtoupper($uniq)}}" class="form-control requiredField" readonly>
                                                <input type="hidden" name="EditId" value="<?php echo $Master->id?>">
                                            </div>
                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                <label for="">Transfer Date</label>
                                                <input type="date" class="form-control requiredField" id="tr_date" name="tr_date" value="<?php echo $Master->tr_date?>">
                                            </div>
                                             <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                                                <label for="">Remarks</label>
                                                <textarea type="text" name="description" id="description" class="form-control requiredField"><?php echo $Master->description?></textarea>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                <label for="">Location From</label>
                                                <select onchange="updateAllWarehouseFrom()" name="main_warehouse_from" id="main_warehouse_from" class="form-control requiredField select2">
                                                    <option value="">Select Warehouse</option>
                                                        <?php foreach(CommonHelper::get_all_warehouse() as $Fil):?> <option {{ $Fil->id == $Master->location_from ? 'selected' : ''}} value="<?php echo $Fil->id?>"><?php echo $Fil->name?></option> <?php endforeach;?>
                                                    
                                                </select>
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                <label for="">Location To</label>
                                                <select onchange="updateAllWarehouseTo()" name="main_warehouse_to" id="main_warehouse_to" class="form-control requiredField select2">
                                                    
                                                <option value="">Select Warehouse</option>
                                                    <?php foreach(CommonHelper::get_all_warehouse() as $Fil):?> <option {{ $Fil->id == $Master->location_to ? 'selected' : ''}} value="<?php echo $Fil->id?>"><?php echo $Fil->name?></option> <?php endforeach;?>
                                                </select>
                                            </div>
                                            
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                <label for="">Brands</label>
                                                <select onchange="select_brand(this)" name="brands" id="brands" class="form-control requiredField select2">
                                                    
                                                <option value="">Select Brands</option>
                                                    <?php foreach(CommonHelper::get_all_brand() as $brand):?> <option {{ $brand->id == $Master->brand_id ? 'selected' : ''}} value="<?php echo $brand->id ?>"><?php echo $brand->name?></option> <?php endforeach;?>
                                                </select>
                                            </div>
                                        </div>
                                        <br>                                          
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered sf-table-list">
                                                        <thead>
                                                            <tr class="text-center">
                                                                <th colspan="3" class="text-center">Stock Transfer Detail</th>
                                                               
                                                                <th class="text-center">
                                                                    <span class="badge badge-success" id="span">1</span>
                                                                </th>
                                                            </tr>
                                                            <tr>
                                                                <th class="text-center" style="width: 30%">Item Name</th>
                                                                <th class="text-center" style="width: 12%">Barcode</th>
                                                                <th class="text-center hide" style="width: 180px;">Batch Code</th>
                                                                <th class="text-center">In Stock Qty</th>
                                                                <th class="text-center">Transfer Qty</th>
                                                                <th style="display: none" class="text-center">Rate</th>
                                                                <th style="display: none" class="text-center">Amount</th>
                                                                <th colspan="1" class="text-center">Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="AppendHtml">
                                                             <?php
                                                            $Couter = 0;
                                                            foreach($Detail as $Fil):
                                                            $Couter++;
                                                            ?>
                                                            <tr class="text-center AutoNo" id="RemoveRow<?php echo $Couter?>">
                                                                <td>
                                                                    <?php $SubItemName = CommonHelper::getCompanyDatabaseTableValueById($m,'subitem','sub_ic',$Fil->item_id);
                                                                    $ItemArray = CommonHelper::get_single_row('subitem','id',$Fil->item_id);
                                                                    ?>
                                                                    <input type="hidden" name="item_idd[]" id="item_<?php echo $Couter?>" class="form-control sam_jass requiredField" value="<?php echo $ItemArray->sub_ic?>">
                                                                    <select name="item_id[]" class="form-control select2 items" onchange="selectElement(this)" id="sub_<?php echo $Couter?>">
                                                                        <option value="0">Select item</option>
                                                                        @foreach (CommonHelper::get_all_subitem_get() as $item)
                                                                            <option @if($Fil->item_id == $item->id) selected @endif value="{{$item->id}}">{{$item->sku_code.' - '. $item->product_name}}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </td>
                                                                <td>
                                                                    <input readonly type="text" name="barcodes[]" id="barcodes" value="{{ App\Helpers\CommonHelper::product_barcode($item->id) }}" style="width: 100%; text-align: center;" class="form-control requiredField barCodes" step="any" min>
                                                                </td>
                                                                <td class="hide">
                                                                    <select onchange="get_stock_qty(this.id,1)" class="form-control requiredField select2" name="batch_code[]" id="batch_code1">
                                                                        <option value="">Select Batch</option>
                                                                    </select>
                                                                </td>
                                                                <td>
                                                                    <input type="text" name="in_stock_qty[]" id="in_stock_qty1" value="1" class="item-stock form-control requiredField" readonly style="width: 100%;">
                                                                </td>
                                                                <td>
                                                                    <input onkeyup="check_qty(this.id,1)" type="text" name="qty[]" value="{{ $Fil->qty }}" id="qty1" class="form-control requiredField SendQty" step="any" min>
                                                                </td>
                                                                <td style="display: none">
                                                                    <input readonly type="number" name="rate[]" id="rate1" class="form-control">
                                                                </td>
                                                                <td style="display: none">
                                                                    <input readonly type="number" name="amount[]" id="amount1" class="form-control">
                                                                </td>
                                                                <td><button type="button" class="btn btn-xs btn-primary" id="BtnAddMore" onclick="AddMoreRows()">Add More</button></td>
                                                            </tr>
                                                            
                                                            <?php endforeach;?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                                            <button type="submit" id="" class="btn btn-success">Submit</button>
                                            <button type="reset" id="reset" class="btn btn-danger">Clear Form</button>
                                        </div>
                                    </div>
                                    <?php echo Form::close();?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>   
<!-- <script>
    var Counter = 1;
        let allItems = @json(CommonHelper::get_all_subitem_get());
        function selectElement(changedSelect) {
            const item = allItems.filter(item => {
                return item.id == $(changedSelect).val()
            });
            $(changedSelect).closest("tr").find(".barCodes").val(item[0]["product_barcode"]);
            // Step 1: Collect all selected item values (except default "0")
        let selectedValues = [];
        $('.item-dropdown').each(function() {
            let val = $(this).val();
            if (val !== "0" && val !== null) {
                selectedValues.push(val);
            }
        });

        // Step 2: Update options in all dropdowns
        $('.item-dropdown').each(function() {
            let $dropdown = $(this);
            let currentValue = $dropdown.val();

            $dropdown.find('option').each(function() {
                let optionVal = $(this).val();

                // Always keep the current selected value in the dropdown
                if (optionVal !== "0" && optionVal !== currentValue) {
                    // Hide the option if it's selected in another dropdown
                    if (selectedValues.includes(optionVal)) {
                        $(this).hide();
                    } else {
                        $(this).show();
                    }
                }
            });
        });

    }
    // Hashim Abbas Start

    $(document).on('change', '.items', function() {
        const oldVal = $(this).data('oldValue') ?? null; 
        $('.items').not(this).find(`option[value="${$(this).val()}"]`).remove();
        if(parseInt(oldVal.split("~")[0]) > 0) {
            $(".items").not(this).each(function() {
                $(this).find(`option[value="0"]`).after(`<option value="${oldVal.split("~")[0]}">${oldVal.split("~")[1]}</option>`);
            })
        }
    });

    $(document).on('select2:opening', '.items', function() {
        $(this).data('oldValue', $(this).val() + "~" + $(this).find('option:selected').text());
    });

    function AddMoreRows() {
        Counter++;

        // Step 1: Get all selected item values
        let selectedItems = [];
        $('.items').each(function () {
            selectedItems.push($(this).val());
        });

        // // Step 2: Build filtered options
        let optionsHtml = '<option value="0">Select item</option>';
        allItems.forEach(function (item) {
            if (!selectedItems.includes(item.id.toString())) {
                optionsHtml += '<option value="' + item.id + '">' + item.sku_code + ' - ' + item.product_name + '</option>';
            }
        });

        // Hashim Abbas End
        
        // Step 3: Append the new row with filtered options
        $('#AppendHtml').append(
            '<tr class="text-center AutoNo" id="RemoveRow' + Counter + '">' +
                '<td>' +
                    '<select onchange="selectElement(this)" name="item_id[]" class="form-control select2 items" id="sub_' + Counter + '">' +
                        optionsHtml +
                    '</select>' +
                '</td>' +
                "<td>"+
                    '<input readonly type="text" name="barcodes[]" id="barcodes" class="form-control requiredField barCodes" step="any" min>'+
                "</td>"+
                '<td class="hide">' +
                    '<select onchange="get_stock_qty(this.id,' + Counter + ')" class="form-control requiredField select2" name="batch_code[]" id="batch_code' + Counter + '">' +
                        '<option value="">Select Batch</option>' +
                    '</select>' +
                '</td>' +
                '<td><input type="text" style="width: 100%;" name="in_stock_qty[]" id="in_stock_qty' + Counter + '" class="form-control requiredField" readonly></td>' +
                '<td><input onkeyup="check_qty(this.id,' + Counter + ')" type="text" name="qty[]" id="qty' + Counter + '" class="form-control requiredField SendQty"></td>' +
                '<td style="display: none"><input readonly type="number" name="rate[]" id="rate' + Counter + '" class="form-control"></td>' +
                '<td style="display: none"><input readonly type="number" name="amount[]" id="amount' + Counter + '" class="form-control"></td>' +
                '<td><button type="button" class="btn btn-xs btn-danger" id="BtnRemove' + Counter + '" onclick="RemoveRows(' + Counter + ')">X</button></td>' +
            '</tr>'
        );

        // Step 4: Reinitialize select2
        $('.select2').select2();
    }
    // Remove Row
    function RemoveRows(id) {
        $('#RemoveRow' + id).remove();
    }

    $(document).on('keydown', 'input[name="qty[]"]', function (e) {
        if (e.key === 'Tab' && !e.shiftKey) {
            let lastRow = $('#AppendHtml tr:last');
            let currentRow = $(this).closest('tr');

            if (currentRow.is(lastRow)) {
                e.preventDefault(); // stop normal tab behavior
                AddMoreRows();

                // Focus the next row’s first select field
                setTimeout(() => {
                    $('#sub_' + Counter).focus();
                }, 200);
            }
        }
    });

    // var Counter = 1;
    // function AddMoreRows() {
    //     Counter++;
    //     $('#AppendHtml').append('<tr class="text-center AutoNo" id="RemoveRow'+Counter+'">' +
    //         '<td>' +
    //         '<select name="item_id[]" class="form-control select2" id="sub_'+Counter+'" onchange="get_stock(\'main_warehouse_from\','+Counter+')">' +
    //         '<option value="0">Select item</option>' +
    //         '@foreach (CommonHelper::get_all_subitem_get() as $item)' +
    //         '<option value="{{$item->id}}">{{$item->sku_code." - ".$item->product_name}}</option>' +
    //         '@endforeach' +
    //         '</select>' +
    //         '</td>' +
    //         '<td class="hide">' +
    //         '<select onchange="get_stock_qty(this.id,'+Counter+')" class="form-control requiredField select2" name="batch_code[]" id="batch_code'+Counter+'">'+
    //         '<option value="">Select Batch</option>'+
    //         '</select>'+
    //         '</td>'+
    //         '<td>' +
    //         '<input type="text" style="width: 100%;" name="in_stock_qty[]" id="in_stock_qty'+Counter+'" class="form-control requiredField" readonly>' +
    //         '</td>' +
    //         '<td>' +
    //         '<input onkeyup="check_qty(this.id,'+Counter+')" type="text" name="qty[]" id="qty'+Counter+'" class="form-control requiredField SendQty">' +
    //         '</td>' +
    //         '<td style="display: none">' +
    //         '<input readonly type="number" name="rate[]" id="rate'+Counter+'" class="form-control">' +
    //         '</td>' +
    //         '<td style="display: none">' +
    //         '<input readonly type="number" name="amount[]" id="amount'+Counter+'" class="form-control">' +
    //         '</td>' +
    //         '<td><textarea name="des[]"></textarea></td>'+
    //         '<td>' +
    //         '<button type="button" class="btn btn-xs btn-danger" id="BtnRemove'+Counter+'" onclick="RemoveRows('+Counter+')">X</button>' +
    //         '</td>'+
    //         '</tr>'
    //     );
    // }
    // $(document).ready(function () {
    //    let territory_id = "{{ Auth::user()->territory_id }}";
    


    //     if (territory_id) {
    //         getWarehousesByTerritoryOnLoad(territory_id);
    //         getWarehousesByTerritoryOnLoadother(territory_id);
    //     }
    // });
    function getWarehousesByTerritoryOnLoad(territory_id) {
        $.ajax({
            url: '{{ route("ajax.get.warehouses.stocktarasfer") }}',
            type: 'GET',
            data: { territory_id: territory_id },
            success: function (data) {
                let $from = $('#main_warehouse_from');
                // let $to   = $('#main_warehouse_to');

                // Clear existing options
                $from.empty().append('<option value="">Select Warehouse</option>');
                // $to.empty().append('<option value="">Select Warehouse</option>');

                // Append new options
                $.each(data, function (i, warehouse) {
                    $from.append('<option value="' + warehouse.id + '">' + warehouse.name + '</option>');
                    // $to.append('<option value="' + warehouse.id + '">' + warehouse.name + '</option>');
                });

                // Re-init select2
                $from.trigger('change.select2');
                // $to.trigger('change.select2');
            },
            error: function (xhr) {
                console.error('Error fetching warehouses:', xhr.responseText);
            }
        });
    }
    function getWarehousesByTerritoryOnLoadother(territory_id) {
        $.ajax({
            url: '{{ route("ajax.get.warehouses.other.stocktarasfer") }}',
            type: 'GET',
            data: { territory_id: territory_id },
            success: function (data) {
                // let $from = $('#main_warehouse_from');
                let $to   = $('#main_warehouse_to');

                // Clear existing options
                // $from.empty().append('<option value="">Select Warehouse</option>');
                $to.empty().append('<option value="">Select Warehouse</option>');

                // Append new options
                $.each(data, function (i, warehouse) {
                    // $from.append('<option value="' + warehouse.id + '">' + warehouse.name + '</option>');
                    $to.append('<option value="' + warehouse.id + '">' + warehouse.name + '</option>');
                });

                // Re-init select2
                // $from.trigger('change.select2');
                $to.trigger('change.select2');
            },
            error: function (xhr) {
                console.error('Error fetching warehouses:', xhr.responseText);
            }
        });
    }
    function RemoveRows(Rows) {
        $('#RemoveRow'+Rows).remove();
        var AutoNo = $(".AutoNo").length;
        $('#span').text(AutoNo);
    }
    function updateAllWarehouseFrom() {
        var mainFromValue = $('#main_warehouse_from').val();
        var $to = $('#main_warehouse_to');

        // Enable all first
        $to.find('option').prop('disabled', false);

        if (mainFromValue) {
            // Disable same option in To
            $to.find('option[value="' + mainFromValue + '"]').prop('disabled', true);

            // If currently selected To == From → reset
            if ($to.val() == mainFromValue) {
                $to.val('').trigger('change');  // force reset
            }
        }
        $to.select2(); // re-init select2 to refresh UI
    }
    function updateAllWarehouseTo() {
        var mainToValue = $('#main_warehouse_to').val();
        var $from = $('#main_warehouse_from');

        // Enable all first
        $from.find('option').prop('disabled', false);

        if (mainToValue) {
            // Disable same option in From
            $from.find('option[value="' + mainToValue + '"]').prop('disabled', true);

            // If currently selected From == To → reset
            if ($from.val() == mainToValue) {
                $from.val('').trigger('change'); // force reset
            }
        }
        $from.select2(); // re-init select2 to refresh UI
    }
    function get_stock_for_first_row() {
        var mainWarehouseFrom = $('#main_warehouse_from').val();
        var item = $('#sub_1').val();
        
        if (mainWarehouseFrom && item && item != '0') {
            get_stock('main_warehouse_from', 1);
        }
    }

    function get_stock(warehouse, number) {
        $('#in_stock_qty' + number).val(0);
        $('#batch_code' + number).empty().append('<option value="">Select Batch</option>');
        
        var warehouseValue = $('#main_warehouse_from').val();
        var item = $('#sub_' + number).val(); // <-- FIXED

        console.log("warehouse", warehouseValue, "number", number, "item", item);

        // AJAX call
        $.ajax({
            url: '<?php echo url('/')?>/pdc/get_stock_location_wise',
            type: "GET",
            data: {warehouse: warehouseValue, item: item},
            success: function(data) {
                console.log("AJAX Response:", data);

                if (data && data !== '') {
                    var parts = data.split("/");
                    var batchDropdown = $('#batch_code' + number);
                    batchDropdown.empty().append('<option value="">Select Batch</option>');

                    for (var i = 0; i < parts.length; i++) {
                        if (parts[i] !== '' && !isNaN(parts[i])) {
                            batchDropdown.append('<option value="BATCH-' + (i + 1) + '">BATCH-' + (i + 1) + '</option>');
                        }
                    }

                    if (parts.length > 0 && parts[0] !== "" && !isNaN(parts[0])) {
                        batchDropdown.val('BATCH-1').trigger('change');
                        get_stock_qty('batch_code' + number, number);
                    }
                }
            }
        });
    }

    function get_stock_qty(warehouse, number) {
        var warehouseValue = $('#main_warehouse_from').val();
        var item = $('#sub_' + number).val(); // <-- FIXED
        var batch_code = $('#batch_code' + number).val();

        $.ajax({
            url: '<?php echo url('/')?>/pdc/get_stock_location_wise?batch_code='+batch_code,
            type: "GET",
            data: {warehouse: warehouseValue, item: item},
            success: function(data) {
                data = data.split('/');
                var stockQty = Math.floor(parseFloat(data[0]));
                $('#in_stock_qty' + number).val(stockQty);
                $('#rate'+number).val(data[1]);
                
                if (data[0] == 0) {
                    $("#sub_" + number).css("background-color", "red");
                } else {
                    $("#sub_" + number).css("background-color", "");
                }
            }
        });
    }
    function check_qty(id, number) {
        var qty = parseFloat($('#'+id).val());
        var instock = parseFloat($('#in_stock_qty'+number).val());

        if (qty > instock) {
            alert('Transfered QTY cannot be greater than actual qty');
            $('#'+id).val(0);
            $('#amount'+number).val(0);
        } else {
            var rate = parseFloat($('#rate'+number).val());
            var total = (qty * rate).toFixed(2);
            $('#amount'+number).val(total);
        }
    }
    $(document).ready(function(){
        // Initialize select2
        $('.select2').select2();
        
        $(".btn-success").click(function(e){
            var vala = 0;
            var flag = false;
            
            // Check if main warehouses are selected
            if (!$('#main_warehouse_from').val()) {
                alert('Please select Location From');
                $('#main_warehouse_from').focus();
                return false;
            }
            
            if (!$('#main_warehouse_to').val()) {
                alert('Please select Location To');
                $('#main_warehouse_to').focus();
                return false;
            }
            
            // Check if item is selected in first row
            if ($('#sub_1').val() == '0') {
                alert('Please select an item');
                $('#sub_1').focus();
                return false;
            }

            $('.SendQty').each(function(){
                vala = parseFloat($(this).val());
                if(vala <= 0 || isNaN(vala)) {
                    alert('Please Enter Correct Transfer Qty....!');
                    $(this).css('border-color','red');
                    flag = true;
                    return false;
                } else {
                    $(this).css('border-color','#ccc');
                }
            });
            
            if(flag == true) {
                return false;
            }
        });

        $('.stop').on('keyup keypress', function(e) {
            var keyCode = e.keyCode || e.which;
            if (keyCode === 13) {
                e.preventDefault();
                return false;
            }
        });
    });
    $(".btn-success").click(function(e){
        var flag = false;
        // Check remarks (description)
        if ($('#description').val().trim() === "") {
            alert('Please enter remarks.');
            $('#description').focus();
            return false;
        }
        // Check if main warehouses are selected
        if (!$('#main_warehouse_from').val()) {
            alert('Please select Location From');
            $('#main_warehouse_from').focus();
            return false;
        }
        if (!$('#main_warehouse_to').val()) {
            alert('Please select Location To');
            $('#main_warehouse_to').focus();
            return false;
        }
        // Check if item is selected in first row
        if ($('#sub_1').val() == '0') {
            alert('Please select an item');
            $('#sub_1').focus();
            return false;
        }
        // Check Transfer Qty
        $('.SendQty').each(function(){
            let qty = parseFloat($(this).val());
            if(qty <= 0 || isNaN(qty)) {
                alert('Please Enter Correct Transfer Qty....!');
                $(this).css('border-color','red');
                flag = true;
                return false;
            } else {
                $(this).css('border-color','#ccc');
            }
        });
        if(flag == true) {
            return false;
        }
        // Check line descriptions
        $('textarea[name="des[]"]').each(function(){
            if($(this).val().trim() === ""){
                alert('Please enter description for all items.');
                $(this).focus();
                flag = true;
                return false;
            }
        });
        if(flag == true) {
            return false;
        }
    });
</script> -->


<script>
    var Counter = 1;
    let allItems = @json(CommonHelper::get_all_subitem_get());
    let allStocks = @json(CommonHelper::get_all_stocks());


    function select_brand() {
        const brand_id = $("#brands").val();
        const $items = $(".items");

        // 1️⃣ Collect all currently selected values (so we don’t hide them)
        const selectedVals = $items.map(function () {
            return $(this).val();
        }).get().filter(v => v && v !== "0");

        // 2️⃣ Hide/disable all non-matching, non-selected options
        $items.find("option[data-brand]").each(function () {
            const $opt = $(this);
            const optBrand = $opt.data("brand");
            const optVal = $opt.val();

            const shouldHide = optBrand != brand_id && !selectedVals.includes(optVal);
            $opt.prop("disabled", shouldHide).prop("hidden", shouldHide);
        });

        // 3️⃣ Refresh all Select2s after DOM is updated
        // This ensures every select reflects updated option states
        $items.each(function () {
            const $select = $(this);
            const currentVal = $select.val(); // preserve selection
            const placeholder = $select.data("placeholder") || "Select an option";

            // Destroy + Re-init Select2 to apply visibility changes
            $select.select2("destroy").select2({
                placeholder,
                width: "100%",
            });

            // Reapply selected value if still valid
            if (currentVal && $select.find(`option[value='${currentVal}']`).length) {
                $select.val(currentVal).trigger("change.select2");
            }
        });
    }

    
    const itemMap = new Map(allItems.map(item => [item.id, item]));
    const stockMap = new Map(allStocks.map(stock => [stock.sub_item_id, stock]));
    $(document).ready(function() {
        $(".items").trigger("change");
    });
    // 🔹 Update barcode + hide duplicate options when item changes
    function selectElement(changedSelect) {
        
        const selectedVal = changedSelect.value;
        const item = itemMap.get(Number(selectedVal));
        const stock = stockMap.get(Number(selectedVal));

        // Get parent row using native DOM
        const row = changedSelect.closest("tr");

        if (item) {
            const barcodeInput = row.querySelector(".barCodes");
            if (barcodeInput) barcodeInput.value = item.product_barcode;
        }

        if (stock) {
            const stockInput = row.querySelector(".item-stock");
            if (stockInput) stockInput.value = stock.total_qty ?? "";
        }


        // Collect selected values
        // let selectedValues = [];
        // $('.item-dropdown, .items').each(function () {
        //     let val = $(this).val();
        //     if (val !== "0" && val) selectedValues.push(val);
        // });

        // // Hide duplicate options across dropdowns
        // $('.item-dropdown, .items').each(function () {
        //     let $dropdown = $(this);
        //     let currentValue = $dropdown.val();
        //     $dropdown.find('option').each(function () {
        //         let optionVal = $(this).val();
        //         if (optionVal !== "0" && optionVal !== currentValue) {
        //             $(this).toggle(!selectedValues.includes(optionVal));
        //         }
        //     });
        // });
    }

    // 🔹 Manage previously selected item (restore old options)
    $(document).on('select2:opening', '.items', function () {
        const $this = $(this);
        const selectedIndex = this.selectedIndex;

        if (selectedIndex >= 0) {
            const selectedOption = this.options[selectedIndex];
            const value = selectedOption.value;
            const label = selectedOption.text;

            if (parseInt(value) > 0) {
                $this.data('oldValue', value + "~" + label);
            } else {
                $this.removeData('oldValue'); // Clean up if needed
            }
        }
    });


    $(document).on('change', '.items', function () {
        const $current = $(this);
        const newVal = $current.val();
        const oldVal = $current.data('oldValue') ?? null;
        const brand_id = $("#brands").val();

        // Cache other selects once
        const $otherItems = $('.items').not(this);

        // Remove new value from all other selects in one pass

        $otherItems.each(function () {
            $(this).find(`option[data-item="${newVal}"]`).remove();
        });

        // Restore old value if valid and not already present
        if (oldVal) {
            const [oldValue, oldLabel] = oldVal.split("~");
            if (parseInt(oldValue) > 0) {
                const optionHTML = `<option value="${oldValue}">${oldLabel}</option>`;
                $otherItems.each(function () {
                    const $select = $(this);
                    if ($select.find(`option[value="${oldValue}"]`).length === 0) {
                        // Insert only if not present
                        $select.find('option[value="0"]').after(optionHTML);
                    }
                });
            }
        }
    });

    // 🔹 Add More Rows
    function AddMoreRows() {
       Counter++;

let selectedSet = new Set($('.items').map(function () {
    return $(this).val();
}).get());

let optionsHtml = '<option value="0">Select item</option>';
for (const item of allItems) {
    let brand_id = $("#brands").val();
    if (!selectedSet.has(item.id.toString())) {
        optionsHtml += `<option value="${item.id}" data-brand="${item.brand_id}">${item.sku_code} - ${item.product_name}</option>`;
    }
}
 

$('#AppendHtml').append(`
    <tr class="text-center AutoNo" id="RemoveRow${Counter}">
        <td>
            <select onchange="selectElement(this)" name="item_id[]" class="form-control select2 items" id="sub_${Counter}">
                ${optionsHtml}
            </select>
        </td>
        <td>
            <input readonly type="text" style="text-align: center;" name="barcodes[]" class="form-control barCodes" />
        </td>
        <td class="hide">
            <select onchange="get_stock_qty(this.id,${Counter})" class="form-control select2" name="batch_code[]" id="batch_code${Counter}">
                <option value="">Select Batch</option>
            </select>
        </td>
        <td><input type="text" name="in_stock_qty[]" id="in_stock_qty${Counter}" class="item-stock form-control" readonly></td>
        <td><input onkeyup="check_qty(this.id,${Counter})" type="text" name="qty[]" id="qty${Counter}" class="form-control SendQty"></td>
        <td style="display: none"><input readonly type="number" name="rate[]" id="rate${Counter}" class="form-control"></td>
        <td style="display: none"><input readonly type="number" name="amount[]" id="amount${Counter}" class="form-control"></td>
        <td><button type="button" class="btn btn-xs btn-danger" onclick="RemoveRows(${Counter})">X</button></td>
    </tr>
`);

// Initialize select2 only for the new elements
$(`#sub_${Counter}, #batch_code${Counter}`).select2();

$("#brands").trigger("change");

    }

    // 🔹 Remove Row
    function RemoveRows(id) {
        $('#RemoveRow' + id).remove();
        $('#span').text($('.AutoNo').length);
    }

    // 🔹 Tab key → auto add next row
    $(document).on('keydown', 'input[name="qty[]"]', function (e) {
        if (e.key === 'Tab' && !e.shiftKey) {
            let currentRow = $(this).closest('tr');
            if (currentRow.is($('#AppendHtml tr:last'))) {
                e.preventDefault();
                AddMoreRows();
                setTimeout(() => $('#sub_' + Counter).focus(), 200);
            }
        }
    });

    // 🔹 Warehouse handling
    function updateAllWarehouseFrom() {
        var from = $('#main_warehouse_from').val();
        var $to = $('#main_warehouse_to');
        $to.find('option').prop('disabled', false);
        if (from) {
            $to.find('option[value="' + from + '"]').prop('disabled', true);
            if ($to.val() == from) $to.val('').trigger('change');
        }
        $to.select2();
    }

    function updateAllWarehouseTo() {
        var to = $('#main_warehouse_to').val();
        var $from = $('#main_warehouse_from');
        $from.find('option').prop('disabled', false);
        if (to) {
            $from.find('option[value="' + to + '"]').prop('disabled', true);
            if ($from.val() == to) $from.val('').trigger('change');
        }
        $from.select2();
    }

    // 🔹 Stock functions
    function get_stock(warehouse, number) {
        $('#in_stock_qty' + number).val(0);
        $('#batch_code' + number).html('<option value="">Select Batch</option>');
        let warehouseValue = $('#main_warehouse_from').val();
        let item = $('#sub_' + number).val();

        $.ajax({
            url: '{{ url("/pdc/get_stock_location_wise") }}',
            type: "GET",
            data: { warehouse: warehouseValue, item: item },
            success: function (data) {
                if (!data) return;
                let parts = data.split("/");
                let $batch = $('#batch_code' + number);
                $batch.html('<option value="">Select Batch</option>');
                parts.forEach((val, i) => {
                    if (val && !isNaN(val)) {
                        $batch.append(`<option value="BATCH-${i + 1}">BATCH-${i + 1}</option>`);
                    }
                });
                if (parts[0] && !isNaN(parts[0])) {
                    $batch.val('BATCH-1').trigger('change');
                    get_stock_qty('batch_code' + number, number);
                }
            }
        });
    }

    function get_stock_qty(id, number) {
        let warehouseValue = $('#main_warehouse_from').val();
        let item = $('#sub_' + number).val();
        let batch_code = $('#' + id).val();
        $.ajax({
            url: '{{ url("/pdc/get_stock_location_wise") }}?batch_code=' + batch_code,
            type: "GET",
            data: { warehouse: warehouseValue, item: item },
            success: function (data) {
                let parts = data.split('/');
                let stockQty = parseFloat(parts[0]) || 0;
                $('#in_stock_qty' + number).val(stockQty);
                $('#rate' + number).val(parts[1]);
                $("#sub_" + number).css("background-color", stockQty === 0 ? "red" : "");
            }
        });
    }

    function check_qty(id, number) {
        let qty = parseFloat($('#' + id).val());
        let instock = parseFloat($('#in_stock_qty' + number).val());
        if (qty > instock) {
            alert('Transferred QTY cannot be greater than actual qty');
            $('#' + id).val(0);
            $('#amount' + number).val(0);
        } else {
            let rate = parseFloat($('#rate' + number).val()) || 0;
            $('#amount' + number).val((qty * rate).toFixed(2));
        }
    }

    // 🔹 Validation before submit
    $(document).ready(function () {
        $('.select2').select2();

        $(".btn-success").click(function () {
            if (!$('#description').val().trim()) {
                alert('Please enter remarks.');
                $('#description').focus();
                return false;
            }
            if (!$('#main_warehouse_from').val()) {
                alert('Please select Location From');
                $('#main_warehouse_from').focus();
                return false;
            }
            if (!$('#main_warehouse_to').val()) {
                alert('Please select Location To');
                $('#main_warehouse_to').focus();
                return false;
            }
            if ($('#sub_1').val() == '0') {
                alert('Please select an item');
                $('#sub_1').focus();
                return false;
            }

            let isInvalid = false;
            $('.SendQty').each(function () {
                let qty = parseFloat($(this).val());
                if (qty <= 0 || isNaN(qty)) {
                    alert('Please enter correct Transfer Qty!');
                    $(this).css('border-color', 'red');
                    isInvalid = true;
                    return false;
                }
                $(this).css('border-color', '#ccc');
            });
            if (isInvalid) return false;

            $('textarea[name="des[]"]').each(function () {
                if ($(this).val().trim() === "") {
                    alert('Please enter description for all items.');
                    $(this).focus();
                    isInvalid = true;
                    return false;
                }
            });
            if (isInvalid) return false;
        });

        // Prevent Enter key submit
        $('.stop').on('keypress', function (e) {
            if (e.which === 13) e.preventDefault();
        });
    });
</script>



<script src="{{ URL::asset('assets/js/select2/js_tabindex.js') }}"></script>
@endsection