<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title>
		@hasSection('page-title')
			@yield('page-title') ·
		@endif
		{{ config('app.name') }}
	</title>

	<meta name="csrf-token" content="{{ csrf_token() }}">

	<link rel="stylesheet" href="{{ mix('css/app.css') }}">
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,300i,400,400i,600,600i,700,700i,900,900i">
</head>
<body @hasSection('game') class="@yield('game')" @endif>
	<header class="navbar">
		<div class="logo">
			<a href="{{ route('home') }}">
				{{ config('app.name') }}
			</a>
		</div>

		@include('layouts.partials.user-area')
	</header>

	<main>
		@hasSection('hideFlashes')
		@else
			@include('layouts.partials.flashes')
		@endif

		@hasSection('heading')
			@yield('heading')
		@else
			@hasSection('page-title')
				<h1>
					@yield('page-title')
				</h1>
			@endif
		@endif

		@yield('content')
	</main>

	<footer>
		© 2018 Ponygon developers ·
		<a href="https://github.com/drweissbrot/ponygon/blob/master/license.md" target="_blank">MIT licensed</a> ·
		<a href="https://github.com/drweissbrot/ponygon" target="_blank">Ponygon on GitHub</a>
	</footer>
</body>
</html>
