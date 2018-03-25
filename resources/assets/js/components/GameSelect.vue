<template>
	<div class="game-select">
		<h3>Select a Game</h3>

		<pg-game v-for="game in games"
			:game="game"
			:playerCount="players.length"
			:key="game.shortname"
			:isLeader="isLeader"
			@startGame="$emit('startGame', game.shortname)">
		</pg-game>
	</div>
</template>

<script>
	export default {
		components: {
			'pg-game': require('./GameSelect-Game.vue')
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
