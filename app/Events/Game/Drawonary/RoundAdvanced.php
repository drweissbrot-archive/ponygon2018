<?php

namespace App\Events\Game\Drawonary;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RoundAdvanced implements ShouldBroadcastNow
{
	use Dispatchable, InteractsWithSockets, SerializesModels;

	public $id;

	public $round;

	/**
	 * Create a new event instance.
	 *
	 * @param mixed $id
	 * @param mixed $round
	 */
	public function __construct($id, $round)
	{
		$this->id = $id;
		$this->round = $round;
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
