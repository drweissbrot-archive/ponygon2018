<div class="input-group @if (isset($type)) --{{ $type }} @endif">
	<div class="label">
		{{ $label ?? '' }}
	</div>

	<div class="input">
		{{ $slot }}
	</div>

	<div class="help">
		{{ $help ?? '' }}

		@if (isset($error))
			<div class="error">
				{{ $error }}
			</div>
		@endif
	</div>
</div>
