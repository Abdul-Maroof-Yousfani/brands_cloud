<?php
$accType = Auth::user()->acc_type;
$m = Input::get('m');
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;

$current_date = date('Y-m-d');
$currentMonthStartDate = date('Y-m-01');
$currentMonthEndDate = date('Y-m-t');
?>
<style>
    td {
        padding: 2px !important;
    }

    th {
        padding: 2px !important;
    }

    .panel {
        margin-top: 8px;
        padding: 0px 30px 0px 30px;
        height: 556px;
        overflow-y: scroll;
    }

    .pointer:hover {
        cursor: pointer;
    }
</style>
@extends('layouts.default')
@section('content')
    @include('select2')
    <div class="panel-body">
        <div class="row">
            <div class="lineHeight">&nbsp;</div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="well_N">
                    <div class="dp_sdw">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <span class="subHeadingLabelClass">View Employee List</span>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">

                                <input type="hidden" id="company_id" value="<?= $m ?>">
                                @if (in_array('print', $operation_rights))
                                    <?php echo CommonHelper::displayPrintButtonInBlade('PrintEmployeeList', '', '1'); ?>
                                @endif
                                @if (in_array('export', $operation_rights))
                                    <?php echo CommonHelper::displayExportButton('EmployeeList', '', '1'); ?>
                                @endif
                            </div>
                        </div>
                        <div class="lineHeight">&nbsp;</div>

                        <div class="row text-right">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top: 28px">
                                <button class="btn btn-info" onclick="viewEmployeeFilteredList()">Filter List</button>
                            </div>
                        </div>
                        <div class="lineHeight">&nbsp;</div>
                        <span id="employee-list">
                            <div class="panel" id="search_area">
                                <div class="panel-body" id="PrintEmployeeList">
                                    <?php echo CommonHelper::headerPrintSectionInPrintView(Input::get('m')); ?>
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12col-xs-12">
                                            <div class="table-responsive">
                                                <table class="table table-bordered sf-table-list table-hover"
                                                    id="EmployeeList">
                                                    <thead>
                                                        <th class="text-center col-sm-1">S.No</th>
                                                        <th class="text-center">Emp Code</th>
                                                        <th class="text-center">Employee Name</th>
                                                        <th class="text-center">Email</th>
                                                        <th class="text-center">Status</th>
                                                        <th class="text-center hidden-print">Action</th>
                                                    </thead>
                                                    <tbody id="appendGetMoreEmp">
                                                        <?php $counter = 1; ?>
                                                        @foreach ($employees as $key => $y)
                                                            <tr class="post-id" id="<?= $y->emp_code ?>">
                                                                <td class="text-center counterId" id="<?php echo $counter; ?>">
                                                                    <?php echo $counter++; ?>
                                                                </td>
                                                                <td class="text-center">{{ $y->emp_code }}</td>
                                                                <td>{{ $y->name }}</td>
                                                                <td>{{ $y->email }}</td>
                                                                <td
                                                                    @if ($y->status == 4) onclick="showDetailModelTwoParamerter('hmfal/makeFormEmployeeInActive','<?php echo $y->id; ?>','','<?php echo $m; ?>')" class="text-center pointer" @else class="text-center" @endif>
                                                                    {{ HrHelper::getStatusLabel($y->status) }}</td>

                                                                <td class="text-center hidden-print">
                                                                    <div class="dropdown">
                                                                        <button
                                                                            class="btn btn-primary dropdown-toggle btn-xs"
                                                                            type="button" id="menu1"
                                                                            data-toggle="dropdown">Actions
                                                                            <span class="caret"></span></button>
                                                                        <ul class="dropdown-menu" role="menu"
                                                                            aria-labelledby="menu1">
                                                                            <li role="presentation">
                                                                                <a class="delete-modal btn"
                                                                                    href="<?= url("/hr/editEmployeeDetailForm/{$y->id}/{$m}?pageType=viewlist&&parentCode=27&&m={$m}")
                                                                                    ?>">
                                                                                    Edit
                                                                                </a>
                                                                            </li>
                                                                            <li role="presentation">
                                                                                <a class="delete-modal btn"
                                                                                    href="{{ url("/had/deleteEmployee/{$y->id}") }}">
                                                                                    Delete
                                                                                </a>
                                                                            </li>



                                                                        </ul>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </span>
                        <div class="text-center ajax-loader"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
	<script>
		var table = $('#EmployeeList').DataTable({
            "paging": true,
            "ordering": true,
            "info": true,
            "searching": true,
        });
	</script>
@endsection
