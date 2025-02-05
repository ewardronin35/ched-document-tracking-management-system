<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Debug Broadcast Auth</title>
</head>
<body>
    <h1>Test /debug-broadcast-auth Route</h1>
    <form id="testAuthForm" action="/debug-broadcast-auth" method="POST">
        <!-- Include the CSRF token -->
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="socket_id" value="dummy.socket.id">
        <input type="hidden" name="channel_name" value="App.Models.User.1">
        <button type="submit">Test Auth</button>
    </form>

    <!-- Include jQuery from CDN -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script>
        // Attach a submit event handler to the form
        $('#testAuthForm').submit(function(e) {
            e.preventDefault(); // Prevent normal form submission
            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: $(this).serialize(),
                success: function(data) {
                    console.log('Debug auth response:', data);
                    alert('Response: ' + JSON.stringify(data));
                },
                error: function(err) {
                    console.error('Debug auth error:', err);
                    alert('Error: ' + JSON.stringify(err));
                }
            });
        });
    </script>
</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Debug Broadcast Auth</title>
</head>
<body>
    <h1>Test /debug-broadcast-auth Route</h1>
    <form id="testAuthForm" action="/debug-broadcast-auth" method="POST">
        <!-- Include the CSRF token -->
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="socket_id" value="dummy.socket.id">
        <input type="hidden" name="channel_name" value="App.Models.User.1">
        <button type="submit">Test Auth</button>
    </form>

    <!-- Include jQuery from CDN -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script>
        // Attach a submit event handler to the form
        $('#testAuthForm').submit(function(e) {
            e.preventDefault(); // Prevent normal form submission
            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: $(this).serialize(),
                success: function(data) {
                    console.log('Debug auth response:', data);
                    alert('Response: ' + JSON.stringify(data));
                },
                error: function(err) {
                    console.error('Debug auth error:', err);
                    alert('Error: ' + JSON.stringify(err));
                }
            });
        });
    </script>
</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Debug Broadcast Auth</title>
</head>
<body>
    <h1>Test /debug-broadcast-auth Route</h1>
    <form id="testAuthForm" action="/debug-broadcast-auth" method="POST">
        <!-- Include the CSRF token -->
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="socket_id" value="dummy.socket.id">
        <input type="hidden" name="channel_name" value="App.Models.User.1">
        <button type="submit">Test Auth</button>
    </form>

    <!-- Include jQuery from CDN -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script>
        // Attach a submit event handler to the form
        $('#testAuthForm').submit(function(e) {
            e.preventDefault(); // Prevent normal form submission
            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: $(this).serialize(),
                success: function(data) {
                    console.log('Debug auth response:', data);
                    alert('Response: ' + JSON.stringify(data));
                },
                error: function(err) {
                    console.error('Debug auth error:', err);
                    alert('Error: ' + JSON.stringify(err));
                }
            });
        });
    </script>
</body>
</html>
