<?php

use App\Models\Drawonary\Deck;
use App\Models\Drawonary\Word;
use Illuminate\Database\Seeder;

class DrawonaryEnglishSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 */
	public function run()
	{
		$deck = new Deck;
		$deck->name = 'English';
		$deck->save();

		$words = file(__DIR__ . '/words/English.txt');

		foreach ($words as $word) {
			$entity = new Word;
			$entity->word = $word;
			$entity->deck_id = $deck->id;
			$entity->approved = true;
			$entity->save();
		}
	}
}
