<?php

namespace App\Games;

use App\Facades\Games\Drawonary;

class Ponygon
{
	protected $games = [
		'draw' => Drawonary::class,
	];

	public function startGame($game, $lobby)
	{
		$this->verifyGameExists($game);

		$this->games[$game]::startGame($lobby);
	}

	protected function verifyGameExists($game)
	{
		abort_unless(array_key_exists($game, $this->games), 400, 'Game does not exist.');
	}
}
