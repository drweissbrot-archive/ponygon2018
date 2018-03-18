<?php

namespace App\Games\TicTacToe;

use Redis;

class TicTacToe
{
	protected $winningPositions = [
		['a1', 'a2', 'a3'],
		['b1', 'b2', 'b3'],
		['c1', 'c2', 'c3'],

		['a1', 'b1', 'c1'],
		['a2', 'b2', 'c2'],
		['a3', 'b3', 'c3'],

		['a1', 'b2', 'c3'],
		['a3', 'b2', 'c1'],
	];

	protected $fields = [
		'a1', 'a2', 'a3', 'b1', 'b2', 'b3', 'c1', 'c2', 'c3',
	];

	public function initializeGame($id, $player = 'X')
	{
		Redis::hmset('game:ttt:' . $id, [
			'state' => 'X',

			'authX' => str_random(255),
			'authO' => str_random(255),

			'xRegistered' => ($player == 'X'),
			'oRegistered' => ($player == 'O'),

			'a1' => null,
			'a2' => null,
			'a3' => null,

			'b1' => null,
			'b2' => null,
			'b3' => null,

			'c1' => null,
			'c2' => null,
			'c3' => null,
		]);

		Redis::expire('game:ttt:' . $id, 3600);
	}

	public function makeMove($id, $player, $move)
	{
		abort_if($this->isInvalidMove($move), 400, 'Invalid move.');
		abort_if($this->isAlreadyOccupied($id, $move), 400, 'Node already occupied.');

		Redis::hset('game:ttt:' . $id, $move, $player);
		Redis::hset('game:ttt:' . $id, 'state', ($player === 'X') ? 'O' : 'X');

		return $this->analyzeState($id);
	}

	public function getNodes($id)
	{
		$state = Redis::hgetall('game:ttt:' . $id);
		$nodes = [];

		foreach ($this->fields as $field) {
			$nodes[$field] = $state[$field];
		}

		return $nodes;
	}

	public function getClientState($id)
	{
		$state = $this->getNodes($id);

		$state['turn'] = Redis::hget('game:ttt:' . $id, 'state');

		return $state;
	}

	protected function analyzeState($id)
	{
		$state = Redis::hgetall('game:ttt:' . $id);

		$winner = $this->isGameWon($state);

		if ($winner) {
			return Redis::hset('game:ttt:' . $id, 'state', 'win' . $winner);
		}

		$tied = $this->isGameTied($state);

		if ($tied) {
			return Redis::hset('game:ttt:' . $id, 'state', 'tied');
		}
	}

	protected function isGameWon($state)
	{
		foreach ($this->winningPositions as $position) {
			if ($state[$position[0]]
				&& $state[$position[0]] == $state[$position[1]]
				&& $state[$position[0]] == $state[$position[2]]) {
				return $state[$position[0]];
			}
		}

		return false;
	}

	protected function isGameTied($state)
	{
		$i = 0;

		foreach ($this->fields as $field) {
			if ($state[$field]) {
				$i++;
			}
		}

		return $i > 8;
	}

	protected function isInvalidMove($move)
	{
		return ! in_array($move, [
			'a1', 'a2', 'a3', 'b1', 'b2', 'b3', 'c1', 'c2', 'c3',
		]);
	}

	protected function isAlreadyOccupied($id, $move)
	{
		return Redis::hget('game:ttt:' . $id, $move);
	}
}
