@component('mail::message')
# Confirm Your Email Address

{{ $user->addressAs() }},

you just signed up at {{ config('app.name') }}.
Please confirm your email address to get started.

If you didn't sign up, you can just ignore this email.
Sorry about that.

@component('mail::button', [
	'url' => route('activate', [
		'email' => $user->email,
		'token' => $token,
	])])
Confirm email address
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
