<style>
.table-responsive { overflow-y: auto; }
.totals-row { font-weight: bold; background-color: #f5f5f5; }
.table-bordered > thead > tr > th {
    white-space: nowrap !important;
    position: sticky;
    top: 0;
    z-index: 2;
}
.table-wrapper { max-height: 900px; overflow-y: auto; }
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12 text-center">
            <h3>Closing Stock Report</h3>
            <h5>{{ date('d-M-Y', strtotime($from_date)) }} to {{ date('d-M-Y', strtotime($to_date)) }}</h5>
        </div>
    </div>

    <div class="table-responsive table-wrapper">
        <table class="table table-bordered table-striped" id="exportTable">
            <thead>
                <tr>
                    <th>S.No</th>
                    <th>SKU Code</th>
                    <th>Item Name</th>
                    <th>Barcode</th>
                    <th>Item Type</th>
                    <th>Brand</th>
                    <th>Packing</th>

                    {{-- Show Warehouse columns --}}
                    @foreach($warehouses as $warehouse)
                        <th>{{ $warehouse->name }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @php $counter = 1; @endphp
                @foreach($stocks as $sku => $row)
                    <tr>
                        <td>{{ $counter++ }}</td>
                        <td>{{ $row['sku_code'] }}</td>
                        <td>{{ $row['item_name'] }}</td>
                        <td>{{ $row['barcode'] }}</td>
                        <td>{{ $row['item_type'] == 1 ? 'Commercial' : 'Non-Commercial' }}</td>
                        <td>{{ $row['brand'] ?? 'N/A' }}</td>
                        <td>{{ $row['packing'] }}</td>

                        {{-- Warehouse-wise quantities --}}
                        @foreach($warehouses as $warehouse)
                            <td>{{ number_format($row[$warehouse->id] ?? 0, 2) }}</td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
