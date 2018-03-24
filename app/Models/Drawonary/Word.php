<?php

namespace App\Models\Drawonary;

use Illuminate\Database\Eloquent\Model;

class Word extends Model
{
	public function deck()
	{
		return $this->belongsTo(Deck::class);
	}
}
