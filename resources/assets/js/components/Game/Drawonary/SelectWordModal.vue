<template>
	<div class="drawonary-modal">
		<h3>Choose a Word!</h3>

		<p class="word" v-for="word in words" @click="$emit('wordSelected', word)">
			{{ word }}
		</p>
	</div>
</template>

<script>
	export default {
		props: {
			words: {
				required: true
			}
		}
	}
</script>
