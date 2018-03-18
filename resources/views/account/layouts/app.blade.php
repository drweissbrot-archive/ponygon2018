@extends('layouts.app')

@section('hideFlashes')
	true
@endsection

@section('content')
	<div class="grid --one-four-fifths">
		@include('account.layouts.partials.nav')

		<div>
			@include('layouts.partials.flashes')

			@yield('account.content')
		</div>
	</div>
@endsection
