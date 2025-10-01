@component('mail::message')
# Email Verification OTP

Your OTP code is:

**{{ $otp }}**

This code will expire in 5 minutes.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
