<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class TicTacToe extends Facade
{
	protected static function getFacadeAccessor()
	{
		return 'ticTacToe';
	}
}
