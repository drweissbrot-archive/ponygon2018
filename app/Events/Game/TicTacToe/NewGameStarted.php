<?php

namespace App\Events\Game\TicTacToe;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewGameStarted implements ShouldBroadcastNow
{
	use Dispatchable, InteractsWithSockets, SerializesModels;

	public $id;

	public $newId;

	public $byPlayer;

	/**
	 * Create a new event instance.
	 *
	 * @param mixed $id
	 * @param mixed $newId
	 * @param mixed $byPlayer
	 */
	public function __construct($id, $newId, $byPlayer)
	{
		$this->id = $id;
		$this->newId = $newId;
		$this->byPlayer = $byPlayer;
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
