<?php

namespace App\Games\Lobby;

use App\Events\Game\Lobby\UserJoined;
use Player;
use Redis;

class Lobby
{
	public function createLobby($user, $auth)
	{
		Player::authenticate($user, $auth);

		$id = $this->getId();

		Redis::rpush("lobby:{$id}:players", $user);
		Redis::expire("lobby:{$id}:players", ONE_DAY);

		$lobby = [
			'id' => $id,
			'leader' => $user,
			'game' => 'lobby',
			'game_id' => null,
		];

		Redis::hmset('lobby:' . $id, $lobby);
		Redis::expire('lobby:' . $id, ONE_DAY);

		return $lobby;
	}

	public function join($id, $user)
	{
		Redis::rpush("lobby:{$id}:players", $user);

		$name = Redis::hget('player:' . $user, 'name');

		event(new UserJoined($id, $name, $user));
	}

	public function getUsers($id, $markLeader = false)
	{
		$players = Redis::lrange("lobby:{$id}:players", 0, -1);

		$users = [];

		foreach ($players as $player) {
			$user = Redis::hgetall('player:' . $player);

			$users[$user['id']] = [
				'id' => $user['id'],
				'name' => $user['name'],
			];
		}

		if ($markLeader) {
			return $this->markLeaderInPlayers($id, $users);
		}

		return $users;
	}

	public function getNamesInUse($id)
	{
		return collect($this->getUsers($id))->pluck('name');
	}

	public function verifyPlayerIsLobbyMember($id, $user, $auth, $abort = true)
	{
		Player::authenticate($user, $auth);

		if (! Redis::lrem("lobby:{$id}:players", 1, $user)) {
			if ($abort) {
				abort(403, 'You are not a member of this lobby.');
			}

			return false;
		}

		Redis::rpush("lobby:{$id}:players", $user);

		return true;
	}

	public function verifyPlayerIsLobbyLeader($id, $user, $auth)
	{
		Player::authenticate($user, $auth);

		abort_unless(Redis::hget('lobby:' . $id, 'leader') === $user, 403, 'You are not the lobby leader.');
	}

	protected function markLeaderInPlayers($id, $users)
	{
		$leader = Redis::hget('lobby:' . $id, 'leader');

		$users[$leader]['leader'] = true;

		return $users;
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
