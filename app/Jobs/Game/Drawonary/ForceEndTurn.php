<?php

namespace App\Jobs\Game\Drawonary;

use App\Events\Game\Drawonary\TurnEnded;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Redis;

class ForceEndTurn implements ShouldQueue
{
	use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

	public $id;

	public $word;

	/**
	 * Create a new job instance.
	 *
	 * @param mixed $id
	 * @param mixed $word
	 */
	public function __construct($id, $word)
	{
		$this->id = $id;
		$this->word = $word;
	}

	/**
	 * Execute the job.
	 */
	public function handle()
	{
		if (Redis::hget('game:' . $this->id, 'word') !== $this->word) {
			// if current word doesn't match the word noted when the job was
			// queued, a new turn has already begun
			return;
		}

		Redis::hdel('game:' . $this->id, 'word');

		event(new TurnEnded($this->id));

		StartWordSelection::dispatch($this->id)
			->delay(now()->addSeconds(5));
	}
}
