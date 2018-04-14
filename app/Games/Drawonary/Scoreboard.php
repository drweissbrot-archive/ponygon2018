<?php

namespace App\Games\Drawonary;

class Scoreboard
{
	protected $players;

	public function __construct()
	{
		$this->players = collect();
	}

	public function addPlayer($player)
	{
		$name = $player['name'];
		$id = $player['id'];
		$points = $player['points'] ?? 0;
		$place = $player['place'] ?? null;

		$this->players[] = new Player($name, $id, $points, $place);

		return $this;
	}

	public function addPoints($id, $points)
	{
		$this->players->map(function ($player) use ($id, $points) {
			if ($player->id != $id) {
				return;
			}

			$player->points += $points;

			return $player;
		});

		$this->updatePlacements();

		return $this;
	}

	public function updatePlacements()
	{
		$this->players = $this->players->sortByDesc(function ($player) {
			return $player->points;
		})->values();

		$place = 1;
		$points = null;

		$this->players->map(function ($player, $key) use (&$place, &$points) {
			if ($points == null) {
				$player->place = $place;
				$points = $player->points;

				return $player;
			}

			if ($player->points == $points) {
				$player->place = $place;

				return $player;
			}

			$player->place = ++$place;
			$points = $player->points;

			return $player;
		});

		return $this;
	}

	public function toJson()
	{
		return $this->players->toJson();
	}

	public function fromJson($json)
	{
		$players = json_decode($json, true);

		foreach ($players as $player) {
			$this->addPlayer($player);
		}

		return $this;
	}
}
