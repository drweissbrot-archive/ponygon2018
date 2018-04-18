<?php

namespace App\Events\Game\Lobby;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ChatMessage implements ShouldBroadcastNow
{
	use Dispatchable, InteractsWithSockets, SerializesModels;

	public $id;

	public $user;

	public $message;

	public $time;

	/**
	 * Create a new event instance.
	 *
	 * @param mixed $id
	 * @param mixed $user
	 * @param mixed $message
	 * @param mixed $time
	 */
	public function __construct($id, $user, $message, $time)
	{
		$this->id = $id;
		$this->user = $user;
		$this->message = $message;
		$this->time = $time->format('c');
	}

	/**
	 * Get the channels the event should broadcast on.
	 *
	 * @return array|\Illuminate\Broadcasting\Channel
	 */
	public function broadcastOn()
	{
		return new PresenceChannel('lobby:' . $this->id);
	}
}
