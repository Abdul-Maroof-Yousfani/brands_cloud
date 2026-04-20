<div class="panel panel-default shadow-sm" style="border-radius:10px; overflow:hidden; margin-top: 20px;">
    <div class="panel-heading text-white" style="background-color:#6c757d; padding:10px 15px;">
        <strong>ACCOUNTING ENTRY PREVIEW</strong>
    </div>
    <div class="panel-body" style="background-color:#ffffff;">
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-condensed mb-0">
                <thead class="bg-light">
                    <tr>
                        <th>Account</th>
                        <th class="text-right">Debit</th>
                        <th class="text-right">Credit</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        // For a Credit Note to customer:
                        // DR Account (e.g. Sales Return/Inventory)
                        // DR Tax
                        // DR Discount
                        // CR Customer (Gross amount)
                        $total_dr = $amount + $tax_amount + $discount_amount;
                        $total_cr = $net_amount; 
                    @endphp

                    @if($debit_acc)
                    <tr>
                        <td><strong>DR. {{ $debit_acc->code }} -- {{ $debit_acc->name }}</strong></td>
                        <td class="text-right">{{ number_format($amount, 2) }}</td>
                        <td class="text-right">0.00</td>
                    </tr>
                    @endif

                    @if($tax_amount > 0 && $tax_acc)
                    <tr>
                        <td><strong>DR. {{ $tax_acc->code }} -- {{ $tax_acc->name }}</strong></td>
                        <td class="text-right">{{ number_format($tax_amount, 2) }}</td>
                        <td class="text-right">0.00</td>
                    </tr>
                    @endif

                    @if($discount_amount > 0 && $disc_acc)
                    <tr>
                        <td><strong>DR. {{ $disc_acc->code }} -- {{ $disc_acc->name }}</strong></td>
                        <td class="text-right">{{ number_format($discount_amount, 2) }}</td>
                        <td class="text-right">0.00</td>
                    </tr>
                    @endif

                    @if($customer_acc)
                    <tr>
                        <td><strong>CR. {{ $customer_acc->code }} -- {{ $customer_acc->name }}</strong></td>
                        <td class="text-right">0.00</td>
                        <td class="text-right">{{ number_format($net_amount, 2) }}</td>
                    </tr>
                    @endif
                </tbody>
                <tfoot class="bg-light fw-bold" style="background: #f8f9fa;">
                    <tr>
                        <th>Total</th>
                        <th class="text-right text-success">{{ number_format($amount + $tax_amount + $discount_amount, 2) }}</th>
                        <th class="text-right text-primary">{{ number_format($net_amount, 2) }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
