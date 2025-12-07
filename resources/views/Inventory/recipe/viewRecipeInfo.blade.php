@php
    use App\Helpers\CommonHelper;
@endphp

<?php


// echo "<pre>";
// print_r($recipe->recipeDatas[0]->subItem);
// exit();
?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
        {{ CommonHelper::displayPrintButtonInView('printRecipeDetail', '', '1') }}
    </div>
</div>
<div class="row" id="printRecipeDetail">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div>
            <table class="table table-bordered table-striped table-condensed tableMargin">
                <tbody>
                    <tr>
                        <th>Finish Goods:</th>
                        <td colspan="3">{{ $recipe->subItem->sub_ic ?? '' }}</td>
                    </tr>
                    <tr>
                        <th>Description:</th>
                        <td colspan="3">{{ $recipe->description }}</td>
                    </tr>
                    <tr>
                        <th>Quantity:</th>
                        <td colspan="3">{{ $recipe->qty ?? '' }}</td>
                    </tr>
                    <th colspan="4" style="text-align: center">Raw matterial input</th>
                    <tr>
                        <th>SNO#</th>
                        <th>Category</th>
                        <th>item id</th>
                        <td>Quantity</td>
                    </tr>

                    @foreach ($recipe->recipeDatas as $key => $data)
                            <tr>
                                <td>{{ ++$key }}</td>
                                <td>{{ CommonHelper::get_sub_category_name($data->category_id) }}</td>
                                <td>
                                    @if(!empty($data->subItem)) {{ $data->subItem->sub_ic }} @endif
                                </td>
                                <td>{{ number_format($data->category_total_qty,2) }}</td>
                            </tr>

                           
                    @endforeach
                   
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top:40px;">
        <div class="container-fluid">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-center">
                    <h6 class="signature_bor">Created By: </h6>
                    <b>
                        <p><?php echo strtoupper($recipe->username); ?></p>
                    </b>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-center">
                    <h6 class="signature_bor">Checked By:</h6>
                    <b>
                        <p><?php ?></p>
                    </b>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-center">
                    <h6 class="signature_bor">Approved By:</h6>
                    <b>
                        <p></p>
                    </b>
                </div>

            </div>
        </div>
    </div>
</div>
