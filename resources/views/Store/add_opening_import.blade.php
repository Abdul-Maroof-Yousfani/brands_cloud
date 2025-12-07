@extends('layouts.default')

@section('content')
@include('modal')
@include('select2')

{{-- ✅ Modal Styling --}}
<style>
    .modal-dialog.modal-dialog-centered {
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .modal-content {
        width: 100%;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
    }

    .modal-header {
        background-color: #007bff;
        color: white;
        border-bottom: none;
        padding: 1rem 1.5rem;
    }

    .modal-title {
        font-size: 1.25rem;
        font-weight: bold;
    }

    .modal-body {
        padding: 1.5rem;
    }

    .modal-footer {
        border-top: none;
        padding: 1rem 1.5rem;
    }

    .btn + .btn {
        margin-left: 10px;
    }

    .modal-header {
    background-color: #7367f0 !important;
        height: 77px;
    
    }
</style>

{{-- ✅ Trigger Button --}}
<div class="container py-5 text-center">
     <h3 class="mb-4 text-primary font-weight-bold">📦 Opening Stock Import Panel</h3><br>
    <button type="button" class="btn btn-primary btn-lg shadow-sm" data-toggle="modal" data-target="#exampleModal">
        <i class="fa fa-upload mr-2"></i> Import Opening (.csv)
    </button>
</div>

{{-- ✅ Modal --}}
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Import Opening Data</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close" style="opacity:1;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            {{-- ✅ Upload Form --}}
            <form id="importForm" enctype="multipart/form-data">
                {{ csrf_field() }}

                <div class="modal-body">
                    <div class="form-group">
                        <label for="file">Choose CSV File</label>
                        <input type="file" name="file" class="form-control" required>
                        <small class="form-text text-muted mt-1">Only .csv files are allowed.</small>
                    </div>
                </div>

                <div class="modal-footer d-flex justify-content-between flex-wrap">
                    <a  style="
    width: 43%;
"target="_blank" href="{{ asset('public/Closing_Inventory_opning.csv') }}" class="btn btn-dark">
                        <i class="fa fa-download mr-1"></i> Download Sample File
                    </a>
                    <div>
                        <button style="
    margin-left: 5px;
" type="button" id="importButton" class="btn btn-primary">
                            <i class="fa fa-file-import mr-1"></i> Import
                        </button>
                        <button style="
    margin-left: 127px;
" type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </form>

        </div>
    </div>
</div>

{{-- ✅ SweetAlert2 --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

{{-- ✅ AJAX Script --}}
<script>
    $(document).ready(function () {
        $('#importButton').on('click', function () {
            var fileInput = $('input[name="file"]')[0];

            if (fileInput.files.length === 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Validation Error',
                    text: 'Please select a file to upload.'
                });
                return;
            }

            var formData = new FormData($('#importForm')[0]);

            Swal.fire({
                title: 'Processing...',
                text: 'Please wait while the file is being uploaded.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

        $.ajax({
    url: "{{ route('add_opening_import_post') }}",
    method: 'POST',
    headers: {
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
    },
    data: formData,
    contentType: false,
    processData: false,
    success: function (response) {
        Swal.close();
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: response.message // fixed here
        }).then(() => {
            location.reload();
        });
    },
    error: function (xhr) {
        Swal.close();
        var errorMessage = 'An error occurred while importing the data.';
        if (xhr.responseJSON) {
            if (xhr.responseJSON.errors) {
                errorMessage = '';
                $.each(xhr.responseJSON.errors, function (key, messages) {
                    errorMessage += messages.join(' ') + ' ';
                });
            } else if (xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message;
            }
        }
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: errorMessage.trim()
        });
    }
});

        });
    });
</script>
@endsection
