<nav class="--full-width">
	<ul>
		<li>
			<a href="{{ route('account.index') }}" class="{{ active_page('account.index') }}">
				Account Overview
			</a>
		</li>

		<li>
			<a href="{{ route('account.profile') }}" class="{{ active_page('account.profile') }}">
				Profile
			</a>
		</li>

		<li>
			<a href="{{ route('account.password') }}" class="{{ active_page('account.password') }}">
				Change Password
			</a>
		</li>

		<li>
			<a href="{{ route('account.delete') }}" class="{{ active_page('account.delete') }}">
				Delete Account
			</a>
		</li>
	</ul>
</nav>
