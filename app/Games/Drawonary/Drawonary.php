<?php

namespace App\Games\Drawonary;

use App\Events\Game\Lobby\GameStarted;
use App\Games\Game;
use Redis;

class Drawonary extends Game
{
	public function startGame($lobby)
	{
		$id = $this->getId();

		Redis::hmset('game:' . $id, [
			'game' => 'draw',
			'lobby_id' => $lobby,
			'deck' => 'German',
			'turn' => $this->getNextTurn($id),
			'scoreboard' => $this->generateBlankScoreboard($lobby),
			'order' => $this->getPlayerOrder($lobby),
		]);
		// Redis::expire('game:' . $id, ONE_DAY); // unnecessary

		$this->updateLobby($lobby, 'draw', $id);

		event(new GameStarted($lobby, $id, 'drawonary'));
	}

	public function getLobbyFromGameId($id)
	{
		return Redis::hget('game:' . $id, 'lobby_id');
	}

	protected function getPlayerOrder($lobby)
	{
		$players = Redis::lrange("lobby:{$lobby}:players", 0, -1);

		return implode($players, ':');
	}

	protected function generateBlankScoreboard($lobby)
	{
		$players = Redis::lrange("lobby:{$lobby}:players", 0, -1);

		$scoreboard = new Scoreboard;

		foreach ($players as $player) {
			$scoreboard->addPlayer(Redis::hgetall('player:' . $player));
		}

		return $scoreboard->toJson();
	}

	protected function getNextTurn($game, $previousPlayer = false)
	{
		//
	}
}
