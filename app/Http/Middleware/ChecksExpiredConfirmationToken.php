<?php

namespace App\Http\Middleware;

use Closure;

class ChecksExpiredConfirmationToken
{
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next, $redirect = '/')
	{
		if ($request->confirmation_token->hasExpired()) {
			return redirect($redirect)->withError('Your confirmation link has expired. Please try again.');
		}

		return $next($request);
	}
}
