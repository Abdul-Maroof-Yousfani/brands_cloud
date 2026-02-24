<?php
use App\Helpers\CommonHelper;
use App\Helpers\ProductionHelper;


use App\Helpers\SalesHelper;?>
<style>
    .modalWidth{
        width: 100%;
    }
    .bold {
        font-size: large;
        font-weight: bold;
    }
</style>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
        <?php echo CommonHelper::displayPrintButtonInView('printMachineDetail','','1');?>
        @if($quotation->quotation_status==1)
            <button onclick="approve('{{ $id }}','{{ $quotation->pr_id  }}')" type="button" class="btn btn-success">Approve</button>
        @endif  
    </div>
</div>
<div style="line-height:5px;">&nbsp;</div>
<div class="row" id="printMachineDetail">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 well">
        <div class="">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <label style="border-bottom:2px solid #000 !important;">Printed On Date&nbsp;:&nbsp;</label><label style="border-bottom:2px solid #000 !important;"><?php echo CommonHelper::changeDateFormat(date('Y-m-d'));$x = date('Y-m-d');
                                echo ' '.'('.date('D', strtotime($x)).')';?></label>


                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <?php echo CommonHelper::get_company_logo(Session::get('run_company'));?>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <h3 style="text-align: center;">View Quotation Detail </h3>
                        </div>
                    </div>
                </div>
            </div>
            <div style="line-height:5px;">&nbsp;</div>
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <table  class="table table-bordered table-striped table-condensed tableMargin">
                        <tbody>
                        <tr>
                            <td style=" width:253px !important;">PR NO</td>
                            <td class="text-center"><?php echo strtoupper($quotation->pr_no);?></td>
                        </tr>

                        <tr>
                            <td>PR Date</td>
                            <td class="text-center"><?php  echo CommonHelper::changeDateFormat($quotation->start_date);?></td>
                        </tr>

                        <tr>
                            <td>Ref No</td>
                            <td class="text-center"><?php echo $quotation->ref_no ?></td>
                        </tr>

                        </tbody>
                    </table>
                </div>



                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <table  class="table table-bordered table-striped table-condensed tableMargin">
                        <tbody>
                        <tr>
                            <td style=" width:253px !important;">Quotation No</td>
                            <td class="text-center">{{ strtoupper($quotation->voucher_no) }}</td>
                        </tr>
                        <tr>
                            <td>Quotation Date</td>
                            <td class="text-center"> {{  CommonHelper::changeDateFormat($quotation->voucher_date)}} </td>
                        </tr>

                        <tr>
                            <td>Vendor</td>
                            <td class="text-center"><?php echo CommonHelper::get_supplier_name($quotation->vendor_id)?></td>
                        </tr>


                        </tbody>
                    </table>
                </div>


            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="table-responsive">
                    <table  class="table table-bordered table-striped table-condensed tableMargin">
                        <thead>
                        <tr>
                            <th class="text-center" style="width:50px;">S.No</th>
                            <th class="text-center">SKU</th>
                            <th class="text-center">Product</th>
                            <th class="text-center">Barcode</th>
                            <th class="text-center">UOM</th>
                            <th class="text-center">QTY</th>
                            <th class="text-center">Rate</th>
                            <th class="text-center">Tax %</th>
                            <th class="text-center">Tax Amount</th>
                            <th class="text-center">Amount</th>
                        </tr>
                        </thead>

                        <tbody>
                        <?php


                        $counter = 1;
                        $total_amount=0;
                        foreach ($quotation_data as $row):
                        ?>
                        <tr class="tex-center">
                            <td class="tex-center"><?php echo $counter++;?></td>
                            <td  class="text-center"> <?php echo CommonHelper::get_product_sku($row->sub_item_id);?></td>
                            <td  class="text-center"> <?php echo CommonHelper::get_product_name($row->sub_item_id);?></td>
                            <td  class="text-center"> <?php echo CommonHelper::product_barcode($row->sub_item_id);?></td>
                            <td class="text-center"><?php echo  CommonHelper::get_uom($row->sub_item_id) ?></td>
                            <td class="text-center "><?php echo number_format($row->qty,2)?></td>
                            <td class="text-center"><?php echo number_format($row->rate,2)?></td>
                            <td class="text-center"><?php echo number_format($row->tax_percent,2)?></td>
                            <td class="text-center">
    <?php 
        $amount = $row->qty * $row->rate;
        $taxAmount = ($amount * $row->tax_percent) / 100;
        echo number_format($taxAmount, 2);
    ?>
</td>
                            <td class="text-center"><?php echo number_format($row->amount,2)?></td>

                        </tr>



                        <?php
                        $total_amount+=$row->amount;
                        endforeach
                        ?>
                        <tr class="text-center">
                            <td class="bold" colspan="9">Total</td>
                            <td class="bold" colspan="1">{{ number_format($total_amount,2) }}</td>
                        </tr>
                       <!-- @if($quotation->gst_amount > 0)     
                        <tr class="text-center">
                            <td class="bold" colspan="9">Sales Tax {{ number_format($quotation->gst).' %' }}</td>
                            <td class="bold" colspan="1">{{ number_format($quotation->gst_amount,2) }}</td>
                        </tr>

                        @endif -->
                        </tbody>
                    </table>



                </div>
            </div>
            <div style=""><?php  ?></div>
            <div style="line-height:8px;">&nbsp;</div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                <style>
                    .signature_bor {
                        border-top:solid 1px #CCC;
                        padding-top:7px;
                    }
                </style>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top:40px;">
                    <div class="container-fluid">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-center">
                                <h6 class="signature_bor">Prepared By: </h6>
                                <b>   <p><?php echo strtoupper($quotation->username);  ?></p></b>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-center">
                                <h6 class="signature_bor">Checked By:</h6>
                                <b>   <p><?php  ?></p></b>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-center">
                                <h6 class="signature_bor">Approved By:</h6>
                                <b>  <p><?php echo strtoupper($quotation->approve_username);  ?></p></b>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function approve(id,pr_id)
    {



            $.ajax({

                url:'{{url('quotation/approve')}}',
                type:'GET',
                data:{id:id,id,pr_id:pr_id},
                success:function(response)
                {

                    if (response=='no')
                    {
                        alert('Quotation Againts This PR Alreday Approved');
                        return false;
                    }
                    $('#'+id).html('Approved');
                     get_data();
                    $('#showDetailModelOneParamerter').modal('hide');
                    
                },
                err:function(err)
                {
                    $('#data').html(err);
                }
            })

    }
</script>


