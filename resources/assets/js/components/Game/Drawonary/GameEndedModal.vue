<template>
	<div class="drawonary-modal">
		<h3>Game finished!</h3>

		<p v-for="player in players">
			<strong>
				#{{ player.place }}
				{{ player.name }}
			</strong>:
			{{ player.points }} pts
		</p>

		<router-link :to="{ name: 'lobby', params: {lobby}}">back to Lobby</router-link>
	</div>
</template>

<script>
	export default {
		props: {
			players: {
				required: true
			},

			lobby: {
				required: true
			}
		}
	}
</script>
