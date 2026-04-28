<style>
    .target-card {
        border-radius: 15px;
        overflow: hidden;
        border: none;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        background: #fff;
    }
    .target-header {
        background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
        color: white;
        padding: 20px;
        position: sticky;
        top: 0;
        z-index: 100;
        backdrop-filter: blur(5px);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .store-section {
        background: #f8f9fc;
        border-bottom: 2px solid #e3e6f0;
        padding: 15px 20px;
        font-weight: 700;
        color: #4e73df;
        display: flex;
        align-items: center;
    }
    .brand-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 15px;
        padding: 20px;
    }
    .brand-item {
        background: #fff;
        border: 1px solid #eaecf4;
        border-radius: 10px;
        padding: 12px;
        transition: all 0.3s ease;
    }
    .brand-item:hover {
        border-color: #4e73df;
        box-shadow: 0 5px 15px rgba(78, 115, 223, 0.1);
    }
    .brand-name {
        font-size: 0.85rem;
        font-weight: 600;
        color: #5a5c69;
        margin-bottom: 8px;
        display: block;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .target-input {
        border-radius: 8px !important;
        border: 1px solid #d1d3e2;
        padding: 8px 12px;
        height: 38px;
    }
    .target-input:focus {
        border-color: #4e73df;
        box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
    }
    .btn-save-targets {
        background: #fff;
        color: #4e73df;
        border-radius: 30px;
        padding: 8px 25px;
        font-weight: 700;
        transition: all 0.3s;
        border: none;
    }
    .btn-save-targets:hover {
        background: #f8f9fc;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }
</style>

<div class="target-card mt-3">
    <div class="target-header">
        <div>
            <h5 class="mb-0 font-weight-bold">
                <i class="fas fa-bullseye mr-2"></i> {{ $month_year }} Targets 
                <span class="badge badge-light ml-2">{{ strtoupper($target_type) }} Basis</span>
            </h5>
        </div>
        <button type="button" form="targetSaveForm" id="saveTargetsBtn" class="btn-save-targets shadow-sm">
            <i class="fas fa-save mr-1"></i> Save Targets
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
                    <i class="fas fa-store-alt mr-2 text-primary"></i>
                    {{ $formation->customer->name ?? 'N/A' }} 
                    <small class="ml-2 text-muted font-weight-normal">(ID: {{ $formation->customer_id }})</small>
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
                                   placeholder="Enter {{ $target_type }}" 
                                   value="{{ $existingValue }}"
                                   min="0" step="0.01">
                        </div>
                    @endforeach
                </div>
            @endforeach
        </form>
    </div>
</div>

<script>
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