<?php

namespace App\Jobs\Game\Drawonary;

use App\Facades\Games\Drawonary;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Redis;

class RandomizeWordSelection implements ShouldQueue
{
	use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

	public $id;

	public $possibleWords;

	/**
	 * Create a new job instance.
	 *
	 * @param mixed $id
	 * @param mixed $turn
	 * @param mixed $possibleWords
	 */
	public function __construct($id, $possibleWords)
	{
		$this->id = $id;
		$this->possibleWords = $possibleWords;
	}

	/**
	 * Execute the job.
	 */
	public function handle()
	{
		$possibleWords = Redis::hget('game:' . $this->id, 'possibleWords');

		if ($possibleWords != $this->possibleWords) {
			// if the possible words retrieved now either don't exist, or don't
			// match with the ones stored when dispaching the job, words have
			// already been selected -- don't choose a word in that case
			return;
		}

		$possibleWords = explode(':', $possibleWords);

		$word = array_random($possibleWords);

		Drawonary::setWord($this->id, $word);
	}
}
