@extends('layouts.default')

@section('content')

{{-- ===== PAGE HEADER ===== --}}
<div class="row well_N align-items-center">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <ul class="cus-ul">
            <li><h1>Purchase Master</h1></li>
            <li><h3><span class="glyphicon glyphicon-chevron-right"></span> &nbsp; Add New Currency</h3></li>
        </ul>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right" style="padding-top:15px;">
        <a href="{{ route('currency.index') }}" class="btn btn-default">
            <span class="glyphicon glyphicon-arrow-left"></span> Back to List
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="well_N">
            <div class="dp_sdw">

                {{-- Flash Messages --}}
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <strong><span class="glyphicon glyphicon-exclamation-sign"></span></strong> {{ session('error') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <ul style="margin:0; padding-left:15px;">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="row">
                    <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1 col-sm-12 col-xs-12">
                        <div class="panel">
                            <div class="panel-body">

                                <div class="headquid">
                                    <h2 class="subHeadingLabelClass">Add New Currency</h2>
                                </div>
                                <div class="lineHeight">&nbsp;</div>

                                <form action="{{ route('currency.store') }}" method="POST" id="currencyForm">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">

                                    <div class="row">

                                        {{-- Currency Name --}}
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                            <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                                                <label class="sf-label">
                                                    Currency Name (e.g. USD, EUR)
                                                    <span class="rflabelsteric"><strong>*</strong></span>
                                                </label>
                                                <input type="text"
                                                       name="name"
                                                       id="name"
                                                       class="form-control"
                                                       placeholder="e.g. USD, EUR"
                                                       value="{{ old('name') }}"
                                                       style="text-transform:uppercase;"
                                                       required>
                                                @if($errors->has('name'))
                                                    <span class="help-block">{{ $errors->first('name') }}</span>
                                                @endif
                                            </div>
                                        </div>

                                        {{-- Currency Rate --}}
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                            <div class="form-group {{ $errors->has('rate') ? 'has-error' : '' }}">
                                                <label class="sf-label">
                                                    Exchange Rate (1 unit = ? PKR)
                                                    <span class="rflabelsteric"><strong>*</strong></span>
                                                </label>
                                                <div class="input-group">
                                                    <input type="number"
                                                           name="rate"
                                                           id="rate"
                                                           class="form-control"
                                                           placeholder="e.g. 278.50"
                                                           value="{{ old('rate') }}"
                                                           step="0.0001"
                                                           min="0"
                                                           required>
                                                    <span class="input-group-addon">PKR</span>
                                                </div>
                                                @if($errors->has('rate'))
                                                    <span class="help-block">{{ $errors->first('rate') }}</span>
                                                @endif
                                            </div>
                                        </div>

                                    </div>

                                    {{-- Preview Row --}}
                                    <div class="row" id="previewRow" style="display:none; margin-bottom:15px;">
                                        <div class="col-lg-12">
                                            <div class="alert alert-info" style="margin:0;">
                                                <strong>Preview:</strong>
                                                1 <span id="prevName">-</span> =
                                                <span id="prevRate">-</span> PKR
                                            </div>
                                        </div>
                                    </div>

                                    <div class="lineHeight">&nbsp;</div>

                                    {{-- Buttons --}}
                                    <div class="row">
                                        <div class="col-lg-12 text-right">
                                            <button type="submit" class="btn btn-success">
                                                <span class="glyphicon glyphicon-floppy-disk"></span> Save Currency
                                            </button>
                                            &nbsp;
                                            <a href="{{ route('currency.index') }}" class="btn btn-default">
                                                <span class="glyphicon glyphicon-remove"></span> Cancel
                                            </a>
                                        </div>
                                    </div>

                                </form>

                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
// Auto-uppercase name field
document.getElementById('name').addEventListener('input', function() {
    this.value = this.value.toUpperCase();
    updatePreview();
});
document.getElementById('rate').addEventListener('input', updatePreview);

function updatePreview() {
    var name = document.getElementById('name').value;
    var rate = document.getElementById('rate').value;

    if (name || rate) {
        document.getElementById('previewRow').style.display = 'block';
        document.getElementById('prevName').innerText = name || '-';
        document.getElementById('prevRate').innerText = rate ? parseFloat(rate).toFixed(4) : '-';
    } else {
        document.getElementById('previewRow').style.display = 'none';
    }
}
</script>

@endsection
