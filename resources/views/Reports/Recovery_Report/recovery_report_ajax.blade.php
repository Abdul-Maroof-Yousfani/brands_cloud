<style>
    .table-responsive { overflow-y: auto; }
    .totals-row { font-weight: bold; background-color: #f5f5f5; }
    .table-bordered > thead > tr > th {
        white-space: nowrap !important;
        position: sticky;
        top: 0;
        z-index: 2;
        background: #f9f9f9;
        border-bottom: 2px solid #ddd;
    }
    .table-bordered > tbody > tr > td {
        white-space: nowrap !important;
    }
    .table-bordered > tbody > tr > td.receipt-nos {
        white-space: pre-line !important;
    }
    .table-wrapper { max-height: 700px; overflow-y: auto; }
</style>

@php
    $totalInvoiceAmount = 0;
    $totalReceiptAmount = 0;
    $totalSaleReturnAmount = 0;
    $totalAdjustmentAmount = 0;
    $totalMoreThan180 = 0;
    $total90to179 = 0;
    $total46to90 = 0;
    $totalLessThan45 = 0;

    $to = request('to') ?? date('Y-m-d');
    $sum = 0;
@endphp

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12 text-center">
            <h3>Outstanding Ageing Report</h3>
            <h5>{{ date('d-M-Y', strtotime($to)) }}</h5>
        </div>
    </div>

<div class="table-responsive table-wrapper">
    <table class="table table-bordered table-striped" id="exportTable">
        <thead>
            <tr>
                <th>S No.</th>
                <th>Branch</th>
                <th>Region</th>
                <th>Warehouse</th>
                <th>Customer ID</th>
                <th>Cust Name</th>
                <th>Cust Address</th>
                <th>Doc Type</th>
                <th>Docno</th>
                <th>Brand</th>
                <th>Date</th>
                <th>Sales Officer Name</th>
                <th>Invoice Amount</th>
                <th>Receipt Doc No</th>
                <th>Receipt Amount</th>
                <th>Receipt Mode</th>
                <th>Sale Return Doc No</th>
                <th>Sale Return Amount</th>
                <th>Adjustment Doc No</th>
                <th>Adjust Amount</th>
                <th>Adjustment Remarks</th>
                <th>Unadjusted Amount</th>
                <th>Total Adjustment</th>
                <th>Outstanding</th>
                <th>Difference</th>
                <th>> 180</th>
                <th>90-179</th>
                <th>46-90</th>
                <th><= 45</th>
            </tr>
        </thead>
        <tbody>
            @foreach($payments as $data)
                @php
                    $totalInvoiceAmount += $data->invoice_amount;
                    $totalReceiptAmount += $data->receipt_amount;
                    $totalSaleReturnAmount += $data->sale_return_amount;
                    $totalAdjustmentAmount += $data->adjustment_amount;
                    $totalMoreThan180 += $data->more_than_one_eighty_days_due;
                    $total90to179 += $data->ninety_one_to_one_seventy_nine_days_due;
                    $total46to90 += $data->fourty_five_to_ninety_days_due;
                    $totalLessThan45 += $data->one_to_fourty_five_days_due;
                @endphp
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $data->branch ?? "N/A" }}</td>
                    <td>{{ $data->territory_name }}</td>
                    <td>{{ \App\Helpers\CommonHelper::get_name_warehouse($data->warehouse_from) }}</td>
                    <td>{{ $data->customer_code }}</td>
                    <td>{{ $data->name }}</td>
                    <td>{{ $data->address }}</td>
                    <td>SI</td>
                    <td>{{ $data->gi_no }}</td>
                    <td>{{ $data->brand_id ? \App\Helpers\CommonHelper::get_brand_by_id($data->brand_id) : "N/A" }}</td>
                    <td>{{ \Carbon\Carbon::parse($data->gd_date)->format("d-M-y") }}</td>
                    <td>{{ $data->sales_person ?? "N/A" }}</td>
                     <td>{{ number_format($data->net_amount + (($data->net_amount * $data->adv_tax) / 100), 2) }}</td>
                    <td class="receipt-nos">{{ $data->rv_numbers ?: "-" }}</td>
                    <td>{{ number_format($data->receipt_amount) }}</td>
                    <td>
                        @if($data->pay_mode == 1)
                            Cheque
                        @elseif($data->pay_mode == 2)
                            Cash
                        @elseif($data->pay_mode == 3)
                            Online Transfer
                        @else
                            N/A
                        @endif
                    </td>
                    <td class="receipt-nos">{{ $data->cr_numbers ?: "-"  }}</td>
                    <td>{{ number_format($data->sale_return_amount) }}</td>
                    <td class="receipt-nos">{{ $data->adjustment_doc_nos ?: "-" }}</td>
                    <td>{{ number_format($data->adjustment_amount) }}</td>
                    <td>{{ $data->adjustment_remarks ?: "-" }}</td>
                    <td>0</td>
                    @php
                        $inv_amount = $data->net_amount + (($data->net_amount * $data->adv_tax) / 100);
                        $sum += $inv_amount;
                    @endphp
                    <td>{{ number_format($inv_amount - ($data->receipt_amount - $data->sale_return_amount - $data->adjustment_amount)) }}</td>
                    <td>{{ number_format($inv_amount - $data->receipt_amount, 2) }}</td>
                    <td>-</td>
                    <td>{{ number_format($data->more_than_one_eighty_days_due) }}</td>
                    <td>{{ number_format($data->ninety_one_to_one_seventy_nine_days_due) }}</td>
                    <td>{{ number_format($data->fourty_five_to_ninety_days_due) }}</td>
                    <td>{{ number_format($data->one_to_fourty_five_days_due) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="totals-row">
                <td colspan="12" class="text-right">Total</td>
                <td>{{ number_format($totalInvoiceAmount) }}</td>
                <td></td>
                <td>{{ number_format($totalReceiptAmount) }}</td>
                <td></td>
                <td></td>
                <td>{{ number_format($totalSaleReturnAmount) }}</td>
                <td></td>
                <td>{{ number_format($totalAdjustmentAmount) }}</td>
                <td></td>
                <td>0</td>
                <td>{{ number_format($totalReceiptAmount - $totalSaleReturnAmount - $totalAdjustmentAmount) }}</td>
                <td>{{ number_format($sum) }}</td>
                <td></td>
                <td>{{ number_format($totalMoreThan180) }}</td>
                <td>{{ number_format($total90to179) }}</td>
                <td>{{ number_format($total46to90) }}</td>
                <td>{{ number_format($totalLessThan45) }}</td>
            </tr>
        </tfoot>
    </table>
</div>
</div>