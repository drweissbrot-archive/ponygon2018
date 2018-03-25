<?php

namespace App\Games\Drawonary;

use App\Events\Game\Drawonary\SelectingWord;
use App\Events\Game\Drawonary\WordSelected;
use App\Events\Game\Lobby\GameStarted;
use App\Games\Game;
use App\Models\Drawonary\Deck;
use Redis;

class Drawonary extends Game
{
	public function startGame($lobby)
	{
		$id = $this->getId();

		$order = $this->getPlayerOrder($lobby);
		$firstPlayer = explode(':', $order)[0];

		Redis::hmset('game:' . $id, [
			'game' => 'draw',
			'lobby_id' => $lobby,
			'deck' => 'German',
			'turn' => $firstPlayer,
			'scoreboard' => $this->generateBlankScoreboard($lobby),
			'order' => $order,
			'usedWords' => null,
			'possibleWords' => null,
		]);
		// Redis::expire('game:' . $id, ONE_DAY); // unnecessary

		$this->updateLobby($lobby, 'draw', $id);

		event(new GameStarted($lobby, $id, 'drawonary'));
		event(new SelectingWord($id, $firstPlayer));
	}

	public function getLobbyFromGameId($id)
	{
		return Redis::hget('game:' . $id, 'lobby_id');
	}

	public function generateWords($id)
	{
		$deck = Redis::hget('game:' . $id, 'deck');
		$usedWords = Redis::hget('game:' . $id, 'usedWords');
		$usedWords = explode(':', $usedWords);

		$deck = Deck::find($deck);
		$words = $deck->words()->inRandomOrder()
			->whereNotIn('word', $usedWords)
			->take(3)
			->get()
			->pluck('word');

		$usedWords = array_merge($usedWords, $words->toArray());
		$usedWords = implode($usedWords, ':');

		Redis::hmset('game:' . $id, [
			'possibleWords' => implode($words->toArray(), ':'),
			'usedWords' => $usedWords,
		]);

		return $words;
	}

	public function setWord($id, $word)
	{
		$turnEnd = now()->addSeconds(90)->format('c');

		Redis::hmset('game:' . $id, compact('word', 'turnEnd'));

		event(new WordSelected($id, mb_strlen($word), $turnEnd));
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
}
