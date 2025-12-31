@extends('layouts.default')

@section('content')
    <div class="container mt-5">
        <h2>BA Opening - Upload XLSX</h2>

        <div class="card p-4 shadow-sm" style="max-width: 500px;">
            <a href="{{ asset('storage/sample.xlsx') }}"
            class="btn btn-outline-secondary mb-3 w-100"
            style="margin-right: 20px"
            download>
                <i class="fas fa-download"></i> Download Sample XLSX
            </a>
            <!-- Upload Button (triggers file picker) -->
            <button type="button" class="btn btn-outline-primary mb-3" id="chooseFileBtn">
                <i class="fas fa-folder-open"></i> Choose XLSX File
            </button>

            <!-- Display selected file name -->
            <div id="selectedFileName" class="mb-3 text-muted">
                No file selected
            </div>

            <!-- Hidden File Input -->
            <input type="file" id="xlsxFileInput" accept=".xlsx,.xls" style="display: none;">

            <!-- Submit Button (triggers upload) -->
            <button type="button" class="btn btn-success btn-lg w-100" id="submitUploadBtn" disabled>
                <i class="fas fa-upload"></i> Upload & Process File
            </button>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            const $fileInput = $('#xlsxFileInput');
            const $chooseBtn = $('#chooseFileBtn');
            const $submitBtn = $('#submitUploadBtn');
            const $fileNameDisplay = $('#selectedFileName');

            let selectedFile = null;

            // Step 1: Click "Choose File" → open file picker
            $chooseBtn.on('click', function() {
                $fileInput.click();
            });

            // Step 2: When user selects a file
            $fileInput.on('change', function() {
                selectedFile = this.files[0];

                if (!selectedFile) {
                    resetUploadState();
                    return;
                }

                // Validate extension
                if (!selectedFile.name.toLowerCase().endsWith('.xlsx')) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Invalid File',
                        text: 'Please select a valid .xlsx file.'
                    });
                    resetUploadState();
                    return;
                }

                // Update UI
                $fileNameDisplay.text(`Selected: ${selectedFile.name}`);
                $submitBtn.prop('disabled', false).removeClass('btn-secondary').addClass('btn-success');
            });

            // Step 3: Click "Upload & Process File" → send to server
            $submitBtn.on('click', function() {
                if (!selectedFile) return;

                let formData = new FormData();
                formData.append('xlsx_file', selectedFile);

                // Show loading
                Swal.fire({
                    title: 'Uploading & Processing...',
                    text: 'Please wait while we process your file.',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                
                $.ajax({
                    url: "{{ route('baFormation.import') }}",
                    method: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: response.message || 'File uploaded and processed successfully.'
                        }).then(() => {
                            resetUploadState();
                        });
                    },
                    error: function(xhr) {
                        Swal.close();

                        let message = 'Upload failed.';
                        if (xhr.responseJSON?.message) {
                            message = xhr.responseJSON.message;
                        } else if (xhr.responseJSON?.errors) {
                            message = Object.values(xhr.responseJSON.errors).flat().join('<br>');
                        }

                        Swal.fire({
                            icon: 'error',
                            title: 'Upload Failed',
                            html: message
                        });
                    }
                });
            });

            // Helper: Reset UI after upload or error
            function resetUploadState() {
                selectedFile = null;
                $fileInput.val('');
                $fileNameDisplay.text('No file selected');
                $submitBtn.prop('disabled', true).removeClass('btn-success').addClass('btn-secondary');
            }

            // Initial state
            $submitBtn.addClass('btn-secondary');
        });
    </script>
@endsection