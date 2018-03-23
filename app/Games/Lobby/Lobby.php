<?php

namespace App\Games\Lobby;

use Player;
use Redis;

class Lobby
{
	public function registerPlayer($username)
	{
		return Player::register($username);
	}

	public function createLobby($user, $auth)
	{
		Player::authenticateUser($user, $auth);

		$id = $this->getId();

		Redis::zadd("lobby:{$id}:players", 1, $user);
		Redis::expire("lobby:{$id}:players", ONE_DAY);

		$lobby = [
			'lobby_id' => $id,
			'leader' => $user,
			'game' => 'lobby',
			'game_id' => null,
		];

		Redis::hmset('lobby:' . $id, $lobby);
		Redis::expire('lobby:' . $id, ONE_DAY);

		return $lobby;
	}

	public function getUsernamesInUse($id)
	{
		$players = Redis::zrange("lobby:{$id}:players", 0, -1);

		$namesInUse = [];

		foreach ($players as $player) {
			$namesInUse[] = Redis::hget('player:' . $player, 'username');
		}

		return $namesInUse;
	}

	protected function getId($iteration = 0)
	{
		$id = str_random(floor(16 + $iteration * .25));

		if (Redis::exists('lobby:' . $id)) {
			return $this->getId($iteration + 1);
		}

		Redis::hset('lobby:' . $id, 'id', $id);

		return $id;
	}
}
