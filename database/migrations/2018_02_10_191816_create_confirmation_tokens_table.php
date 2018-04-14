<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConfirmationTokensTable extends Migration
{
	/**
	 * Run the migrations.
	 */
	public function up()
	{
		Schema::create('confirmation_tokens', function (Blueprint $table) {
			$table->increments('id');
			$table->timestamps();

			$table->string('token');
			$table->timestamp('expires_at')->nullable();

			$table->integer('user_id')->index()->unsigned();
			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down()
	{
		Schema::dropIfExists('confirmation_tokens');
	}
}
