<template>
	<div class="game-select">
		<h3>Select a Game</h3>

		<pg-game v-for="game in games"
			:game="game"
			:playerCount="players.length"
			:key="game.shortname"
			:isLeader="isLeader">
		</pg-game>
	</div>
</template>

<script>
	const gameSelectGame = require('./GameSelect-Game.vue')

	export default {
		components: {
			'pg-game': gameSelectGame
		},

		props: {
			players: {
				default: []
			},

			isLeader: {
				default: false
			}
		}
	}
</script>
