<template>
	<div :class="'game --' + game.shortname">
		<div class="meta">
			<h3>{{ game.title }}</h3>

			<p v-if="game.description">
				{{ game.description }}
			</p>

			<p v-if="game.maxPlayers && playerCount > game.maxPlayers">
				Note: You can only play this game with two players.
				All other players will spectate the game.
			</p>
		</div>

		<button>Play</button>
	</div>
</template>

<script>
	export default {
		props: {
			game: {
				required: true
			},
			playerCount: {
				default: 1
			}
		}
	}
</script>
