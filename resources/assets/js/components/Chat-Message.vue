<template>
	<div class="chat-message" :class="{'--action': isAction}">
		<div class="name" :data-message-datetime="time">
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

			time: {
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
