<?php

namespace App\Games\Drawonary;

use App\Events\Game\Drawonary\SelectingWord;
use App\Events\Game\Drawonary\WordGuessed;
use App\Events\Game\Drawonary\WordSelected;
use App\Events\Game\Lobby\ChatMessage;
use App\Events\Game\Lobby\GameStarted;
use App\Games\Game;
use App\Jobs\Game\Drawonary\EndTurn;
use App\Jobs\Game\Drawonary\RandomizeWordSelection;
use App\Models\Drawonary\Deck;
use Illuminate\Support\Carbon;
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
			'round' => 1,
			'rounds' => 3,
			'turn' => $firstPlayer,
			'scoreboard' => $this->generateBlankScoreboard($lobby),
			'order' => $order,
			'usedWords' => null,
			'possibleWords' => null,
			'roundData' => '{}',
		]);

		$this->updateLobby($lobby, 'draw', $id);

		event(new GameStarted($lobby, $id, 'drawonary'));

		$this->generateWords($id);
	}

	public function getLobbyFromGameId($id)
	{
		return Redis::hget('game:' . $id, 'lobby_id');
	}

	public function generateWords($id)
	{
		Redis::hdel('game:' . $id, 'word');

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

		$possibleWords = implode($words->toArray(), ':');

		Redis::hmset('game:' . $id, compact('possibleWords', 'usedWords'));

		$selectEndsAt = now()->addSeconds(15);

		event(new SelectingWord($id, Redis::hget('game:' . $id, 'turn'), $selectEndsAt->format('c')));

		RandomizeWordSelection::dispatch($id, $possibleWords)
			->delay($selectEndsAt);

		return $words;
	}

	public function getGeneratedWords($id)
	{
		$words = Redis::hget('game:' . $id, 'possibleWords');
		$words = explode(':', $words);

		if (! count($words)) {
			return $this->generateWords($id);
		}

		return $words;
	}

	public function setWord($id, $word)
	{
		$turnEnd = now()->addSeconds(90)->format('c');

		Redis::hmset('game:' . $id, compact('word', 'turnEnd'));
		Redis::hdel('game:' . $id, 'possibleWords');

		event(new WordSelected($id, mb_strlen($word), $turnEnd));

		EndTurn::dispatch($id, $word)->delay($turnEnd);
	}

	public function advanceTurn($id)
	{
		$turn = Redis::hget('game:' . $id, 'turn');

		$order = Redis::hget('game:' . $id, 'order');
		$order = explode(':', $order);

		$no = array_search($turn, $order);

		if (++$no >= count($order)) {
			$rounds = Redis::hget('game:' . $id, 'rounds');
			$round = Redis::hget('game:' . $id, 'round');

			$round++;

			if ($round > $rounds) {
				throw new \Exception('game ended (not yet implemented)');
			}

			Redis::hset('game:' . $id, 'round', $round);

			$no = 0;
		}

		$nextPlayer = $order[$no];

		Redis::hset('game:' . $id, 'turn', $nextPlayer);

		return $nextPlayer;
	}

	public function analyzeChatMessage($lobbyId, $user, $message)
	{
		$gameId = Redis::hget('lobby:' . $lobbyId, 'game_id');
		$word = Redis::hget('game:' . $gameId, 'word');

		$word = trim(mb_strtolower($word));
		$message = trim(mb_strtolower($message));

		if ($word == $message) {
			return $this->guessWord($gameId, $user);
		}

		// the word is not guessed -- broadcast the message
		event(new ChatMessage($lobbyId, $user, $message, now()));

		$similarity = similar_text($word, $message);

		if ($similarity > mb_strlen($word) - 2) {
			return [
				'emitEventToParent' => true,
				'word' => $message,
				'closeGuess' => $similarity,
			];
		}
	}

	protected function endTurnIfNeeded($id)
	{
		$roundData = Redis::hget('game:' . $id, 'roundData');
		$roundData = json_decode($roundData, true);

		$players = Redis::hget('game:' . $id, 'order');
		$players = explode(':', $players);

		if (count($roundData) != count($players) - 1) {
			return false;
		}

		EndTurn::dispatchNow($id, false);
	}

	protected function getPlayerOrder($lobby)
	{
		$players = Redis::lrange("lobby:{$lobby}:players", 0, -1);

		shuffle($players);

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

	protected function guessWord($id, $user)
	{
		abort_if(Redis::hget('game:' . $id, 'turn') == $user, 403, 'You may not guess the word while drawing.');

		$roundData = json_decode(Redis::hget('game:' . $id, 'roundData') ?? '{}');
		abort_if($roundData->{$user} ?? false, 403, 'You have already guessed the word.');

		$now = now();
		$roundEnd = Redis::hget('game:' . $id, 'turnEnd');
		$roundEnd = Carbon::parse($roundEnd);

		$remainingTime = $roundEnd->diffInSeconds($now);
		$points = $remainingTime * 5 + 90;

		$roundData->{$user} = $points;
		$roundData = json_encode($roundData);

		$scoreboard = new Scoreboard;
		$scoreboard = $scoreboard->fromJson(Redis::hget('game:' . $id, 'scoreboard'))
			->addPoints($user, $points)
			->toJson();

		Redis::hmset('game:' . $id, compact('scoreboard', 'roundData'));

		$this->endTurnIfNeeded($id);

		return event(new WordGuessed($id, $user, $now, $scoreboard));
	}
}
