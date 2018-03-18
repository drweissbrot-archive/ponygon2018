@extends('layouts.app')

@section('page-title')
	Sign in
@endsection

@section('content')
	<form method="post" action="{{ route('login') }}">
		@csrf

		@inputgroup
			@slot('label')
				<label for="email">
					Email
				</label>
			@endslot

			<input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus>

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

			@slot('error')
				@if ($errors->has('password'))
					{{ $errors->first('password') }}
				@endif
			@endslot
		@endinputgroup

		@inputgroup(['type' => 'checkbox'])
			<label>
				<input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
				Keep me signed in
			</label>
		@endinputgroup

		@inputgroup(['type' => 'button'])
			<a href="{{ route('activation.resend') }}">
				Resend Activation Email
			</a>

			<a href="{{ route('password.request') }}">
				Forgot Your Password?
			</a>

			<button type="submit">
				Sign in
			</button>
		@endinputgroup
	</form>
@endsection
