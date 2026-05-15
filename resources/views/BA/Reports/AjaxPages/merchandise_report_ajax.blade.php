<div class="report-card">
    <div class="table-responsive">
        <table class="table table-hover table-bordered table-report align-middle mb-0">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>BA Name</th>
                    <th>Store</th>
                    <th>Before Rack</th>
                    <th>After Rack</th>
                    <th>Remarks</th>
                </tr>
            </thead>
            <tbody>
                @forelse($merchandise as $m)
                    <tr>
                        <td>{{ date('d-M-Y', strtotime($m->merchandise_date)) }}</td>
                        <td>{{ $m->user->name ?? 'N/A' }}</td>
                        <td>
                            <strong>{{ $m->distributor->name ?? 'N/A' }}</strong>
                        </td>
                        <td class="text-center">
                            @if($m->before_rack)
                                <a href="{{ url('storage/' . $m->before_rack) }}" target="_blank">
                                    <img src="{{ url('storage/' . $m->before_rack) }}" alt="Before" style="max-width: 100px; height: auto; border-radius: 4px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                                </a>
                            @else
                                <span class="text-muted small">No Image</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($m->after_rack)
                                <a href="{{ url('storage/' . $m->after_rack) }}" target="_blank">
                                    <img src="{{ url('storage/' . $m->after_rack) }}" alt="After" style="max-width: 100px; height: auto; border-radius: 4px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                                </a>
                            @else
                                <span class="text-muted small">No Image</span>
                            @endif
                        </td>
                        <td>
                            <p class="mb-0 italic small text-muted">{{ $m->remarks ?? '-' }}</p>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4">No merchandise records found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
