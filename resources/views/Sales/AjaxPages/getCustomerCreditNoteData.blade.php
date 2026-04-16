<?php
use App\Helpers\CommonHelper;
use App\Helpers\SalesHelper;
use App\Helpers\ReuseableCode;

$view   = ReuseableCode::check_rights(124);
$edit   = ReuseableCode::check_rights(125);
$counter = 1;
$OverAllTotal = 0;
?>

@foreach($credit_note as $row)
<?php
    // SO Number
    $SoNo = '';
    if (!empty($row->so_id) && $row->so_id > 0) {
        $SoData = CommonHelper::get_single_row('sales_order', 'id', $row->so_id);
        $SoNo   = $SoData->so_no ?? '';
    }

    // Net Amount from credit_note_data
    $NetAmount = DB::Connection('mysql2')
        ->table('credit_note_data')
        ->where('master_id', $row->id)
        ->sum('net_amount');

    $OverAllTotal += $NetAmount;

    // SI / Deliver No — from query (si_dn_no column via MAX(cnd.voucher_no))
    $si_dn_display = strtoupper($row->si_dn_no ?? '-');

    // Customer name
    $customerName = '-';
    $buyerId = $row->buyer_id ?? $row->buyers_id ?? null;
    if ($buyerId) {
        $custObj = DB::Connection('mysql2')->table('customers')->where('id', $buyerId)->select('name')->first();
        $customerName = $custObj->name ?? '-';
    }
?>
    <tr id="{{ $row->id }}">
        <td class="text-center">{{ $counter++ }}</td>
        <td class="text-center"><strong>{{ strtoupper($SoNo) ?: '-' }}</strong></td>
        <td class="text-center">{{ $si_dn_display }}</td>
        <td class="text-center">
            @if($row->type == 1) DN
            @elseif($row->type == 2) SI
            @else POS
            @endif
        </td>
        <td class="text-center" title="{{ $row->id }}">{{ strtoupper($row->cr_no) }}</td>
        <td class="text-center">{{ CommonHelper::changeDateFormat($row->cr_date) }}</td>
        <td class="text-center">{{ $customerName }}</td>
        <td class="text-center">{{ number_format($NetAmount, 2) }}</td>
        <td class="text-center">
            <?php if ($view == true): ?>
                <button onclick="showDetailModelOneParamerter('sales/viewCreditNoteDetail','<?php echo $row->id ?>','View Sales Return')"
                        type="button" class="btn btn-success btn-xs">View</button>
            <?php endif; ?>
            <?php if ($edit == true): ?>
                <a href="{{ URL::asset('sales/editSalesReturn/'.$row->id.'?m='.$m) }}" class="btn btn-warning btn-xs">Edit</a>
                <button onclick="delete_sales_return('{{$row->id}}','{{$row->cr_no}}')" type="button" class="btn btn-danger btn-xs">Delete</button>
            <?php endif; ?>
        </td>
    </tr>
@endforeach

<tr>
    <td colspan="7"><strong>TOTAL</strong></td>
    <td class="text-center"><strong><?php echo number_format($OverAllTotal, 2) ?></strong></td>
</tr>