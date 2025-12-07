@extends('layouts.default')
@section('content')

<style>
    .import { text-align: right; }
    .table-scroll { max-height: 500px; overflow:auto; display:block; }
    .table thead th { position: sticky; top:0; background:#f9f9f9; }
</style>

<div class="panel-body">
    <div class="row">
        <div class="col-lg-12">
            <div class="well_N">
                <div class="row mb-3">
                    <div class="col-lg-6">
                        <span class="subHeadingLabelClass">Brand Discount Report</span>
                    </div>
                    <div class="col-lg-6 text-right">
                        <div class="import">
                            <a href="{{ route('special-prices.import.form') }}" class="btn btn-primary">Import Data</a>
                            <button onclick="exportTableToExcel('brandDiscountTable','brand_discount_report')" class="btn btn-success">Export Excel</button>
                        </div>
                    </div>
                </div>

                <!-- Filter Form -->
              <form method="GET" action="{{ url('sales/assignDicountList') }}">
    <div class="row">

        <!-- Territory Filter -->
        <div class="col-lg-3">
            <label>Territory(s)</label>
            <select name="territory_id[]" class="form-control select2" multiple>
                <option value="all" {{ in_array('all', request()->input('territory_id', [])) ? 'selected' : '' }}>All Territories</option>
                @foreach($territories as $territory)
                    <option value="{{ $territory->id }}" 
                        {{ in_array($territory->id, request()->input('territory_id', [])) ? 'selected' : '' }}>
                        {{ $territory->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Store Filter (only stores of selected territories) -->
      <!-- Store Filter -->
<div class="col-lg-3">
    <label>Store(s)</label>
    <select name="store_id[]" class="form-control select2" multiple>
        <option value="all" {{ in_array('all', request()->input('store_id', [])) ? 'selected' : '' }}>All Stores</option>
        @foreach($stores as $store)
            <option value="{{ $store->id }}"
                {{ in_array($store->id, request()->input('store_id', [])) ? 'selected' : '' }}>
                {{ $store->name }}
            </option>
        @endforeach
    </select>
</div>


        <!-- Brand Filter -->
        <div class="col-lg-3">
            <label>Brand(s)</label>
            <select name="brand_id[]" class="form-control select2" multiple>
                <option value="all" {{ in_array('all', request()->input('brand_id', [])) ? 'selected' : '' }}>All Brands</option>
                @foreach($brands as $brand)
                    <option value="{{ $brand->id }}" 
                        {{ in_array($brand->id, request()->input('brand_id', [])) ? 'selected' : '' }}>
                        {{ $brand->name }}
                    </option>
                @endforeach
            </select>
        </div>

      <div class="col-lg-3 d-flex justify-content-end mt-3">
    <button style="margin-top: 35px;" type="submit" class="btn btn-primary">Load Report</button>
    <a href="{{ url('sales/assignDicountList') }}" class="btn btn-primary" style="margin-top: 35px;margin-left: 5px;">Reset Filters</a>
</div>

    </div>
</form>


                <div class="lineHeight">&nbsp;</div>

                <!-- Report Table -->
              @if(request()->has('territory_id') || request()->has('store_id') || request()->has('brand_id'))
    @if($specialPrices->isNotEmpty())
        <div class="panel">
            <div class="panel-body">
                <div class="table-scroll">
                    <table id="brandDiscountTable" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Store</th>
                                @foreach($brands as $brand)
                                    <th>{{ $brand->name }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($stores as $store)
                                <tr>
                                    <td>{{ $store->name }}</td>
                                    @foreach($brands as $brand)
                                        <td>{{ $matrix[$store->id][$brand->id] ?? 0 }}</td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @else
        <div class="alert alert-warning">No data found for selected filters.</div>
    @endif
@endif

            </div>
        </div>
    </div>
</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Select2 -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.sheetjs.com/xlsx-latest/package/dist/xlsx.full.min.js"></script>

<script>
$(document).ready(function() {
    $('.select2').select2();

  $('select[name="territory_id[]"]').on('change', function() {
    var territoryIds = $(this).val();
    var selectedStores = $('select[name="store_id[]"]').val() || [];

    $.ajax({
        url: "{{ route('getStoresByTerritory') }}",
        type: "GET",
        data: { territory_id: territoryIds },
        success: function(stores) {
            var storeSelect = $('select[name="store_id[]"]');
            storeSelect.empty(); // Clear current options

            // Add "All Stores" option
            storeSelect.append('<option value="all">All Stores</option>');

            // Add returned stores and preselect if in selectedStores
            $.each(stores, function(index, store) {
                var selected = selectedStores.includes(store.id.toString()) ? 'selected' : '';
                storeSelect.append('<option value="'+store.id+'" '+selected+'>'+store.name+'</option>');
            });

            storeSelect.trigger('change'); // Refresh select2
        }
    });
});

});



function exportTableToExcel(tableID, filename = 'excel_data') {
    // Get the table
    var table = document.getElementById(tableID);

    // Convert HTML table to SheetJS worksheet
    var workbook = XLSX.utils.table_to_book(table, { sheet: "Sheet1" });

    // Write the workbook and trigger download
    XLSX.writeFile(workbook, filename + ".xlsx");
}



</script>

@endsection
