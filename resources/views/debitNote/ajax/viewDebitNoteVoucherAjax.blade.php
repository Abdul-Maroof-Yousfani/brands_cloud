<?php
use App\Helpers\FinanceHelper;
use App\Helpers\CommonHelper;
use App\Helpers\SalesHelper;
use App\Helpers\ReuseableCode;

$m = $m ?? $_GET['m'];
$id = $id ?? $_GET['id'];
$Checking = strpos($id, ',') !== false ? explode(',', $id)[1] : $id;

FinanceHelper::companyDatabaseConnection($m);
$row = DB::table('debits')->where('id', $Checking)->orWhere('rv_no', $Checking)->first();
FinanceHelper::reconnectMasterDatabase();

if ($row) {
    $id = $row->id;
    $approved_user = $row->approved_user ?? "N/A";
?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
        @if ($row->is_approved == 0)
            <button onclick="approve(this, '{{$row->id}}')" type="button" class="btn btn-primary btn-xs">Approve</button>
        @endif
        <button class="btn btn-xs btn-info" onclick="printView('viereceiptvoucher','','1')">
            <span class="glyphicon glyphicon-print"> Print</span>
        </button>
    </div>
    <div style="line-height:5px;">&nbsp;</div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="well" id="viereceiptvoucher">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <?php echo CommonHelper::get_company_logo(Session::get('run_company'));?>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-left"></div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                    <h3 style="text-align: center;">Debit Note Voucher</h3>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 text-right"></div>
            </div>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div style="line-height:5px;">&nbsp;</div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div style="width:40%; float:left;">
                                <table class="table table-bordered table-striped table-condensed tableMargin">
                                    <tbody>
                                        <tr>
                                            <td style="width:40%;">RV No.</td>
                                            <td style="width:60%;"><?php echo strtoupper($row->rv_no);?></td>
                                        </tr>
                                        <tr>
                                            <td>Date</td>
                                            <td><?php echo FinanceHelper::changeDateFormat($row->date);?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-condensed tableMargin">
                                    <thead>
                                        <tr>
                                            <th class="text-center" style="width:50px;">S.No</th>
                                            <th class="text-center">Account</th>
                                            <th class="text-center" style="width:150px;">Debit</th>
                                            <th class="text-center" style="width:150px;">Credit</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        FinanceHelper::companyDatabaseConnection($m);
                                        // Use debits master table's debit/credit columns to construct the view
                                        $rvsDetail = collect([
                                            (object)[
                                                'acc_id' => $row->debit, // Customer Account
                                                'amount' => $row->amount,
                                                'debit_credit' => 1      // Debit
                                            ],
                                            (object)[
                                                'acc_id' => $row->credit, // Selected Account
                                                'amount' => $row->amount,
                                                'debit_credit' => 0      // Credit
                                            ]
                                        ]);
                                        
                                        FinanceHelper::reconnectMasterDatabase();
                                        
                                        $counter = 1;
                                        $g_t_debit = 0;
                                        $g_t_credit = 0;
                                        
                                        foreach ($rvsDetail as $row2) {
                                        ?>
                                        <tr>
                                            <td class="text-center"><?php echo $counter++;?></td>
                                            <td><?php echo CommonHelper::get_account_code($row2->acc_id).'---'.FinanceHelper::getAccountNameByAccId($row2->acc_id, $m);?></td>
                                            <td class="debit_amount text-right">
                                                <?php
                                                if($row2->debit_credit == 1) {
                                                    $g_t_debit += $row2->amount;
                                                    echo number_format($row2->amount, 2);
                                                }
                                                ?>
                                            </td>
                                            <td class="credit_amount text-right">
                                                <?php
                                                if($row2->debit_credit == 0) {
                                                    $g_t_credit += $row2->amount;
                                                    echo number_format($row2->amount, 2);
                                                }
                                                ?>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                        <tr class="sf-table-total">
                                            <td class="text-center" colspan="2">
                                                <label class="sf-label"><b>Total</b></label>
                                            </td>
                                            <td class="text-right"><b><?php echo number_format($g_t_debit, 2);?></b></td>
                                            <td class="text-right"><b><?php echo number_format($g_t_credit, 2);?></b></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <b>Description:</b> {{$row->details}}
                        </div>
                        <div style="line-height:8px;">&nbsp;</div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 text-center">
                                        <h6 class="signature_bor">Prepared By: </h6>
                                        <b><p>{{ Auth::user()->username }}</p></b>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 text-center">
                                        <h6 class="signature_bor">Verified By:</h6>
                                        <b><p></p></b>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 text-center">
                                        <h6 class="signature_bor">Approved By:</h6>
                                        <b><p><?php echo strtoupper($approved_user)?></p></b>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 text-center">
                                        <h6 class="signature_bor">Received By</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php } ?>
