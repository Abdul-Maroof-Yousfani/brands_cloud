<?php use App\Helpers\CommonHelper; ?>
<?php use App\Helpers\PurchaseHelper;
use App\Helpers\ReuseableCode;
$export=ReuseableCode::check_rights(243);

 $financial_year=ReuseableCode::get_account_year_from_to($_GET['m']);

        if (isset($_GET['type'])):
            $type=$_GET['type'];
        else:
        $type=0;
        endif;




?>
@extends('layouts.default')
@section('content')
@include('select2')

<?php




?>

<div class="">
    <div class="well_N">
    <div class="">    
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 dp_sdw">
        <div class="row">


            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                <label for="email">  @if ($type==1) From @else To Date @endif</label>
                <input
                    id="from_date"
                    name="from_date"
                    required
                    min="{{ $financial_year[0] }}"
                    max="{{ $financial_year[1] }}"
                    class="date1 form-control"
                    type="date"
                    value="{{ now()->toDateString() }}"
                />
            </div>
            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                <label for="email">  @if ($type==1) To @else To Date @endif</label>
                <input
                    id="to_date"
                    name="to_date"
                    required
                    min="{{ $financial_year[0] }}"
                    max="{{ $financial_year[1] }}"
                    class="date1 form-control"
                    type="date"
                    value="{{ now()->toDateString() }}"
                />
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                <label for="">Items</label>
                <select name="ItemId" id="ItemId" class="form-control">
                    <option value="">ALL</option>
                    <?php foreach(App\Helpers\CommonHelper::get_all_subitems() as $subitem):?>
                        <option value="<?php echo $subitem->id?>"><?php echo $subitem->product_name?></option>
                    <?php endforeach;?>
                </select>
            </div>

             <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                <label for="">Brands</label>
                <select name="brand_id" id="brand_id" class="form-control select2">
                    <option value="">ALL</option>
                    <?php foreach(App\Helpers\CommonHelper::get_all_brand() as $brand):?>
                        <option value="<?php echo $brand->id?>"><?php echo $brand->name?></option>
                    <?php endforeach;?>
                </select>
            </div>

            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                <label> Purchase <input id="purchase" type="checkbox" value="1"  ></label>
                <label> Sales <input id="sales" type="checkbox" value="2"  ></label>
                </div>



            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                <button type="button" class="btn btn-sm btn-primary" onclick="stockReportItemWise()" style="margin: 30px 0px 0px 0px;">Submit</button>
            </div>
        <input type="hidden" id="accyearfrom" value="{{$financial_year[0]}}"/>
        </div>

        <div>&nbsp;</div>

        <div id="printBankReceiptVoucherList">
            <div class="row">

                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="panel">
                        <div class="panel-body">
                            <?php //echo CommonHelper::headerPrintSectionInPrintView($m);?>
                            <?php echo CommonHelper::displayPrintButtonInBlade('filterBookDayList','HrefHide','1');?>
                                <?php if($export == true):?>
                                <a id="dlink" style="display:none;"></a>
                                <button type="button" class="btn btn-warning" onclick="ExportToExcel('xlsx')">Export <b>(xlsx)</b></button>
                            <?php endif;?>
                            <div id="filterBookDayList"></div>

                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>
</div>



<script src="{{ URL::asset('assets/custom/js/exportToExcelXlsx.js') }}"></script>
<script !src="">
    function ExportToExcel(type, fn, dl) {
        var elt = document.getElementById('EmpExitInterviewList');
        var wb = XLSX.utils.table_to_book(elt, { sheet: "sheet1" });
        return dl ?
                XLSX.write(wb, { bookType: type, bookSST: true, type: 'base64' }) :
                XLSX.writeFile(wb, fn || ('Inventory Movement <?php echo date('d-m-Y')?>.' + (type || 'xlsx')));
    }
</script>
    <script>

        $(document).ready(function(){
            $('#ItemId').select2();
            $(".select2").select2();
        });
        function stockReportItemWise(){
            var ReportType =1;
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();
            var brand_id = $("#brand_id").val();
            var ItemId = $('#ItemId').val();
            var accyearfrom = $('#accyearfrom').val();
            var purchase =0;
            if ($('#purchase').is(":checked"))
            {
                purchase=1;
            }

            var sales =0;
            if ($('#sales').is(":checked"))
            {
                sales=1;
            }


            $('#filterBookDayList').html('<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div>');
            $.ajax({
                url: '<?php echo url('/')?>/store/stock_movemnet',
                method:'GET',
                data:{brand_id,from_date:from_date,to_date:to_date,accyearfrom:accyearfrom,ItemId:ItemId,ReportType:ReportType,purchase:purchase,sales:sales},
                error: function()
                {
                    alert('error');
                },
                success: function(response){
                    $('#filterBookDayList').html(response);
                }
            });
        }
    </script>
@endsection