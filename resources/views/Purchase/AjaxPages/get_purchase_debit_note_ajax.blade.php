<?php
use App\Helpers\CommonHelper;
?>

<style>
    .table-dn-main {
        width: 100%;
        border-collapse: collapse;
        font-size: 11px;
    }
    
    .table-dn-main thead th {
        background-color: #f8f9fa;
        color: #333;
        font-weight: 700;
        border: 1px solid #dee2e6;
        padding: 10px 5px;
        text-align: center;
        text-transform: uppercase;
    }
    
    .table-dn-main tbody td {
        border: 1px solid #dee2e6;
        padding: 8px 5px;
        text-align: center;
    }

    .table-dn-main tfoot td {
        background-color: #f8f9fa;
        font-weight: 700;
        border: 1px solid #dee2e6;
        padding: 10px 5px;
    }
    
    .status-badge-dn {
        padding: 3px 8px;
        border-radius: 3px;
        font-size: 9px;
        font-weight: 700;
        color: #fff;
    }
    
    .bg-approved { background-color: #2e7d32; }
    .bg-pending { background-color: #ef6c00; }
    .bg-rejected { background-color: #c62828; }
</style>

<table class="table-dn-main" id="dnDataTable">
    <thead>
        <tr>
            <th>SR #</th>
            <th>Supplier</th>
            <th>Debit Note No</th>
            <th>Ref. Invoice No</th>
            <th>Date</th>
            <th>Item Details</th>
            <th>Quantity</th>
            <th>Rate</th>
            <th>Total Amount</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @php $totalDebitAmount = 0; @endphp
        @forelse($debitNotes as $index => $dn)
            @php $totalDebitAmount += $dn->net_amount; @endphp
            <tr>
                <td>{{ $debitNotes->firstItem() + $index }}</td>
                <td>{{ $dn->supplier_name }}</td>
                <td>{{ strtoupper($dn->pr_no) }}</td>
                <td>{{ strtoupper($dn->grn_no) ?: 'N/A' }}</td>
                <td>{{ \App\Helpers\CommonHelper::changeDateFormat($dn->pr_date) }}</td>
                <td>{{ $dn->item_details }}</td>
                <td>{{ number_format($dn->return_qty, 2) }}</td>
                <td>{{ number_format($dn->rate, 2) }}</td>
                <td class="debit-amount-highlight">{{ number_format($dn->net_amount, 2) }}</td>
                <td>
                    @if($dn->status == 1)
                        <span class="status-badge-dn bg-approved">Approved</span>
                    @elseif($dn->status == 2)
                        <span class="status-badge-dn bg-pending">Pending</span>
                    @else
                        <span class="status-badge-dn bg-rejected">Rejected</span>
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="10" class="text-center p-4">No debit notes found for the selected criteria.</td>
            </tr>
        @endforelse
    </tbody>
    @if($debitNotes->count() > 0)
    <tfoot>
        <tr>
            <td colspan="8" class="text-right">Total Debit Amount Summary:</td>
            <td class="text-center debit-amount-highlight">{{ number_format($totalDebitAmount, 2) }}</td>
            <td></td>
        </tr>
    </tfoot>
    @endif
</table>

<div class="mt-4 d-flex justify-content-center">
    {!! $debitNotes->appends(request()->all())->links() !!}
</div>
