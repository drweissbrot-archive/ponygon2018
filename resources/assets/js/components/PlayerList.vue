<template>
	<div class="player-list">
		<h3>Players in your Lobby</h3>

		<div class="player" :class="{'--leader': player.leader}" v-for="player in players">
			<div class="avatar">
				<img src="https://placehold.it/500x500">
			</div>

			<div class="name">
				<p>{{ player.name }}</p>

				<p class="role">
					<span v-if="player.leader">Lobby Leader</span>
					<span v-if="player.id == currentPlayer.id">you</span>
				</p>
			</div>

			<div class="actions">
				<a href="#" v-if="player.id != currentPlayer.id">
					Votekick
				</a>

				<a href="#"
					v-if="! player.leader && currentPlayer.leader"
					@click="changeLobbyLeader($event, player.id)">
					make Lobby Leader
				</a>
			</div>
		</div>
	</div>
</template>

<script>
	export default {
		props: {
			players: {
				required: true
			},

			currentPlayer: {
				default: false
			}
		},

		methods: {
			changeLobbyLeader(e, id) {
				e.preventDefault()

				this.$emit('changeLeader', id)
			}
		}
	}
</script>
