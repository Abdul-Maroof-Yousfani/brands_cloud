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
                                                <span class="subHeadingLabelClass">Stock In Form</span>
                                            </div>
                                            <div class="col-lg-6 col-md-6 text-right">
                                                <a href="{{ url('store/stock_in_list?m=' . $m) }}" class="btn btn-primary btn-sm">View Stock In List</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="lineHeight">&nbsp;</div>
                                <div class="row">
                                    <?php echo Form::open(['url' => 'pad/addStockInDetail?m=' . $m . '', 'id' => 'addStockInDetail', 'class' => 'stop']); ?>
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="hidden" name="pageType" value="<?php echo $_GET['pageType'] ?? 'view'; ?>">
                                    <input type="hidden" name="parentCode" value="<?php echo $_GET['parentCode'] ?? '0'; ?>">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="row">
                                            <?php $uniq = PurchaseHelper::get_unique_no_stock_in(date('y'), date('m')); ?>
                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                <label for="">Stock In No</label>
                                                <input type="text" id="si_no" name="si_no"
                                                    value="{{ strtoupper($uniq) }}" class="form-control requiredField"
                                                    readonly>
                                            </div>
                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                <label for="">Date</label>
                                                <input type="date" class="form-control requiredField" id="si_date"
                                                    name="si_date" value="<?php echo date('Y-m-d'); ?>">
                                            </div>
                                            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                                                <label for="">Reference / Remarks</label>
                                                <textarea type="text" name="description" id="description" class="form-control requiredField"></textarea>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                <label for="">Warehouse To (Receiving)</label>
                                                <select onchange="get_pending_requests()" name="main_warehouse_to"
                                                    id="main_warehouse_to" class="form-control requiredField select2">
                                                    <option value="">Select Warehouse</option>
                                                    <?php foreach(CommonHelper::get_all_warehouse() as $Fil):?> 
                                                        @if($Fil->is_virtual == 0)
                                                            <option value="<?php echo $Fil->id; ?>"><?php echo $Fil->name; ?></option> 
                                                        @endif
                                                    <?php endforeach;?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row">
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
                                                                <th colspan="6" class="text-center">Stock In Detail</th>
                                                                <th class="text-center">
                                                                    <span class="badge badge-success" id="span">1</span>
                                                                </th>
                                                            </tr>
                                                                <th class="text-center" style="width: 14%">Item Name</th>
                                                                <th class="text-center" style="width: 8%">Product / SKU</th>
                                                                <th class="text-center" style="width: 8%">Stock Out No</th>
                                                                <th class="text-center" style="width: 9%">From Wh</th>
                                                                <th class="text-center" style="width: 9%">To Wh</th>
                                                                <th class="text-center" style="width: 8%">Total Qty</th>
                                                                <th class="text-center" style="width: 8%">Already Received</th>
                                                                <th class="text-center" style="width: 8%">Pending Qty</th>
                                                                <th class="text-center" style="width: 8%">Qty In</th>
                                                                <th class="text-center" style="width: 15%">Description</th>
                                                                <th class="text-center" style="width: 5%">Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="AppendHtml">
                                                            <!-- Data will be loaded via AJAX based on warehouse selection -->
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                                            <button type="submit" class="btn btn-success">Submit Stock In</button>
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
        var row_count = 1;
        let allItems = @json(CommonHelper::get_all_subitem_get());

        function get_pending_requests() {
            var wh_to = $('#main_warehouse_to').val();
            // Load all if no warehouse selected, otherwise filter
            $.ajax({
                url: "{{ url('fdc/get_pending_stock_outputs') }}",
                type: 'GET',
                data: { warehouse_to: wh_to, m: "{{ $m }}" },
                success: function(data) {
                    $('#AppendHtml').empty();
                    row_count = 0;
                    if (data.length > 0) {
                        data.forEach(function(item) {
                            add_pending_row(item);
                        });
                    } else {
                        // Add one empty row if no pending
                        AddMoreRows();
                    }
                }
            });
        }

        function add_pending_row(data) {
            row_count++;
            var html = '<tr class="text-center AutoNo" id="row_' + row_count + '">' +
                '<td>' +
                '<select name="item_id[]" id="sub_' + row_count + '" class="form-control select2 item_id">' +
                '<option value="' + data.item_id + '">' + data.product_name + ' (' + data.sku_code + ')</option>' +
                '</select>' +
                '</td>' +
                '<td><input readonly type="text" value="' + (data.sku_code || '') + '" class="form-control " style="width: 100%; text-align: center;"></td>' +
                '<td><input type="text" name="stock_out_no[]" value="' + data.so_no + '" class="form-control" readonly></td>' +
                '<td><input type="hidden" name="warehouse_from[]" value="' + data.warehouse_from + '"><input type="text" value="' + (data.from_warehouse_name || 'Warehouse ' + data.warehouse_from) + '" class="form-control" readonly></td>' +
                '<td><input type="text" value="' + (data.to_warehouse_name || 'Warehouse ' + data.warehouse_to) + '" class="form-control" readonly></td>' +
                '<td><input type="number" step="any" value="' + data.total_qty + '" class="form-control" readonly></td>' +
                '<td><input type="number" step="any" value="' + data.prev_received_qty + '" class="form-control" readonly></td>' +
                '<td><input type="number" step="any" value="' + data.pending_qty + '" class="form-control" readonly></td>' +
                '<td><input type="hidden" name="stock_out_data_id[]" value="' + data.id + '"><input type="number" step="any" name="qty[]" value="' + data.pending_qty + '" class="form-control requiredField SendQty" required onkeyup="checkReceivedQty(this, ' + data.pending_qty + ')"></td>' +
                '<td><input type="text" name="des[]" value="Ref: ' + data.so_no + '" class="form-control"></td>' +
                '<td><button type="button" onclick="remove_row(' + row_count + ')" class="btn btn-danger btn-xs"><i class="fa fa-remove"></i></button></td>' +
                '</tr>';
            $('#AppendHtml').append(html);
            $('#sub_' + row_count).select2();
        }

        function add_row() {
            AddMoreRows();
        }

        function remove_row(id) {
            $('#row_' + id).remove();
            $('#span').text($(".AutoNo").length);
        }

        function select_brand() {
            const brand_id = $("#brands").val();
            const $items = $(".items");

            $items.find("option[data-brand]").each(function() {
                const $opt = $(this);
                const optBrand = $opt.data("brand");
                const shouldHide = brand_id && optBrand != brand_id;
                $opt.prop("disabled", shouldHide).prop("hidden", shouldHide);
            });

            $items.each(function() {
                $(this).select2('destroy').select2();
            });
        }

        const itemMap = new Map(allItems.map(item => [item.id, item]));

        function selectElement(changedSelect) {
            const selectedVal = changedSelect.value;
            const item = itemMap.get(Number(selectedVal));
            const row = changedSelect.closest("tr");
            if (item) {
                row.querySelector(".barCodes").value = item.product_barcode || '';
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
                '<td><input type="text" name="stock_out_no[]" class="form-control" readonly></td>' +
                '<td><input type="text" class="form-control" readonly></td>' +
                '<td><input type="text" class="form-control" readonly></td>' +
                '<td><input type="number" name="qty[]" id="qty' + Counter + '" class="form-control requiredField SendQty" step="any" min="0.01"></td>' +
                '<td><input type="text" name="des[]" class="form-control"></td>' +
                '<td><button type="button" class="btn btn-xs btn-danger" onclick="RemoveRows(' + Counter + ')">X</button></td>' +
                '</tr>'
            );
            $('.select2').last().select2();
            $('#span').text($(".AutoNo").length);
        }

        function RemoveRows(id) {
            $('#RemoveRow' + id).remove();
            $('#span').text($(".AutoNo").length);
        }

        $(document).ready(function() {
            $('.select2').select2();
            get_pending_requests(); // Load all on first time
            
            $(".btn-success").click(function() {
                if ($('#sub_1').val() == '0' && $('#AppendHtml tr').length == 0) {
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

        function checkReceivedQty(input, maxQty) {
            if (parseFloat(input.value) > parseFloat(maxQty)) {
                alert('Received quantity cannot exceed pending quantity (' + maxQty + ')');
                input.value = maxQty;
            }
        }

        function remove_row(row_id) {
            $('#row_' + row_id).remove();
        }
    </script>
@endsection
