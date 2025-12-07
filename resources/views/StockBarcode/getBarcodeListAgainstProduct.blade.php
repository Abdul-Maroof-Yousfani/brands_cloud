<div class="row align-items-center ">
    <div class="col-md-6">
        <h3 style="font-weight: bold;text-transform: uppercase">List of Barcode/Qr Code</h3>
    </div>

    <div class="col-md-6 text-right">
        <input type="hidden" id="voucherItemQty" value="{{$voucherItemCount}}">
            <button class="btn-primary">Document QTY : <span class="voucher_qty">{{$voucherItemCount}}</span></button>
            <button class="btn-{{count($barcode) == $voucherItemCount ? 'success' : 'danger'}}">Barcode Scanned : <span class="voucher_qty">{{count($barcode)}}</span></button>
            <button class="btn-warning">Remaining for Scanning : <span class="voucher_qty">{{$voucherItemCount-count($barcode)}}</span></button>
    </div>
</div>
<table class="table table-bordered sf-table-list">
    <thead >
    <tr class="text-center">
        <th class="text-center">S.no</th>
        <th class="text-center">Barcode / QR Code</th>
        <th class="text-center">Product</th>
        <th class="text-center">Document No</th>
        <th class="text-center">Action</th>
    </tr>
    </thead>
    <tbody>
    @foreach($stockbarcode as $key => $sb)
        <tr>
            <td>{{$key+1}}</td>
            <td>{{$sb->barcode}}</td>

            <td>{{$sb->product_name}}</td>
            <td class="text-uppercase">{{$sb->voucher_no}}</td>
            <td>
                <button>Delete</button>
            </td>
        </tr>
    @endforeach

    </tbody>
</table>
<div id="paginationLinks">
    {{ $stockbarcode->links() }}
</div>


<script>
    // Pass the barcode array from PHP to JavaScript
    var existingBarcodes = @json($barcode); // Use this variable in jQuery or localStorage

    // Optionally, you can store it in localStorage for later use
    localStorage.setItem('existingBarcodes', JSON.stringify(existingBarcodes));


</script>
