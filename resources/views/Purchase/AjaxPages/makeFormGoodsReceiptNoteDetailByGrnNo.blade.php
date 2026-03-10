<?php
use App\Helpers\CommonHelper;
use App\Helpers\PurchaseHelper;
use App\Helpers\ReuseableCode;

$m = $_GET['m'];
$makeGetValue = explode('*', $_GET['GrnNo']);
$GrnId = $makeGetValue[0] ?? 0;
$GrnNo = $makeGetValue[1] ?? '';
$GrnDate = $makeGetValue[2] ?? '';

// Fetch Invoice/Voucher Data
$invoice = DB::connection('mysql2')->table('new_purchase_voucher')
    ->where('grn_id', $GrnId)
    ->where('status', 1)
    ->where('approved_user', '!=', null)
    ->where('approve_user_2', '!=', null)
    ->first();
?>

@include('number_formate')
@include('select2')

@if(!empty($invoice))
    <?php
    $sales_tax_rate_val = 0;
    if ($invoice->sales_tax_acc_id) {
        $tax_acc = DB::connection('mysql2')->table('gst')->where('id', $invoice->sales_tax_acc_id)->first();
        $sales_tax_rate_val = $tax_acc ? $tax_acc->percent : 0;
    }

    // Original Totals for Proportional WHT Calculation
    // We need to know the original invoice net and wht to calculate return wht correctly
    $tax_summary = DB::connection('mysql2')->table('new_purchase_voucher_data')
        ->where('master_id', $invoice->id)
        ->selectRaw('SUM(net_amount) as total_net')
        ->first();
    $orig_net_total = $tax_summary->total_net ?? 0;
    $orig_wht = $invoice->sales_tax_amount ?? 0;
    $wht_factor = $orig_net_total > 0 ? ($orig_wht / $orig_net_total) : 0;
    ?>

    <input type="hidden" id="global_wht_percent" value="{{ $sales_tax_rate_val }}">
    <input type="hidden" id="global_wht_factor" value="{{ $wht_factor }}">
    <input type="hidden" id="invoice_tax_acc_name" value="{{ $tax_acc->name ?? 'SYSTEM' }}">

    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="well">
                <div class="row">
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                        <label class="sf-label" style="color: #999;">PV No.</label>
                        <input readonly type="text" class="form-control" value="{{ $invoice->pv_no ?? 'N/A' }}" />
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                        <label class="sf-label" style="color: #999;">PV Date</label>
                        <input readonly type="text" class="form-control" value="{{ !empty($invoice->pv_date) ? CommonHelper::changeDateFormat($invoice->pv_date) : 'N/A' }}" />
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                        <label class="sf-label" style="color: #999;">Vendor</label>
                        <input readonly type="text" class="form-control" value="{{ ucwords(CommonHelper::get_supplier_name($invoice->supplier ?? '')) }}" />
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                        <label class="sf-label" style="color: #999;">Bill No.</label>
                        <input readonly type="text" class="form-control" value="{{ $invoice->slip_no ?? 'N/A' }}" />
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                        <label class="sf-label" style="color: #999;">Bill Date</label>
                        <input readonly type="text" class="form-control" value="{{ !empty($invoice->bill_date) ? CommonHelper::changeDateFormat($invoice->bill_date) : 'N/A' }}" />
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="table-responsive">
                <table class="table table-bordered sf-table-list">
                    <thead>
                        <th class="text-center">Sr.No</th>
                        <th class="text-center">Item Name</th>
                        <th class="text-center">Location</th>
                        <th class="text-center hide">Batch Code</th>
                        <th class="text-center">Received Qty</th>
                        <th class="text-center"> Return QTY</th>
                        <th class="text-center">Rate</th>
                        <th class="text-center">Amount</th>
                        <th class="text-center">Tax %</th>
                        <th class="text-center">Tax Amount</th>
                        <th class="text-center">Discount Amount</th>
                        <th class="text-center">Net Amount</th>
                        <th class="text-center hide">Stock Qty</th>
                        <th class="text-center">Return Qty</th>
                        <th class="text-center">Enable/Disable</th>
                    </thead>
                    <tbody>
                        <?php
                        $Counter = 1;
                        $Count = 0;
                        foreach ($DataDetail as $Fil):
                            $inv_item = DB::connection('mysql2')->table('new_purchase_voucher_data')
                                ->where('grn_data_id', $Fil->id)
                                ->first();

                            if(!$inv_item) continue;

                            // Calculate original item discount rate relative to Gross+Tax
                            $orig_gross_plus_tax = ($inv_item->amount ?? 0) + ($inv_item->tax_amount ?? 0);
                            $item_disc_rate = $orig_gross_plus_tax > 0 ? ($inv_item->discount_amount ?? 0) / $orig_gross_plus_tax : 0;
                        ?>
                        <input type="hidden" name="grn_data_id[]" value="{{$Fil->id}}" />
                        <tr class="text-center">
                            <td><?php echo $Counter++; ?></td>
                            <input type="hidden" name="GrnDataId[]" readonly value="<?php echo $Fil->id; ?>" class="form-control" />
                            <td>
                                <input type="hidden" name="SubItemId[]" readonly id="subItemId_<?php echo $Fil->id; ?>" value="<?php echo $Fil->sub_item_id; ?>" class="form-control" />
                                <textarea name="item_desc[]" readonly id="item_desc<?php echo $Fil->id; ?>" class="form-control" style="margin: 0px 221.973px 0px 0px; resize: none; height: 90px;"><?php echo CommonHelper::get_item_name($Fil->sub_item_id); ?></textarea>
                            </td>
                            <td><?php echo CommonHelper::getCompanyDatabaseTableValueById($m, 'warehouse', 'name', $Fil->warehouse_id); ?>
                                <input value="<?php echo $Fil->warehouse_id ?>" type="hidden" name="WarehouseId[]" id="warehouse_id_<?php echo $Fil->id; ?>" />
                            </td>
                            <td class="hide"><?php echo $Fil->batch_code ?>
                                <input type="hidden" name="BatchCode[]" id="BatchCode<?php echo $Fil->id ?>" value="<?php echo $Fil->batch_code; ?>">
                            </td>
                            <td class="text-center"><?php echo number_format($Fil->purchase_recived_qty, 2); ?>
                                <input value="<?php echo $Fil->purchase_recived_qty ?>" type="hidden" name="PurchaseRecQty[]" id="purchase_recived_qty_<?php echo $Fil->id; ?>" />
                            </td>
                            <?php $reurn = 0; ?>
                            <?php $return_qty = DB::Connection('mysql2')->selectOne('select sum(return_qty)qty from purchase_return_data where status=1 and grn_data_id="' . $Fil->id . '" group by grn_data_id') ?>
                            <td class="text-center">@if(!empty($return_qty->qty)){{$reurn=$return_qty->qty}}@endif</td>
                            <input type="hidden" id="return_<?php echo $Fil->id; ?>" value="{{$reurn}}" />

                            <td class="text-center">
                                {{ number_format($Fil->rate,2) }}
                                <input value="{{ $Fil->rate }}" type="hidden" name="Rate[]" id="rate_{{ $Fil->id }}"/>
                            </td>
                            <?php 
                                $available_qty = $Fil->purchase_recived_qty - $reurn;
                                $row_amount = $available_qty * $Fil->rate;
                                $row_tax = ($row_amount * ($inv_item->tax_rate ?? 0)) / 100;
                                $row_disc = ($row_amount + $row_tax) * $item_disc_rate;
                                $row_net = ($row_amount + $row_tax) - $row_disc;
                            ?>
                            <td class="text-center">
                                <span id="amount_disp_{{ $Fil->id }}">{{ number_format($row_amount, 2) }}</span>
                                <input value="{{ number_format($row_amount, 2, '.', '') }}" type="hidden" name="Amount[]" id="amount_{{ $Fil->id }}" class="item_row_amount"/>
                            </td>
                            <td class="text-center">
                                {{ $inv_item->tax_rate ?? 0 }} %
                                <input type="hidden" id="item_tax_rate_{{ $Fil->id }}" value="{{ $inv_item->tax_rate ?? 0 }}">
                            </td>
                            <td class="text-center">
                                <span id="tax_amount_disp_{{ $Fil->id }}">{{ number_format($row_tax, 2) }}</span>
                                <input type="hidden" class="item_row_tax_amount" id="tax_amount_{{ $Fil->id }}" value="{{ number_format($row_tax, 2, '.', '') }}">
                            </td>
                            <td class="text-center">
                                <span id="disc_amount_disp_{{ $Fil->id }}">{{ number_format($row_disc, 2) }}</span>
                                <input type="hidden" id="disc_amount_{{ $Fil->id }}" value="{{ number_format($row_disc, 2, '.', '') }}">
                            </td>
                            <td class="text-center">
                                <span id="net_amount_disp_{{ $Fil->id }}">{{ number_format($row_net, 2) }}</span>
                                <input type="hidden" id="net_amount_{{ $Fil->id }}" value="{{ number_format($row_net, 2, '.', '') }}" class="item_row_net_amount"/>
                            </td>

                            {{-- Item Configs --}}
                            <input type="hidden" id="item_disc_rate_{{ $Fil->id }}" value="{{ $item_disc_rate }}">

                            <td class="hide">
                                <input type="number" class="form-control" id="stock_qty<?php echo $Fil->id ?>" name="stock_qty[]" value="{{ReuseableCode::get_stock($Fil->sub_item_id,$Fil->warehouse_id,0,$Fil->batch_code)}}" readonly>
                            </td>
                            <td>
                                <input type="number" step="any" class="form-control" id="return_qty_<?php echo $Fil->id ?>" name="ReturnQty[]" value="{{ number_format($available_qty ?? 0, 2, '.', '') }}" readonly onkeyup="check_val('<?php echo $Fil->id ?>')">
                            </td>
                            <td>
                                <input type="checkbox" name="enable_disable[]" id="enable_disable_<?php echo $Fil->id ?>" value="<?php echo $Count; ?>" class="form-control amount" style="height: 25px !important;" onclick="ChkUnChk('<?php echo $Fil->id ?>')">
                            </td>
                            <input type="hidden" name="discount_percent[]" value="{{$Fil->discount_percent}}" />
                        </tr>
                        <?php $Count++; endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"></div>
        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
            <label class="sf-label" style="color: #999;">Tax %</label>
            <input readonly type="text" class="form-control" id="summary_tax_acc_name" value="SYSTEM" />
        </div>
        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
            <label class="sf-label" style="color: #999;">Tax Amount</label>
            <input readonly id="summary_tax_amount" type="text" class="form-control" value="0.00" />
        </div>
        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
            <label class="sf-label" style="color: #999;">Withholding %</label>
            <input readonly type="text" class="form-control" value="{{ $sales_tax_rate_val }} %" />
        </div>
        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
            <label class="sf-label" style="color: #999;">Withholding Tax</label>
            <input readonly id="summary_withholding_tax" type="text" class="form-control" value="0.00" />
        </div>
    </div>

@else
    {{-- OLD View (Simple) --}}
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="table-responsive">
                <table class="table table-bordered sf-table-list">
                    <thead>
                        <th class="text-center">Sr.No</th>
                        <th class="text-center">Item Name</th>
                        <th class="text-center">Location</th>
                        <th class="text-center hide">Batch Code</th>
                        <th class="text-center">Received Qty</th>
                        <th class="text-center"> Return QTY</th>
                        <th style="display: none" class="text-center">Rate</th>
                        <th style="display: none" class="text-center">Amount</th>
                        <th class="text-center hide">Stock Qty</th>
                        <th class="text-center">Return Qty</th>
                        <th class="text-center">Enable/Disable</th>
                    </thead>
                    <tbody>
                        <?php
                        $Counter = 1;
                        $Count = 0;
                        foreach ($DataDetail as $Fil):
                        ?>
                        <input type="hidden" name="grn_data_id[]" value="{{$Fil->id}}" />
                        <tr class="text-center">
                            <td><?php echo $Counter++; ?></td>
                            <input type="hidden" name="GrnDataId[]" readonly value="<?php echo $Fil->id; ?>" class="form-control" />
                            <td>
                                <input type="hidden" name="SubItemId[]" readonly id="subItemId_<?php echo $Fil->id; ?>" value="<?php echo $Fil->sub_item_id; ?>" class="form-control" />
                                <textarea name="item_desc[]" readonly id="item_desc<?php echo $Fil->id; ?>" class="form-control" style="margin: 0px 221.973px 0px 0px; resize: none; height: 90px;"><?php echo CommonHelper::get_item_name($Fil->sub_item_id); ?></textarea>
                            </td>
                            <td><?php echo CommonHelper::getCompanyDatabaseTableValueById($m, 'warehouse', 'name', $Fil->warehouse_id); ?>
                                <input value="<?php echo $Fil->warehouse_id ?>" type="hidden" name="WarehouseId[]" id="warehouse_id_<?php echo $Fil->id; ?>" />
                            </td>
                            <td class="hide"><?php echo $Fil->batch_code ?>
                                <input type="hidden" name="BatchCode[]" id="BatchCode<?php echo $Fil->id ?>" value="<?php echo $Fil->batch_code; ?>">
                            </td>
                            <td class="text-center"><?php echo number_format($Fil->purchase_recived_qty, 2); ?>
                                <input value="<?php echo $Fil->purchase_recived_qty ?>" type="hidden" name="PurchaseRecQty[]" id="purchase_recived_qty_<?php echo $Fil->id; ?>" />
                            </td>
                            <?php $reurn = 0; ?>
                            <?php $return_qty = DB::Connection('mysql2')->selectOne('select sum(return_qty)qty from purchase_return_data where status=1 and grn_data_id="' . $Fil->id . '" group by grn_data_id') ?>
                            <td class="text-center">@if(!empty($return_qty->qty)){{$reurn=$return_qty->qty}}@endif</td>
                            <input type="hidden" id="return_<?php echo $Fil->id; ?>" value="{{$reurn}}" />

                            <td style="display: none" class="text-center"><?php echo number_format($Fil->rate, 2); ?>
                                <input value="<?php echo $Fil->rate ?>" type="hidden" name="Rate[]" id="rate_<?php echo $Fil->id; ?>" />
                            </td>
                            <td style="display: none" class="text-center"><?php echo number_format($Fil->amount, 2); ?>
                                <input value="<?php echo $Fil->amount ?>" type="hidden" name="Amount[]" id="amount_<?php echo $Fil->id; ?>" />
                            </td>

                            <td class="hide">
                                <input type="number" class="form-control" id="stock_qty<?php echo $Fil->id ?>" name="stock_qty[]" value="{{ReuseableCode::get_stock($Fil->sub_item_id,$Fil->warehouse_id,0,$Fil->batch_code)}}" readonly>
                            </td>
                            <td>
                                <input type="number" step="any" class="form-control" id="return_qty_<?php echo $Fil->id ?>" name="ReturnQty[]" value="{{ number_format($available_qty ?? 0, 2, '.', '') }}" readonly onkeyup="check_val('<?php echo $Fil->id ?>')">
                            </td>
                            <td>
                                <input type="checkbox" name="enable_disable[]" id="enable_disable_<?php echo $Fil->id ?>" value="<?php echo $Count; ?>" class="form-control amount" style="height: 25px !important;" onclick="ChkUnChk('<?php echo $Fil->id ?>')">
                            </td>
                            <input type="hidden" name="discount_percent[]" value="{{$Fil->discount_percent}}" />
                        </tr>
                        <?php $Count++; endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endif

{{-- Common Footer Sections --}}
<div class="row">
    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
        <?php
        $str = DB::Connection('mysql2')->selectOne("select max(convert(substr(`pr_no`,3,length(substr(`pr_no`,3))-4),signed integer)) reg from `purchase_return` where substr(`pr_no`,-4,2) = " . date('m') . " and substr(`pr_no`,-2,2) = " . date('y') . "")->reg;
        $PurchaseReturnNo = 'dr' . ($str + 1) . date('my');
        ?>
        <label for="">Purchase Return No</label>
        <input type="text" class="form-control" id="" value="<?php echo strtoupper($PurchaseReturnNo) ?>" readonly>
    </div>
    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
        <label for="">Purchase Return Date</label>
        <input type="date" id="PurchaseReturnDate" name="PurchaseReturnDate" value="<?php echo date('Y-m-d') ?>" class="form-control">
    </div>
    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
        <label for="">Good Receipt Not Date</label>
        <input type="date" id="GrnDate" name="GrnDate" value="<?php echo $GrnDate ?>" class="form-control" readonly>
        <input type="hidden" id="GrnNo" name="GrnNo" value="<?php echo $GrnNo ?>" class="form-control" readonly>
        <input type="hidden" id="GrnId" name="GrnId" value="<?php echo $GrnId ?>" class="form-control" readonly>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <label for="">Remarks</label>
        <span class="rflabelsteric"><strong>*</strong></span>
        <textarea name="Remarks" id="Remarks" cols="30" rows="3" class="form-control requiredField" placeholder="REMARKS"></textarea>
    </div>
</div>

<script !src="">
    function check_val(Id) {
        var stock_qty = parseFloat($('#stock_qty' + Id).val()) || 0;
        var inputReturnQty = parseFloat($('#return_qty_' + Id).val()) || 0;
        var ReceivedQty = parseFloat($('#purchase_recived_qty_' + Id).val()) || 0;
        var alreadyReturned = parseFloat($('#return_' + Id).val()) || 0;

        var availableToReturn = ReceivedQty - alreadyReturned;

        if (inputReturnQty > availableToReturn) {
            alert('Return Qty cannot exceed available quantity (' + availableToReturn + ')');
            $('#return_qty_' + Id).val('0.00');
            inputReturnQty = 0;
        } else if (inputReturnQty > stock_qty) {
            alert('Return Qty cannot exceed physical stock (' + stock_qty + ')');
            $('#return_qty_' + Id).val('0.00');
            inputReturnQty = 0;
        }

        // Logic for Return Values (not remaining)
        if ($('#rate_' + Id).length) {
            var rate = parseFloat($('#rate_' + Id).val()) || 0;
            var tax_rate = parseFloat($('#item_tax_rate_' + Id).val()) || 0;
            var disc_rate = parseFloat($('#item_disc_rate_' + Id).val()) || 0;

            var returnAmount = inputReturnQty * rate; // Gross Return
            var returnTax = (returnAmount * tax_rate) / 100; // GST on Return
            var returnDisc = (returnAmount + returnTax) * disc_rate; // Discount on Return
            var returnNet = (returnAmount + returnTax) - returnDisc; // Net Return Total

            $('#amount_disp_' + Id).text(returnAmount.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}));
            $('#amount_' + Id).val(returnAmount.toFixed(2));

            $('#tax_amount_disp_' + Id).text(returnTax.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}));
            $('#tax_amount_' + Id).val(returnTax.toFixed(2));

            $('#disc_amount_disp_' + Id).text(returnDisc.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}));
            $('#disc_amount_' + Id).val(returnDisc.toFixed(2));

            $('#net_amount_disp_' + Id).text(returnNet.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}));
            $('#net_amount_' + Id).val(returnNet.toFixed(2));

            recalculate_summary();
        }
    }

    $(document).ready(function() {
        var taxAccName = $('#invoice_tax_acc_name').val();
        if (taxAccName && taxAccName !== 'SYSTEM') {
            $('#summary_tax_acc_name').val(taxAccName);
        }
        recalculate_summary();
    });

    function recalculate_summary() {
        if (!$('.item_row_amount').length) return;

        var totalReturnTax = 0;
        var totalReturnNet = 0;

        // Only sum items where enable_disable checkbox is checked
        $('input[name="enable_disable[]"]:checked').each(function () {
            var id = $(this).attr('id').split('_')[2]; // enable_disable_ID
            var rowTax = parseFloat($('#tax_amount_' + id).val()) || 0;
            var rowNet = parseFloat($('#net_amount_' + id).val()) || 0;
            
            totalReturnTax += rowTax;
            totalReturnNet += rowNet;
        });

        $('#summary_tax_amount').val(totalReturnTax.toFixed(2));

        // Proportional WHT Calculation for Return
        var whtFactor = parseFloat($('#global_wht_factor').val()) || 0;
        var returnWHT = totalReturnNet * whtFactor;
        
        $('#summary_withholding_tax').val(returnWHT.toFixed(2));
    }

    function ChkUnChk(Id) {
        if ($('#enable_disable_' + Id).prop("checked") == true) {
            $('#return_qty_' + Id).prop('readonly', false);
        } else {
            $('#return_qty_' + Id).prop('readonly', true);
        }
        recalculate_summary();
    }
</script>
<script src="{{ URL::asset('assets/js/select2/js_tabindex.js') }}"></script>
