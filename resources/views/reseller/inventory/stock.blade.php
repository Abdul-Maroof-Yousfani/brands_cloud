@extends('layouts.reseller')

@section('content')
<div class="container-fluid">
    <div class="well_N">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">My Inventory Stock</h3>
                    </div>
                    <div class="panel-body" style="background:#fff; padding: 20px;">
                                <form action="{{ route('reseller.inventory.stock') }}" method="GET" class="mb-4" style="margin-bottom: 20px;">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Filter by Product</label>
                                                <select name="product_id" class="form-control select2">
                                                    <option value="">All Products</option>
                                                    @foreach($products as $product)
                                                        <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>
                                                            {{ $product->product_name }} - {{ $product->sku_code }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2" style="padding-top: 25px;">
                                            <button type="submit" class="btn btn-primary btn-sm" style="height: 34px; margin-top: 2px;">Search</button>
                                            @if(request('product_id'))
                                                <a href="{{ route('reseller.inventory.stock') }}" class="btn btn-danger btn-sm" style="height: 34px; margin-top: 2px; line-height: 2;">Clear</a>
                                            @endif
                                        </div>
                                    </div>
                                </form>
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Product Name</th>
                                            <th>SKU Code</th>
                                            <th>Warehouse</th>
                                            <th>Available Quantity</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($stocks as $stock)
                                        <tr>
                                            <td>{{ $stock->product_name }}</td>
                                            <td>{{ $stock->sku_code }}</td>
                                            <td>{{ $stock->warehouse_name }}</td>
                                            <td>{{ $stock->available_qty }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="4" class="text-center">No stock available in your inventory yet.</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
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
        if ($.fn.select2) {
            $('.select2').select2({
                placeholder: "Search and select a product",
                allowClear: true,
                width: '100%'
            });
        }
    });
</script>
@endsection
