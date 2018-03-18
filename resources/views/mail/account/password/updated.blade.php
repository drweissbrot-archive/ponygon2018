@component('mail::message')
# Password updated

{{ $user->addressAs() }},

we're just letting you know that your password on {{ config('app.name') }} was updated.
If you did that, everything is fine.

In case you did not change your password, someone else might have gotten access to your account.

Please contact us immediately if you think that might be the case.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
