<!-- resources/views/layouts/partials/toastr.blade.php -->

<!-- Toastr CSS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">

<!-- jQuery (Required for Toastr) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Toastr JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<!-- Toastr Initialization Script -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Toastr Options
        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showDuration": "300",
            "hideDuration": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        };

        // Remove or Comment Out the Test Message
        // toastr.success("Test message: Toastr is working!");

        // Display Toastr Notifications from Session
        @if(session('success'))
            toastr.success(@json(session('success')));
        @endif

        @if(session('error'))
            toastr.error(@json(session('error')));
        @endif

        @if(session('status'))
            toastr.info(@json(session('status')));
        @endif

        // Validation Errors
        @if ($errors->any())
            @foreach ($errors->all() as $error)
                toastr.error(@json($error));
            @endforeach
        @endif
    });

    // Show Spinner on Form Submission
    document.addEventListener('DOMContentLoaded', function () {
        const loginForm = document.getElementById('login-form');
        if (loginForm) {
            loginForm.addEventListener('submit', function () {
                document.getElementById('loading-spinner').classList.remove('hidden');
            });
        }
    });
</script>
