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
                        <a href="#">
                            <div class="mainDashBox">
                                <div class="title">
                                    <h6>Today Total Sales</h6>
                                    <p>value only approved sales invoices</p>
                                </div>
                                <img src="assets/img/miniBar.svg" alt="">
                                <h4>
                                    {{number_format(DashboardHelper::getApprovedInvoicesAmount(date('Y-m-d'),date('Y-m-d')),0)}}
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
                        <a href="#">
                            <div class="mainDashBox">
                                <div class="title">
                                    <h6>Monthly sales</h6>
                                    <p>only sales value</p>
                                </div>
                                <img src="assets/img/miniBar.svg" alt="">
                                <h4>
                                    {{number_format(DashboardHelper::getMonthlySalesValue($monthStartDate,$monthEndDate),0)}}
                                </h4>
                            </div>
                        </a>
                        <a href="#">
                            <div class="mainDashBox">
                                <div class="title">
                                    <h6>Total receipt Voucher</h6>
                                </div>
                                <img src="assets/img/miniBar.svg" alt="">
                                <h4>{{ number_format($thisMonthCollection, 2) }}</h4>
                            </div>
                        </a>
                        <a href="#">
                            <div class="mainDashBox">
                                <div class="title">
                                    <h6>customer aging as to today as totality</h6>
                                </div>
                                <img src="assets/img/miniBar.svg" alt="">
                                <h4>{{ number_format($totalReceivables, 2) }}</h4>
                            </div>
                        </a>
                        <a href="#">
                            <div class="mainDashBox">
                                <div class="title">
                                    <h6>vendor aging as of today as totality</h6>
                                </div>
                                <img src="assets/img/miniBar.svg" alt="">
                                <h4>{{ number_format($totalPayables, 2) }}</h4>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-5">
                                <div class="card barChartHead" style="height: 100%;">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h6 class="card-title mb-0">BA Target Achievement %</h6>
                                        <span class="badge bg-primary">Current Month</span>
                                    </div>
                                    <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                                        @php
                                            $baAchievements = DashboardHelper::getBATargetAchievementList();
                                        @endphp
                                        <div class="list-group list-group-flush">
                                            @forelse($baAchievements as $ba)
                                            <div class="list-group-item px-0 py-3">
                                                <div class="d-flex justify-content-between mb-1">
                                                    <span class="fw-bold">{{ $ba->name }}</span>
                                                    <span class="text-primary fw-bold">{{ $ba->percentage }}%</span>
                                                </div>
                                                <div class="progress" style="height: 8px;">
                                                    <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" 
                                                         style="width: {{ min($ba->percentage, 100) }}%;" 
                                                         aria-valuenow="{{ $ba->percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                                <div class="d-flex justify-content-between mt-1 small text-muted">
                                                    <span>Target: {{ number_format($ba->target, 0) }}</span>
                                                    <span>Actual: {{ number_format($ba->actual, 0) }}</span>
                                                </div>
                                            </div>
                                            @empty
                                            <p class="text-center text-muted py-4">No target data found for this month.</p>
                                            @endforelse
                                        </div>
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
                                                        <h6>Pending Sales Orders for Approval</h6>
                                                        <a class="btn btn-primary btn-sm" target="_blank" id="myBtn" href="{{url('/selling/listSaleOrder?pageType=view&&parentCode=89&&m=1#Rototec')}}">View All</a>
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
                                                                $pendingSaleOrders = DashboardHelper::getPendingSalesOrdersForApproval();
                                                                $overallSubTotal = 0;
                                                                $overallTaxAmount = 0;
                                                                foreach($pendingSaleOrders as $lsoRow) {
                                                                    $overallSubTotal += $lsoRow->total_amount;
                                                                    $overallTaxAmount += $lsoRow->total_amount_after_sale_tax;
                                                                }
                                                            @endphp
                                                            <tr style="background: #eef2f7; font-weight: bold;">
                                                                <td colspan="4" class="text-center">TOTAL</td>
                                                                <td class="text-center">{{number_format($overallSubTotal,0)}}</td>
                                                                <td class="text-center">{{number_format($overallTaxAmount - $overallSubTotal,0)}}</td>
                                                                <td class="text-center">{{number_format($overallTaxAmount,0)}}</td>
                                                                <td class="text-center">---</td>
                                                            </tr>
                                                            @if(!empty($pendingSaleOrders))
                                                            @foreach($pendingSaleOrders as $lsoKey => $lsoRow)
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

                    <div class="col-md-12 mp-20">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="topExport topSelling">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h6>Top Selling Products</h6>
                                        <div class="d-flex gap-2">
                                            <form action="" method="GET" class="d-flex gap-2">
                                                <select name="top_type" class="form-select form-select-sm" onchange="this.form.submit()">
                                                    <option value="value" {{ request('top_type') == 'value' ? 'selected' : '' }}>By Value</option>
                                                    <option value="qty" {{ request('top_type') == 'qty' ? 'selected' : '' }}>By Qty</option>
                                                </select>
                                                <select name="top_period" class="form-select form-select-sm" onchange="this.form.submit()">
                                                    <option value="month" {{ request('top_period') == 'month' ? 'selected' : '' }}>This Month</option>
                                                    <option value="today" {{ request('top_period') == 'today' ? 'selected' : '' }}>Today</option>
                                                </select>
                                            </form>
                                        </div>
                                    </div>
                                    <ul>
                                        @php
                                            $topType = request('top_type', 'value');
                                            $topPeriod = request('top_period', 'month');
                                            $topProducts = DashboardHelper::getTopSellingProducts(5, $topType, $topPeriod);
                                        @endphp
                                        @forelse($topProducts as $product)
                                        <li>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <h6>{{ $product->product_name }}</h6>
                                                    <span class="text-muted small">{{ $product->sku_code }}</span>
                                                </div>
                                                <div class="text-right">
                                                    @if($topType == 'qty')
                                                        <h6 class="mb-0">{{ number_format($product->total_qty, 0) }} Units</h6>
                                                    @else
                                                        <h6 class="mb-0">{{ number_format($product->total_sales, 0) }}</h6>
                                                    @endif
                                                </div>
                                            </div>
                                        </li>
                                        @empty
                                        <li class="text-center py-4">No data found</li>
                                        @endforelse
                                        <li class="printListBtn text-center">
                                            <a href="{{url('/selling/listSaleOrder?pageType=view&&parentCode=89&&m=1#Rototec')}}" class="btn btn-primary btn-sm mt-2">View All</a>
                                        </li>
                                    </ul>
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