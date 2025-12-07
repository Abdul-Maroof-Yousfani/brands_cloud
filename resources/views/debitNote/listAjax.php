@foreach($debits as $debit)
    <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $debit->store }}</td>
        <td>{{ $debit->delivery_man }}</td>
        <td>{{ $debit->details }}</td>
        <td>{{ $debit->amount }}</td>
        <td>{{ $debit->credit }}</td>
        <td>{{ $debit->debit }}</td>
        <td>{{ $debit->on_record == 1 ? "Yes" : "No" }}</td>
        <td>{{ $debit->voucher_type }}</td>
        <td>{{ $debit->branch }}</td>
        <td>{{ $debit->is_approved == 1 ? "Approved" : "Pending" }}</td>
        <td style="display: flex; gap: 10px;">
            <a href="{{ route('creditnote.update', [ 'credit' => $debit->id ]) }}" class="btn btn-primary">Update</a>
            <a href="{{ route('creditNote.delete', [ 'credit' => $debit->id ]) }}" class="btn btn-danger">Delete</a>
            <a href="{{ route('creditNote.approve', ['credit' => $debit->id]) }}" class="btn btn-success">Approve</a>
        </td>
    </tr>
@endforeach