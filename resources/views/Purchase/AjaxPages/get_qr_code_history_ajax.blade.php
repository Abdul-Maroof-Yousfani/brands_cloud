<table class="table table-hover mb-0" id="qrDataTable" style="font-size:13px; border-collapse: separate; border-spacing: 0;">
    <thead style="background-color: #f1f3f5; color: #4b6584;">
        <tr>
            <th class="text-center" width="40">#</th>
            <th width="200">Product Name</th>
            <th width="150" class="text-center">QR Code</th>
            <th width="120" class="text-center">Batch/Lot No</th>
            <th width="130" class="text-center">Scan Type</th>
            <th width="150" class="text-center">Reference</th>
            <th width="160" class="text-center">Date & Time</th>
            <th width="120">User</th>
            <th width="100" class="text-center">Status</th>
        </tr>
    </thead>
    <tbody style="color: #2f3640;">
        @if(count($historyData) > 0)
            @php $i = ($historyData->currentPage() - 1) * $historyData->perPage() + 1; @endphp
            @foreach($historyData as $row)
                <tr>
                    <td class="text-center text-muted font-weight-bold">{{ $i++ }}</td>
                    <td>
                        <div class="font-weight-bold text-dark">{{ $row->product_name }}</div>
                        <small class="text-muted text-uppercase tracking-tighter" style="font-size: 10px;">SKU: {{ $row->sku_code ?: 'N/A' }}</small>
                    </td>
                    <td class="text-center">
                        <span class="qr-code-text">{{ $row->barcode }}</span>
                        <div class="mt-1">
                            <small class="text-muted" style="cursor: pointer;" title="Show QR Detail"><i class="fa fa-qrcode"></i> View</small>
                        </div>
                    </td>
                    <td class="text-center font-weight-bold" style="color: #4b6584;">{{ $row->batch_code }}</td>
                    <td class="text-center">
                        <span class="qr-badge status-{{ strtolower(str_replace([' ', '(', ')'], ['','',''], $row->status_label)) }}">
                            {{ $row->status_label }}
                        </span>
                    </td>
                    <td class="text-center">
                        @php
                            $clickAction = '';
                            if ($row->voucher_type == 1) {
                                // GRN view expects grn_no as ?id=, not the DB id
                                $clickAction = "showDetailModelOneParamerter('pdc/viewGoodsReceiptNoteDetail', '{$row->voucher_no}', 'View GRN', '{$m}')";
                            } elseif ($row->voucher_type == 2) {
                                $clickAction = "showDetailModelOneParamerter('sales/viewDeliveryNoteDetail/{$row->voucher_id}', '', 'View GDN', '{$m}')";
                            } elseif ($row->voucher_type == 3) {
                                $clickAction = "showDetailModelOneParamerter('sales/viewCreditNoteDetail', '{$row->voucher_id}', 'View Sale Return', '{$m}')";
                            }
                        @endphp
                        
                        @if($row->voucher_id > 0)
                            <a href="javascript:void(0)" onclick="{!! $clickAction !!}" class="font-weight-bold" style="color: #3498db; text-decoration: underline;">{{ $row->voucher_no }}</a>
                        @else
                            <span class="font-weight-bold" style="color: #34495e;">{{ $row->voucher_no }}</span>
                        @endif
                        <div style="font-size: 10px; color: #95a5a6; text-transform: uppercase;">Type: {{ $row->voucher_type == 1 ? 'GRN' : ($row->voucher_type == 2 ? 'GDN' : 'Sale Return') }}</div>
                    </td>
                    <td class="text-center">
                        <div class="font-weight-bold">{{ date('d-M-Y', strtotime($row->created_at)) }}</div>
                        <div class="text-muted" style="font-size: 11px;">{{ date('h:i A', strtotime($row->created_at)) }}</div>
                    </td>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="user-init" style="width:24px; height:24px; background:#e2e8f0; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:10px; margin-right:8px; font-weight:700;">
                                {{ strtoupper(substr($row->history_username, 0, 1)) }}
                            </div>
                            <span>{{ $row->history_username }}</span>
                        </div>
                    </td>
                    <td class="text-center">
                        @if($row->status == 1)
                            <span class="badge" style="background-color: #2ecc71; color: white; padding: 3px 8px; border-radius: 4px; font-size: 10px;">RECORDED</span>
                        @else
                            <span class="badge" style="background-color: #95a5a6; color: white; padding: 3px 8px; border-radius: 4px; font-size: 10px;">INACTIVE</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="9" class="text-center p-5">
                    <div class="p-4" style="color: #bdc3c7;">
                        <i class="fa fa-qrcode fa-4x mb-3"></i>
                        <h4 class="font-weight-bold">No Scanning History Found</h4>
                        <p>Adjust your filters or try a different date range.</p>
                    </div>
                </td>
            </tr>
        @endif
    </tbody>
</table>

@if(count($historyData) > 0)
    <div class="pagination-qr mt-4 d-flex justify-content-between align-items-center">
        <div class="text-muted" style="font-size: 13px;">
            Showing {{ $historyData->firstItem() }} to {{ $historyData->lastItem() }} of {{ $historyData->total() }} results
        </div>
        <div id="qrPaginationLinks">
            {!! $historyData->appends(request()->all())->links() !!}
        </div>
    </div>
@endif
