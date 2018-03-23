<?php

namespace App\Games\Lobby;

use Redis;

class Player
{
	public function register($username)
	{
		$username = ($username) ?: 'some random username'; // TODO generate random usernames

		$id = $this->getId();

		$auth = str_random(128);

		Redis::hmset('player:' . $id, compact('id', 'auth', 'username'));

		Redis::expire('player:' . $id, ONE_DAY);

		return compact('id', 'auth', 'username');
	}

	public function authenticateUser($user, $auth)
	{
		abort_unless(Redis::hget('player:' . $user, 'auth') === $auth, 403, 'Invalid auth code.');
	}

	protected function getId($iteration = 0)
	{
		$id = str_random(floor(16 + $iteration * .25));

		if (Redis::exists('player:' . $id)) {
			return $this->getId($iteration + 1);
		}

		Redis::hset('player:' . $id, 'id', $id);

		return $id;
	}
}
