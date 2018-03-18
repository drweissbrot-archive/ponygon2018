<?php

namespace App\Events\Game\TicTacToe;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MoveMade implements ShouldBroadcastNow
{
	use Dispatchable, InteractsWithSockets, SerializesModels;

	public $id;

	public $state;

	/**
	 * Create a new event instance.
	 *
	 * @param mixed $state
	 * @param mixed $id
	 */
	public function __construct($id, $state)
	{
		$this->id = $id;
		$this->state = $state;
	}

	/**
	 * Get the channels the event should broadcast on.
	 *
	 * @return array|\Illuminate\Broadcasting\Channel
	 */
	public function broadcastOn()
	{
		return new Channel('game.ttt.' . $this->id);
	}
}
