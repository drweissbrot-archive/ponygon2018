<?php

namespace App\Games\ConnectFour;

use App\Events\Game\Lobby\ChatMessage;
use App\Events\Game\Lobby\GameStarted;
use App\Games\Game;
use Cache;
use Lobby;
use Redis;

class ConnectFour extends Game
{
	public function startGame($lobby)
	{
		$id = $this->getId();

		$users = Lobby::getUsers($lobby);

		if (count($users) !== 2) {
			return event(new ChatMessage($lobby, null, 'You need exactly two players to play Connect Four', now()));
		}

		$firstPlayer = array_rand($users);

		$nextPlayer = array_keys(array_filter($users, function ($key) use ($firstPlayer) {
			return $key !== $firstPlayer;
		}, \ARRAY_FILTER_USE_KEY));

		$fields = [];

		for ($i = 0; $i < 7; $i++) {
			$fields["{$i}"] = [];

			for ($j = 0; $j < 6; $j++) {
				$fields["${i}"]["{$j}"] = null;
			}
		}

		Redis::hmset('game:' . $id, [
			'id' => $id,
			'lobby_id' => $lobby,
			'game' => 'c4',
			'width' => 7,
			'height' => 6,
			'turn' => $firstPlayer,
			'nextPlayer' => reset($nextPlayer),
			'fields' => json_encode($fields),
			'lines' => json_encode($this->generateLines(7, 6, $firstPlayer, reset($nextPlayer))),
		]);

		$this->updateLobby($lobby, 'c4', $id);

		event(new GameStarted($lobby, $id, 'connectfour'));
	}

	public function analyzeLineForWin(array $lines, $user)
	{
		foreach ($lines as $line) {
			if ($line[$user] >= 4) {
				dump($line);
			}
		}
	}

	public function generateLines($width, $height, $x, $o)
	{
		$lines = Cache::rememberForever("connectfour-lines-{$width}-{$height}", function () use ($width, $height) {
			$lines = [];

			for ($column = 1; $column < $width; $column++) {
				for ($row = 1; $row < $height; $row++) {
					$neLine = $this->generateNorthEastLine($column, $row, $width, $height);

					if ($neLine) {
						$lines[] = $neLine;
					}

					$swLine = $this->generateSouthWestLine($column, $row, $width, $height);

					if ($swLine) {
						$lines[] = $swLine;
					}
				}
			}

			$lines = array_intersect_key($lines, array_unique(array_map('serialize', $lines)));

			return $this->addSimpleLines($lines, $width, $height);
		});

		foreach ($lines as &$line) {
			$line = [
				'nodes' => $line,
				$x => 0,
				$o => 0,
			];
		}

		return $lines;
	}

	protected function addSimpleLines(array $lines, $width = 7, $height = 6)
	{
		for ($column = 1; $column < $width; $column++) {
			$line = [];

			for ($row = 1; $row < $height; $row++) {
				$line[] = ['x' => $column, 'y' => $row];
			}

			$lines[] = $line;
		}

		for ($row = 1; $row < $height; $row++) {
			$line = [];

			for ($column = 1; $column < $width; $column++) {
				$line[] = ['x' => $column, 'y' => $row];
			}

			$lines[] = $line;
		}

		return $lines;
	}

	protected function generateNorthEastLine($column, $row, $width, $height)
	{
		$nodes = [
			['x' => $column, 'y' => $row],
		];

		$node = [
			'x' => $column,
			'y' => $row,
		];

		while (true) {
			$node = [
				'x' => $node['x'] + 1,
				'y' => $node['y'] - 1,
			];

			if ($node['x'] < 1 || $node['y'] < 1 || $node['x'] > $width || $node['y'] > $height) {
				break;
			}

			$nodes[] = $node;
		}

		$node = [
			'x' => $column,
			'y' => $row,
		];

		while (true) {
			$node = [
				'x' => $node['x'] - 1,
				'y' => $node['y'] + 1,
			];

			if ($node['x'] < 1 || $node['y'] < 1 || $node['x'] > $width || $node['y'] > $height) {
				break;
			}

			$nodes[] = $node;
		}

		if (count($nodes) < 4) {
			return false;
		}

		usort($nodes, function ($a, $b) {
			if ($a['x'] > $b['x']) {
				return 1;
			}

			if ($a['x'] < $b['x']) {
				return -1;
			}

			return 0;
		});

		return $nodes;
	}

	protected function generateSouthWestLine($column, $row, $width, $height)
	{
		$nodes = [
			['x' => $column, 'y' => $row],
		];

		$node = [
			'x' => $column,
			'y' => $row,
		];

		while (true) {
			$node = [
				'x' => $node['x'] - 1,
				'y' => $node['y'] - 1,
			];

			if ($node['x'] < 1 || $node['y'] < 1 || $node['x'] > $width || $node['y'] > $height) {
				break;
			}

			$nodes[] = $node;
		}

		$node = [
			'x' => $column,
			'y' => $row,
		];

		while (true) {
			$node = [
				'x' => $node['x'] + 1,
				'y' => $node['y'] + 1,
			];

			if ($node['x'] < 1 || $node['y'] < 1 || $node['x'] > $width || $node['y'] > $height) {
				break;
			}

			$nodes[] = $node;
		}

		if (count($nodes) < 4) {
			return false;
		}

		usort($nodes, function ($a, $b) {
			if ($a['x'] > $b['x']) {
				return 1;
			}

			if ($a['x'] < $b['x']) {
				return -1;
			}

			return 0;
		});

		return $nodes;
	}
}
