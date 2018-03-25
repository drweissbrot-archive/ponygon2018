<?php

namespace App\Http\Controllers\Play;

use App\Facades\Games\Drawonary;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Lobby;
use Player;
use Redis;

class DrawonaryController extends Controller
{
	public function status(Request $request, $id)
	{
		$lobby = Drawonary::getLobbyFromGameId($id);
		$user = $request->user;
		$auth = $request->auth;

		Lobby::verifyPlayerIsLobbyMember($lobby, $user, $auth);

		$data = Redis::hgetall('game:' . $id);

		return [
			'id' => $data['id'],
			'lobby_id' => $data['lobby_id'],
			'deck' => $data['deck'],
			'turn' => $data['turn'],
			'scoreboard' => $data['scoreboard'],
			'order' => $data['order'],
		];
	}

	public function getWords(Request $request, $id)
	{
		$user = $request->user;
		$auth = $request->auth;

		Player::authenticate($user, $auth);

		abort_unless(Redis::hget('game:' . $id, 'turn') === $user, 403, 'It is not your turn.');

		$words = Drawonary::getGeneratedWords($id);

		return compact('words');
	}

	public function selectWord(Request $request, $id)
	{
		$user = $request->user;
		$auth = $request->auth;
		$word = $request->word;

		Player::authenticate($user, $auth);

		abort_unless(Redis::hget('game:' . $id, 'turn') === $user, 403, 'It is not your turn.');
		abort_if(Redis::hget('game:' . $id, 'word'), 403, 'A word has already been selected.');

		$possibleWords = Redis::hget('game:' . $id, 'possibleWords');
		$possibleWords = explode(':', $possibleWords);

		abort_unless(in_array($word, $possibleWords), 403, 'Not a possible word.');

		Drawonary::setWord($id, $word);
	}
}
