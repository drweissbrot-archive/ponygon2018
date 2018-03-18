@extends('layouts.app')

@section('page-title')
	Reset Password
@endsection

@section('content')
	<form method="post" action="{{ route('password.request') }}">
		@csrf

		<input type="hidden" name="token" value="{{ $token }}">

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

		@inputgroup(['type' => 'button'])
			<button type="submit">
				Reset Password
			</button>
		@endinputgroup
	</form>
@endsection
