<?php

namespace App\Listeners\Game\Lobby;

use Redis;

class NotifyGamesOfUserJoined
{
	// Games that have to be informed about joining users
	protected $games = [
		'draw',
	];

	/**
	 * Handle the event.
	 *
	 * @param object $event
	 */
	public function handle($event)
	{
		// $event->lobbyId, name, id

		// find out if a game is running
		$game = Redis::hget('lobby:' . $event->lobbyId, 'game');

		if ($game == 'lobby' || ! in_array($game, $this->games)) {
			return;
		}

		$method = 'inform' . ucwords($game, '_');

		$this->{$method}($event);
	}

	protected function informDraw($event)
	{
		$gameId = Redis::hget('lobby:' . $event->lobbyId, 'game_id');

		$order = Redis::hget('game:' . $gameId, 'order');
		$order .= ':' . $event->id;
		Redis::hset('game:' . $gameId, 'order', $order);
	}
}
