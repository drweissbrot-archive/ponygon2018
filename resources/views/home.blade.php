@extends('layouts.app')

@section('page-title')

@endsection

@section('heading')

@endsection

@section('content')
	<h3>
		<a href="{{ route('play.tic-tac-toe.index') }}">
			Play Tic Tac Toe!
		</a>
	</h3>

	<p>
		<a href="{{ route('play.tic-tac-toe.index') }}">
			Play a game of Tic Tac Toe against a friend online!
		</a>
	</p>
@endsection
