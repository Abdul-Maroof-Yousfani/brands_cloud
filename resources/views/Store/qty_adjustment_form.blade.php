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
                                                <span class="subHeadingLabelClass">Quantity Adjustment Form</span>
                                            </div>
                                            <div class="col-lg-6 col-md-6 text-right">
                                                <a href="{{ url('store/qty_adjustment_list?m=' . $m) }}" class="btn btn-primary btn-sm">View Adjustment List</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="lineHeight">&nbsp;</div>
                                <div class="row">
                                    <form action="{{ url('stad/addQtyAdjustmentDetail?m=' . $m) }}" method="POST" id="addQtyAdjustmentDetail" class="stop">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="hidden" name="m" value="{{ $m }}">
                                        
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="row">
                                                <?php $uniq = PurchaseHelper::get_unique_no_qty_adj(date('y'), date('m')); ?>
                                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                    <label for="">Adj No</label>
                                                    <input type="text" id="adj_no" name="adj_no"
                                                        value="{{ strtoupper($uniq) }}" class="form-control"
                                                        readonly>
                                                </div>
                                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                    <label for="">Date</label>
                                                    <input type="date" class="form-control requiredField" id="adj_date"
                                                        name="adj_date" value="<?php echo date('Y-m-d'); ?>">
                                                </div>
                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                    <label for="">Warehouse</label>
                                                    <select name="warehouse_id" id="warehouse_id" class="form-control requiredField select2">
                                                        <option value="">Select Warehouse</option>
                                                        <?php foreach(CommonHelper::get_all_warehouse() as $Fil):?> 
                                                            @if($Fil->is_virtual == 0)
                                                                <option value="<?php echo $Fil->id; ?>"><?php echo $Fil->name; ?></option> 
                                                            @endif
                                                        <?php endforeach;?>
                                                    </select>
                                                </div>
                                                <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
                                                    <label for="">Remarks</label>
                                                    <textarea name="description" id="description" class="form-control" rows="1"></textarea>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="row">
                                                <div class="col-lg-12 text-right">
                                                    <button type="button" onclick="AddMore()" class="btn btn-success btn-sm">Add More</button>
                                                </div>
                                            </div>
                                            <br>
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered sf-table-list" id="adjustment_table">
                                                            <thead>
                                                                <tr class="bg-primary">
                                                                    <th class="text-center">S.No</th>
                                                                    <th class="text-center">Item Name</th>
                                                                    <th class="text-center col-sm-2">System Qty</th>
                                                                    <th class="text-center col-sm-2">Actual Qty</th>
                                                                    <th class="text-center col-sm-2">Diff Qty</th>
                                                                    <th class="text-center">Action</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <!-- Rows will be added here -->
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-12 text-right">
                                                    <button type="submit" class="btn btn-primary">Save Adjustment</button>
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
        var rowCount = 0;

        function AddMore() {
            var warehouseId = $('#warehouse_id').val();
            if (!warehouseId) {
                alert('Please select a warehouse first.');
                return;
            }
            
            rowCount++;
            var html = '<tr id="row_' + rowCount + '">' +
                '<td class="text-center">' + rowCount + '</td>' +
                '<td>' +
                    '<select name="item_id[]" class="form-control select2-dynamic" onchange="fetchItemStock(this, ' + rowCount + ')">' +
                        '<option value="">Search Item...</option>' +
                        '<?php foreach(CommonHelper::get_all_subitems() as $item): ?>' +
                            '<option value="{{ $item->id }}">{{ $item->product_name }} ({{ $item->sku_code }})</option>' +
                        '<?php endforeach; ?>' +
                    '</select>' +
                '</td>' +
                '<td class="text-center"><input type="number" name="old_qty[]" value="0" class="form-control text-center old_qty" readonly tabindex="-1"></td>' +
                '<td class="text-center"><input type="number" step="0.01" name="actual_qty[]" class="form-control text-center actual_qty" onkeyup="calculateDiff(' + rowCount + ')"></td>' +
                '<td class="text-center"><input type="number" step="0.01" name="diff_qty[]" class="form-control text-center diff_qty" readonly tabindex="-1"></td>' +
                '<td class="text-center"><button type="button" onclick="removeRow(' + rowCount + ')" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i></button></td>' +
                '</tr>';
            $('#adjustment_table tbody').append(html);
            
            // Initialize select2 for the new row
            var $newSelect = $('#row_' + rowCount + ' .select2-dynamic');
            $newSelect.select2({
                placeholder: "Search Item...",
                width: '100%'
            });

            // Auto focus actual_qty when item is selected
            $newSelect.on('select2:select', function(e) {
                $(this).closest('tr').find('.actual_qty').focus();
            });
        }

        function fetchItemStock(element, id) {
            var itemId = $(element).val();
            var warehouseId = $('#warehouse_id').val();
            if (!itemId) return;

            $.ajax({
                url: '<?php echo url('pdc/get_stock_location_wise'); ?>',
                type: 'GET',
                data: { warehouse: warehouseId, item: itemId },
                success: function(response) {
                    var systemQty = 0;
                    if(response) {
                        var parts = response.split('/');
                        systemQty = parseFloat(parts[0]) || 0;
                    }
                    $('#row_' + id + ' .old_qty').val(systemQty);
                    calculateDiff(id);
                }
            });
        }

        function addRow(itemId, itemName, systemQty) {
            rowCount++;
            var html = '<tr id="row_' + rowCount + '">' +
                '<td class="text-center">' + rowCount + '</td>' +
                '<td>' + itemName + '<input type="hidden" name="item_id[]" value="' + itemId + '"></td>' +
                '<td class="text-center"><input type="number" name="old_qty[]" value="' + systemQty + '" class="form-control text-center" readonly></td>' +
                '<td class="text-center"><input type="number" step="0.01" name="actual_qty[]" class="form-control text-center" onkeyup="calculateDiff(' + rowCount + ')"></td>' +
                '<td class="text-center"><input type="number" step="0.01" name="diff_qty[]" class="form-control text-center" readonly></td>' +
                '<td class="text-center"><button type="button" onclick="removeRow(' + rowCount + ')" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i></button></td>' +
                '</tr>';
            $('#adjustment_table tbody').append(html);
        }

        function calculateDiff(id) {
            var oldQty = parseFloat($('#row_' + id + ' input[name="old_qty[]"]').val()) || 0;
            var actualQty = parseFloat($('#row_' + id + ' input[name="actual_qty[]"]').val()) || 0;
            var diff = actualQty - oldQty;
            $('#row_' + id + ' input[name="diff_qty[]"]').val(diff.toFixed(2));
        }

        function removeRow(id) {
            $('#row_' + id).remove();
            // Optional: Resequence S.No
        }

        $(document).ready(function() {
            $('.select2').select2();

            $('#warehouse_id').on('change', function() {
                // Clear existing rows on warehouse change
                $('#adjustment_table tbody').empty();
                rowCount = 0;
            });

            $('#addQtyAdjustmentDetail').on('submit', function(e) {
                // Find rows where actual_qty is empty and remove them before submit
                $('input[name="actual_qty[]"]').each(function() {
                    if ($(this).val() === "" || $(this).val() === null) {
                        $(this).closest('tr').remove();
                    }
                });
                
                // If no rows are being adjusted, prevent submission
                if ($('[name="item_id[]"]').length === 0) {
                    alert('Please enter Actual Qty for at least one item before saving.');
                    e.preventDefault();
                    return false;
                }
            });

            // Handle Tab key on Actual Qty to add more rows
            $(document).on('keydown', '.actual_qty', function(e) {
                if (e.keyCode == 9 && !e.shiftKey) { // Tab key pressed
                    var currentRow = $(this).closest('tr');
                    if (currentRow.is(':last-child')) {
                        e.preventDefault(); // Prevent leaving the current input
                        AddMore();
                        // Focus and open the new row's selector
                        $('#adjustment_table tbody tr:last .select2-dynamic').select2('open');
                    }
                }
            });
        });

        function loadWarehouseItems(warehouseId) {
            $.ajax({
                url: '<?php echo url('pdc/get_warehouse_stock_bulk'); ?>',
                type: 'GET',
                data: { warehouse: warehouseId },
                success: function(data) {
                    $.each(data, function(index, item) {
                        addRow(item.id, item.name, item.qty);
                    });
                }
            });
        }
    </script>
@endsection
