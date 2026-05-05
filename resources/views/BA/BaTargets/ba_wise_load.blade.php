<style>
    .target-card {
        border-radius: 15px;
        overflow: hidden;
        border: none;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        background: #fff;
    }
    .target-header {
        background: #fff;
        color: #1e293b;
        padding: 20px 25px;
        position: sticky;
        top: 0;
        z-index: 100;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .store-section {
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        padding: 12px 25px;
        font-weight: 700;
        color: #3b82f6;
        display: flex;
        align-items: center;
    }
    .brand-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 15px;
        padding: 25px;
    }
    .brand-item {
        background: #fff;
        border: 1px solid #f1f5f9;
        border-radius: 12px;
        padding: 15px;
        transition: all 0.2s ease;
        box-shadow: 0 2px 4px rgba(0,0,0,0.02);
    }
    .brand-item:hover {
        border-color: #3b82f6;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.08);
    }
    .brand-name {
        font-size: 0.8rem;
        font-weight: 700;
        color: #64748b;
        margin-bottom: 10px;
        display: block;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .target-input {
        border-radius: 8px !important;
        border: 1px solid #cbd5e1;
        padding: 10px 15px;
        height: 42px;
        font-weight: 600;
        color: #1e293b;
    }
    .target-input:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
    }
    .btn-save-targets {
        background: #3b82f6;
        color: #fff;
        border-radius: 8px;
        padding: 10px 25px;
        font-weight: 700;
        transition: all 0.2s;
        border: none;
        font-size: 14px;
    }
    .btn-save-targets:hover {
        background: #2563eb;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }
</style>

<div class="target-card mt-3">
    <div class="target-header shadow-sm">
        <div>
            <h5 class="mb-0 fw-bold text-dark">
                <i class="fas fa-bullseye me-2 text-primary"></i> {{ $month_year }} Targets 
                <span class="badge bg-soft-primary text-primary border border-primary ms-2 text-uppercase" style="font-size: 10px;">{{ strtoupper($target_type) }} Basis</span>
            </h5>
        </div>
        <button type="button" form="targetSaveForm" id="saveTargetsBtn" class="btn-save-targets">
            <i class="fas fa-save me-2"></i> Save Targets
        </button>
    </div>

    <div class="target-body">
        <form id="targetSaveForm">
            <input type="hidden" value="{{ csrf_token() }}" name="_token">
            <input type="hidden" name="employee_id" value="{{ $employee_id }}">
            <input type="hidden" name="month_year" value="{{ $month_year }}">
            <input type="hidden" name="target_type" value="{{ $target_type }}">

            @foreach($formations as $formation)
                <div class="store-section">
                    <i class="fas fa-store-alt me-2 text-primary"></i>
                    <span class="text-dark">{{ $formation->customer->name ?? 'N/A' }}</span>
                    <small class="ms-2 text-muted fw-normal">(ID: {{ $formation->customer_id }})</small>
                </div>
                
                <div class="brand-grid">
                    @foreach($formation->assigned_brands as $brand)
                        @php
                            $targetKey = $formation->customer_id . '_' . $brand->id;
                            $existingValue = isset($existing_targets[$targetKey]) ? $existing_targets[$targetKey]->target : '';
                        @endphp
                        <div class="brand-item">
                            <label class="brand-name" title="{{ $brand->name }}">{{ $brand->name }}</label>
                            <input type="number" 
                                   name="targets[{{ $formation->customer_id }}][{{ $brand->id }}]" 
                                   class="form-control target-input" 
                                   placeholder="0.00" 
                                   value="{{ $existingValue }}"
                                   min="0" step="0.01">
                        </div>
                    @endforeach
                </div>
            @endforeach

            <!-- Redesigned Grand Total Section - White Theme -->
            <div class="mt-4 border-top" style="background: #f8fafc; border-radius: 0 0 15px 15px; padding: 30px 40px; border-top: 1px solid #e2e8f0 !important;">
                <div style="display: flex; justify-content: space-between; align-items: center; width: 100%;">
                    <div style="display: flex; align-items: center; gap: 20px;">
                        <div style="background: #fff; width: 60px; height: 60px; border-radius: 12px; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 12px rgba(0,0,0,0.05); border: 1px solid #e2e8f0;">
                            <i class="fas fa-calculator" style="color: #3b82f6 !important; font-size: 24px;"></i>
                        </div>
                        <div>
                            <div style="color: #64748b !important; font-size: 0.7rem; font-weight: 800; text-transform: uppercase; letter-spacing: 1.5px; margin-bottom: 4px;">Summary Dashboard</div>
                            <div style="color: #1e293b !important; font-size: 1.5rem; font-weight: 800; margin: 0; line-height: 1;">Grand Total ({{ strtoupper($target_type) }})</div>
                        </div>
                    </div>
                    <div style="text-align: right;">
                        <div id="overallTotalDisplay" style="color: #3b82f6 !important; font-size: 3rem; font-weight: 900; letter-spacing: -2px; line-height: 1;">0.00</div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    function calculateGrandTotal() {
        let grandTotal = 0;
        $('.target-input').each(function() {
            let val = parseFloat($(this).val()) || 0;
            grandTotal += val;
        });
        $('#overallTotalDisplay').text(grandTotal.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
    }

    // Initial calculation
    calculateGrandTotal();

    // Recalculate on input change
    $('.target-input').on('input change', function() {
        calculateGrandTotal();
    });

    $('#saveTargetsBtn').click(function (e) {
        e.preventDefault();
        let btn = $(this);
        let form = $('#targetSaveForm');

        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Saving...');

        $.ajax({
            url: "{{ route('baTargets.saveBaWise') }}",
            type: 'POST',
            data: form.serialize(),
            success: function (res) {
                if (res.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: res.message,
                        timer: 2000,
                        showConfirmButton: false
                    });
                } else {
                    Swal.fire('Error', res.message || 'Something went wrong', 'error');
                }
                btn.prop('disabled', false).html('<i class="fas fa-save mr-1"></i> Save Targets');
            },
            error: function () {
                Swal.fire('Error', 'Server Error. Please try again.', 'error');
                btn.prop('disabled', false).html('<i class="fas fa-save mr-1"></i> Save Targets');
            }
        });
    });
</script>