<?php
use App\Helpers\ReuseableCode;
use App\Helpers\CommonHelper;
use App\Helpers\SalesHelper;

$count = 1;
$grand_total_gross = 0;
$grand_total_cost = 0;
$grand_total_profit = 0;

$data = DB::Connection('mysql2')->select('
    select 
        a.gi_no, a.so_no, a.gi_date, a.buyers_id, a.id as si_id,
        b.item_id, b.qty, b.rate, b.amount as gross_amount,
        c.product_name as item_name, c.sku_code as sku_code, c.purchase_price as unit_cost,
        d.gd_no as dn_no
    from sales_tax_invoice a
    inner join sales_tax_invoice_data b on b.master_id = a.id
    inner join subitem c on c.id = b.item_id
    left join delivery_note d on d.id = b.dn_data_ids
    where a.status = 1 
    and a.gi_date BETWEEN "'.$from.'" and "'.$to.'"
    and a.so_type = 0
    order by a.gi_date desc, a.gi_no desc
');
?>

<div class="text-right" style="margin-bottom: 10px;">
    <input type="button" value="Print Report" onclick="printReport()" class="btn btn-primary">
</div>

<div id="print_section">
    <table class="table table-bordered sf-table-list">
        <thead class="thead-dark">
            <tr class="text-center">
                <th>S.No#</th>
                <th>SI No</th>
                <th>SO No</th>
                <th>SI Date</th>
                <th>DN No</th>
                <th>Customer</th>
                <th>Item Name</th>
                <th>SKU Code</th>
                <th>Qty</th>
                <th>Unit Amount</th>
                <th>Gross Amount</th>
                <th>Unit Cost</th>
                <th>Total Cost</th>
                <th>Profit</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $row)
                <?php
                    $qty = $row->qty;
                    $gross = $row->gross_amount;
                    $unit_cost = $row->unit_cost ?? 0;
                    $total_cost = $qty * $unit_cost;
                    $profit = $gross - $total_cost;

                    $grand_total_gross += $gross;
                    $grand_total_cost += $total_cost;
                    $grand_total_profit += $profit;
                ?>
                <tr class="text-center">
                    <td>{{ $count++ }}</td>
                    <td>{{ strtoupper($row->gi_no) }}</td>
                    <td>{{ strtoupper($row->so_no) }}</td>
                    <td>{{ CommonHelper::changeDateFormat($row->gi_date) }}</td>
                    <td>{{ strtoupper($row->dn_no) }}</td>
                    <td>{{ SalesHelper::get_customer_name($row->buyers_id) }}</td>
                    <td>{{ $row->item_name }}</td>
                    <td>{{ $row->sku_code }}</td>
                    <td>{{ number_format($qty, 2) }}</td>
                    <td>{{ number_format($row->rate, 2) }}</td>
                    <td>{{ number_format($gross, 2) }}</td>
                    <td>{{ number_format($unit_cost, 2) }}</td>
                    <td>{{ number_format($total_cost, 2) }}</td>
                    <td @if($profit <= 0) style="background-color: #ffdce0;" @endif>
                        {{ number_format($profit, 2) }}
                    </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr style="font-weight: bold; background-color: #f8f9fa;" class="text-center">
                <td colspan="10">Grand Total</td>
                <td>{{ number_format($grand_total_gross, 2) }}</td>
                <td></td>
                <td>{{ number_format($grand_total_cost, 2) }}</td>
                <td>{{ number_format($grand_total_profit, 2) }}</td>
            </tr>
        </tfoot>
    </table>
</div>

<script>
    function printReport() {
        var divToPrint = document.getElementById('print_section');
        var newWin = window.open('', 'Print-Window');
        newWin.document.open();
        newWin.document.write('<html><head><title>COGS Report</title><link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"></head><body onload="window.print()">' + divToPrint.innerHTML + '</body></html>');
        newWin.document.close();
    }
</script>




