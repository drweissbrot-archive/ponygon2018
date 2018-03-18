<?php

namespace App\Http\Controllers\Play;

use App\Events\Game\TicTacToe\MoveMade;
use App\Events\Game\TicTacToe\NewGameStarted;
use App\Events\Game\TicTacToe\UserRegistered;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Redis;
use TicTacToe;

class TicTacToeController extends Controller
{
	public function index()
	{
		return view('play.tic-tac-toe.index');
	}

	public function startGame()
	{
		$id = $this->getGameId();

		TicTacToe::initializeGame($id);

		return [
			'game' => 'tic-tac-toe',
			'game_id' => $id,
			'auth' => Redis::hget('game:ttt:' . $id, 'authX'),
		];
	}

	public function startNewGame(Request $request, $id)
	{
		// TODO prevent if game not ended
		$auth = $request->auth;
		$player = $request->player;

		$this->authorizeRequest($id, $auth, $player);

		$newPlayer = ($player == 'X') ? 'O' : 'X';

		$newId = $this->getGameId();

		TicTacToe::initializeGame($newId, $newPlayer);

		event(new NewGameStarted($id, $newId, $player));

		return [
			'game' => 'tic-tac-toe',
			'game_id' => $newId,
			'auth' => Redis::hget('game:ttt:' . $newId, 'auth' . $newPlayer),
		];
	}

	public function registerForGame($id, $player)
	{
		$lowercasePlayer = mb_strtolower($player);

		abort_if(Redis::hget('game:ttt:' . $id, $lowercasePlayer . 'Registered'), 403);
		abort_if(($player !== 'X' && $player !== 'O'), 400);

		Redis::hset('game:ttt:' . $id, $lowercasePlayer . 'Registered', true);

		event(new UserRegistered($id, Redis::hget('game:ttt:' . $id, 'state')));

		return [
			'game' => 'tic-tac-toe',
			'game_id' => $id,
			'auth' => Redis::hget('game:ttt:' . $id, 'auth' . $player),
		];
	}

	public function makeMove(Request $request, $id)
	{
		$auth = $request->auth;
		$player = $request->player;
		$move = $request->move;

		$this->authorizeMove($id, $auth, $player);

		TicTacToe::makeMove($id, $player, $move);

		event(new MoveMade($id, TicTacToe::getClientState($id)));
	}

	protected function getGameId()
	{
		$id = str_random(16);

		if (Redis::exists('game:ttt:' . $id)) {
			return $this->getGameId();
		}

		Redis::hset('game:ttt:' . $id, 'state', null);

		return $id;
	}

	protected function authorizeRequest($id, $auth, $player, $turn = false)
	{
		if ($player !== 'X' && $player !== 'O') {
			abort(400);
		}

		$game = Redis::hgetall('game:ttt:' . $id);

		if ($turn && $game['state'] !== $player) {
			abort(403, 'It is not your turn.');

			dd('asdf');
		}

		if (! $game['oRegistered'] || ! $game['xRegistered']) {
			abort(401, 'Second Player has not yet registered.');
		}

		if ($game['auth' . $player] !== $auth) {
			abort(403, 'You have not provided a valid auth token.');
		}
	}

	protected function authorizeMove($id, $auth, $player)
	{
		return $this->authorizeRequest($id, $auth, $player, true);
	}
}
