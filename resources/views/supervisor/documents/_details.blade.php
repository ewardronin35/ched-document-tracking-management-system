<!-- resources/views/admin/documents/_details.blade.php -->

    <h5 class="modal-title" id="documentDetailsModalLabel">Document Details</h5>
    <div class="modal-body">
    @php
        // Construct the public URL of the document
        $documentUrl = asset('storage/' . $document->file_path);
        // Get the file extension in lowercase
        $extension = strtolower(pathinfo($document->file_path, PATHINFO_EXTENSION));
    @endphp

    @if(in_array($extension, ['pdf']))
        <!-- Embed PDF in an iframe -->
        <iframe src="{{ $documentUrl }}" width="100%" height="500px" style="border: none;"></iframe>
    @elseif(in_array($extension, ['jpg', 'jpeg', 'png', 'gif']))
        <!-- Display the image -->
        <img src="{{ $documentUrl }}" class="img-fluid" alt="Document Image">
    @else
        <p>Preview is not available for this file type.</p>
        <a href="{{ $documentUrl }}" target="_blank" class="btn btn-primary">Download Document</a>
    @endif

    <!-- Optionally display additional meta information -->
    <hr>
   
</div>