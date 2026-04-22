<?php
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
    <div class="container-fluid">
        <div class="well_N">
            <div class="dp_sdw">
                <div class="panel">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6">
                                                <span class="subHeadingLabelClass">Stock Out Form</span>
                                            </div>
                                            <div class="col-lg-6 col-md-6 text-right">
                                                <a href="{{ url('store/stock_out_list?m=' . $m) }}" class="btn btn-primary btn-sm">View Stock Out List</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="lineHeight">&nbsp;</div>
                                <div class="row">
                                    <?php echo Form::open(['url' => 'pad/addStockOutDetail?m=' . $m . '', 'id' => 'addStockOutDetail', 'class' => 'stop']); ?>
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="hidden" name="pageType" value="<?php echo $_GET['pageType'] ?? 'view'; ?>">
                                    <input type="hidden" name="parentCode" value="<?php echo $_GET['parentCode'] ?? '0'; ?>">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="row">
                                            <?php $uniq = PurchaseHelper::get_unique_no_stock_out(date('y'), date('m')); ?>
                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                <label for="">Stock Out No</label>
                                                <input type="text" id="so_no" name="so_no"
                                                    value="{{ strtoupper($uniq) }}" class="form-control requiredField"
                                                    readonly>
                                            </div>
                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                <label for="">Date</label>
                                                <input type="date" class="form-control requiredField" id="so_date"
                                                    name="so_date" value="<?php echo date('Y-m-d'); ?>">
                                            </div>
                                            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                                                <label for="">Reference / Remarks</label>
                                                <textarea type="text" name="description" id="description" class="form-control requiredField"></textarea>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                <label for="">Warehouse From</label>
                                                <select onchange="get_all_stocks_for_wh()" name="main_warehouse_from"
                                                    id="main_warehouse_from" class="form-control requiredField select2">
                                                    <option value="">Select Warehouse From</option>
                                                    <?php foreach(CommonHelper::get_all_warehouse() as $Fil):?> 
                                                        @if($Fil->is_virtual == 0)
                                                            <option value="<?php echo $Fil->id; ?>"><?php echo $Fil->name; ?></option> 
                                                        @endif
                                                    <?php endforeach;?>
                                                </select>
                                            </div>

                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                <label for="">Warehouse To</label>
                                                <select name="main_warehouse_to"
                                                    id="main_warehouse_to" class="form-control requiredField select2">
                                                    <option value="">Select Warehouse To</option>
                                                    <?php foreach(CommonHelper::get_all_warehouse() as $Fil):?> 
                                                        @if($Fil->is_virtual == 0)
                                                            <option value="<?php echo $Fil->id; ?>"><?php echo $Fil->name; ?></option> 
                                                        @endif
                                                    <?php endforeach;?>
                                                </select>
                                            </div>

                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                <label for="">Brands (Filter)</label>
                                                <select onchange="select_brand()" name="brands" id="brands"
                                                    class="form-control select2">
                                                    <option value="">All Brands</option>
                                                    <?php foreach(CommonHelper::get_all_brand() as $brand):?> <option value="<?php echo $brand->id; ?>">
                                                        <?php echo $brand->name; ?></option> <?php endforeach;?>
                                                </select>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div class="table-responsive" style="height: 400px; overflow-y: auto;">
                                                    <table class="table table-bordered sf-table-list">
                                                        <thead>
                                                            <tr class="text-center">
                                                                <th colspan="4" class="text-center">Stock Out Detail</th>
                                                                <th class="text-center">
                                                                    <span class="badge badge-success" id="span">1</span>
                                                                </th>
                                                            </tr>
                                                            <tr>
                                                                <th class="text-center" style="width: 35%">Item Name</th>
                                                                <th class="text-center" style="width: 15%">Barcode</th>
                                                                <th class="text-center">Available Stock</th>
                                                                <th class="text-center">Quantity Out</th>
                                                                <th class="text-center">Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="AppendHtml">
                                                            <tr class="text-center AutoNo">
                                                                <td>
                                                                    <select onchange="selectElement(this)" name="item_id[]"
                                                                        class="form-control select2 items" id="sub_1">
                                                                        <option value="0">Select item</option>
                                                                        @foreach (CommonHelper::get_all_subitem_get() as $item)
                                                                            <option value="{{ $item->id }}"
                                                                                data-brand="{{ $item->brand_id }}">
                                                                                {{ $item->sku_code . ' - ' . $item->product_name }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </td>
                                                                <td>
                                                                    <input readonly type="text" name="barcodes[]"
                                                                        class="form-control barCodes"
                                                                        style="width: 100%; text-align: center;">
                                                                </td>
                                                                <td>
                                                                    <input readonly type="text" name="in_stock_qty[]"
                                                                        id="in_stock_qty1" class="form-control item-stock"
                                                                        style="width: 100%; text-align: center;">
                                                                </td>
                                                                <td>
                                                                    <input onkeyup="check_qty(this.id,1)" type="number" name="qty[]" id="qty1"
                                                                        class="form-control requiredField SendQty"
                                                                        step="any" min="0.01">
                                                                </td>
                                                                <td><button type="button" class="btn btn-xs btn-primary"
                                                                         onclick="AddMoreRows()">Add More</button></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                                            <button type="submit" class="btn btn-success">Submit Stock Out</button>
                                            <button type="reset" class="btn btn-danger">Clear Form</button>
                                        </div>
                                    </div>
                                    <?php echo Form::close(); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        var Counter = 1;
        let allItems = @json(CommonHelper::get_all_subitem_get());

        function select_brand() {
            const brand_id = $("#brands").val();
            
            $(".items").each(function() {
                const $select = $(this);
                const currentVal = $select.val();
                
                $select.find("option").each(function() {
                    const $opt = $(this);
                    if ($opt.val() == "0") return;
                    
                    const optBrand = $opt.data("brand");
                    const shouldHide = brand_id && optBrand != brand_id;
                    
                    $opt.prop("disabled", shouldHide);
                    if (shouldHide) {
                        $opt.attr("hidden", "hidden");
                    } else {
                        $opt.removeAttr("hidden");
                    }
                });

                // Clear value if current selection doesn't match new brand
                if (brand_id && currentVal != "0") {
                    const selectedOpt = $select.find("option[value='" + currentVal + "']");
                    if (selectedOpt.data("brand") != brand_id) {
                        $select.val("0").trigger('change');
                    }
                }
                
                $select.select2('destroy').select2();
            });
        }

        const itemMap = new Map(allItems.map(item => [item.id, item]));

        function selectElement(changedSelect) {
            const selectedVal = changedSelect.value;
            const item = itemMap.get(Number(selectedVal));
            const row = changedSelect.closest("tr");
            const number = row.id ? row.id.replace('RemoveRow', '') : 1;

            // Nullify Quantity Out when item changes
            $(row).find('.SendQty').val('');

            if (item) {
                row.querySelector(".barCodes").value = item.product_barcode || '';
                get_stock_qty(number);
            }
        }

        function get_stock_qty(number) {
            var warehouseValue = $('#main_warehouse_from').val();
            var item = $('#sub_' + number).val();

            if (!warehouseValue || item == '0') return;

            $.ajax({
                url: '<?php echo url('/'); ?>/pdc/get_stock_location_wise',
                type: "GET",
                data: {
                    warehouse: warehouseValue,
                    item: item
                },
                success: function(data) {
                    if (data) {
                        var parts = data.split('/');
                        var stockQty = Math.floor(parseFloat(parts[0] || 0));
                        $('#in_stock_qty' + number).val(stockQty);
                    }
                }
            });
        }

        function get_all_stocks_for_wh() {
            $('.AutoNo').each(function(index) {
                let number = $(this).attr('id') ? $(this).attr('id').replace('RemoveRow', '') : 1;
                get_stock_qty(number);
            });
        }

        function check_qty(id, number) {
            var qty = parseFloat($('#' + id).val() || 0);
            var instock = parseFloat($('#in_stock_qty' + number).val() || 0);

            if (qty > instock) {
                alert('Stock Out QTY cannot be greater than available stock');
                $('#' + id).val(instock);
            }
        }

        function AddMoreRows() {
            Counter++;
            let optionsHtml = $('#sub_1').html();
            
            $('#AppendHtml').append(
                '<tr class="text-center AutoNo" id="RemoveRow' + Counter + '">' +
                '<td>' +
                '<select onchange="selectElement(this)" name="item_id[]" class="form-control select2 items" id="sub_' + Counter + '">' +
                optionsHtml +
                '</select>' +
                '</td>' +
                '<td><input readonly type="text" name="barcodes[]" class="form-control barCodes" style="width: 100%; text-align: center;"></td>' +
                '<td><input readonly type="text" name="in_stock_qty[]" id="in_stock_qty' + Counter + '" class="form-control item-stock" style="width: 100%; text-align: center;"></td>' +
                '<td><input onkeyup="check_qty(this.id,' + Counter + ')" type="number" name="qty[]" id="qty' + Counter + '" class="form-control requiredField SendQty" step="any" min="0.01"></td>' +
                '<td><button type="button" class="btn btn-xs btn-danger" onclick="RemoveRows(' + Counter + ')">X</button></td>' +
                '</tr>'
            );
            $('.select2').last().select2();
            $('#span').text($(".AutoNo").length);
            select_brand(); // Apply filter to new row
        }

        function RemoveRows(id) {
            $('#RemoveRow' + id).remove();
            $('#span').text($(".AutoNo").length);
        }

        $(document).ready(function() {
            $('.select2').select2();
            
            $(".btn-success").click(function() {
                if (!$('#main_warehouse_from').val()) {
                    alert('Please select Warehouse From');
                    return false;
                }
                if (!$('#main_warehouse_to').val()) {
                    alert('Please select Warehouse To');
                    return false;
                }
                if ($('#main_warehouse_from').val() == $('#main_warehouse_to').val()) {
                    alert('Warehouse From and To cannot be same');
                    return false;
                }
                if ($('#sub_1').val() == '0') {
                    alert('Please select at least one item');
                    return false;
                }
                let emptyQty = false;
                $('.SendQty').each(function() {
                    if (!$(this).val() || $(this).val() <= 0) {
                        emptyQty = true;
                    }
                });
                if (emptyQty) {
                    alert('Please enter valid quantities');
                    return false;
                }
            });
        });
    </script>
@endsection
