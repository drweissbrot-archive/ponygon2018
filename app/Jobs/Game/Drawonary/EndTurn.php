<?php

namespace App\Jobs\Game\Drawonary;

use App\Events\Game\Drawonary\TurnEnded;
use App\Facades\Games\Drawonary;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Redis;

class EndTurn implements ShouldQueue
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
		$word = Redis::hget('game:' . $this->id, 'word');

		if ($this->word && $word !== $this->word) {
			// if current word doesn't match the word noted when the job was
			// queued, a new turn has already begun
			return;
		}

		Drawonary::givePointsToPersonDrawing($this->id);

		$addedPoints = Redis::hget('game:' . $this->id, 'roundData');

		Redis::hdel('game:' . $this->id, 'word');
		Redis::hdel('game:' . $this->id, 'roundData');

		$scoreboard = Redis::hget('game:' . $this->id, 'scoreboard');

		event(new TurnEnded($this->id, $addedPoints, $scoreboard, $word));

		StartWordSelection::dispatch($this->id)
			->delay(now()->addSeconds(5));
	}
}
