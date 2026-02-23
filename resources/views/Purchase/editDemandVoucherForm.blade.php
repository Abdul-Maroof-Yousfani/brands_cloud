<?php
use App\Helpers\NotificationHelper;
$accType = Auth::user()->acc_type;
$currentDate = date('Y-m-d');
if ($accType == 'client') {
    $m = $_GET['m'];
} else {
    $m = Auth::user()->company_id;
}
use App\Helpers\PurchaseHelper;
use App\Helpers\CommonHelper;
?>
@extends('layouts.default')

@section('content')
    @include('select2')
    @include('modal')


    <style>
        * {
            font-size: 12px !important;
            font-family: Arial;
        }

        .select2 {
            width: 100%;
        }
    </style>
    <?php
    
    ?>

    <?php
    
    $demand_no = $demand->demand_no;
    ?>
    <div class="container-fluid">
        <div class="row ">
            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12" style="display: none;">
                @include('Purchase.' . $accType . 'purchaseMenu')
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="well_N">
                    <div class="dp_sdw">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <span class="subHeadingLabelClass">Edit Purchase Request Form</span>
                            </div>
                        </div>
                        <div class="lineHeight">&nbsp;</div>
                        <div class="row">
                            <?php echo Form::open(['url' => 'pad/updateDemandDetail?m=' . $m . '', 'id' => 'cashPaymentVoucherForm', 'class' => 'stop']); ?>
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="pageType" value="<?php //echo $_GET['pageType']
                            ?>">
                            <input type="hidden" name="parentCode" value="<?php //echo $_GET['parentCode']
                            ?>">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="panel">
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <input type="hidden" name="demandsSection[]"
                                                    class="form-control requiredField" id="demandsSection" value="1" />
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div class="row">

                                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                        <label class="sf-label">PR NO. <span
                                                                class="rflabelsteric"><strong>*</strong></span></label>
                                                        <input readonly type="text" class="form-control requiredField"
                                                            placeholder="" name="pr_no" id="pr_no"
                                                            value="{{ strtoupper($demand_no) }}" />
                                                        <input type="hidden" name="EditId" value="<?php echo $id; ?>">
                                                    </div>

                                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                        <label class="sf-label">PR Date.</label>
                                                        <span class="rflabelsteric"><strong>*</strong></span>
                                                        <input type="date" class="form-control requiredField"
                                                            max="<?php echo date('Y-m-d'); ?>" name="demand_date_1"
                                                            id="demand_date_1" value="<?php echo $demand->demand_date; ?>" />
                                                    </div>
                                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                        <label class="sf-label">Ref No. <span
                                                                class="rflabelsteric"><strong>*</strong></span></label>
                                                        <input autofocus type="text" class="form-control requiredField"
                                                            placeholder="Ref  No" name="slip_no_1" id="slip_no_1"
                                                            value="<?php echo $demand->slip_no; ?>" />
                                                    </div>
                                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                        <label class="sf-label">Required Date.</label>
                                                        <span class="rflabelsteric"><strong>*</strong></span>
                                                        <input type="date" class="form-control requiredField"
                                                            min="<?php echo date('Y-m-d'); ?>" name="required_date"
                                                            id="required_date" value="{{ $demand->required_date }}" />
                                                    </div>



                                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                        <label class="sf-label">Type</label>
                                                        <span class="rflabelsteric"><strong>*</strong></span>
                                                        <select class="form-control requiredField select2" name="v_type"
                                                            id="v_type">
                                                            <option value="">Select Department</option>
                                                            @foreach (NotificationHelper::get_all_type() as $row)
                                                                <option @if ($demand->p_type == $row->id) selected @endif
                                                                    value="{{ $row->id }}">{{ $row->name }}</option>
                                                            @endforeach
                                                        </select>

                                                    </div>
                                                </div>
                                                <input type="hidden" name="demand_type" id="demand_type">
                                                <div class="row">


                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div class="row">
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                        <label class="sf-label">Description</label>
                                                        <textarea name="description_1" id="description_1" rows="4" cols="50" style="resize:none;"
                                                            class="form-control "><?php echo $demand->description; ?></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="lineHeight">&nbsp;</div>

                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div class="table-responsive" id="">
                                                    <table class="table table-bordered">
                                                        <thead>
                                                            <tr class="text-center">
                                                                <th colspan="6" class="text-center">
                                                                    Item Details
                                                                </th>
                                                                <th colspan="3" class="text-center">
                                                                    <input type="button" class="btn btn-sm btn-primary"
                                                                        onclick="AddMoreDetails()"
                                                                        value="Add More Rows" />
                                                                </th>

                                                            </tr>
                                                            <tr>
                                                                <th style="width: 250px" class="text-center hide">Category
                                                                </th>
                                                                <th style="width: 250px" class="text-center hide">Item
                                                                    Code
                                                                </th>
                                                                <th style="width: 250px" class="text-center">Brand
                                                                </th>
                                                                <th style="width: 250px" class="text-center">Product Name
                                                                </th>

                                                                <th style="width: 250px" class="text-center">Product Type
                                                                </th>
                                                                <th style="width: 250px" class="text-center">Product
                                                                    Barcode
                                                                </th>
                                                                <th style="width: 250px" class="text-center">Product
                                                                    Classification
                                                                </th>
                                                                <th style="width: 250px" class="text-center">Product Trend
                                                                </th>
                                                                <th style="width: 100px" class="text-center">UOM<span
                                                                        class="rflabelsteric"><strong>*</strong></span>
                                                                </th>
                                                                <th style="width: 130px" class="text-center">QTY<span
                                                                        class="rflabelsteric"><strong>*</strong></span>
                                                                </th>
                                                                <th style="width: 130px" class="text-center hide">Closing
                                                                    Stock<span
                                                                        class="rflabelsteric"><strong>*</strong></span>
                                                                </th>
                                                                <th style="width: 130px" class="text-center hide">Last
                                                                    Order
                                                                    QTY</th>
                                                                <th style="width: 130px" class="text-center hide">Last
                                                                    Received QTY</th>
                                                                <th style="width: 100px" class="text-center hide">History
                                                                </th>
                                                                <th style="width: 100px" class="text-center">Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="AppnedHtml">
                                                            <?php
                                                $Counter = 0;
                                                foreach($demand_data as $Fil):
                                    
                                                $Counter++;
                                                $SubItem = CommonHelper::get_single_row('subitem','id',$Fil->sub_item_id);
                                                $ItemDetail = CommonHelper::get_data($Fil->sub_item_id);
                                                $ItemDetail = explode(',',$ItemDetail);
                                                $main_ic = $SubItem->main_ic_id;
                                                        

                                                ?>
                                                            <tr class="RemoveRows<?php echo $Counter; ?> AutoNo main">


                                                                <td>
                                                                    <select style="width: 150px;"
                                                                        onChange="get_product_by_brand(this,1)"
                                                                        name="brand_id[]" class="form-control select2"
                                                                        id="brand_id1">
                                                                        <option value="">Select</option>
                                                                        @foreach (CommonHelper::get_all_brand() as $item)
                                                                            <option
                                                                                {{ $Fil->brand_id == $item->id ? 'selected' : '' }}
                                                                                value="{{ $item->id }}">
                                                                                {{ $item->name }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </td>

                                                                <td>
                                                                    <select autofocus
                                                                        onchange="get_type_barcode_by_product('productName1')"
                                                                        name="item_id[]"
                                                                        id="productName{{ $Counter }}"
                                                                        class="form-control requiredField select2 itemsclass"
                                                                        style="width:200px !important;">
                                                                        <option value="">Select Products</option>
                                                                        <?php echo CommonHelper::getSubItemByBrand($Fil->brand_id, $Fil->sub_item_id); ?>

                                                                    </select>
                                                                </td>




                                                                <td>
                                                                    <input readonly type="text" class="form-control"
                                                                        name="product_type[]"
                                                                        id="product_type{{ $Counter }}">
                                                                </td>
                                                                <td>
                                                                    <input readonly type="text" class="form-control"
                                                                        name="product_barcode[]"
                                                                        id="product_barcode{{ $Counter }}">
                                                                </td>
                                                                <td>
                                                                    <input readonly type="text" class="form-control"
                                                                        name="product_classification[]"
                                                                        id="product_classification{{ $Counter }}">
                                                                </td>
                                                                <td>
                                                                    <input readonly type="text" class="form-control"
                                                                        name="product_trend[]"
                                                                        id="product_trend{{ $Counter }}">
                                                                </td>
                                                                <td>
                                                                    <input readonly type="text" class="form-control"
                                                                        name="uom_id[]" id="uom_id{{ $Counter }}">
                                                                </td>
                                                                <td>
                                                                    <input type="text"
                                                                        class="form-control requiredField"
                                                                        name="quantity[]" value="{{ $Fil->qty }}"
                                                                        id="quantity{{ $Counter }}">
                                                                </td>
                                                                <td class="hide">
                                                                    <input readonly type="text" class="form-control"
                                                                        name="closing_stock[]"
                                                                        id="closing_stock{{ $Counter }}">
                                                                </td>
                                                                <td class="hide">
                                                                    <input readonly type="text" class="form-control"
                                                                        name="last_ordered_qty[]"
                                                                        id="last_ordered_qty{{ $Counter }}">
                                                                </td>
                                                                <td class="hide">
                                                                    <input readonly type="text" class="form-control"
                                                                        name="last_received_qty[]"
                                                                        id="last_received_qty{{ $Counter }}">
                                                                </td>
                                                                <td class="text-center hide"><input
                                                                        onclick="view_history(1)" type="checkbox"
                                                                        id="view_history{{ $Counter }}">
                                                                </td>
                                                                <td class="text-center">
                                                                    <div style="display: flex; justify-content: center; gap: 5px;">
                                                                        <?php if($Counter > 1):?>
                                                                        <button type="button" class="btn btn-xs btn-danger"
                                                                            id="BtnRemove'+Counter+'"
                                                                            onclick="RemoveSection('<?php echo $Counter; ?>')"><span class="glyphicon glyphicon-trash"></span></button>

                                                                        <?php endif;?>


                                                                        <a href="#" class="btn btn-sm btn-primary"
                                                                            onclick="AddMoreDetails()"><span
                                                                                class="glyphicon glyphicon-plus-sign"></span></a>
                                                                    </div>
                                                                </td>
                                                            </tr>

                                                            <?php
                                                endforeach;
                                                ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="demandsSection"></div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                                    {{ Form::submit('Submit', ['class' => 'btn btn-success']) }}

                                </div>
                            </div>
                            <?php echo Form::close(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            var count = 0;
            @foreach ($demand_data as $index => $row)
                count += 1;
                get_type_barcode_by_product('productName' + count)
            @endforeach
        });
        var Counter = '<?php echo $Counter; ?>';


        function AddMoreDetails() {
            Counter++;


            var previousBrandId = $('#brand_id' + (Counter - 1)).val();


            var category = 'CategoryId' + Counter;
            var sub_category = 'sub_category' + Counter;
            var ProductName = 'productName' + Counter;
            // Append the new row
            $('#AppnedHtml').append(
                '<tr class="RemoveRows' + Counter + ' AutoNo main">' +
                '<td class="hide">' +
                '<select onchange="get_sub_category_by_id(`' + category + '`)" name="category" id="CategoryId' +
                Counter +
                '" class="form-control category select2 normal_width">' +
                '<option value="">Select</option>' +
                '@foreach (CommonHelper::get_all_category() as $category):' +
                '<option value="{{ $category->id }}"> {{ $category->main_ic }} </option>' +
                '@endforeach' +
                '</select>' +
                '</td>' +
                '<td class="hide">' +
                '<select onchange="get_product_by_id(`' + sub_category + '`)" name="SubCategoryId[]" id="sub_category' +
                Counter +
                '" class="form-control select2">' +
                '<option> Select Sub Category</option>' +
                '</select>' +
                '</td>' +
                '<td>' +
                '<select style="width: 150px;" onChange="get_product_by_brand(this,`' + Counter +
            '`)" name="brand_id[]" class="form-control" id="brand_id' + Counter + '">' +
                '<option value="">Select</option>' +
                '@foreach (CommonHelper::get_all_brand() as $item)' +
                '<option value="{{ $item->id }}">' +
                '{{ $item->name }}' +
                '</option>' +
                '@endforeach' +
                '</select>' +
                '</td>' +
                '<td>' +
                '<select onchange="get_type_barcode_by_product(`' + ProductName +
            '`)" name="item_id[]" id="productName' +
                Counter +
                '" class="form-control select2 itemsclass">' +
                '<option value="">Select Product</option>' +
                '</select>' +
                '</td>' +
                '<td>' +
                '<input tabindex="-1" readonly type="text" class="form-control" name="product_type[]" id="product_type' +
                Counter + '">' +
                '</td>' +
                '<td>' +
                '<input tabindex="-1" readonly type="text" class="form-control" name="product_barcode[]" id="product_barcode' +
                Counter +
                '">' +
                '</td>' +
                '<td>' +
                '<input tabindex="-1" readonly type="text" class="form-control" name="product_classification[]" id="product_classification' +
                Counter + '">' +
                '</td>' +
                '<td>' +
                '<input tabindex="-1" readonly type="text" class="form-control" name="product_trend[]" id="product_trend' +
                Counter + '">' +
                '</td>' +
                '<td>' +
                '<input tabindex="-1" readonly type="text" class="form-control" name="uom_id[]" id="uom_id' + Counter +
                '">' +
                '</td>' +
                '<td>' +
                '<input type="text" class="form-control next-total requiredField" name="quantity[]" id="quantity' +
                Counter + '">' +
                '</td>' +
                '<td class="hide">' +
                '<input tabindex="-1" readonly type="text" class="form-control" name="closing_stock[]" id="closing_stock' +
                Counter + '">' +
                '</td>' +
                '<td class="hide">' +
                '<input tabindex="-1" readonly type="text" class="form-control" name="last_ordered_qty[]" id="last_ordered_qty' +
                Counter + '">' +
                '</td>' +
                '<td class="hide">' +
                '<input tabindex="-1" readonly type="text" class="form-control" name="last_received_qty[]" id="last_received_qty' +
                Counter + '">' +
                '</td>' +
                '<td class="text-center hide">' +
                '<input onclick="view_history(' + Counter +
                ')" type="checkbox" id="view_history' + Counter + '">' +
                '</td>' +
                '<td class="text-center">' +
                '<div style="display: flex; justify-content: center; gap: 5px;">' +
                '<button type="button" class="btn btn-danger" id="BtnRemove' + Counter + '" onclick="RemoveSection(' +
                Counter + ')"><span class="glyphicon glyphicon-trash"></span></button>' +
                '<a href="#" class="btn btn-sm btn-primary" onclick="AddMoreDetails()"><span class="glyphicon glyphicon-plus-sign"></span></a>' +
                '</div>' +
                '</td>' +
                '</tr>'
            );



            // Initialize Select2 for the new row
            $('#CategoryId' + Counter).select2();
            $('#sub_category' + Counter).select2();
            $('#brand_id' + Counter).select2();
            $('#productName' + Counter).select2();

            // Set the new brand_id select to the previous value
            if (previousBrandId) {
                $('#brand_id' + Counter).val(previousBrandId).trigger('change');
            }
            var AutoCount = 1;
            $(".AutoCounter").each(function() {
                AutoCount++;
                $(this).html(AutoCount);
            });

            var AutoNo = $(".AutoNo").length;
            $('#span').text(AutoNo);


            $('.sam_jass').bind("enterKey", function(e) {


                $('#items').modal('show');
            });
            $('.sam_jass').keyup(function(e) {
                if (e.keyCode == 13) {
                    selected_id = this.id;
                    $(this).trigger("enterKey");
                }
            });
        }

        function get_product_by_brand(element, number) {
            var value = element.value;
            $(element).closest('.main').find('.itemsclass').empty();
            $.ajax({
                url: '{{ url('/getSubItemByBrand') }}',
                type: 'Get',
                data: {
                    id: value
                },
                success: function(data) {
                    $(element).closest('.main').find('.itemsclass').append(data);
                }
            });
        }

        function RemoveSection(Row) {
            //            alert(Row);
            $('.RemoveRows' + Row).remove();
            $(".AutoCounter").html('');
            var AutoCount = 1;
            $(".AutoCounter").each(function() {
                AutoCount++;
                $(this).html(AutoCount);
            });
            var AutoNo = $(".AutoNo").length;
            $('#span').text(AutoNo);
        }


        function clear_fiel(id) {
            $('#' + id).prop('readonly', false);
            $('#' + id).val('');

        }

        $('.sam_jass').bind("enterKey", function(e) {


            $('#items').modal('show');
            e.preventDefault();

        });
        $('.sam_jass').keyup(function(e) {
            if (e.keyCode == 13) {
                selected_id = this.id;
                $(this).trigger("enterKey");
                e.preventDefault();

            }

        });


        $('.stop').on('keyup keypress', function(e) {
            var keyCode = e.keyCode || e.which;
            if (keyCode === 13) {
                e.preventDefault();
                return false;
            }
        });

        function get_sub_category_by_id(id) {
            var category = $('#' + id).val();
            var index_val = id.replace("CategoryId", "");

            $('#sub_category' + index_val).html('');
            if (category) {
                $.ajax({
                    url: '/pdc/get_sub_category_by_id',
                    type: 'Get',
                    data: {
                        category: category
                    },
                    success: function(response) {
                        $('#sub_category' + index_val).append(response);
                    }
                });
            }

        }

        $(function() {



            $(".btn-success").click(function(e) {
                var purchaseRequest = new Array();
                var val;
                //$("input[name='demandsSection[]']").each(function(){
                purchaseRequest.push($(this).val());
                //});
                var _token = $("input[name='_token']").val();
                for (val of purchaseRequest) {
                    jqueryValidationCustom();
                    if (validate == 0) {

                        $('#cashPaymentVoucherForm').submit();
                    } else {
                        return false;
                    }
                }

            });
        });

        function get_product_by_id(id) {

            var sub_category = $('#' + id).val();
            var index_val = id.replace("sub_category", "");
            console.log('index value is ' + index_val);
            $('#productName' + index_val).html('');

            //  $('#productName').empty();
            var category = $('#CategoryId').val();
            //  var sub_category = $('#sub_category').val();
            //  var item_master_id = $('#item_master').val();
            if (sub_category > 0) {
                $.ajax({
                    url: '/pdc/get_product_by_id',
                    type: 'Get',
                    data: {
                        category: category,
                        sub_category: sub_category
                    },
                    success: function(response) {
                        $('#productName' + index_val).append(response);

                    }
                });
            } else {
                $('#item_code').val('');
            }

        }

        function get_type_barcode_by_product(id) {

            var productName = $('#' + id).val();
            var index_val = id.replace("productName", "");
            $('#product_type' + index_val).html('');
            $('#product_barcode' + index_val).html('');
            $('#product_classification' + index_val).html('');
            $('#product_trend' + index_val).html('');
            $('#uom_id' + index_val).html('');

            //  $('#productName').empty();
            var category = $('#CategoryId').val();
            //  var sub_category = $('#sub_category').val();
            //  var item_master_id = $('#item_master').val();
            if (productName > 0) {
                $.ajax({
                    url: '/pdc/get_type_barcode_by_product',
                    type: 'Get',
                    data: {
                        // category: category,
                        productName: productName
                    },
                    success: function(response) {
                        $('#product_type' + index_val).val(response.product_type_id);
                        $('#product_barcode' + index_val).val(response.product_barcode);
                        $('#product_classification' + index_val).val(response.product_classification_id);
                        $('#product_trend' + index_val).val(response.product_trend_id);
                        $('#uom_id' + index_val).val(response.uom);
                    }
                });
            } else {
                $('#item_code').val('');
            }

        }
    </script>


    <script>
        function get_detail(id, number) {
            var item = $('#' + id).val();


            $.ajax({
                url: '{{ url(' / pdc / get_data ') }}',
                data: {
                    item: item
                },
                type: 'GET',
                success: function(response) {

                    var data = response.split(',');
                    $('#uom_id' + number).val(data[0]);
                    $('#last_ordered_qty' + number).val(data[1]);
                    $('#last_received_qty' + number).val(data[2]);
                    $('#closing_stock' + number).val(data[3]);

                }
            })



        }
    </script>

    <script>
        function view_history(id) {

            var v = $('#sub_item_id_1_' + id).val();


            if ($('#history_1_' + id).is(":checked")) {
                if (v != null) {
                    showDetailModelOneParamerter('pdc/viewHistoryOfItem?id=' + v);
                } else {
                    alert('Select Item');
                }

            }





        }

        function get_item_name(index) {

            var item = $('#item_id' + index).val();
            var uom = item.split('@');
            $('#uom_id' + index).val(uom[1]);
            $('#item_code' + index).val(uom[2]);
        }
    </script>


    <script type="text/javascript">
        $('.select2').select2();
    </script>

    <script src="{{ URL::asset('assets/js/select2/js_tabindex.js') }}"></script>
@endsection
