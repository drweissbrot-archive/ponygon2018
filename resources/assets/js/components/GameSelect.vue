<template>
	<div class="game-select">
		<h3>Select a Game</h3>

		<pg-game :game="games.tictactoe" :playerCount="players.length"></pg-game>
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
			}
		}
	}
</script>
