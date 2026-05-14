@extends('layouts.default')
@section('content')
    @include('select2')
    <style>
        .table-bordered th, .table-bordered td {
            padding: 4px !important;
            vertical-align: middle !important;
        }
        .form-control {
            padding: 4px 8px !important;
            height: auto !important;
        }
        .select2-container {
            width: 100% !important;
        }
    </style>
    <div class="container-fluid">
        <div class="well_N">
            <div class="dp_sdw">
                <div class="panel">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <span class="subHeadingLabelClass">Item Conversion Form</span>
                                <div class="lineHeight">&nbsp;</div>
                                
                                <form action="{{ url('store/item-conversion/save?m=' . $m) }}" method="POST" id="conversionForm">
                                    {!! csrf_field() !!}
                                    
                                    <div class="row">
                                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                            <label>Conversion No</label>
                                            <input type="text" name="conversion_no" value="{{ $conversion_no }}" class="form-control" readonly>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                            <label>Conversion Date</label>
                                            <input type="date" name="conversion_date" value="{{ date('Y-m-d') }}" class="form-control requiredv">
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                            <label>Warehouse</label>
                                            <select name="warehouse_id" id="warehouse_id" class="form-control select2 requiredv">
                                                <option value="">Select Warehouse</option>
                                                @foreach($warehouses as $wh)
                                                    <option value="{{ $wh->id }}">{{ $wh->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                            <label>Remarks</label>
                                            <textarea name="remarks" class="form-control" rows="1"></textarea>
                                        </div>
                                    </div>

                                    <div class="lineHeight">&nbsp;</div>

                                    <div class="row">
                                        <!-- Left Side: Existing Inventory (RED) -->
                                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                            <div style="background-color: #f8d7da; padding: 15px; border-radius: 5px; border: 1px solid #f5c6cb;">
                                                <h4 class="text-center" style="color: #721c24; font-weight: bold;">Existing Inventory (Stock OUT)</h4>
                                                <table class="table table-bordered" id="existingTable" style="table-layout: fixed; width: 100%;">
                                                    <thead>
                                                        <tr>
                                                            <th style="width: 20%;">Brand</th>
                                                            <th style="width: 30%;">Item (SKU + Name)</th>
                                                            <th style="width: 10%;">Avail Qty</th>
                                                            <th style="width: 10%;">Conv Qty</th>
                                                            <th style="width: 10%;">Rate</th>
                                                            <th style="width: 10%;">Value</th>
                                                            <th style="width: 10%;">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="existingBody">
                                                        <tr class="item-row">
                                                            <td>
                                                                <select class="form-control select2 brand-select" onchange="filterItems(this)">
                                                                    <option value="">Select Brand</option>
                                                                    @foreach($brands as $brand)
                                                                        <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <select name="existing_items[0][item_id]" class="form-control select2 item-select" onchange="fetchStock(this)">
                                                                    <option value="">Select Item</option>
                                                                    @foreach($items as $item)
                                                                        <option value="{{ $item->id }}" data-brand="{{ $item->brand_id }}" data-barcode="{{ $item->product_barcode }}">
                                                                            {{ $item->sku_code }} - {{ $item->product_name }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </td>
                                                            <td><input type="text" class="form-control avail-qty" readonly tabindex="-1"></td>
                                                            <td><input type="number" name="existing_items[0][qty]" class="form-control conv-qty" step="any" oninput="calculateValue(this)"></td>
                                                            <td><input type="number" name="existing_items[0][rate]" class="form-control item-rate" step="any" readonly tabindex="-1"></td>
                                                            <td><input type="number" class="form-control item-value" readonly tabindex="-1"></td>
                                                            <td><button type="button" class="btn btn-sm btn-danger" onclick="removeRow(this)">X</button></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                                <button type="button" class="btn btn-primary btn-sm" onclick="addRow('existing')">Add Item (+)</button>
                                            </div>
                                        </div>

                                        <!-- Right Side: Conversion (BLUE) -->
                                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                            <div style="background-color: #cce5ff; padding: 15px; border-radius: 5px; border: 1px solid #b8daff;">
                                                <h4 class="text-center" style="color: #004085; font-weight: bold;">Conversion (Stock IN)</h4>
                                                <table class="table table-bordered" id="conversionTable" style="table-layout: fixed; width: 100%;">
                                                    <thead>
                                                        <tr>
                                                            <th style="width: 25%;">Brand</th>
                                                            <th style="width: 35%;">Item (SKU + Name)</th>
                                                            <th style="width: 10%;">Qty</th>
                                                            <th style="width: 10%;">Rate</th>
                                                            <th style="width: 10%;">Value</th>
                                                            <th style="width: 10%;">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="conversionBody">
                                                        <tr class="item-row">
                                                            <td>
                                                                <select class="form-control select2 brand-select" onchange="filterItems(this)">
                                                                    <option value="">Select Brand</option>
                                                                    @foreach($brands as $brand)
                                                                        <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <select name="conversion_items[0][item_id]" class="form-control select2 item-select">
                                                                    <option value="">Select Item</option>
                                                                    @foreach($items as $item)
                                                                        <option value="{{ $item->id }}" data-brand="{{ $item->brand_id }}" data-barcode="{{ $item->product_barcode }}">
                                                                            {{ $item->sku_code }} - {{ $item->product_name }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </td>
                                                            <td><input type="number" name="conversion_items[0][qty]" class="form-control conv-qty" step="any" oninput="calculateValue(this); updateGrandTotals();"></td>
                                                            <td><input type="number" name="conversion_items[0][rate]" class="form-control item-rate" step="any" oninput="calculateValue(this); updateGrandTotals();"></td>
                                                            <td><input type="number" class="form-control item-value" readonly tabindex="-1"></td>
                                                            <td><button type="button" class="btn btn-sm btn-danger" onclick="removeRow(this)">X</button></td>
                                                        </tr>
                                                    </tbody>
                                                    <tfoot>
                                                        <tr style="background: #e9ecef; font-weight: bold;">
                                                            <td colspan="2" class="text-right">Total IN:</td>
                                                            <td id="totalInQty">0.00</td>
                                                            <td></td>
                                                            <td id="totalInValue">0.00</td>
                                                            <td></td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                                <button type="button" class="btn btn-primary btn-sm" onclick="addRow('conversion')">Add Item (+)</button>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Totals Summary for OUT Section -->
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div style="background: #f8d7da; padding: 5px 15px; border: 1px solid #f5c6cb; border-top: none;">
                                                <table style="width: 100%;">
                                                    <tr style="font-weight: bold;">
                                                        <td style="width: 50%;" class="text-right">Total OUT Qty: <span id="totalOutQty">0.00</span></td>
                                                        <td style="width: 50%; padding-left: 20px;" class="text-right">Total OUT Value: <span id="totalOutValue">0.00</span></td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="lineHeight">&nbsp;</div>
                                    
                                    <div class="row">
                                        <div class="col-lg-12 text-right">
                                            <button type="submit" class="btn btn-success btn-lg">Save Conversion</button>
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

    <script>
        let existingCounter = 1;
        let conversionCounter = 1;

        function filterItems(select) {
            let brandId = $(select).val();
            let row = $(select).closest('tr');
            let itemSelect = row.find('.item-select');
            
            itemSelect.find('option').each(function() {
                if ($(this).val() === "") return;
                if (brandId === "" || $(this).data('brand') == brandId) {
                    $(this).show();
                } else {
                    $(this).hide();
                    if ($(this).is(':selected')) {
                        itemSelect.val("").trigger('change');
                    }
                }
            });
            itemSelect.select2();
        }

        function fetchStock(select) {
            let itemId = $(select).val();
            let warehouseId = $('#warehouse_id').val();
            let row = $(select).closest('tr');
            
            if (!warehouseId) {
                alert("Please select warehouse first");
                $(select).val("").trigger('change');
                return;
            }

            if (!itemId) return;

            $.ajax({
                url: "{{ url('store/item-conversion/get-stock') }}",
                data: { item: itemId, warehouse: warehouseId },
                success: function(data) {
                    row.find('.avail-qty').val(data.qty);
                    row.find('.item-rate').val(data.rate);
                    calculateValue(row.find('.conv-qty')[0]);
                }
            });
        }

        function calculateValue(input) {
            let row = $(input).closest('tr');
            let qty = parseFloat(row.find('.conv-qty').val()) || 0;
            let rate = parseFloat(row.find('.item-rate').val()) || 0;
            row.find('.item-value').val((qty * rate).toFixed(2));
            updateGrandTotals();
        }

        function updateGrandTotals() {
            let totalOutQty = 0;
            let totalOutValue = 0;
            $('#existingBody tr').each(function() {
                totalOutQty += parseFloat($(this).find('.conv-qty').val()) || 0;
                totalOutValue += parseFloat($(this).find('.item-value').val()) || 0;
            });
            $('#totalOutQty').text(totalOutQty.toFixed(2));
            $('#totalOutValue').text(totalOutValue.toFixed(2));

            let totalInQty = 0;
            let totalInValue = 0;
            $('#conversionBody tr').each(function() {
                totalInQty += parseFloat($(this).find('.conv-qty').val()) || 0;
                totalInValue += parseFloat($(this).find('.item-value').val()) || 0;
            });
            $('#totalInQty').text(totalInQty.toFixed(2));
            $('#totalInValue').text(totalInValue.toFixed(2));
        }

        function addRow(type) {
            let tableBody, counter, namePrefix;
            if (type === 'existing') {
                tableBody = $('#existingBody');
                counter = existingCounter++;
                namePrefix = 'existing_items';
                
                let html = `
                    <tr class="item-row">
                        <td>
                            <select class="form-control select2 brand-select" onchange="filterItems(this)">
                                <option value="">Select Brand</option>
                                @foreach($brands as $brand)
                                    <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <select name="${namePrefix}[${counter}][item_id]" class="form-control select2 item-select" onchange="fetchStock(this)">
                                <option value="">Select Item</option>
                                @foreach($items as $item)
                                    <option value="{{ $item->id }}" data-brand="{{ $item->brand_id }}">
                                        {{ $item->sku_code }} - {{ $item->product_name }}
                                    </option>
                                @endforeach
                            </select>
                        </td>
                        <td><input type="text" class="form-control avail-qty" readonly tabindex="-1"></td>
                        <td><input type="number" name="${namePrefix}[${counter}][qty]" class="form-control conv-qty" step="any" oninput="calculateValue(this); updateGrandTotals();"></td>
                        <td><input type="number" name="${namePrefix}[${counter}][rate]" class="form-control item-rate" step="any" readonly tabindex="-1"></td>
                        <td><input type="number" class="form-control item-value" readonly tabindex="-1"></td>
                        <td><button type="button" class="btn btn-sm btn-danger" onclick="removeRow(this)">X</button></td>
                    </tr>
                `;
                tableBody.append(html);
            } else {
                tableBody = $('#conversionBody');
                counter = conversionCounter++;
                namePrefix = 'conversion_items';

                let html = `
                    <tr class="item-row">
                        <td>
                            <select class="form-control select2 brand-select" onchange="filterItems(this)">
                                <option value="">Select Brand</option>
                                @foreach($brands as $brand)
                                    <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <select name="${namePrefix}[${counter}][item_id]" class="form-control select2 item-select">
                                <option value="">Select Item</option>
                                @foreach($items as $item)
                                    <option value="{{ $item->id }}" data-brand="{{ $item->brand_id }}">
                                        {{ $item->sku_code }} - {{ $item->product_name }}
                                    </option>
                                @endforeach
                            </select>
                        </td>
                        <td><input type="number" name="${namePrefix}[${counter}][qty]" class="form-control conv-qty" step="any" oninput="calculateValue(this); updateGrandTotals();"></td>
                        <td><input type="number" name="${namePrefix}[${counter}][rate]" class="form-control item-rate" step="any" oninput="calculateValue(this); updateGrandTotals();"></td>
                        <td><input type="number" class="form-control item-value" readonly tabindex="-1"></td>
                        <td><button type="button" class="btn btn-sm btn-danger" onclick="removeRow(this)">X</button></td>
                    </tr>
                `;
                tableBody.append(html);
            }
            $('.select2').select2();
        }

        function removeRow(btn) {
            if ($(btn).closest('tbody').find('tr').length > 1) {
                $(btn).closest('tr').remove();
            } else {
                alert("At least one row is required.");
            }
        }

        $(document).ready(function() {
            $('.select2').select2();
            
            $('#conversionForm').on('submit', function(e) {
                if (!$('#warehouse_id').val()) {
                    alert("Please select warehouse");
                    e.preventDefault();
                    return false;
                }
                
                // Validate that at least one item is selected in each section
                let existingItems = false;
                $('#existingBody .item-select').each(function() {
                    if ($(this).val()) existingItems = true;
                });
                
                let conversionItems = false;
                $('#conversionBody .item-select').each(function() {
                    if ($(this).val()) conversionItems = true;
                });
                
                if (!existingItems || !conversionItems) {
                    alert("Please add at least one item in both sections");
                    e.preventDefault();
                    return false;
                }
            });
        });
    </script>
@endsection
