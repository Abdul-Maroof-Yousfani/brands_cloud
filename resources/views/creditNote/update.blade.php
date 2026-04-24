@extends('layouts.default')

@section('content')
    <?php
    use App\Helpers\CommonHelper;
    $so_no = CommonHelper::generateUniquePosNo('production_work_order', 'work_no', 'WO');
    $is_against_invoice = count($bridge_data) > 0;
    ?>
    <div class="row well_N align-items-center">
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
            <ul class="cus-ul">
                <li>
                    <h1>Finance</h1>
                </li>
                <li>
                    <h3><span class="glyphicon glyphicon-chevron-right"></span> &nbsp; Credit Note</h3>
                </li>
            </ul>
        </div>
    </div>
    <div class="row">
        <!-- LEFT SIDE FORM -->
        <div class="col-lg-8 col-md-8 col-sm-12">
            <div class="well_N dp_sdw">
                <form method="POST" id="submitForm">
                    <div class="panel">
                        <div class="panel-body">
                            <div class="headquid">
                                <h2 class="subHeadingLabelClass">Edit Credit Note ({{ $credit->rv_no }})</h2>
                            </div>

                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <div class="row qout-h">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Stores</label>
                                        <select name="store" onchange="getData()" id="ClientId"
                                            class="form-control select2">
                                            <option value="">Select Store</option>
                                            @foreach ($customers as $customer)
                                                <option value="{{ $customer->id }}"
                                                    {{ $credit->store == $customer->id ? 'selected' : '' }}>
                                                    {{ $customer->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-check d-flex align-items-center">
                                            <input class="form-check-input me-2" type="radio" onchange="change_type(this)" {{ $is_against_invoice ? 'checked' : '' }} name="type"
                                                value="against-invoice">
                                            <label class="form-check-label">
                                                Against invoice
                                            </label>
                                        </div>

                                        <div class="form-check d-flex align-items-center mt-2">
                                            <input class="form-check-input me-2" type="radio" onchange="change_type(this)" {{ !$is_against_invoice ? 'checked' : '' }} name="type"
                                                value="without-invoice">
                                            <label class="form-check-label">
                                                Without Invoices
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6" id="amount" style="{{ $is_against_invoice ? 'display: none;' : 'display: block;' }}">
                                    <div class="form-group">
                                        <label>Amount</label>
                                        <input type="number" class="form-control" name="amount" id="amount_val"
                                            value="{{ $credit->amount }}" placeholder="">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Date Time</label>
                                        <input type="date" class="form-control" id="date_and_time" name="date_and_time"
                                            value="{{ $credit->date }}">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Details</label>
                                        <textarea class="form-control" id="details" name="details">{{ $credit->details }}</textarea>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Debit Account</label>
                                        <select class="form-control select2" name="debit" id="debit" onchange="updateAccountingPreview()">
                                            <option value="">Select Debit</option>
                                            @foreach ($accounts as $y)
                                                <option {{ $credit->debit == $y->id ? 'selected' : '' }}
                                                    value="{{ $y->id }}">
                                                    {{ $y->code . ' ---- ' . $y->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Branch</label>
                                        <select class="form-control select2" name="branch" id="branch">
                                            <option value="">Select Branch</option>
                                            @foreach ($branches as $branch)
                                                <option value="{{ $branch->id }}" {{ $credit->branch == $branch->id ? 'selected' : '' }}>{{ $branch->branch_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 col-lg-12" style="margin-top: 30px; padding-left: 0px; width: 100%;"
                        id="receipt-table"></div>
                    
                    <div class="col-md-12 col-lg-12" id="total-amount-display" style="display:none; margin-top:20px;">
                        <div class="alert alert-info">
                            <h4>Total Net Amount: <span id="display-net-total">0.00</span></h4>
                        </div>
                    </div>

                    <div class="col-md-12 col-lg-12" style="margin-top: 20px; padding-left: 0px; width: 100%;"
                        id="accounting-entry-preview"></div>
                    <div class="col-md-12" style="margin-top: 30px; padding-left: 0px;">
                        <button type="reset" class="btn btn-danger">Reset</button>
                        <button type="submit" class="btn btn-success">Update Voucher</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- RIGHT SIDE PANELS -->
        <div class="col-lg-4 col-md-4 col-sm-12">
            <div class="d-flex flex-column gap-3">
                <!-- Pending Invoices -->
                <div class="panel panel-default shadow-sm" id="invoices" style="border-radius:10px; overflow:hidden; {{ !$is_against_invoice ? 'display: none;' : '' }}">
                    <div class="panel-heading text-white" style="background-color:#007bff; padding:10px 15px;">
                        <strong>PENDING INVOICES (PARTIAL PAYMENT)</strong>
                    </div>
                    <div class="panel-body" style="background-color:#f8f9fa;">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-condensed mb-0" id="table">

                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


    <script>
        let invoice_required = {{ $is_against_invoice ? 'true' : 'false' }};
        function change_type(el) {
            const type = $(el).val();

            if(type === "without-invoice") {
                $("#invoices").css("display", "none");
                $("#amount").css("display", "block");
                $("#receipt-table").html("");
                temp = []; 
                $(".AllCheckbox").prop("checked", false);
                invoice_required = false;
                updateAccountingPreview();
            } else if(type === "against-invoice") {
                $("#invoices").css("display", "block");

                $("#amount").css("display", "none");
                invoice_required = true;
            }
            console.log($(el).val());
        }
    </script>
    <script>
        function issue() {
            $("#submitForm").trigger("submit");
        }
    </script>

    <script>
        var temp = {!! json_encode($bridge_data->pluck('si_id')->toArray()) !!};
        
        $(document).ready(function() {
            if (temp.length > 0) {
                receiptData();
            }
            getData();
        });

        document.getElementById("submitForm").addEventListener("submit", function(event) {
            event.preventDefault();

            const store = $("#ClientId");
            const type = $("input[name='type']:checked").val();
            const date_and_time = $("#date_and_time");
            const details = $("#details");
            const debit = $("#debit");
            const branch = $("#branch");

            if (!store.val()) {
                alert("Store name is mandatory");
                return;
            }
            if (!date_and_time.val()) {
                alert("Date and Time is mandatory");
                return;
            }
            if (!details.val()) {
                alert("Detail is mandatory");
                return;
            }
            if (!debit.val()) {
                alert("Debit is mandatory");
                return;
            }
            if (!branch.val()) {
                alert("Branch is mandatory");
                return;
            }

            const receiptTable = $("#receipt-table");
            if (!receiptTable.children().length && invoice_required) {
                alert("Enter at least one invoice");
                return;
            }

            if(!invoice_required && type !== "against-invoice" && !$("#amount_val").val()) {
                alert("Amount field is required");
                return;
            }

            this.submit();
        });

        function CheckUncheck() {
            temp = [];
            $('.AllCheckbox').each(function() {
                if ($(this).is(':checked')) {
                    temp.push($(this).val());
                }
            });

            if (temp.length > 0) {
                $('.BtnSub').prop('disabled', false);
                receiptData();
            } else {
                $('.BtnSub').prop('disabled', true);
                $('#receipt-table').html(""); 
                updateAccountingPreview(); 
            }
        }

        function receiptData() {
            $.ajax({
                url: '{{ url('/creditNote/customer/showReceipt') }}',
                type: 'Get',
                data: {
                    checkbox: temp
                },
                success: function(response) {
                    $('#receipt-table').html(response);
                    
                    // After loading the grid, set the saved values from bridge_data
                    setTimeout(function() {
                        @foreach($bridge_data as $b)
                            // Find the row containing this si_id
                            var $row = $('input[name="si_id[]"][value="{{ $b->si_id }}"]').closest('tr');
                            if ($row.length) {
                                var $recvInput = $row.find('.receive_amount');
                                $recvInput.val('{{ $b->received_amount }}');
                                
                                // Trigger calc by simulating keyup
                                $recvInput.trigger('keyup');
                            }
                        @endforeach
                        updateAccountingPreview();
                    }, 500);
                }
            });
        }

        function getData() {
            var ClientId = $('#ClientId').val();
            if (!ClientId) return;

            $.ajax({
                url: '{{ url('/sdc/getRecieptDataClientWise/create') }}',
                type: 'Get',
                data: {
                    ClientId: ClientId,
                    m: 1
                },
                success: function(response) {
                    $('#table').html(response);
                    // Check checkboxes for existing temp values
                    temp.forEach(function(id) {
                        $('.AllCheckbox[value="' + id + '"]').prop('checked', true);
                    });
                    updateAccountingPreview();
                }
            });
        }

        function updateAccountingPreview() {
            const debit_acc_id = $("#debit").val();
            const customer_id = $("#ClientId").val();
            const type = $("input[name='type']:checked").val();
            
            let amount = 0;
            let tax_amount = 0;
            let discount_amount = 0;
            let net_total = 0;
            let tax_percent = "";

            if(type === "against-invoice") {
                amount = parseFloat($("#net_total").val()) || 0; 
                tax_amount = parseFloat($("#tax_total").val()) || 0;
                discount_amount = parseFloat($("#discount_total").val()) || 0;
                net_total = amount;
                
                let receive_total = 0;
                $(".receive_amount").each(function() {
                    receive_total += parseFloat($(this).val()) || 0;
                });
                amount = receive_total;
                net_total = parseFloat($("#net_total").val()) || 0;
                tax_percent = $(".tex_p").val();

                $("#total-amount-display").show();
                $("#display-net-total").text(net_total.toLocaleString(undefined, {minimumFractionDigits: 2}));
            } else {
                amount = parseFloat($("#amount_val").val()) || 0;
                net_total = amount;
                $("#total-amount-display").hide();
            }

            if(!debit_acc_id || !customer_id) {
                $("#accounting-entry-preview").html("");
                return;
            }

            $.ajax({
                url: '{{ route("creditNote.accounting.preview") }}',
                type: 'GET',
                data: {
                    debit_acc_id: debit_acc_id,
                    customer_id: customer_id,
                    amount: amount,
                    tax_amount: tax_amount,
                    discount_amount: discount_amount,
                    net_amount: net_total,
                    tax_percent: tax_percent
                },
                success: function(response) {
                    $("#accounting-entry-preview").html(response);
                }
            });
        }

        $(document).on("change", "#debit, #ClientId, #amount_val", function() {
            updateAccountingPreview();
        });

        $(document).on("keyup", ".receive_amount, .tax, .discount", function() {
            updateAccountingPreview();
        });
    </script>

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