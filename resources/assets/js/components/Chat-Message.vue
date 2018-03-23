<template>
	<div class="chat-message" :class="{'--action': isAction}">
		<div class="name"
			data-message-datetime="Mar 23 · 00:31:32">
			{{ name }}
		</div>

		<div class="message">
			{{ message }}
		</div>
	</div>
</template>

<script>
	export default {
		props: {
			name: {
				required: true
			},
			datetime: {
				default: null
			},
			message: {
				required: true
			},
			isAction: {
				default: false
			}
		}
	}
</script>
