<?php

namespace App\Rules;

use Auth;
use Illuminate\Contracts\Validation\Rule;

class IsCurrentUsersEmail implements Rule
{
	/**
	 * Determine if the validation rule passes.
	 *
	 * @param  string  $attribute
	 * @param  mixed  $value
	 * @return bool
	 */
	public function passes($attribute, $value)
	{
		return (Auth::user()->email === $value);
	}

	/**
	 * Get the validation error message.
	 *
	 * @return string
	 */
	public function message()
	{
		return 'This is not your email address.';
	}
}
