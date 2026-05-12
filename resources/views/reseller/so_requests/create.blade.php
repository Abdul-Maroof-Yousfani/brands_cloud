@extends('layouts.reseller')

@section('content')
<div class="container-fluid">
    <div class="well_N">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default" style="margin-top: 20px;">
                    <div class="panel-heading">
                                <h4>Create SO Request</h4>
                            </div>
                            <div class="panel-body" style="background:#fff; padding: 20px;">
                                <form action="{{ route('reseller.so.store') }}" method="POST">
                                    {{ csrf_field() }}
                                    
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Request Date</label>
                                                <input type="date" class="form-control" name="request_date" value="{{ date('Y-m-d') }}" required>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <hr>
                                    
                                    <h5>Products</h5>
                                    <table class="table table-bordered" id="product_table">
                                        <thead>
                                            <tr>
                                                <th style="width: 25%">Brand</th>
                                                <th style="width: 35%">Product</th>
                                                <th style="width: 15%">Available</th>
                                                <th style="width: 15%">Quantity</th>
                                                <th style="width: 10%">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <select class="form-control select2 brand-select" name="brand_id[]" required>
                                                        <option value="">Select Brand</option>
                                                        @foreach($brands as $brand)
                                                        <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <select class="form-control select2 product-select" name="product_id[]" required disabled>
                                                        <option value="" data-avail="0">Select Brand First</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control available-qty" readonly value="0">
                                                </td>
                                                <td>
                                                    <input type="number" class="form-control qty-input" name="qty[]" min="1" required>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-danger btn-sm remove-row">Remove</button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <button type="button" class="btn btn-success btn-sm" id="add_row">Add Product</button>
                                    
                                    <div class="form-group text-right mt-3">
                                        <button type="submit" class="btn btn-primary">Submit Request</button>
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
    $(document).ready(function() {
        // Initialize select2
        if ($.fn.select2) {
            $('.select2').select2({ width: '100%' });
        }

        $(document).on('change', '.brand-select', function() {
            var brandId = $(this).val();
            var $productSelect = $(this).closest('tr').find('.product-select');
            
            if(!brandId) {
                if ($.fn.select2) { $productSelect.select2('destroy'); }
                $productSelect.html('<option value="">Select Brand First</option>');
                $productSelect.prop('disabled', true);
                if ($.fn.select2) { $productSelect.select2({ width: '100%' }); }
                return;
            }

            // Show loading
            if ($.fn.select2) { $productSelect.select2('destroy'); }
            $productSelect.html('<option value="">Loading Products...</option>');
            $productSelect.prop('disabled', true);
            if ($.fn.select2) { $productSelect.select2({ width: '100%' }); }

            // Fetch products
            $.ajax({
                url: '{{ route("reseller.so.get_products") }}',
                type: 'GET',
                data: { brand_id: brandId },
                success: function(data) {
                    var options = '<option value="" data-avail="0">Select Product</option>';
                    if(data.length === 0) {
                        options = '<option value="" data-avail="0">No Products Found</option>';
                    } else {
                        data.forEach(function(p) {
                            options += '<option value="' + p.id + '" data-avail="' + p.available + '">' + p.name + '</option>';
                        });
                    }

                    if ($.fn.select2) { $productSelect.select2('destroy'); }
                    $productSelect.html(options);
                    $productSelect.prop('disabled', data.length === 0);
                    if ($.fn.select2) { $productSelect.select2({ width: '100%' }); }
                    
                    // Reset available input
                    $productSelect.closest('tr').find('.available-qty').val(0);
                }
            });
        });

        $(document).on('change', '.product-select', function() {
            var selectedOption = $(this).find('option:selected');
            var avail = selectedOption.data('avail') || 0;
            $(this).closest('tr').find('.available-qty').val(avail);
        });

        $('#add_row').click(function() {
            var brandOptions = '<option value="">Select Brand</option>';
            @foreach($brands as $brand)
            brandOptions += '<option value="{{ $brand->id }}">{{ $brand->name }}</option>';
            @endforeach

            var row = '<tr>' +
                '<td><select class="form-control select2 brand-select" name="brand_id[]" required>' + brandOptions + '</select></td>' +
                '<td><select class="form-control select2 product-select" name="product_id[]" required disabled><option value="" data-avail="0">Select Brand First</option></select></td>' +
                '<td><input type="text" class="form-control available-qty" readonly value="0"></td>' +
                '<td><input type="number" class="form-control qty-input" name="qty[]" min="1" required></td>' +
                '<td><button type="button" class="btn btn-danger btn-sm remove-row">Remove</button></td>' +
                '</tr>';
            var $newRow = $(row);
            $('#product_table tbody').append($newRow);
            if ($.fn.select2) {
                $newRow.find('.select2').select2({ width: '100%' });
            }
        });

        $(document).on('input', '.qty-input', function() {
            var $row = $(this).closest('tr');
            var qty = parseInt($(this).val()) || 0;
            var available = parseInt($row.find('.available-qty').val()) || 0;
            
            if (qty > available) {
                alert('Quantity cannot exceed available stock (' + available + ').');
                $(this).val(available);
            }
        });

        $(document).on('click', '.remove-row', function() {
            if ($('#product_table tbody tr').length > 1) {
                // Destroy select2 instance before removing to prevent memory leaks
                if ($.fn.select2) {
                    $(this).closest('tr').find('.select2').select2('destroy');
                }
                $(this).closest('tr').remove();
            } else {
                alert("At least one product is required.");
            }
        });
    });
</script>
@endsection
