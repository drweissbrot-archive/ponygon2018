<?php

namespace App\Jobs\Game\Drawonary;

use App\Facades\Games\Drawonary;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class StartWordSelection implements ShouldQueue
{
	use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

	public $id;

	/**
	 * Create a new job instance.
	 *
	 * @param mixed $id
	 */
	public function __construct($id)
	{
		$this->id = $id;
	}

	/**
	 * Execute the job.
	 */
	public function handle()
	{
		$matchStillOngoing = Drawonary::advanceTurn($this->id);

		if (! $matchStillOngoing) {
			return; // match has ended
		}

		Drawonary::generateWords($this->id);
	}
}
