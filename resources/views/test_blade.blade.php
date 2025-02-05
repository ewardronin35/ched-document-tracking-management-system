<!DOCTYPE html>
<html>
<head>
    <title>reCAPTCHA v3 Test</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <button id="myButton">Submit</button>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script>
        const siteKey = '6LeQ7bwqAAAAAGcMe2QelqERRNMnLi-bqcMHfSrP'; // Replace with your *actual* site key!

        document.getElementById('myButton').addEventListener('click', function() {
            grecaptcha.ready(function() {
                grecaptcha.execute(siteKey, {action: 'homepage'}).then(function(token) {
                    // Send the token to your server for verification
                    fetch('/verify-recaptcha', { // Replace with your server endpoint
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') // For Laravel CSRF protection
                        },
                        body: JSON.stringify({token: token})
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // reCAPTCHA verification successful
                            console.log("reCAPTCHA verification successful");
                            // Proceed with form submission or other actions
                        } else {
                            // reCAPTCHA verification failed
                            console.error("reCAPTCHA verification failed");
                            // Handle the error (e.g., display a message to the user)
                        }
                    });
                });
            });
        });
    </script>
        <meta name="csrf-token" content="{{ csrf_token() }}">
</body>
</html>