<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
	/**
	 * Run the migrations.
	 */
	public function up()
	{
		Schema::create('users', function (Blueprint $table) {
			$table->increments('id');
			$table->timestamps();

			$table->rememberToken();

			$table->string('name');
			$table->string('address_as')->nullable();

			$table->boolean('activated')->default(false);

			$table->string('email')->unique();
			$table->string('password');
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down()
	{
		Schema::dropIfExists('users');
	}
}
