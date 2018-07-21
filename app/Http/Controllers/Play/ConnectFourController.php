<?php

namespace App\Http\Controllers\Play;

use App\Events\Game\ConnectFour\MoveMade;
use App\Facades\Games\ConnectFour;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Lobby;
use Redis;

class ConnectFourController extends Controller
{
	public function status(Request $request, $id)
	{
		$lobby = ConnectFour::getLobbyFromGameId($id);
		$user = $request->user;
		$auth = $request->auth;

		Lobby::verifyPlayerIsLobbyMember($lobby, $user, $auth);

		$data = Redis::hgetall('game:' . $id);

		return [
			'id' => $data['id'],
			'lobby_id' => $data['lobby_id'],
			'game' => $data['game'],
			'width' => $data['width'],
			'height' => $data['height'],
			'turn' => $data['turn'],
			'fields' => $data['fields'],
		];
	}

	public function makeMove(Request $request, $id)
	{
		$lobby = ConnectFour::getLobbyFromGameId($id);
		$user = $request->user;
		$auth = $request->auth;

		Lobby::verifyPlayerIsLobbyMember($lobby, $user, $auth);

		$turn = Redis::hget('game:' . $id, 'turn');

		abort_unless($turn === $user, 403, 'It is not your turn.');

		$fields = json_decode(Redis::hget('game:' . $id, 'fields'), true);

		$width = Redis::hget('game:' . $id, 'width');
		$height = Redis::hget('game:' . $id, 'height');

		$column = $request->column;

		$i = 0;

		for ($i = $height - 1; $i >= 0; $i--) {
			if ($fields[$column][$i]) {
				abort_if($i === 0, 400, 'Column already full.');

				continue;
			}

			$fields[$column][$i] = $turn;
			break;
		}

		$lines = json_decode(Redis::hget('game:' . $id, 'lines'), true);

		foreach ($lines as &$line) {
			$position = array_search(['x' => $column, 'y' => $i], $line['nodes']);

			if ($position !== false) {
				$line[$user]++;
				$line['nodes'][$position]['user'] = $user;
			}
		}

		Redis::hset('game:' . $id, 'lines', json_encode($lines));

		$nextPlayer = Redis::hget('game:' . $id, 'nextPlayer');

		Redis::hmset('game:' . $id, [
			'turn' => $nextPlayer,
			'nextPlayer' => $turn,
			'fields' => json_encode($fields),
		]);

		event(new MoveMade($id, $nextPlayer, $fields, $i));

		ConnectFour::analyzeLineForWin($lines, $user);
	}
}
