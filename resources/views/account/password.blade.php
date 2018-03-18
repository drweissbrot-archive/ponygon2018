@extends('account.layouts.app')

@section('page-title')
	Change Password · Account Settings
@endsection

@section('heading')
	<h1>
		Account Settings
	</h1>
@endsection

@section('account.content')
	@panel
		@slot('title')
			<h3>Change your Password</h3>
		@endslot

		<form method="post" action="{{ route('account.password') }}">
			@csrf

			@inputgroup
				@slot('label')
					<label for="password">
						Current Password
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
					<label for="new">
						New Password
					</label>
				@endslot

				<input type="password" id="new" name="new" required>

				@slot('help')
					lorem ipsum
				@endslot

				@slot('error')
					@if ($errors->has('new'))
						{{ $errors->first('new') }}
					@endif
				@endslot
			@endinputgroup

			@inputgroup
				@slot('label')
					<label for="new_confirmation">
						Confirm Password
					</label>
				@endslot

				<input type="password" id="new_confirmation" name="new_confirmation" required>

				@slot('help')
					lorem ipsum
				@endslot

				@slot('error')
					@if ($errors->has('new_confirmation'))
						{{ $errors->first('new_confirmation') }}
					@endif
				@endslot
			@endinputgroup

			@inputgroup(['type' => 'button'])
				<button type="submit">
					Change Password
				</button>
			@endinputgroup
		</form>
	@endpanel
@endsection
