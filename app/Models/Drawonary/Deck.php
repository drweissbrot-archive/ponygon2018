<?php

namespace App\Models\Drawonary;

use Illuminate\Database\Eloquent\Model;

class Deck extends Model
{
	public function words()
	{
		return $this->hasMany(Word::class);
	}
}
