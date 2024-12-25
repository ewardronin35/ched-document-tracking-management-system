<!-- resources/views/emails/password-reset.blade.php -->

@component('mail::message')
# Welcome, {{ $name }}!

Your account has been created successfully. To set your password, please click the button below:

@component('mail::button', ['url' => $resetLink])
Set Your Password
@endcomponent

If you did not expect this email, please ignore it.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
