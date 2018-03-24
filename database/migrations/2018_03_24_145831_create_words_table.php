<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWordsTable extends Migration
{
	/**
	 * Run the migrations.
	 */
	public function up()
	{
		Schema::create('words', function (Blueprint $table) {
			$table->increments('id');
			$table->timestamps();

			$table->string('word');
			$table->integer('votes')->default(0);
			$table->integer('suggested')->unsigned()->default(0);
			$table->boolean('approved')->default(false);

			$table->integer('deck_id')->index()->unsigned();
			$table->foreign('deck_id')->references('id')->on('decks')->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down()
	{
		Schema::dropIfExists('words');
	}
}
