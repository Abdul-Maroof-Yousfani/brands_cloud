<?php
$counter = 1;

?>
<div class="table-responsive wrapper">
    <h5 style="text-align: center" id="h3"></h5>
    <table class="userlittab table table-bordered table-hover tableFixHead" id="exportList">
        <thead>
        <th>S.No</th>
        <th>Asset Code</th>
        <th>Asset Nmae</th>
        <th>Installed Date</th>
        <th>Premise</th>
        <th>Useful Life</th>
        <th>Purchase Price</th>
        <th>Depreciation</th>
        <th id="hide-table-column" class="hidden-print">Action</th>
        </thead>
        <tbody>
        @if(!empty($assets))
            @foreach ($assets as $key => $value)
                <tr>
                    <td>{{ $counter++ }}</td>
                    <td>{{ $value->asset_code }}</td>
                    <td>{{ $value->asset_name }}</td>
                    <td>{{ $value->installed_date }}</td>
                    <td>@if(array_key_exists($value->premise_id, $premises_array)) {{ $premises_array[$value->premise_id]->premises_name }} @endif</td>
                    <td>@if(array_key_exists($value->useful_life_id, $life_array)) {{ $life_array[$value->useful_life_id]->useful_life_name }} @endif</td>
                    <td>{{ $value->purchase_price }}</td>
                    <td>{{ $value->depreciation }}</td>
                    <td id="hide-table-column" class="hidden-print">
                        <div class="dropdown">
                            <button class="drop-bt dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">
                                ...
                            </button>
                            <ul class="dropdown-menu">
                                <li onclick="showModal('editAssetsForm','{{ $value->id }}','Edit Assets Form','')">
                                    <a class="edit-modal">Edit</a>
                                </li>
                            </ul>
                        </div>
                    </td>
                </tr>
            @endforeach
        @else
            <tr><td colspan="8" style="text-align:center;color: red">No record found !</td></tr>
        @endif
        </tbody>
    </table>
</div>

<script>
    $(document).ready(function() {
        var table = $('#exportList').DataTable({
            "paging": false,
            "ordering": false,
            "info": false,
            "searching": true,
        });

        // Hide the search input
        $('#exportList_filter').addClass('hidden');

        // Add search functionality
        $('#searchInput').on('keyup', function () {
            table.search(this.value).draw();
        });
    });
</script>