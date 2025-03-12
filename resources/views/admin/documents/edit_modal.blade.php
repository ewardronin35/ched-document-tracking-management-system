@php
    // Determine if the current user has full power.
    // Full-power roles: admin, Records, RegionalDirector.
    $fullPower = auth()->user()->hasAnyRole(['admin', 'Records', 'RegionalDirector']);
@endphp

<form id="edit-document-form" method="POST" action="{{ $fullPower ? route('admin.documents.update', $document->id) : route('supervisor.documents.update', $document->id) }}">
    @csrf
    @method('PUT')

    @if($fullPower)
        <!-- Routing Section for Full Power Roles -->
        <div class="mb-3">
            <label for="routed_to" class="form-label">Route To</label>
            <select name="routed_to" id="routed_to" class="form-select">
                <option value="">Select a user</option>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}" {{ (isset($document->routed_to) && $document->routed_to == $user->id) ? 'selected' : '' }}>
                        {{ $user->name }} ({{ $user->getRoleNames()->join(', ') }})
                    </option>
                @endforeach
            </select>
        </div>
    @else
        <!-- For Limited Power Roles: Force Routing to Default Records Office -->
        <input type="hidden" name="routed_to" value="{{ $recordsUser->id }}">
        <div class="mb-3">
            <label class="form-label">Route To</label>
            <input type="text" class="form-control" value="{{ $recordsUser->name }} ({{ $recordsUser->getRoleNames()->join(', ') }})" disabled>
        </div>
    @endif

    <!-- Approval Status Section -->
  
    <!-- Always include approval_status (hidden) to avoid Laravel validation errors -->
    <input type="hidden" name="approval_status" value="">

    <!-- Remarks Section -->
    <div class="mb-3">
        <label for="remarks" class="form-label">Remarks</label>
        <textarea name="remarks" id="remarks" rows="3" class="form-control">{{ $document->status_details ? json_decode($document->status_details)->remarks ?? '' : '' }}</textarea>
    </div>

    <button type="submit" class="btn btn-primary">Save Changes</button>

    @unless($fullPower)
        <!-- For limited roles, show a Release Document button -->
        <button type="button" class="btn btn-success" id="release-button">Release Document</button>
    @endunless
</form>

<!-- SweetAlert2 & jQuery Script for AJAX submission and release action -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        // For full power roles, enable the Approval Status select after a user is selected in "Route To"
        @if($fullPower)
            $('#routed_to').on('change', function() {
                if ($(this).val()) {
                    $('#approval_status').prop('disabled', false);
                    $('#approval_status_container').fadeIn();
                } else {
                    $('#approval_status').prop('disabled', true).val('');
                    $('#approval_status_container').fadeOut();
                }
            });
        @endif

        // Handle the edit form submission via AJAX
        $('#edit-document-form').on('submit', function(e) {
            e.preventDefault();
            var form = $(this);
            $.ajax({
                url: form.attr('action'),
                type: form.attr('method'),
                data: form.serialize(),
                dataType: 'json',
                success: function(response) {
                    Swal.fire({
                        title: 'Success!',
                        text: response.message,
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then(function() {
                        $('#documents-table').DataTable().ajax.reload();
                        var modalEl = document.getElementById('editDocumentModal');
                        var modal = bootstrap.Modal.getInstance(modalEl);
                        modal.hide();
                    });
                },
                error: function(xhr) {
                    Swal.fire({
                        title: 'Error',
                        text: 'An error occurred while saving changes.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            });
        });

        // For limited roles, handle the "Release Document" button click.
        @unless($fullPower)
            $('#release-button').on('click', function() {
                Swal.fire({
                    title: 'Release Document?',
                    text: 'Are you sure you want to release this document to the Records office?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, release it!',
                    cancelButtonText: 'Cancel'
                }).then(function(result) {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '{{ route("supervisor.documents.release", $document->id) }}',
                            type: 'POST',
                            data: { _token: '{{ csrf_token() }}' },
                            dataType: 'json',
                            success: function(response) {
                                Swal.fire({
                                    title: 'Released!',
                                    text: response.message,
                                    icon: 'success',
                                    confirmButtonText: 'OK'
                                }).then(function() {
                                    $('#documents-table').DataTable().ajax.reload();
                                    var modalEl = document.getElementById('editDocumentModal');
                                    var modal = bootstrap.Modal.getInstance(modalEl);
                                    modal.hide();
                                });
                            },
                            error: function(xhr) {
                                Swal.fire({
                                    title: 'Error',
                                    text: 'An error occurred while releasing the document.',
                                    icon: 'error',
                                    confirmButtonText: 'OK'
                                });
                            }
                        });
                    }
                });
            });
        @endunless
    });
</script>
