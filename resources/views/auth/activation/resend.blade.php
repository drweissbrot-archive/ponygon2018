@extends('layouts.app')

@section('page-title')
	Resend Activation Email
@endsection

@section('content')
	<form method="post" action="{{ route('activation.resend') }}">
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

		@inputgroup(['type' => 'button'])
			<button type="submit">
				Resend Activation Email
			</button>
		@endinputgroup
	</form>
@endsection
