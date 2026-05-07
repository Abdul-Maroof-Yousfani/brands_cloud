<?php
    use App\Helpers\CommonHelper;
    use App\Helpers\DashboardHelper;
    use Illuminate\Support\Carbon;
    $m = '';
    if(isset($_GET['m']))
    {
        $m = $_GET['m'];
    }
    else
    {
        $m = '';
    }
    $UserId = Auth::user()->id;
?>
@extends('layouts.default')
@section('content')
<?php $count=0;
   if(Auth::user()->id == 104)
   {
   $companiesList = DB::table('company')->select(['name','id','dbName'])->where('status','=','1')->get();
   }
   else{
   $companiesList = DB::table('company')->select(['name','id','dbName'])->where('status','=','1')->get();
   }
?>
@if(Session::get('run_company')==''):
<div id="companyListModel" class="modal fade in" role="dialog" aria-hidden="false" style="display: block;">
   <div class="modal-dialog modalWidth dply">
      <!-- Modal content-->
      <div class="model-n modal-content">
         <div class="modal-body">
            <div class="mdel-bx">
               <img class="circle" src="../assets/img/animation/circledot.png">
               <div class="model-logo">
                  <img src="assets/img/logos/logo.png">
                  <h4 class="modal-title">Select Company</h4>
               </div>
               @foreach($companiesList  as $key => $cRow1)
               <div class="row">
                  <ul class="ban-list">
                     <li>
                        <div class="banq-box">
                           <a href="{{url('set_user_db_id?company='.$cRow1->id)}}">
                              <span class="companyLetr theme-bg theme-f-m">D</span>
                              <h3 class="item-model-company theme-f-m">{{ $cRow1->name }}</h3>
                           </a>
                        </div>
                     </li>
                  </ul>
                  @endforeach
               </div>
               <a href="{{url('/logout')}}" class="btn-b">Sign Out</a>
            </div>
         </div>
      </div>
   </div>
   <div class="modal-backdrop fade in"></div>
</div>
@endif
<div class="well_N">
    <div>
        <?php if(Session::get('run_company')):?>
        <span style="display: block;">
            <div class="wrapper wrapper-content">
                <div class="row">
                    <div class="col-lg-12 priorMainBox">
                        <a href="#" onclick="getDashboardSaleSummary(1,'{{date('Y-m-d')}}','{{date('Y-m-d')}}');">
                            <div class="mainDashBox">
                                <div class="title">
                                    <h6>Today's Sales</h6>
                                    <p>{{date('Y-m-d')}}</p>
                                </div>
                                <img src="assets/img/miniBar.svg" alt="">
                                <h4>
                                    {{number_format(DashboardHelper::getSaleSummaryAmount(date('Y-m-d'),date('Y-m-d')),0)}}
                                </h4>
                            </div>
                        </a>
                        @php
                            $monthStartDate = date('Y-m-01');
                            $monthEndDate = date('Y-m-t');
                            $thisMonthCollection = DashboardHelper::getCollectionSummaryAmount($monthStartDate, $monthEndDate);
                            $totalReceivables = DashboardHelper::getTotalReceivablesAmount();
                            $totalPayables = DashboardHelper::getTotalPayablesAmount();
                        @endphp
                        <a href="#" onclick="getDashboardSaleSummary(2,'{{$monthStartDate}}','{{$monthEndDate}}');">
                            <div class="mainDashBox">
                                <div class="title">
                                    <h6>This Month Sales</h6>
                                    <p>{{date('Y')}}</p>
                                </div>
                                <img src="assets/img/miniBar.svg" alt="">
                                <h4>
                                    {{number_format(DashboardHelper::getSaleSummaryAmount($monthStartDate,$monthEndDate),0)}}
                                </h4>
                            </div>
                        </a>
                        <a href="#">
                            <div class="mainDashBox">
                                <div class="title">
                                    <h6>This Month's Collection</h6>
                                </div>
                                <img src="assets/img/miniBar.svg" alt="">
                                <h4>{{ number_format($thisMonthCollection, 2) }}</h4>
                            </div>
                        </a>
                        <a href="#">
                            <div class="mainDashBox">
                                <div class="title">
                                    <h6>Total Receivables</h6>
                                </div>
                                <img src="assets/img/miniBar.svg" alt="">
                                <h4>{{ number_format($totalReceivables, 2) }}</h4>
                            </div>
                        </a>
                        <a href="#">
                            <div class="mainDashBox">
                                <div class="title">
                                    <h6>Total Payables</h6>
                                </div>
                                <img src="assets/img/miniBar.svg" alt="">
                                <h4>{{ number_format($totalPayables, 2) }}</h4>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-5">
                                <div class="card barChartHead">
                                    <div>
                                        <div>
                                            <h6>Business Flow Chart</h6>
                                        </div>
                                        <div class="text-right">
                                            <h6>{{ number_format($thisMonthCollection, 2) }}</h6>
                                            <p>Total Sales – Monthly</p>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <canvas class="bar-chart-ex chartjs" data-height="320"></canvas>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div id="printBankPaymentVoucherList">
                                    <div class="panel ">
                                        <div id="PrintPanel">
                                            <div id="ShowHide">
                                                <div class="table-responsive dashTable mhe">
                                                    <div class="dashTableHeading printListBtn">
                                                        <h6>Sales Orders</h6>
                                                        <a class="btn btn-primary" target="_blank" id="myBtn" href="{{url('/selling/listSaleOrder?pageType=view&&parentCode=89&&m=1#Rototec')}}">View All Sales Orders</a>
                                                    </div>
                                                    <table class="userlittab table table-bordered sf-table-list" id="TableExportToCsv">
                                                        <thead class="bgBlueofTd">
                                                            <tr>
                                                                <th class="text-center" colspan="2">Customer</th>
                                                                <th class="text-center">Order No</th>
                                                                <th class="text-center">Order Date</th>
                                                                <th class="text-center">Without Tax Amount</th>
                                                                <th class="text-center">Tax Amount</th>
                                                                <th class="text-center">Sub Total</th>
                                                                <th class="text-center">Status</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="data" class="dashTableBody">
                                                            @php
                                                                $latestSaleOrders = CommonHelper::displayLatestSaleOrdersDetail();
                                                                $overallSubTotal = 0;
                                                                $overallTaxAmount = 0;
                                                            @endphp
                                                            @if(!empty($latestSaleOrders))
                                                            @foreach($latestSaleOrders as $lsoKey => $lsoRow)
                                                                @php
                                                                    $overallSubTotal += $lsoRow->total_amount;
                                                                    $overallTaxAmount += $lsoRow->total_amount_after_sale_tax;
                                                                @endphp
                                                                <tr>
                                                                    <td class="text-center" colspan="2">{{strtoupper($lsoRow->name)}}</td>
                                                                    <td class="text-center">{{strtoupper($lsoRow->so_no)}}</td>
                                                                    <td class="text-center">{{CommonHelper::changeDateFormat($lsoRow->so_date)}}</td>
                                                                    <td class="text-center">{{number_format($lsoRow->total_amount,0)}}</td>
                                                                    <td class="text-center">{{number_format($lsoRow->total_amount_after_sale_tax - $lsoRow->total_amount,0)}}</td>
                                                                    <td class="text-center">{{number_format($lsoRow->total_amount_after_sale_tax,0)}}</td>
                                                                    <td class="text-center">
                                                                        @if($lsoRow->so_status == 0) Pending
                                                                        @elseif($lsoRow->so_status == 2) Draft
                                                                        @else Sale Order Created
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                            <tr>
                                                                <td colspan="4">Total</td>
                                                                <td class="text-right">{{number_format($overallSubTotal,0)}}</td>
                                                                <td class="text-right"></td>
                                                                <td class="text-right">{{number_format($overallTaxAmount,0)}}</td>
                                                                <td class="text-center">---</td>
                                                            </tr>
                                                            @endif
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
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card barChartHead">
                                    <div class="card-header d-flex flex-sm-row flex-column justify-content-md-between align-items-start justify-content-start">
                                        <div class="cashSection">
                                            <div>
                                                <h4 class="card-subtitle mb-25">Cash Flow</h4>
                                                <p class="card-title font-weight-bolder">Cash Coming in and going out of your business</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div id="bar-chart"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="payable pieChartHead">
                                    <div class="statistics card-header d-flex flex-sm-row flex-column justify-content-md-between align-items-start justify-content-start">
                                        <h6 class="card-title mb-sm-0 mb-1">Receivables and Payables</h6>
                                    </div>
                                    <ul>
                                        <li>
                                            <h6>Receivables</h6>
                                            <ul>
                                                <li>
                                                    <p>Total</p>
                                                    <p>{{ number_format($totalReceivables, 2) }}</p>
                                                </li>
                                            </ul>
                                        </li>
                                        <li>
                                            <h6>Payables</h6>
                                            <ul>
                                                <li>
                                                    <p>Total</p>
                                                    <p>{{ number_format($totalPayables, 2) }}</p>
                                                </li>
                                            </ul>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 mp-20">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="topExport topSelling">
                                    <div>
                                        <h6>Top Selling Products</h6>
                                    </div>
                                    <ul>
                                        @php
                                            $topProducts = DashboardHelper::getTopSellingProducts(4);
                                        @endphp
                                        @forelse($topProducts as $product)
                                        <li>
                                            <div>
                                                <h6>{{ $product->product_name }}</h6>
                                                <div>
                                                    <h6>{{ $product->sku_code }}</h6>
                                                    <p>Total Sales: <span>{{ number_format($product->total_sales, 0) }}</span></p>
                                                </div>
                                            </div>
                                        </li>
                                        @empty
                                        <li class="text-center">No data found</li>
                                        @endforelse
                                        <li class="printListBtn text-center">
                                            <a href="{{url('/selling/listSaleOrder?pageType=view&&parentCode=89&&m=1#Rototec')}}" class="btn btn-primary">View All</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="topExport debitCardSection">
                                    <h6>Primary Account</h6>
                                    <div>
                                        <img src="assets/img/debitcard.svg" alt="">
                                        <div class="balance">
                                            <div>
                                                <h6>Bank Balance</h6>
                                                <h4>{{ number_format(DashboardHelper::getBankBalance(), 2) }}</h4>
                                            </div>
                                            <img src="assets/img/mastercard.svg" alt="">
                                        </div>
                                        <div class="numbers">
                                            <div><p>Rototec</p><p>12/14</p></div>
                                            <pre>4197 **** **** 4116</pre>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="well" id="ShowHide"></div>
        </span>
        <?php endif;?>
    </div>
</div>
<script src="assets/js/charts/chart-chartjs.js"></script>
<script src="assets/js/charts/chart-chartjs.min.js"></script>
<script>
    $(window).on('load', function() {
        @php
            $salesData = DashboardHelper::getMonthlySalesData();
        @endphp
        if (typeof Chart !== 'undefined' && Chart.instances) {
            Object.values(Chart.instances).forEach(function(instance) {
                if ($(instance.chart.canvas).hasClass('bar-chart-ex')) {
                    instance.data.labels = {!! json_encode($salesData['labels']) !!};
                    instance.data.datasets[0].data = {!! json_encode($salesData['data']) !!};
                    instance.update();
                }
            });
        }
    });
</script>
<script !src="">
   $(document).ready(function() {});
   function getDashboardInfo(Type)
   {
      var m = '<?php echo $m?>';
      $('#ShowHide').html('<div class="loader"></div>');
      $.ajax({
         url: '/pdc/get_dashboard_info',
         type: 'Get',
         data: {Type: Type,m:m},
         success: function (response)
         {
            $('#ShowHide').html(response);
         }
      });
   }
</script>
@endsection