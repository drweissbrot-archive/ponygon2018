<template>
	<div class="chat">
		<h3>Chat</h3>

		<div class="wrap">
			<div class="message-history">
				<pg-chat-message name="drweissbrot" message="created the lobby" isAction="true"></pg-chat-message>
				<pg-chat-message name="someone" message="joined" isAction="true"></pg-chat-message>
				<pg-chat-message name="drweissbrot" message="Hi there!"></pg-chat-message>
				<pg-chat-message name="somebody" message="joined" isAction="true"></pg-chat-message>
				<pg-chat-message name="drweissbrot" message="Hi there!"></pg-chat-message>
			</div>

			<div class="send-message">
				<input type="text" placeholder="Type a message...">
			</div>
		</div>
	</div>
</template>

<script>
	const chatMessage = require('./Chat-Message.vue')

	export default {
		components: {
			'pg-chat-message': chatMessage
		}
	}
</script>
