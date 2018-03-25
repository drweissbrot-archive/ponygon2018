<template>
	<div class="drawonary">
		<h2>Donnerstagsmaler</h2>

		<div class="grid --one-three-one-fifth">
			<pg-player-list :players="players"></pg-player-list>

			<pg-draw-board :words="words"
				:wordLength="wordLength"
				:turn="turn"
				:action="action"
				:turnEndsAt="turnEndsAt"
				:endsAtIsSelection="endsAtIsSelection"
				@wordSelected="selectWord">
			</pg-draw-board>

			<pg-chat></pg-chat>
		</div>
	</div>
</template>

<script>
	const axios = require('axios')
	const moment = require('moment')

	const board = require('./DrawonaryBoard.vue')

	export default {
		components: {
			'pg-draw-board': board
		},

		data() {
			return {
				id: this.$route.params.id,

				words: null,
				wordLength: 0,

				turn: null,
				action: null,
				turnEndsAt: null,
				endsAtIsSelection: false,

				players: []
			}
		},

		async mounted() {
			await this.getStatus()
			this.subscribe()
		},

		methods: {
			getStatus() {
				return axios.post('/play/draw/status/' + this.id, {
					user: window.user.id,
					auth: window.user.auth
				})
				.then((res) => {
					this.id = res.data.id
					this.lobby_id = res.data.lobby_id
					this.deck = res.data.deck
					this.turn = res.data.turn

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

					this.onSelectingWord({
						user: this.turn,
						selectionEndsAt: moment().add(14, 'seconds').format()
					})
				})
				.catch((err) => {
					console.error(err)
				})
			},

			subscribe() {
				Echo.channel('game:' + this.id)
				.listen('Game\\Drawonary\\SelectingWord', this.onSelectingWord)
				.listen('Game\\Drawonary\\WordSelected', this.onWordSelected)
				.listen('Game\\Drawonary\\TurnEnded', this.onTurnEnded)
			},

			onSelectingWord(e) {
				if (e.user == window.user.id) {
					// current user is selecting word! PANIC!
					this.getWords()
				}

				let player = this.findPlayerById(e.user)

				this.turnEndsAt = e.selectionEndsAt
				this.endsAtIsSelection = true
				this.turn = (player) ? player.name : 'someone'
				this.action = 'selecting a word'
				// TODO show "user is selecting" message as modal (?)
			},

			onWordSelected(e) {
				this.wordLength = e.wordLength
				this.action = 'drawing'
				this.turnEndsAt = e.turnEndsAt
				this.endsAtIsSelection = false
				this.words = null
			},

			onTurnEnded(e) {
				//
			},

			getWords() {
				axios.post('/play/draw/words/' + this.id, {
					user: window.user.id,
					auth: window.user.auth
				})
				.then((res) => {
					this.words = res.data.words
				})
				.catch((err) => {
					console.error(err)
				})
			},

			selectWord(word) {
				axios.post('/play/draw/select/' + this.id, {
					user: window.user.id,
					auth: window.user.auth,
					word
				})
				.then((res) => {
					this.words = null
				})
				.catch((err) => {
					console.error(err)
				})
			},

			findPlayerById(id) {
				for (let player of this.players) {
					if (player.id === id) {
						return player
					}
				}
			}
		}
	}
</script>
