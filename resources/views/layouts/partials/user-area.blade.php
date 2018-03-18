<nav class="user-area --horizontal">
	<ul>
		@guest
			<li>
				<a href="{{ route('login') }}">Sign in</a>
			</li>

			<li>
				<a href="{{ route('register') }}">Sign up</a>
			</li>
		@else
			<li class="--empty">
				Hi, {{ Auth::user()->addressAs() }}!
			</li>

			<li>
				<a href="{{ route('dashboard') }}">
					Dashboard
				</a>
			</li>

			<li>
				<a href="{{ route('account.index') }}">Manage my Account</a>
			</li>

			<li>
				<a href="{{ route('logout') }}" onclick="event.preventDefault();document.querySelector('#logout-form').submit()">
					Sign out
				</a>
			</li>

			<form id="logout-form" action="{{ route('logout') }}" method="post" style="display:none">
				{{ csrf_field() }}
			</form>
		@endguest
	</ul>
</nav>
