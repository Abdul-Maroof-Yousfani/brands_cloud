<?php

use App\Helpers\CommonHelper;
use App\Helpers\PurchaseHelper;

$accType = Auth::user()->acc_type;
if ($accType == 'client') {
    $m = $_GET['m'];
} else {
    $m = Auth::user()->company_id;
}

?>
@extends('layouts.default')

@section('content')
    @include('select2')
    <div class="well_N">
        <div class="dp_sdw">
            <div class="panel">
                <div class="panel-body">
                    <div class="row">

                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="well">
                                <div class="row hide">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="row">

                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                <div class="form-group ">
                                                    <label for="email">Category</label>
                                                    <select name="category_id" id="category_id"
                                                        onchange="get_category_wise_sub_category()"
                                                        class="form-control  select2">
                                                        <?php echo PurchaseHelper::categoryList($_GET['m'], '0'); ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                <div class="form-group ">
                                                    <label for="email">Sub Category</label>
                                                    <select name="sub_category_id" id="sub_category_id"
                                                        class="form-control  select2" width="183px;">

                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12" style="margin-top: 31px">
                                                <div class="form-group">
                                                    <button type="button" class="btn btn-success"
                                                        onclick="BookDayList();">Submit</button>
                                                </div>
                                            </div>

                                        </div>
                                        <span class="subHeadingLabelClass">View Product List</span>
                                    </div>
                                </div>



                                <div class="row">
                                    <div class="col-md-12">
                                        <form id="filterForm">
                                            <div class="row ">
                                                <input type="hidden" name="m" value="<?php echo $_GET['m']; ?>">
                                                <div class="col-md-1 mb-3">
                                                    <label for="customers" class="form-label">Show Entries</label>
                                                    <select name="per_page" class="form-control">
                                                        <option value="25">25</option>
                                                        <option value="50">50</option>
                                                        <option value="100">100</option>
                                                    </select>
                                                </div>

                                                <div class="col-md-11 mb-3 ">
                                                    <div class="row justify-content-end text-right">
                                                        <div class="col-md-2 mb-3">
                                                            <label>Classification </label>
                                                            <select name="product_classification_id[]" multiple class="form-control select2">
                                                                <option disabled>Select Product Classification</option>
                                                                @foreach($product_classification as $key => $i)
                                                                    <option value="{{ $i->id}}">{{ $i->name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-2 mb-3">
                                                            <label>User </label>
                                                            <select name="username[]" multiple class="form-control select2">
                                                                <option disabled>Select User</option>
                                                                @foreach($username as $item)
                                                                    <option value="{{ $item->username}}">{{ $item->username}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-2 mb-3">
                                                            <label>Trend </label>
                                                            <select name="product_trend_id[]" multiple class="form-control select2">
                                                                <option disabled>Select Trend</option>
                                                                @foreach($product_trends as $item)
                                                                    <option value="{{ $item->id}}">{{ $item->name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-2 mb-3">
                                                            <label for="brand_ids" class="form-label">Brands</label>
                                                            <select name="brand_ids[]" id="brand_ids" multiple class="form-control select2">
                                                                <option  disabled>Select Brands</option>
                                                                @foreach (CommonHelper::get_all_brand() as $brand)
                                                                    <option value="{{ $brand->id }}">
                                                                        {{ $brand->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-2 mb-3">
                                                            <label>Date</label>
                                                            <input type="date" name="creation_date" class="form-control" />
                                                        </div>
                                                        <div class="col-md-2 mb-3">
                                                            <label>Status </label>
                                                            <select name="product_status" class="form-control select2">
                                                                <option value="">Select Status</option>
                                                                <option value="1">Active</option>
                                                                <option value="0">Inactive</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-2 mb-3">
                                                            <label for="search" class="form-label">Search</label>
                                                            <input type="text" class="form-control" id="search"
                                                                placeholder="Type here Product Name, Brand Name, SKU" name="search"
                                                                value="">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>


<!-- 
 <button type="button" class="btn btn-warning" onclick="ExportToExcel('xlsx')">Export
                                                    <b>(xlsx)</b></button> -->

                              <button type="button" class="btn btn-warning" onclick="submitExportForm()">Export <b>(xlsx)</b></button>

<form id="exportForm" method="GET" action="{{ route('export.subitems') }}" target="_blank" style="display:none;"></form>






                                <!-- <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">

                                               
                                                <?php echo Form::open(['url' => 'pad/subitems/import?m=' . $m . '', 'id' => 'ImportSubItems', 'method' => 'POST', 'files' => true]); ?>
                                               
                                                <div class="form-group">


                                                    <label for="file">Choose Excel file</label>
                                                    <input type="file" name="file" class="form-control" id="file"
                                                        accept=".xlsx, .xls, .csv" required>
                                                </div>
                                                <button type="submit" class="btn btn-primary">Import</button>
                                                
                                                <?php
                                                echo Form::close();
                                                ?>
                                            </div> -->
                                <div class="lineHeight">&nbsp;</div>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div id="filteredData">
                                            <div class="text-center spinnerparent">
                                                <div class="loader" role="status"></div>
                                            </div>
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
                    var elt = document.getElementById('table');

                    // Temporarily remove the first column
                    var rows = elt.rows;
                    var removedCells = [];
                    var removedLastCells = [];
                    for (var i = 0; i < rows.length; i++) {
                        removedCells.push(rows[i].removeChild(rows[i].cells[0]));
                        removedLastCells.push(rows[i].removeChild(rows[i].cells[rows[i].cells.length - 1]));
                    }


                    var wb = XLSX.utils.table_to_book(elt, {
                        sheet: "sheet1"
                    });

                    for (var i = 0; i < rows.length; i++) {
                        if (removedCells[i]) {
                            rows[i].insertBefore(removedCells[i], rows[i].cells[0]);
                        }
                        if (removedLastCells[i]) {
                            rows[i].appendChild(removedLastCells[i]);
                        }
                    }



                    return dl ?
                        XLSX.write(wb, {
                            bookType: type,
                            bookSST: true,
                            type: 'base64'
                        }) :
                        XLSX.writeFile(wb, fn || ('Sub Item List.' + (type || 'xlsx')));
                }


                //viewSubItemList();
            </script>
            <script>
                var loading = false;
                $(document).ready(function() {
                    $('.select2').select2();
                });

                function get_category_wise_sub_category() {
                    var category_id = $('#category_id').val();
                    if (category_id > 0) {
                        $.ajax({
                            url: '<?php echo url('/'); ?>/pmfal/get_category_wise_sub_category',
                            type: "GET",
                            data: {
                                category_id: category_id
                            },
                            success: function(data) {
                                $('#sub_category_id').html(data);
                            }
                        });
                    } else {
                        $('#sub_category_id').html('');
                    }

                }

                function subItemListLoadDepandentCategoryId(id, value) {
                    //alert(id+' --- '+value);
                    var arr = id.split('_');
                    var m = '<?php echo $_GET['m']; ?>';
                    $.ajax({
                        url: '<?php echo url('/'); ?>/pmfal/subItemListLoadDepandentCategoryId',
                        type: "GET",
                        data: {
                            id: id,
                            m: m,
                            value: value
                        },
                        success: function(data) {
                            $('#sub_item_id_' + arr[2] + '_' + arr[3] + '').html(data);
                        }
                    });
                }

                function BookDayList() {
                    //if (loading == false) {
                    var category = $('#category_id').val();
                    var sub_category = $('#sub_category_id').val();
                    var m = '<?php echo $_GET['m']; ?>';
                    if (category != "" || sub_category != "") {
                        $('#viewSubItemList').html(
                            '<tr><td colspan="7"><div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center"><div class="loader"></div></div></div></div></td><tr>'
                        );
                        $.ajax({
                            url: '<?php echo url('/'); ?>/pdc/viewSubItemListAjax',
                            method: 'GET',
                            data: {
                                category: category,
                                sub_category: sub_category,
                                m: m
                            },
                            error: function() {
                                alert('error');
                            },
                            success: function(response) {
                                $('#viewSubItemList').html(response);
                                //              loading = false;
                            }
                        });
                        //viewSubItemList();
                        //alert("Please Select Category");
                    } else {
                        //loading = true;

                    }
                    //} else {
                    //  alert("Wait Loading");
                    //}
                    //}
                }
            </script>
            <script>
                $(document).ready(function() {
                    filterationCommonGlobal('{{ route('viewSubItemListAjax') }}');
                });
            </script>

            <script type="text/javascript">
                function viewSubItemList() {
                    alert();
                    if (loading == false) {
                        loading = true;
                        $('#viewSubItemList').html(
                            '<tr><td colspan="7"><div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center"><div class="loader"></div></div></div></div></td><tr>'
                        );
                        var m = '<?php echo $_GET['m']; ?>';
                        $.ajax({
                            url: '<?php echo url('/'); ?>/pdc/viewSubItemList',
                            type: "GET",
                            data: {
                                m: m
                            },
                            success: function(data) {
                                setTimeout(function() {
                                    $('#viewSubItemList').html(data);
                                }, 1000);
                                loading = false;
                            }
                        });
                    } else {
                        alert("Wait Loading");
                    }
                }

                function deletee(id) {
                    alert();
                    if (confirm('Are You Sure ? You want to delete this recored...!')) {
                        var m = '<?php echo $m; ?>';

                        $.ajax({
                            url: '/purchase/deleteCompanyMasterTableRecord',
                            type: 'Get',
                            data: {
                                id: id: m: m
                            },

                            success: function(response) {
                                $('#RemoveTr' + response).remove();
                            }
                        });
                    } else {}
                }
            </script>

            <script>
   function submitExportForm() {
    const filterForm = document.querySelector('#filterForm');
    const exportForm = document.querySelector('#exportForm');

    if (!exportForm || !filterForm) {
        alert('Form not found!');
        return;
    }

    exportForm.innerHTML = ''; // Clear previous inputs

    const formData = new FormData(filterForm);

    for (const [name, value] of formData.entries()) {
        // If value is empty string, skip it
        if (value === '') continue;

        // If it's a multiple select (e.g. name="brand_ids[]"), multiple values come separately
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = name;
        input.value = value;
        exportForm.appendChild(input);
    }

    exportForm.submit();
}
</script>

        @endsection
