<?php

namespace App\Events\Game\Drawonary;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class WordGuessed implements ShouldBroadcastNow
{
	use Dispatchable, InteractsWithSockets, SerializesModels;

	public $id;

	public $user;

	public $time;

	public $scoreboard;

	/**
	 * Create a new event instance.
	 *
	 * @param mixed $id
	 * @param mixed $user
	 * @param mixed $scoreboard
	 * @param mixed $time
	 */
	public function __construct($id, $user, $time, $scoreboard)
	{
		$this->id = $id;
		$this->user = $user;
		$this->time = $time;
		$this->scoreboard = $scoreboard;
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
