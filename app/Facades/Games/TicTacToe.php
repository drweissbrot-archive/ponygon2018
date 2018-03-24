<?php

namespace App\Facades\Games;

use Illuminate\Support\Facades\Facade;

class TicTacToe extends Facade
{
	protected static function getFacadeAccessor()
	{
		return 'ticTacToe';
	}
}
