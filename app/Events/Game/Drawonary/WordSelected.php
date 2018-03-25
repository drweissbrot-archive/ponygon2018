<?php

namespace App\Events\Game\Drawonary;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class WordSelected implements ShouldBroadcastNow
{
	use Dispatchable, InteractsWithSockets, SerializesModels;

	public $id;

	public $wordLength;

	public $turnEndsAt;

	/**
	 * Create a new event instance.
	 *
	 * @param mixed $id
	 * @param mixed $wordLength
	 * @param mixed $turnEndsAt
	 */
	public function __construct($id, $wordLength, $turnEndsAt)
	{
		$this->id = $id;
		$this->wordLength = $wordLength;
		$this->turnEndsAt = $turnEndsAt;
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
