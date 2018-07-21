<?php

namespace App\Games;

use App\Facades\Games\Drawonary;
use App\Facades\Games\ConnectFour;

class Ponygon
{
	protected $games = [
		'draw' => Drawonary::class,
		'c4' => ConnectFour::class,
	];

	public function startGame($game, $lobby)
	{
		$this->verifyGameExists($game);

		$this->games[$game]::startGame($lobby);
	}

	public function analyzeChatMessage($game, $id, $user, $message)
	{
		if (! $this->gameAnalyzesChatMessages($game)) {
			return false;
		}

		return $this->games[$game]::analyzeChatMessage($id, $user, $message);
	}

	public function gameAnalyzesChatMessages($game)
	{
		return in_array($game, [
			'draw',
		]);
	}

	protected function verifyGameExists($game)
	{
		abort_unless(array_key_exists($game, $this->games), 400, 'Game does not exist.');
	}
}
