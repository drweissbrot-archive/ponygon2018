<template>
	<div class="lobby">
		<h2>Lobby</h2>

		<div class="invite-wrap">
			To invite people to join your lobby, send them this link:
			<a href="https://ponygon.test/app#lobby-1234567890123456">
				https://ponygon.test/app#lobby-1234567890123456
			</a>
		</div>

		<div class="grid --one-one-one-third">
			<pg-player-list :players="players" :currentPlayer="currentPlayer"></pg-player-list>

			<pg-chat></pg-chat>

			<pg-game-select :players="players"></pg-game-select>
		</div>
	</div>
</template>

<script>
	const gameSelect = require('../components/GameSelect.vue')

	export default {
		components: {
			'pg-game-select': gameSelect
		},

		data() {
			return {
				players: [
					{
						name: 'drweissbrot',
						leader: true
					},
					{
						name: 'someone',
						leader: false
					},
					{
						name: 'somebody',
						leader: false
					}
				],
				currentPlayer: {
					name: 'drweissbrot',
					leader: true
				}
			}
		}
	}
</script>
