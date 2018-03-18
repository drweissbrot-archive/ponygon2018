@extends('layouts.app')

@section('page-title')
	Create an Account
@endsection

@section('content')
	<form method="post" action="{{ route('register') }}">
		@csrf

		@inputgroup
			@slot('label')
				<label for="name">
					Name
				</label>
			@endslot

			<input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus>

			@slot('error')
				@if ($errors->has('name'))
					{{ $errors->first('name') }}
				@endif
			@endslot
		@endinputgroup

		@inputgroup
			@slot('label')
				<label for="address_as">
					We'll call you
				</label>
			@endslot

			<input type="text" id="address_as" name="address_as" value="{{ old('address_as') }}">

			@slot('help')
				Please tell us how you'd like us to call you.
			@endslot

			@slot('error')
				@if ($errors->has('address_as'))
					{{ $errors->first('address_as') }}
				@endif
			@endslot
		@endinputgroup

		@inputgroup
			@slot('label')
				<label for="email">
					Email Address
				</label>
			@endslot

			<input type="email" id="email" name="email" value="{{ old('email') }}" required>

			@slot('error')
				@if ($errors->has('email'))
					{{ $errors->first('email') }}
				@endif
			@endslot
		@endinputgroup

		@inputgroup
			@slot('label')
				<label for="password">
					Password
				</label>
			@endslot

			<input type="password" id="password" name="password" required>

			@slot('help')
				lorem ipsum
			@endslot

			@slot('error')
				@if ($errors->has('password'))
					{{ $errors->first('password') }}
				@endif
			@endslot
		@endinputgroup

		@inputgroup
			@slot('label')
				<label for="password_confirmation">
					Confirm Password
				</label>
			@endslot

			<input type="password" id="password_confirmation" name="password_confirmation" required>

			@slot('help')
				lorem ipsum
			@endslot

			@slot('error')
				@if ($errors->has('password_confirmation'))
					{{ $errors->first('password_confirmation') }}
				@endif
			@endslot
		@endinputgroup

		@inputgroup(['type' => 'checkbox'])
			<label>
				<input type="checkbox" name="tos" {{ old('tos') ? 'checked' : '' }} required>
				I have read and accept the
				<a href="{{ route('meta.tos') }}" target="_blank">Terms and Conditions</a>.
			</label>

			@slot('error')
				@if ($errors->has('tos'))
					{{ $errors->first('tos') }}
				@endif
			@endslot
		@endinputgroup

		@inputgroup(['type' => 'button'])
			<a href="{{ route('activation.resend') }}">
				Resend Activation Email
			</a>

			<button type="submit">
				Sign up
			</button>
		@endinputgroup
	</form>
@endsection
