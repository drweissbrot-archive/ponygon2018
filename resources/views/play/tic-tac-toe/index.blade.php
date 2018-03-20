@extends('layouts.app')

@section('page-title')
	Play Tic Tac Toe
@endsection

@section('game')
	tic-tac-toe
@endsection

@section('content')
	<div class="game">
		<h3 id="state">
			Loading...
		</h3>

		<h4 class="player-area">
			Playing as <strong id="player">X</strong>
		</h4>

		<p id="link-area" data-base="{{ route('play.tic-tac-toe.index') }}">
			Send this link to your friend:
			<a href="#">
				(one moment, we're getting set up...)
			</a>
		</p>

		<p class="new-game">
			Want to play again?
			<a href="#" data-action="new-game">Start a new game</a>
		</p>

		<div class="board">
			<div class="node" data-node="a1"></div>
			<div class="node" data-node="a2"></div>
			<div class="node" data-node="a3"></div>

			<div class="node" data-node="b1"></div>
			<div class="node" data-node="b2"></div>
			<div class="node" data-node="b3"></div>

			<div class="node" data-node="c1"></div>
			<div class="node" data-node="c2"></div>
			<div class="node" data-node="c3"></div>
		</div>
	</div>

	<script src="{{ mix('js/tic-tac-toe.js') }}"></script>
@endsection
