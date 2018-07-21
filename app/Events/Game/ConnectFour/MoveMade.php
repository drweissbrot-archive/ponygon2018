<?php

namespace App\Events\Game\ConnectFour;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MoveMade implements ShouldBroadcastNow
{
	use Dispatchable, InteractsWithSockets, SerializesModels;

	public $id;

	public $nextPlayer;

	public $fields;

	public $row;

	/**
	 * Create a new event instance.
	 */
	public function __construct($id, $nextPlayer, $fields, $row)
	{
		$this->id = $id;
		$this->nextPlayer = $nextPlayer;
		$this->fields = $fields;
		$this->row = $row;
	}

	/**
	 * Get the channels the event should broadcast on.
	 *
	 * @return array|\Illuminate\Broadcasting\Channel
	 */
	public function broadcastOn()
	{
		return new Channel('game:' . $this->id);
	}
}
