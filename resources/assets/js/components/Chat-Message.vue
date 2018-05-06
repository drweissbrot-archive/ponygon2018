<template>
	<div class="chat-message" :class="{'--action': isAction, '--spacer': spacer}">
		<div class="name" :data-message-datetime="time" v-if="! spacer">
			{{ name }}
		</div>

		<div class="message" v-if="! spacer">
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
			},

			spacer: {
				default: false
			}
		}
	}
</script>
