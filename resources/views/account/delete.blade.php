@extends('account.layouts.app')

@section('page-title')
	Delete Account · Account Settings
@endsection

@section('heading')
	<h1>
		Account Settings
	</h1>
@endsection

@section('account.content')
	@panel
		@slot('title')
			<h3>Delete Account</h3>
		@endslot

		<form method="post" action="{{ route('account.delete') }}">
			@csrf

			@inputgroup
				<p>
					Are you absolutely sure you want to delete your account?
					Anything associated with it will be <strong>lost, immediately and forever</strong>.
				</p>
			@endinputgroup

			@inputgroup
				@slot('label')
					<label for="email">
						Email
					</label>
				@endslot

				<input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus>

				@slot('help')
					Please type in your email address.
					We want to make sure you know which account you're deleting.
				@endslot

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
					Please confirm your password.
					We also want to make sure it's really you.
				@endslot

				@slot('error')
					@if ($errors->has('password'))
						{{ $errors->first('password') }}
					@endif
				@endslot
			@endinputgroup

			@inputgroup(['type' => 'button'])
				<button type="submit">
					Delete my Account
				</button>
			@endinputgroup
		</form>
	@endpanel
@endsection
