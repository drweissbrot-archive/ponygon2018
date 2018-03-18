@if (session()->has('success'))
	@component('components.flash', ['type' => 'success'])
		{{ session('success') }}
	@endcomponent
@endif

@if (session()->has('error'))
	@component('components.flash', ['type' => 'error'])
		{{ session('error') }}
	@endcomponent
@endif
