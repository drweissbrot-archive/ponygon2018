@extends('account.layouts.app')

@section('page-title')
	Your Profile · Account Settings
@endsection

@section('heading')
	<h1>
		Account Settings
	</h1>
@endsection

@section('account.content')
	@panel
		@slot('title')
			<h3>Your Profile</h3>
		@endslot

		<form method="post" action="{{ route('account.profile') }}">
			@csrf

			@inputgroup
				@slot('label')
					<label for="name">
						Name
					</label>
				@endslot

				<input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required>

				@slot('help')
					lorem ipsum
				@endslot

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

				<input type="text" id="address_as" name="address_as" value="{{ old('address_as', $user->address_as) }}">

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
						Email
					</label>
				@endslot

				<input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required>

				@slot('help')
					lorem ipsum
				@endslot

				@slot('error')
					@if ($errors->has('email'))
						{{ $errors->first('email') }}
					@endif
				@endslot
			@endinputgroup

			@inputgroup(['type' => 'button'])
				<button type="submit">
					Save Changes
				</button>
			@endinputgroup
		</form>
	@endpanel
@endsection
