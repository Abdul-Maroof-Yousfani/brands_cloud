@extends('layouts.default')

@section('content')
<?php
use App\Helpers\CommonHelper;
$so_no =CommonHelper::generateUniquePosNo('sales_order','so_no','SO');
?>
    <div class="row well_N align-items-center">
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
            <ul class="cus-ul">
                <li>
                    <h1>Production</h1>
                </li>
                <li>
                    <h3><span class="glyphicon glyphicon-chevron-right"></span> &nbsp; Pipe Machine</h3>
                </li>
            </ul>
        </div>
        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 text-right">
          <ul class="cus-ul2">
                <li>
                    <a href="{{ url()->previous() }}" class="btn-a">Back</a>
                </li>
                {{-- <li>
                    <input type="text" class="fomn1" placeholder="Search Anything" >
                </li>
                <li>
                    <a href="#" class="cus-a"><span class="glyphicon glyphicon-edit"></span> Edit Columns</a>
                </li>
                <li>
                    <a href="#" class="cus-a"><span class="glyphicon glyphicon-filter"></span> Filter</a>
                </li> --}}
            </ul> 
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="well_N">
    <div class="dp_sdw">
        <div class="panel">
            <div class="panel-body">
                <div class="row borderBtmMnd pTB40">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="headquid">
                            <h2 class="subHeadingLabelClass">Creation of pipe</h2>
                        </div>
                        <div id="printBankPaymentVoucherList">
                            <div class="panel">
                                <div id="PrintPanel">
                                    <div id="ShowHide">
                                        <div class="table-responsive">
                                            <h5 style="text-align: center" id="h3"></h5>
                                            <table class="userlittab table table-bordered sf-table-list"
                                                id="TableExportToCsv">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center">S No</th>
                                                        <!-- <th class="text-center">Job Card No</th> -->
                                                        <th class="text-center">Production Plan No</th>
                                                        <th class="text-center">So No.</th>
                                                        <th class="text-center">Item Name</th>

                                                        <th class="text-center">Making Length</th>
                                                        <th class="text-center">Received Length</th>
                                                        <th class="text-center">Date</th>
                                                        <th class="text-center">Status</th>
                                                        <th class="text-center">Receive</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="data">
                                                 
                                                   
                    
                                                    
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row borderBtmMnd pTB40">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div id="printBankPaymentVoucherList">
                            <div class="panel">
                                <div id="PrintPanel">
                                    <div id="ShowHide">
                                        <div class="table-responsive">
                                            <h5 style="text-align: center" id="h3"></h5>
                                            <table class="userlittab table table-bordered sf-table-list"
                                                id="TableExportToCsv">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center">S No</th>
                                                        <!-- <th class="text-center">Job Card No</th> -->
                                                    
                                                        <th class="text-center" >Out Roll No</th>
                                                        <th class="text-center" >Machine</th>
                                                        <th class="text-center" >Operator</th>
                                                        <th class="text-center" >Shift</th>
                                                        <th class="text-center" >Machine Process date</th>
                                                        <th>Ready Length</th>
                                                        {{-- <th class="text-center">After Printing Weight</th> --}}
                                                        {{-- <th class="text-center">Transfer</th> --}}
                                                        <th class="text-center">machine process stage</th>
                                                        <th class="text-center">Tag</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="data_1">
                                                  
                                              
                                                   
                                            
                                                </tbody>
                                            </table>
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
</div>
        </div>
    </div> 
    <script>
        $(document).ready(function(){
            viewProductInProccess();
            viewProductProccessComplete();
        });



function viewProductInProccess()
        {

            var Filter=$('#search').val();

           
            $('#data').html('<tr><td colspan="12"><div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div></td><tr>');

            $.ajax({
                url: '<?php echo url('/')?>/selling/viewProductInProccess',
                type: 'Get',
                data: {Filter:Filter},
                success: function (response) {

                    $('#data').html(response);


                }
            });


        }
        
function viewProductProccessComplete()
        {

            var Filter=$('#search').val();

           
            $('#data_1').html('<tr><td colspan="12"><div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div></td><tr>');

            $.ajax({
                url: '<?php echo url('/')?>/selling/viewProductProccessComplete',
                type: 'Get',
                data: {Filter:Filter},
                success: function (response) {

                    $('#data_1').html(response);


                }
            });


        }
    </script>

   
@endsection