<template>
	<div class="c4">
		<h2>Connect Four</h2>

		<div class="board-wrap">
			<!-- <pg-player-list :players="players"></pg-player-list> -->

			<div>here go player list</div>

			<div></div>

			<pg-c4-board @makeMove="makeMove" :fields="fields" :turn="turn"></pg-c4-board>

			<div></div>

			<div>chat goe here</div>

			<!-- <pg-chat ref="chat"
				:lobby="this.lobbyId"
				:players="players">
			</pg-chat> -->
		</div>
	</div>
</template>

<script>
	const axios = require('axios')
	const moment = require('moment')

	export default {
		components: {
			'pg-c4-board': require('./Board.vue')
		},

		data() {
			return {
				id: this.$route.params.id,
				lobbyId: null,

				width: 7,
				height: 6,
				turn: false,
				fields: {}
			}
		},

		async mounted() {
			await this.getStatus()
			this.subscribe()
		},

		methods: {
			getStatus() {
				return axios.post('/play/c4/status/' + this.id, {
					user: window.user.id,
					auth: window.user.auth
				})
				.then((res) => {
					this.id = res.data.id
					this.lobbyId = res.data.lobby_id
					this.width = res.data.width
					this.height = res.data.height
					this.turn = res.data.turn === window.user.id

					this.fields = JSON.parse(res.data.fields)
				})
				.catch((err) => {
					console.error(err)
				})
			},

			subscribe() {
				Echo.channel('game:' + this.id)
				.listen('Game\\ConnectFour\\MoveMade', this.onMoveMade)
				.listen('Game\\ConnectFour\\GameEnded', this.onGameEnded)
			},

			onMoveMade(e) {
				this.fields = e.fields

				// let column = e.column = this.fields[e.column]
				//
				// column[e.row] = (e.nextPlayer != window.user.id) ? 'X' : 'O'
				//
				// this.fields.splice(e.column, 1, column)

				this.turn = (e.nextPlayer === window.user.id)
			},

			onGameEnded(e) {
				//
			},

			makeMove(column) {
				axios.post('/play/c4/move/' + this.id, {
					column,
					user: window.user.id,
					auth: window.user.auth
				})
				.then((res) => {
					//
				})
				.catch((err) => {
					console.error(err)
				})
			}
		}
	}
</script>
