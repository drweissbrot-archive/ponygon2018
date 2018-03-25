<template>
	<div class="drawonary-modal">
		<h3>
			{{ user }} is selecting a word!
		</h3>
	</div>
</template>

<script>
	export default {
		props: {
			user: {
				required: true
			}
		}
	}
</script>
