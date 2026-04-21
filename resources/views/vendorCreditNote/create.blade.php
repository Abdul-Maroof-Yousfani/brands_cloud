
@extends('layouts.default')

@section('content')
<div class="row well_N align-items-center">
    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
        <ul class="cus-ul">
            <li>
                <h1>Finance</h1>
            </li>
            <li>
                <h3><span class="glyphicon glyphicon-chevron-right"></span> &nbsp; Vendor Credit Note</h3>
            </li>
        </ul>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="well_N dp_sdw">
            <form method="POST" action="{{ route('vendorcreditnote.store') }}" id="submitForm">
            <div class="panel">
                <div class="panel-body">
                    <div class="headquid">
                        <h2 class="subHeadingLabelClass">Create Vendor Credit Note</h2>
                    </div>

                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="row qout-h">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Vendor</label>
                                    <select name="vendor_id" id="VendorId" class="form-control select2" required>
                                        <option value="">Select Vendor</option>
                                        @foreach($vendors as $vendor)
                                            <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>


                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Date</label>
                                    <input type="date" class="form-control" name="date" value="{{ date('Y-m-d') }}" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Amount</label>
                                    <input type="number" step="0.01" class="form-control" name="amount" required placeholder="0.00">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Details</label>
                                    <textarea class="form-control" name="details" placeholder="Enter details..."></textarea>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group" >
                                    <label>Debit Account (Selected Finance Account)</label>
                                    <select class="form-control select2" name="debit" required>
                                        <option value="">Select Debit Account</option>
                                        @foreach($accounts as $y)
                                            <option value="{{ $y->id }}">
                                                {{ $y->code .' ---- '. $y->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <p class="help-block"><small>Note: Vendor will be Credited automatically.</small></p>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Branch</label>
                                    <select class="form-control select2" name="branch">
                                        <option value="">Select Branch</option>
                                        @foreach($branches as $branch)
                                            <option value="{{ $branch->id }}">{{ $branch->branch_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="col-md-12" style="margin-top: 30px; padding-left: 0px;">
                    <button type="reset" class="btn btn-danger">Reset</button>
                    <button type="submit" class="btn btn-success">Create Credit Note</button>
                </div>
            </form>
            </div>
    </div>
</div>

<!-- Select2 CSS/JS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function() {
    $('.select2').select2({
        placeholder: "Select an option",
        allowClear: true,
        width: '100%'
    });
});
</script>

@endsection
