<?php

namespace App\Jobs\Game\Drawonary;

use App\Events\Game\Drawonary\ShowLetter as ShowLetterEvent;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Redis;

class ShowLetter implements ShouldQueue
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
			return; // not the same turn anymore
		}

		$length = mb_strlen($this->word);

		$position = random_int(0, $length);

		event(new ShowLetterEvent($this->id, $position, mb_substr($this->word, $position, 1)));
	}
}
