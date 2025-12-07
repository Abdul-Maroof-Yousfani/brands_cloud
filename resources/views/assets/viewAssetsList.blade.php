<?php
use App\Helpers\CommonHelper;
?>

@extends('layouts.default')
@section('content')
    <div class="well_N">
        <div class="dp_sdw">
            <div class="panel">
                <div class="panel-body">
                    <div class="row align-items-center head-bott">
                        <div class="col-md-4 head-h2">
                            <h2 class="subHeadingLabelClass">View Assets List</h2>
                        </div>
                        <div class="col-md-8 text-right">
                            {{ CommonHelper::displayPrintButtonInBlade('PrintEmpExitInterviewList','','1') }}
                            {{ CommonHelper::displayExportButton('EmpExitInterviewList','','1') }}&nbsp;&nbsp;
                            <input class="fom2 hidden-print" id="searchInput" type="text" placeholder="Search Asset">&nbsp;&nbsp;
                            <!-- <a href="#" onclick="showModal('assetsEditColumnsList','','Edit Columns','')"><i class="fa-solid fa-file-pen"></i> Edit Columns</a> &nbsp;&nbsp; -->
                            <a href="#" onclick="showModal('assetsFilter','','Filter Assets','')"> <i class="glyphicon glyphicon-solid glyphicon-filter"></i> Filter</a>&nbsp;&nbsp;
                        </div>
                    </div>
                    <div id="printList">
                        <div class="panel">
                            <div>
                                <form id="list_data" method="get" action="{{ route('assets-list') }}">
                                    <span id="response"></span>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $( document ).ready(function() {
            getAjaxData();
        });

    </script>
@endsection
