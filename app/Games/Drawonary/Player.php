<?php

namespace App\Games\Drawonary;

class Player
{
	public $name;

	public $id;

	public $points;

	public $place;

	public function __construct($name, $id, $points = 0)
	{
		$this->name = $name;
		$this->id = $id;
		$this->points = $points;
	}
}
