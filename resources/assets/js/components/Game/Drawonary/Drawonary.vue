<template>
	<div class="drawonary">
		<h2>Donnerstagsmaler</h2>

		<div class="grid --one-three-one-fifth">
			<pg-player-list :players="players"></pg-player-list>

			<pg-draw-board></pg-draw-board>

			<pg-chat></pg-chat>
		</div>
	</div>
</template>

<script>
	const axios = require('axios')
	const board = require('./DrawonaryBoard.vue')

	export default {
		components: {
			'pg-draw-board': board
		},

		data() {
			return {
				id: this.$route.params.id,

				players: []
			}
		},

		mounted() {
			this.getStatus()
			this.subscribe()
		},

		methods: {
			getStatus() {
				axios.post('/play/draw/status/' + this.id, {
					user: window.user.id,
					auth: window.user.auth
				})
				.then((res) => {
					let players = JSON.parse(res.data.scoreboard)

					let order = res.data.order.split(':')

					for (let i = 0; i < order.length; i++) {
						for (let j = 0; j < players.length; j++) {
							if (players[j].id == order[i]) {
								order[i] = players[j]
								break
							}
						}
					}

					// TODO is this order actually the right order?
					this.players = order
				})
				.catch((err) => {
					console.error(err)
				})
			},

			subscribe() {
				//
			}
		}
	}
</script>
