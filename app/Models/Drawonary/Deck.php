<?php

namespace App\Models\Drawonary;

use Illuminate\Database\Eloquent\Model;

class Deck extends Model
{
	public static function find($name)
	{
		return static::where('name', $name)->first();
	}

	public function words()
	{
		return $this->hasMany(Word::class);
	}
}
