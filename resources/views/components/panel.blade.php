<div class="panel @if (! isset($title)) --no-title @endif">
	@if (isset($title))
		<div class="title">
			{{ $title }}
		</div>
	@endif

	<div class="body">
		{{ $slot }}
	</div>
</div>
