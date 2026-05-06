<?php
use App\Helpers\CommonHelper;
use App\Helpers\ReuseableCode;
$export=ReuseableCode::check_rights(251);

$accType = Auth::user()->acc_type;
if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}
$current_date = date('Y-m-d');
$currentMonthStartDate = date('Y-m-01');
$currentMonthEndDate   = date('Y-m-t');
$AccYearDate = DB::table('company')->select('accyearfrom','accyearto')->where('id',$_GET['m'])->first();
$AccYearFrom = $AccYearDate->accyearfrom;
$AccYearTo = $AccYearDate->accyearto;

?>

@extends('layouts.default')

@section('content')
<style>
    .filter-card {
        background: #ffffff;
        border-radius: 12px;
        padding: 25px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        margin-bottom: 30px;
        border: 1px solid #eef2f7;
    }
    .subHeadingLabelClass {
        font-weight: 700 !important;
        color: #1a202c !important;
        font-size: 24px !important;
        margin: 0 !important;
    }
    .head_flex {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
        background: #fff;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }
    .filter-label {
        font-weight: 600;
        color: #4a5568;
        font-size: 13px;
        margin-bottom: 8px;
        display: block;
    }
    .form-control {
        border-radius: 8px !important;
        border: 1px solid #e2e8f0 !important;
        height: 42px !important;
        font-size: 14px !important;
        transition: all 0.3s !important;
    }
    .btn-generate {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        color: white;
        font-weight: 600;
        padding: 10px 25px;
        border-radius: 8px;
        transition: all 0.3s;
        height: 42px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }
    .btn-generate:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        color: white;
    }
    .btn-export {
        border-radius: 8px;
        font-weight: 600;
        height: 42px;
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 0 20px;
    }
    .badge-date {
        background: #edf2f7;
        color: #4a5568;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    .loader {
        border: 4px solid #f3f3f3;
        border-top: 4px solid #667eea;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        animation: spin 1s linear infinite;
        margin: 50px auto;
    }
    @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
</style>

<div class="container-fluid">
    <div class="well_N">
        <div class="head_flex">
            <div>
                <h2 class="subHeadingLabelClass">Profit & Loss Statement</h2>
                <div id="selected_filters_display" style="margin-top: 10px;">
                    <span class="badge-date">
                        <i class="fa-regular fa-calendar"></i>
                        <span id="display_from">{{ date('d M, Y', strtotime($currentMonthStartDate)) }}</span> 
                        - 
                        <span id="display_to">{{ date('d M, Y', strtotime($currentMonthEndDate)) }}</span>
                    </span>
                    <span id="extra_badges_container" style="margin-left: 5px;"></span>
                </div>
            </div>
            <div class="d-flex" style="gap: 10px;">
                <?php echo CommonHelper::displayPrintButtonInBlade('trial_bal','','1');?>
                <?php if($export == true):?>
                <button type="button" class="btn btn-success btn-export" onclick="ExportToExcel('xlsx')">
                    <i class="fa-regular fa-file-excel"></i> Excel
                </button>
                <?php endif;?>
            </div>
        </div>

        <div class="filter-card">
            <div class="row align-items-end">
                <div class="col-lg-3 col-md-3">
                    <label class="filter-label">From Date</label>
                    <input name="from_date" id="from_date" max="<?php echo $AccYearTo ?>" min="<?php echo $AccYearFrom?>" required class="form-control" type="date" value="<?php echo $currentMonthStartDate?>" />
                </div>
                <div class="col-lg-3 col-md-3">
                    <label class="filter-label">To Date</label>
                    <input name="to_date" id="to_date" class="form-control" type="date" max="<?php echo $AccYearTo?>" min="<?php echo $AccYearFrom?>" required value="<?php echo $currentMonthEndDate?>" />
                </div>
                <div class="col-lg-2 col-md-2">
                    <label class="filter-label">Comparative</label>
                    <select name="comparetive" id="comparetive" class="form-control">
                        <option value="">Standard</option>
                        <option value="1">Comparative</option>
                    </select>
                </div>
                <div class="col-lg-2 col-md-2">
                    <button onclick="Generate()" type="button" class="btn btn-generate w-100">
                        <i class="fa-solid fa-arrows-rotate"></i> Update Report
                    </button>
                </div>
            </div>
        </div>

        <div id="trial_bal">
            <div class="text-center py-5 text-muted" style="padding: 100px 0;">
                <i class="fa-solid fa-chart-line fa-3x mb-3" style="opacity: 0.1; font-size: 60px;"></i>
                <p style="margin-top: 20px; font-size: 16px;">Click "Update Report" to view financial data</p>
            </div>
        </div>
    </div>
</div>
    <script src="{{ URL::asset('assets/custom/js/exportToExcelXlsx.js') }}"></script>
    <script !src="">
        function ExportToExcel(type, fn, dl) {
            var decide = $('#AccountSpaces').val();
            if(decide == 1)
            {
                $('.SpacesCls').show();
                //$('.SpacesCls').css('display','block');
            }
            else{
                $('.SpacesCls').html('');

            }
            var elt = document.getElementById('exportIncomeStatement1');
            var wb = XLSX.utils.table_to_book(elt, { sheet: "sheet1" });
            return dl ?
                    XLSX.write(wb, { bookType: type, bookSST: true, type: 'base64' }) :
                    XLSX.writeFile(wb, fn || ('Profit & Loss <?php echo date('d-m-Y')?>.' + (type || 'xlsx')));
        }
    </script>
    <script>
        function Generate()
        {
            var from_date = $("#from_date").val();
            var to_date = $("#to_date").val();
            var comparetive = $("#comparetive").val();
            var m = '<?= $_GET['m']; ?>';

            // Update badges
            $('#display_from').text(from_date);
            $('#display_to').text(to_date);

            var extra_badges = '';
            if(comparetive == '1') {
                extra_badges += '<span class="badge-date" style="background: #ebf8ff; color: #2b6cb0;"><i class="fa-solid fa-layer-group"></i> Comparative</span>';
            } else {
                extra_badges += '<span class="badge-date" style="background: #f0fff4; color: #2f855a;"><i class="fa-solid fa-check"></i> Standard</span>';
            }
            
            $('#extra_badges_container').html(extra_badges);

            $('#trial_bal').html('<div class="loader"></div>');

            $.ajax({
                url: '<?php echo url('/');?>/fdc/IncomeStatement',
                type: 'GET',
                data: {from_date: from_date, to_date: to_date, m:m ,comparetive:comparetive},
                success: function (response) {
                    $('#trial_bal').html(response);
                    $('.profit_Loss_Statement').addClass('table table-hover');
                },
                error: function() {
                    $('#trial_bal').html('<div class="alert alert-danger">Error loading report. Please try again.</div>');
                }
            });
        }

        $(document).ready(function() {
            Generate();
        });


        function newTabOpen(FromDate,ToDate,AccCode)
        {

            var Url = '<?php echo url('finance/viewTrialBalanceReportAnotherPage?')?>';
            window.open(Url+'from='+FromDate+'&&to='+ToDate+'&&acc_code='+AccCode, '_blank');
        }

        function AddRemoveSpace()
        {
            var decide = $('#AccountSpaces').val();
            if(decide == 1)
            {
                $('.SpacesCls').show();
                //$('.SpacesCls').css('display','block');
            }
            else{
                $('#AccountSpaces').attr('disabled','disabled');
                $('.SpacesCls').hide();
            }

//            var decide = $('#AccountSpaces').val();
//            if(decide == 1)
//            {
//                $('.SpacesCls').css('display','inline');
//                $('.SpacesCls').css('display','block');
//            }
//            else{
//                $('.SpacesCls').css('display','none');
//            }
        }

        function ResetFunc()
        {

            $('#trial_bal').html('');
            Generate();
            $('#AccountSpaces').attr('disabled',false);
            $('#AccountSpaces').val('1');

        }


    </script>
    <div class="col-sm-12">&nbsp;</div>

@endsection
