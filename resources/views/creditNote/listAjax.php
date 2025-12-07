@foreach($credits as $credit)
    <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $credit->store }}</td>
        <td>{{ $credit->delivery_man }}</td>
        <td>{{ $credit->details }}</td>
        <td>{{ $credit->amount }}</td>
        <td>{{ $credit->credit }}</td>
        <td>{{ $credit->debit }}</td>
        <td>{{ $credit->on_record == 1 ? "Yes" : "No" }}</td>
        <td>{{ $credit->voucher_type }}</td>
        <td>{{ $credit->branch }}</td>
        <td>{{ $credit->is_approved == 1 ? "Approved" : "Pending" }}</td>
        <td style="display: flex; gap: 10px;">
            <a href="{{ route('creditnote.update', [ 'credit' => $credit->id ]) }}" class="btn btn-primary">Update</a>
            <a href="{{ route('creditNote.delete', [ 'credit' => $credit->id ]) }}" class="btn btn-danger">Delete</a>
            <a href="{{ route('creditNote.approve', ['credit' => $credit->id]) }}" class="btn btn-success">Approve</a>
        </td>
    </tr>
@endforeach