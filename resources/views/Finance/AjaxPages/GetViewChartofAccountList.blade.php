<?php
$accType = Auth::user()->acc_type;
// if($accType == 'client'){ $m = $_GET['m']; }
// else{ $m = Auth::user()->company_id; }
if($accType == 'client') { $get_m= $m; }
else{ $get_m= Auth::user()->company_id; }

use App\Helpers\CommonHelper;
use App\Helpers\FinanceHelper;

use App\Helpers\ReuseableCode;


$edit=ReuseableCode::check_rights(146);
$delete=ReuseableCode::check_rights(147);
$export=ReuseableCode::check_rights(223);


$company=ReuseableCode::get_account_year_from_to(Session::get('run_company'));

$from_date = $company[0];
$to_date = $company[1];
?>
<table id="myTable" class="userlittab table table-bordered sf-table-list">
    <thead>
    <th class="text-center col-sm-1">S.No</th>
    <th class="text-center col-sm-1">Code</th>
    <th class="text-center">Account Name</th>
    <th class="text-center">Nature Of Account</th>
    <th class="text-center">Current Balance</th>
    <th class="text-center col-sm-1 hidden-print">Edit</th>
    <th class="text-center col-sm-1 hidden-print">Delete</th>
    </thead>
    <tbody >
    <?php $counter = 1;?>
    @foreach($accounts as $key => $y)



        <?php


        $array = explode('-',$y->code);
        $level = count($array);
        $nature = $array[0];
        ?>

        <tr title="{{$y->id}}" @if($y->type==1)style="background-color:lightblue" @endif
        @if($y->type==4)style="background-color:lightgray"  @endif
        id="{{$y->id}}">
            <td class="text-center"><?php echo $counter++;?></td>
            <td>{{ '`'.$y->code}}</td>
            <td style="cursor: pointer" onclick="newTabOpen('<?php echo $from_date?>','<?php echo $to_date?>','<?php echo $y->code?>')">
                @if($level == 1)
                    <b style="font-size: 15px;font-weight: 600">{{ strtoupper($y->name)}}</b>
                @elseif($level == 2)
                    {{ '&emsp;&emsp;'. $y->name}}
                @elseif($level == 3)
                    {{ '&emsp;&emsp;&emsp;&emsp;'. $y->name}}
                @elseif($level == 4)
                    {{ '&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;'. $y->name}}
                @elseif($level == 5)
                    {{ '&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;'. $y->name}}
                @elseif($level == 6)
                    {{ '&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;'. $y->name}}
                @elseif($level == 7)
                    {{ '&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;'. $y->name}}
                @endif


            </td>
            <td>
                @if($nature == 01)
                    Assets
                @elseif($nature == 02)
                Liabilties
                @elseif($nature == 03)
                Equity
                @elseif($nature == 04)
                Expenses
                @elseif($nature == 05)
                Revenue
                @elseif($nature == 06)
                    Cost Of Sales
                @elseif($nature == 7)
                    COGS
                @elseif($nature == 8)
                    CAPITALS
                @endif
            </td>
            <td class="text-right"><?php 
            // echo number_format(FinanceHelper::ChartOfAccountCurrentBalance($m,$level,$y->code),2);get_m
            echo number_format(FinanceHelper::ChartOfAccountCurrentBalance($get_m,$level,$y->code),2);
            ?></td>

            <td class="text-center hidden-print">
                <?php if($y->type!=0):?>
                    <span class="badge badge-success" style="background-color: #428bca !important">Link To Master</span>
                <?php endif?>
                @if ($y->id!=1 && $y->id!=2 && $y->id!=1 && $y->id!=3 && $y->id!=4 && $y->id!=5 && $y->type!=2)
                <?php if($edit == true):?>
                    <button    onclick="showDetailModelOneParamerter('fdc/editChartOfAccountForm/<?php echo $y->id ?>')" class="btn btn-primary btn-xs">Edit</button>
                <?php endif;?>
                @endif
            </td>
            <td class="hidden-print text-center">
                @if ($y->type==0 && $y->id!=1  && $y->id!=2 && $y->id!=1 && $y->id!=3 && $y->id!=4 && $y->id!=5)
                    <?php if($delete == true):?>
                    <button onclick="delete_record('{{$y->id}}')" type="button" class="btn btn-danger btn-xs">Delete</button>
                    <?php endif;?>
                @endif


            </td>
        </tr>
    @endforeach
    </tbody>
</table>