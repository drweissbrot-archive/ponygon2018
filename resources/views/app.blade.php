@extends('layouts.app')

@section('page-title')

@endsection

@section('content')
	<div id="vue-app">
		<pg-app>
			<p>Loading...</p>
		</pg-app>
	</div>

	<script src="{{ mix('js/app.js') }}"></script>
@endsection
