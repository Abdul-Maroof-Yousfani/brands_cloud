@extends('layouts.default')
@section('content')
    <style>
        .premium-card {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.9) 0%, rgba(255, 255, 255, 0.7) 100%);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.05);
            padding: 30px;
            margin-bottom: 30px;
        }

        .step-number {
            background: #4e73df;
            color: white;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            margin-right: 10px;
        }

        .step-title {
            font-weight: 700;
            color: #2e3b4e;
            font-size: 1.1rem;
        }

        .filter-label {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #4e73df;
            font-weight: 800;
            margin-bottom: 8px;
            display: block;
        }

        .page-title {
            font-weight: 800;
            color: #2e3b4e;
            letter-spacing: -1px;
            margin-bottom: 30px;
        }

        .well_N {
            background: #f4f7fc;
            min-height: 100vh;
            padding: 40px;
        }

        .export-btn {
            background: #4e73df;
            border: none;
            border-radius: 12px;
            padding: 12px 25px;
            font-weight: 700;
            transition: all 0.3s;
        }

        .export-btn:hover {
            background: #2e59d9;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(78, 115, 223, 0.3);
        }
    </style>

    <div class="well_N">
        <h1 class="page-title"><i class="fas fa-file-import text-primary mr-2"></i> Bulk Target Importer</h1>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-3" role="alert">
                <i class="fas fa-check-circle mr-2"></i> {!! session('success') !!}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show rounded-3" role="alert">
                <i class="fas fa-exclamation-triangle mr-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            <!-- Step 1: Export Template -->
            <div class="col-md-6">
                <div class="premium-card h-100">
                    <div class="mb-4">
                        <span class="step-number">1</span>
                        <span class="step-title">Export Target Template</span>
                        <p class="text-muted small mt-2">Generate an Excel file with all BA-Store-Brand combinations for the
                            selected period.</p>
                    </div>

                    <form action="{{ route('baTargets.exportTemplate') }}" method="GET">
                        <div class="row g-3">
                            <div class="col-6">
                                <label class="filter-label">Target Month</label>
                                <select name="month" class="form-select select2">
                                    @for($m = 1; $m <= 12; $m++)
                                        <option value="{{ sprintf('%02d', $m) }}" {{ date('m') == $m ? 'selected' : '' }}>
                                            {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-6">
                                <label class="filter-label">Target Year</label>
                                <select name="year" class="form-select select2">
                                    @for($y = date('Y') - 1; $y <= date('Y') + 1; $y++)
                                        <option value="{{ $y }}" {{ date('Y') == $y ? 'selected' : '' }}>{{ $y }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="filter-label">Tracking Mode</label>
                                <select name="target_type" class="form-select select2">
                                    <option value="qty">Quantity (Units) wise</option>
                                    <option value="amount">Amount (Revenue) wise</option>
                                </select>
                            </div>
                            <div class="col-12 mt-4">
                                <button type="submit" class="btn btn-primary export-btn w-100">
                                    <i class="fas fa-download mr-2"></i> Download Template
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Step 2: Import Data -->
            <div class="col-md-6">
                <div class="premium-card h-100">
                    <div class="mb-4">
                        <span class="step-number">2</span>
                        <span class="step-title">Upload & Import Targets</span>
                        <p class="text-muted small mt-2">Upload the filled template to bulk update targets in the system.
                        </p>
                    </div>

                    <form action="{{ route('baTargets.importExcel') }}" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="mb-4">
                            <label class="filter-label">Select Excel File (.xlsx, .xls)</label>
                            <input type="file" name="xlsx_file" class="form-control" required
                                style="border-radius: 12px; padding: 10px;">
                        </div>

                        <div class="bg-light p-3 rounded-3 mb-4">
                            <ul class="small text-muted mb-0">
                                <li>Do not change the ID columns (Employee, Store, Brand).</li>
                                <li>The system uses these IDs to map targets correctly.</li>
                                <li>Empty values will be skipped.</li>
                            </ul>
                        </div>

                        <button type="submit" class="btn btn-success export-btn w-100" style="background: #1cc88a;">
                            <i class="fas fa-upload mr-2"></i> Start Bulk Import
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="mt-4 text-center">
            <a href="{{ route('baTargets.create') }}" class="text-primary text-decoration-none font-weight-bold">
                <i class="fas fa-arrow-left mr-1"></i> Back to Manual Setup
            </a>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            $('.select2').select2({
                theme: 'bootstrap-5'
            });
        });
    </script>
@endsection