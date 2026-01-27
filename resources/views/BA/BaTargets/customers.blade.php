    <style>
     /* Horizontal scroll only */
    .table-wrapper {
        width: 100%;
        overflow-x: auto;
    }

    /* Normal vertical page scroll */
    body {
        overflow-y: auto;
    }

    .table {
        table-layout: auto;
        width: max-content; /* important */
    }

    .table td,
    .table th {
        white-space: nowrap;
    }

    .table input.form-control {
        min-width: 140px;
    }
    </style>
        <form id="targetForm" action="{{ route('insert.target') }}" method="GET">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Code</th>
                <th>Zone</th>

                <!-- Dynamic headers -->
                @foreach($brands as $brand)
                    <th class="dynamic-col">{{ $brand->name }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
                @foreach($customers as $customer)
                    <tr>
                        <!-- Code (disabled) -->
                        <td>
                            <input type="hidden" name="year" value="{{ $year }}" />
                            <input type="hidden" name="month" value="{{ $month }}" />
                            <input type="hidden" name="customer_id[{{ $customer->id }}]" value="{{ $customer->id }}" />
                            <input type="text" class="form-control" value="{{ $customer->customer_code }}" readonly>
                        </td>

                        <!-- Zone (disabled) -->
                        <td>
                            <input type="text" class="form-control" name="zone[{{ $customer->id }}]" value="{{ $customer->zone }}" readonly>
                        </td>

                        @foreach($brands as $brand)
                            <td>
                                <input type="number" name="target[{{ $customer->id }}][]" class="form-control" 
                                    value="{{ $target_items[$customer->id . '_' . $brand->id]["target"] ?? 0 }}">
                                    <input type="hidden" name="brand_id[{{ $customer->id }}][]" value="{{ $brand->id }}" />
                            </td>
                        @endforeach

                    </tr>
                @endforeach
            </tbody>
        </table>
        
        <button style="
                margin-top: 23px;
                " class="btn btn-primary waves-effect waves-float waves-light" type="submit">Create</button>
                </form>


<script>
    $(document).ready(function() {
    $('#targetForm').on('submit', function(e) {
        e.preventDefault(); // prevent normal form submission

        var form = $(this);
        var url = form.attr('action');

        // Serialize all inputs (same as normal form)
        var data = form.serialize();

        $.ajax({
            url: url,
            type: 'GET', // same as your form method
            data: data,
            success: function(response) {
                // handle success
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: 'Data saved successfully!',
                    timer: 2000,
                    showConfirmButton: false
                });
            },
            beforeSend: function() {
                Swal.fire({
                    title: 'Loading...',
                    html: 'Please wait while we fetch the data.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading(); // show spinner
                    }
                });
            },  
            error: function(xhr) {
                // handle errors
                alert('Something went wrong.');
                console.log(xhr.responseText);
            }
        });
    });
});
</script>
