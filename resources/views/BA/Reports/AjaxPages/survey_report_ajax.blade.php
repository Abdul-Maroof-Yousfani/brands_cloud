<div class="report-card mb-4">
    <h5 class="fw-bold mb-3">BA-Wise Survey Summary</h5>
    <div class="table-responsive">
        <table class="table table-sm table-bordered">
            <thead class="bg-light">
                <tr>
                    <th>BA Name</th>
                    <th class="text-center">Total Responses</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $summary = [];
                    foreach($surveys as $sv) {
                        $ba = $sv->user->name ?? 'N/A';
                        $summary[$ba] = ($summary[$ba] ?? 0) + 1;
                    }
                @endphp
                @foreach($summary as $ba => $count)
                    <tr>
                        <td>{{ $ba }}</td>
                        <td class="text-center fw-bold text-primary">{{ number_format($count) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="report-card">
    <div class="table-responsive">
        <table class="table table-hover table-bordered table-report align-middle mb-0">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>BA Name</th>
                    <th>Customer Info</th>
                    <th>Brand Insight</th>
                    <th>Product & Remarks</th>
                </tr>
            </thead>
            <tbody>
                @forelse($surveys as $survey)
                    <tr>
                        <td>{{ date('d-M-Y', strtotime($survey->created_at)) }}</td>
                        <td>{{ $survey->user->name ?? 'N/A' }}</td>
                        <td>
                            <strong>{{ $survey->customer_name }}</strong><br>
                            <small class="text-muted"><i class="fa fa-phone me-1"></i> {{ $survey->contact }}</small><br>
                            <span class="badge bg-light text-dark border mt-1">{{ $survey->distributor->name ?? 'N/A' }}</span>
                        </td>
                        <td>
                            <div class="mb-1">
                                <span class="small text-muted">Current Brand 1:</span><br>
                                <span class="fw-bold">{{ $survey->currently_using_brand_id ?? 'N/A' }}</span>
                            </div>
                            <div>
                                <span class="small text-muted">Current Brand 2:</span><br>
                                <span class="fw-bold">{{ $survey->currently_using_brand_2_id ?? 'N/A' }}</span>
                            </div>
                        </td>
                        <td>
                            <div class="mb-1">
                                <span class="small text-muted">Recommended Product:</span><br>
                                <span class="fw-bold text-primary">{{ $survey->product->product_name ?? 'N/A' }}</span>
                            </div>
                            <div class="border-top mt-1 pt-1">
                                <span class="small text-muted">Remarks:</span><br>
                                <p class="mb-0 italic small">{{ $survey->remarks ?? '-' }}</p>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-4">No survey records found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
