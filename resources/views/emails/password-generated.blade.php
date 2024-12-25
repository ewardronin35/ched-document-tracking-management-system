<!-- resources/views/emails/password-generated.blade.php -->

@component('mail::message')
# Welcome, {{ $user->name }}!

Your account has been created successfully. Below are your login credentials:

@component('mail::panel')
**Email:** {{ $user->email }}  
**Password:** {{ $password }}
@endcomponent

Please log in using these credentials and change your password immediately after logging in for security purposes.

@component('mail::button', ['url' => route('login')])
Login Now
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
