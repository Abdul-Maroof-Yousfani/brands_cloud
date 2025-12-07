<?php 
use App\Helpers\CommonHelper;
use App\Helpers\FinanceHelper;
?>
<div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="view_flow">
                                                <div class="view_header">
                                                    <h2><?php  echo CommonHelper::getCompanyName(Session::get('run_company'));?></h2>
                                                    <h3>CASH FLOW</h3>
                                                    <h4>Form Date: (<?php  echo CommonHelper::changeDateFormat($from_date);?>) To Date: (<?php  echo CommonHelper::changeDateFormat($to_date);?>)</h4>
                                                    <h4>Printed On: <?php echo date_format(date_create(date('Y-m-d')),'F d, Y')?></h4>
                                                </div>
                                               <div class="view_header2">
                                                    <h2>CASH FLOW ACTIVITIES</h2>
                                                </div>
                                                <div class="table-responsive">
                                                    <table class="table table-bordered sf-table-th sf-table-list profit_Loss_Statement viewcashflow_report"style="pading:10px 8px !important;" id="exportIncomeStatement1" style="background:#FFF !important;">

                                                        <thead>
                                                            <tr>
                                                                <th>Particulars</th>
                                                                <th>Start Rate</th>
                                                                <th>End Rate</th>
                                                                <th>Diff</th>
                                                                <th></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td scope="row"></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                            </tr>
                                                            <tr>
                                                                <td style="font-size: 17px !important;font-weight:bold;color:#000;" scope="row">Net Income</td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td style="font-size: 17px !important;font-weight:bold;color:#000;">{{number_format($net_income, 2)}}</td>
                                                            </tr>
                                                        </tbody>
                                                        <thead>
                                                            <tr>
                                                                <th>Operating Activities</th>
                                                                <th></th>
                                                                <th></th>
                                                                <th></th>
                                                                <th></th>
                                                            </tr>
                                                        </thead>
                                                        <?php
                                                            $start_operating_total = 0;
                                                            $operating_total = 0;
                                                            $operating_diff = 0;
                                                            $netIncome_with_operating = 0;
                                                        ?>
                                                        <tbody>
                                                            <tr>
                                                                <td scope="row">Adjustments for non-cash items, such as:</td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                            </tr>
                                                            <tr>
                                                                <?php
                                                                    $name1 = 'DEPRECIATION';
                                                                    $code1 = CommonHelper::get_account_code_by_name($name1);
                                                                
                                                                    if($code1 == 'fail'){
                                                                        $from1 = 0;
                                                                        $to1 = 0;
                                                                        $diff1 = 0;
                                                                    }else{
                                                                        $from1 = FinanceHelper::ChartOfAccountCashFlowCurrentBalance($CompanyId, $code1, $from_date, 0);
                                                                        $from1 = $from1[0];

                                                                        $to1 = FinanceHelper::ChartOfAccountCashFlowCurrentBalance($CompanyId, $code1, $from_date, $to_date);
                                                                        $to1 = $to1[0];

                                                                        $diff1 = $from1 - $to1;
                                                                    }

                                                                    $start_operating_total = $start_operating_total + $from1;
                                                                    $operating_total = $operating_total + $to1;
                                                                    $operating_diff = $operating_diff + $diff1;
                                                                ?>
                                                                <td scope="row">Depreciation and Amortization</td>
                                                                <td>{{ number_format($from1, 2) }}</td>
                                                                <td>{{ number_format($to1, 2) }}</td>
                                                                <td>{{ number_format($diff1, 2) }}</td>
                                                                <td></td>
                                                            </tr>
                                                            <tr>
                                                                <?php
                                                                    $name2 = 'RECEIVABLE';
                                                                    $code2 = CommonHelper::get_account_code_by_name($name2);
                                                                
                                                                    if($code2 == 'fail'){
                                                                        $from2 = 0;
                                                                        $to2 = 0;
                                                                        $diff2 = 0;
                                                                    }else{
                                                                        $from2 = FinanceHelper::ChartOfAccountCashFlowCurrentBalance($CompanyId, $code2, $from_date, 0);
                                                                        $from2 = $from2[0];

                                                                        $to2 = FinanceHelper::ChartOfAccountCashFlowCurrentBalance($CompanyId, $code2, $from_date, $to_date);
                                                                        $to2 = $to2[0];

                                                                        $diff2 = $from2 - $to2;
                                                                    }

                                                                    $start_operating_total = $start_operating_total + $from2;
                                                                    $operating_total = $operating_total + $to2;
                                                                    $operating_diff = $operating_diff + $diff2;
                                                                ?>
                                                                <td scope="row">Accounts Receivable</td>
                                                                <td>{{ number_format($from2, 2) }}</td>
                                                                <td>{{ number_format($to2, 2) }}</td>
                                                                <td>{{ number_format($diff2, 2) }}</td>
                                                                <td></td>
                                                            </tr>
                                                            <tr>
                                                                <?php
                                                                    $name3 = 'INVENTORY';
                                                                    $code3 = CommonHelper::get_account_code_by_name($name3);
                                                                
                                                                    if($code3 == 'fail'){
                                                                        $from3 = 0;
                                                                        $to3 = 0;
                                                                        $diff3 = 0;
                                                                    }else{
                                                                        $from3 = FinanceHelper::ChartOfAccountCashFlowCurrentBalance($CompanyId, $code3, $from_date, 0);
                                                                        $from3 = $from3[0];

                                                                        $to3 = FinanceHelper::ChartOfAccountCashFlowCurrentBalance($CompanyId, $code3, $from_date, $to_date);
                                                                        $to3 = $to3[0];

                                                                        $diff3 = $from3 - $to3;
                                                                    }

                                                                    $start_operating_total = $start_operating_total + $from3;
                                                                    $operating_total = $operating_total + $to3;
                                                                    $operating_diff = $operating_diff + $diff3;
                                                                ?>
                                                                <td scope="row">Inventory</td>
                                                                <td>{{ number_format($from3, 2) }}</td>
                                                                <td>{{ number_format($to3, 2) }}</td>
                                                                <td>{{ number_format($diff3, 2) }}</td>
                                                                <td></td>
                                                            </tr>
                                                            <tr>
                                                                <?php
                                                                    $name4 = 'PAYABLES';
                                                                    $code4 = CommonHelper::get_account_code_by_name($name4);
                                                                
                                                                    if($code4 == 'fail'){
                                                                        $from4 = 0;
                                                                        $to4 = 0;
                                                                        $diff4 = 0;
                                                                    }else{
                                                                        $from4 = FinanceHelper::ChartOfAccountCashFlowCurrentBalance($CompanyId, $code4, $from_date, 0);
                                                                        $from4 = $from4[0];

                                                                        $to4 = FinanceHelper::ChartOfAccountCashFlowCurrentBalance($CompanyId, $code4, $from_date, $to_date);
                                                                        $to4 = $to4[0];

                                                                        $diff4 = $from4 - $to4;
                                                                    }

                                                                    $start_operating_total = $start_operating_total + $from4;
                                                                    $operating_total = $operating_total + $to4;
                                                                    $operating_diff = $operating_diff + $diff4;
                                                                ?>
                                                                <td scope="row">Accounts Payable</td>
                                                                <td>{{ number_format($from4, 2) }}</td>
                                                                <td>{{ number_format($to4, 2) }}</td>
                                                                <td>{{ number_format($diff4, 2) }}</td>
                                                                <td></td>
                                                            </tr>
                                                            <tr>
                                                                <?php
                                                                    $name5 = 'ACCRUED';
                                                                    $code5 = CommonHelper::get_account_code_by_name($name5);
                                                                
                                                                    if($code5 == 'fail'){
                                                                        $from5 = 0;
                                                                        $to5 = 0;
                                                                        $diff5 = 0;
                                                                    }else{
                                                                        $from5 = FinanceHelper::ChartOfAccountCashFlowCurrentBalance($CompanyId, $code5, $from_date, 0);
                                                                        $from5 = $from5[0];

                                                                        $to5 = FinanceHelper::ChartOfAccountCashFlowCurrentBalance($CompanyId, $code5, $from_date, $to_date);
                                                                        $to5 = $to5[0];

                                                                        $diff5 = $from5 - $to5;
                                                                    }

                                                                    $start_operating_total = $start_operating_total + $from5;
                                                                    $operating_total = $operating_total + $to5;
                                                                    $operating_diff = $operating_diff + $diff5;
                                                                ?>
                                                                <td scope="row">Accrued Expenses</td>
                                                                <td>{{ number_format($from5, 2) }}</td>
                                                                <td>{{ number_format($to5, 2) }}</td>
                                                                <td>{{ number_format($diff5, 2) }}</td>
                                                                <td></td>
                                                            </tr>
                                                            <tr>
                                                                <td style="font-size: 17px !important;font-weight:bold; color:#000;background: transparent;" scope="row">Total</td>
                                                                <td style="font-size: 17px !important;font-weight:bold;color:#000;background: transparent;">{{ number_format($start_operating_total, 2) }}</td>
                                                                <td style="font-size: 17px !important;font-weight:bold;color:#000;background: transparent;">{{ number_format($operating_total, 2) }}</td>
                                                                <td style="font-size: 17px !important;font-weight:bold;color:#000;background: transparent;">{{ number_format($operating_diff, 2) }}</td>
                                                                <td></td>
                                                            </tr>
                                                            
                                                            <tr>
                                                                <?php $netIncome_with_operating = $net_income + $operating_diff; ?>
                                                                <td style="font-size: 17px !important;font-weight:bold; color:#000;background: transparent;" scope="row">Net Cash Provided by Operating Activities</td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td style="font-size: 17px !important;font-weight:bold;color:#000;background: transparent;">{{ number_format($netIncome_with_operating, 2) }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td scope="row"></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                            </tr>
                                                        </tbody>

                                                        <thead>
                                                            <tr>
                                                                <th>Investing Activities</th>
                                                                <th></th>
                                                                <th></th>
                                                                <th></th>
                                                                <th></th>
                                                            </tr>
                                                        </thead>
                                                        <?php
                                                            $start_investing_total = 0;
                                                            $investing_total = 0;
                                                            $investing_diff = 0;
                                                            $netIncome_with_investing = 0;
                                                        ?>
                                                        <tbody>
                                                            <tr>
                                                                <td scope="row"></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                            </tr>
                                                            <tr>
                                                                <?php
                                                                    $name11 = 'PPE';
                                                                    $code11 = CommonHelper::get_account_code_by_name($name11);
                                                                
                                                                    if($code11 == 'fail'){
                                                                        $from11 = 0;
                                                                        $to11 = 0;
                                                                        $diff11 = 0;
                                                                    }else{
                                                                        $from11 = FinanceHelper::ChartOfAccountCashFlowCurrentBalance($CompanyId, $code11, $from_date, 0);
                                                                        $from11 = $from11[0];

                                                                        $to11 = FinanceHelper::ChartOfAccountCashFlowCurrentBalance($CompanyId, $code11, $from_date, $to_date);
                                                                        $to11 = $to11[0];

                                                                        $diff11 = $from11 - $to11;
                                                                    }

                                                                    $start_investing_total = $start_investing_total + $from11;
                                                                    $investing_total = $investing_total + $to11;
                                                                    $investing_diff = $investing_diff + $diff11;
                                                                ?>
                                                                <td scope="row">Property Plant and Equipment</td>
                                                                <td>{{ number_format($from11, 2) }}</td>
                                                                <td>{{ number_format($to11, 2) }}</td>
                                                                <td>{{ number_format($diff11, 2) }}</td>
                                                                <td></td>
                                                            </tr>
                                                            <tr>
                                                                <?php
                                                                    $name22 = 'INVESTMENTS';
                                                                    $code22 = CommonHelper::get_account_code_by_name($name22);
                                                                
                                                                    if($code22 == 'fail'){
                                                                        $from22 = 0;
                                                                        $to22 = 0;
                                                                        $diff22 = 0;
                                                                    }else{
                                                                        $from22 = FinanceHelper::ChartOfAccountCashFlowCurrentBalance($CompanyId, $code22, $from_date, 0);
                                                                        $from22 = $from22[0];

                                                                        $to22 = FinanceHelper::ChartOfAccountCashFlowCurrentBalance($CompanyId, $code22, $from_date, $to_date);
                                                                        $to22 = $to22[0];

                                                                        $diff22 = $from22 - $to22;
                                                                    }

                                                                    $start_investing_total = $start_investing_total + $from22;
                                                                    $investing_total = $investing_total + $to22;
                                                                    $investing_diff = $investing_diff + $diff22;
                                                                ?>
                                                                <td scope="row">Investments</td>
                                                                <td>{{ number_format($from22, 2) }}</td>
                                                                <td>{{ number_format($to22, 2) }}</td>
                                                                <td>{{ number_format($diff22, 2) }}</td>
                                                                <td></td>
                                                            </tr>
                                                            <tr>
                                                                <?php
                                                                    $name33 = 'OTHER INVESTING';
                                                                    $code33 = CommonHelper::get_account_code_by_name($name33);
                                                                
                                                                    if($code33 == 'fail'){
                                                                        $from33 = 0;
                                                                        $to33 = 0;
                                                                        $diff33 = 0;
                                                                    }else{
                                                                        $from33 = FinanceHelper::ChartOfAccountCashFlowCurrentBalance($CompanyId, $code33, $from_date, 0);
                                                                        $from33 = $from33[0];

                                                                        $to33 = FinanceHelper::ChartOfAccountCashFlowCurrentBalance($CompanyId, $code33, $from_date, $to_date);
                                                                        $to33 = $to33[0];

                                                                        $diff33 = $from33 - $to33;
                                                                    }

                                                                    $start_investing_total = $start_investing_total + $from33;
                                                                    $investing_total = $investing_total + $to33;
                                                                    $investing_diff = $investing_diff + $diff33;
                                                                ?>
                                                                <td scope="row">Other Investing</td>
                                                                <td>{{ number_format($from33, 2) }}</td>
                                                                <td>{{ number_format($to33, 2) }}</td>
                                                                <td>{{ number_format($diff33, 2) }}</td>
                                                                <td></td>
                                                            </tr>
                                                            <tr>
                                                                <td style="font-size: 17px !important;font-weight:bold; color:#000;background: transparent;" scope="row">Total</td>
                                                                <td style="font-size: 17px !important;font-weight:bold;color:#000;background: transparent;">{{ number_format($start_investing_total, 2) }}</td>
                                                                <td style="font-size: 17px !important;font-weight:bold;color:#000;background: transparent;">{{ number_format($investing_total, 2) }}</td>
                                                                <td style="font-size: 17px !important;font-weight:bold;color:#000;background: transparent;">{{ number_format($investing_diff, 2) }}</td>
                                                                <td></td>
                                                            </tr>
                                                            <tr>
                                                                <?php $netIncome_with_investing = $net_income + $investing_diff; ?>
                                                                <td style="font-size: 17px !important;font-weight:bold; color:#000;background: transparent;" scope="row">Net Cash Used in Investing Activities</td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td style="font-size: 17px !important;font-weight:bold;color:#000;background: transparent;">{{ number_format($netIncome_with_investing, 2) }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td scope="row"></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                            </tr>
                                                        </tbody>

                                                        <thead>
                                                            <tr>
                                                                <th>Financing Activities</th>
                                                                <th></th>
                                                                <th></th>
                                                                <th></th>
                                                                <th></th>
                                                            </tr>
                                                        </thead>
                                                        <?php
                                                            $start_financing_total = 0;
                                                            $financing_total = 0;
                                                            $financing_diff = 0;
                                                            $netIncome_with_financing = 0;
                                                        ?>
                                                        <tbody>
                                                            <tr>
                                                                <td scope="row"></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                            </tr>
                                                            <tr>
                                                                <?php
                                                                    $name111 = 'STOCK';
                                                                    $code111 = CommonHelper::get_account_code_by_name($name111);
                                                                
                                                                    if($code111 == 'fail'){
                                                                        $from111 = 0;
                                                                        $to111 = 0;
                                                                        $diff111 = 0;
                                                                    }else{
                                                                        $from111 = FinanceHelper::ChartOfAccountCashFlowCurrentBalance($CompanyId, $code111, $from_date, 0);
                                                                        $from111 = $from111[0];

                                                                        $to111 = FinanceHelper::ChartOfAccountCashFlowCurrentBalance($CompanyId, $code111, $from_date, $to_date);
                                                                        $to111 = $to111[0];

                                                                        $diff111 = $from111 - $to111;
                                                                    }

                                                                    $start_financing_total = $start_financing_total + $from111;
                                                                    $financing_total = $financing_total + $to111;
                                                                    $financing_diff = $financing_diff + $diff111;
                                                                ?>
                                                                <td scope="row">Common Stock</td>
                                                                <td>{{ number_format($from111, 2) }}</td>
                                                                <td>{{ number_format($to111, 2) }}</td>
                                                                <td>{{ number_format($diff111, 2) }}</td>
                                                                <td></td>
                                                            </tr>
                                                            <tr>
                                                                <?php
                                                                    $name222 = 'LOAN';
                                                                    $code222 = CommonHelper::get_account_code_by_name($name222);
                                                                
                                                                    if($code222 == 'fail'){
                                                                        $from222 = 0;
                                                                        $to222 = 0;
                                                                        $diff222 = 0;
                                                                    }else{
                                                                        $from222 = FinanceHelper::ChartOfAccountCashFlowCurrentBalance($CompanyId, $code222, $from_date, 0);
                                                                        $from222 = $from222[0];

                                                                        $to222 = FinanceHelper::ChartOfAccountCashFlowCurrentBalance($CompanyId, $code222, $from_date, $to_date);
                                                                        $to222 = $to222[0];

                                                                        $diff222 = $from222 - $to222;
                                                                    }

                                                                    $start_financing_total = $start_financing_total + $from222;
                                                                    $financing_total = $financing_total + $to222;
                                                                    $financing_diff = $financing_diff + $diff222;
                                                                ?>
                                                                <td scope="row">Debt (Bonds, Loans)</td>
                                                                <td>{{ number_format($from222, 2) }}</td>
                                                                <td>{{ number_format($to222, 2) }}</td>
                                                                <td>{{ number_format($diff222, 2) }}</td>
                                                                <td></td>
                                                            </tr>
                                                            <tr>
                                                                <?php
                                                                    $name333 = 'DIVIDEND';
                                                                    $code333 = CommonHelper::get_account_code_by_name($name333);
                                                                
                                                                    if($code333 == 'fail'){
                                                                        $from333 = 0;
                                                                        $to333 = 0;
                                                                        $diff333 = 0;
                                                                    }else{
                                                                        $from333 = FinanceHelper::ChartOfAccountCashFlowCurrentBalance($CompanyId, $code333, $from_date, 0);
                                                                        $from333 = $from333[0];

                                                                        $to333 = FinanceHelper::ChartOfAccountCashFlowCurrentBalance($CompanyId, $code333, $from_date, $to_date);
                                                                        $to333 = $to333[0];

                                                                        $diff333 = $from333 - $to333;
                                                                    }

                                                                    $start_financing_total = $start_financing_total + $from333;
                                                                    $financing_total = $financing_total + $to333;
                                                                    $financing_diff = $financing_diff + $diff333;
                                                                ?>
                                                                <td scope="row">Dividends</td>
                                                                <td>{{ number_format($from333, 2) }}</td>
                                                                <td>{{ number_format($to333, 2) }}</td>
                                                                <td>{{ number_format($diff333, 2) }}</td>
                                                                <td></td>
                                                            </tr>
                                                            <tr>
                                                                <?php
                                                                    $name444 = 'OTHER FINANCING';
                                                                    $code444 = CommonHelper::get_account_code_by_name($name444);
                                                                
                                                                    if($code444 == 'fail'){
                                                                        $from444 = 0;
                                                                        $to444 = 0;
                                                                        $diff444 = 0;
                                                                    }else{
                                                                        $from444 = FinanceHelper::ChartOfAccountCashFlowCurrentBalance($CompanyId, $code444, $from_date, 0);
                                                                        $from444 = $from444[0];

                                                                        $to444 = FinanceHelper::ChartOfAccountCashFlowCurrentBalance($CompanyId, $code444, $from_date, $to_date);
                                                                        $to444 = $to444[0];

                                                                        $diff444 = $from444 - $to444;
                                                                    }

                                                                    $start_financing_total = $start_financing_total + $from444;
                                                                    $financing_total = $financing_total + $to444;
                                                                    $financing_diff = $financing_diff + $diff444;
                                                                ?>
                                                                <td scope="row">Other Financing</td>
                                                                <td>{{ number_format($from444, 2) }}</td>
                                                                <td>{{ number_format($to444, 2) }}</td>
                                                                <td>{{ number_format($diff444, 2) }}</td>
                                                                <td></td>
                                                            </tr>
                                                            <tr>
                                                                <td style="font-size: 17px !important;font-weight:bold; color:#000;background: transparent;" scope="row">Total</td>
                                                                <td style="font-size: 17px !important;font-weight:bold;color:#000;background: transparent;">{{ number_format($start_financing_total, 2) }}</td>
                                                                <td style="font-size: 17px !important;font-weight:bold;color:#000;background: transparent;">{{ number_format($financing_total, 2) }}</td>
                                                                <td style="font-size: 17px !important;font-weight:bold;color:#000;background: transparent;">{{ number_format($financing_diff, 2) }}</td>
                                                                <td></td>
                                                            </tr>
                                                            <tr>
                                                                <?php $netIncome_with_financing = $net_income + $financing_diff; ?>
                                                                <td style="font-size: 17px !important;font-weight:bold; color:#000;background: transparent;" scope="row">Net Cash Provided by/Used in Financing Activities</td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td style="font-size: 17px !important;font-weight:bold;color:#000;background: transparent;">{{ number_format($netIncome_with_financing, 2) }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td scope="row"></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                            </tr>
                                                        </tbody>

                                                        <thead>
                                                            <tr>
                                                                <th>Net Increase/Decrease in Cash and Cash Equivalents</th>
                                                                <th></th>
                                                                <th></th>
                                                                <th></th>
                                                                <th></th>
                                                            </tr>
                                                        </thead>

                                                        <tbody>
                                                            <tr>
                                                                <td scope="row"></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Net Cash Provided by Operating Activities</td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td>{{ number_format($netIncome_with_operating, 2) }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Net Cash Used in Investing Activities</td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td>{{ number_format($netIncome_with_investing, 2) }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Net Cash Provided by/Used in Financing Activities</td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td>{{ number_format($netIncome_with_financing, 2) }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td scope="row"></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                            </tr>
                                                        </tbody>

                                                        <?php
                                                            $name9 = 'CASH / BANK';
                                                            $code9 = CommonHelper::get_account_code_by_name($name9);
                                                        
                                                            if($code9 == 'fail'){
                                                                $from9 = 0;
                                                                $to9 = 0;
                                                            }else{
                                                                $from9 = FinanceHelper::ChartOfAccountCashFlowCurrentBalance($CompanyId, $code9, $from_date, 0);
                                                                $from9 = $from9[0];

                                                                $to9 = FinanceHelper::ChartOfAccountCashFlowCurrentBalance($CompanyId, $code9, $from_date, $to_date);
                                                                $to9 = $to9[0];
                                                            }

                                                            $beginning_total = $from9 + $netIncome_with_operating + $netIncome_with_investing + $netIncome_with_financing;
                                                        ?>
                                                        <thead>
                                                            <tr>
                                                                <th>Add Beginning Cash and Cash Equivalents</th>
                                                                <th></th>
                                                                <th></th>
                                                                <th></th>
                                                                <th></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td scope="row"></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Cash and cash equivalents at the beginning of the period</td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td>{{ number_format($from9, 2) }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Total</td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td>{{ number_format($beginning_total, 2) }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td scope="row"></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                            </tr>
                                                        </tbody>

                                                        <thead>
                                                            <tr>
                                                                <th>Ending Cash and Cash Equivalents</th>
                                                                <th></th>
                                                                <th></th>
                                                                <th></th>
                                                                <th></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td scope="row"></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Cash and cash equivalents at the end of the period</td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td>{{ number_format($to9, 2) }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td scope="row"></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>