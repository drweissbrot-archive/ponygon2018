<?php

namespace App\Games;

use Redis;

abstract class Game
{
	abstract public function startGame($lobby);

	public function getLobbyFromGameId($id)
	{
		return Redis::hget('game:' . $id, 'lobby_id');
	}

	protected function getId($iteration = 0)
	{
		$id = str_random(16 + floor($iteration * .25));

		if (Redis::exists('game:' . $id)) {
			return $this->getId($iteration + 1);
		}

		Redis::hset('game:' . $id, 'id', $id);
		Redis::expire('game:' . $id, ONE_DAY);

		return $id;
	}

	protected function updateLobby($lobby, $game, $id)
	{
		Redis::hmset('lobby:' . $lobby, [
			'game' => $game,
			'game_id' => $id,
		]);
		Redis::expire('lobby:' . $lobby, ONE_DAY);
	}
}
