<template>
	<div class="drawonary">
		<h2>Donnerstagsmaler</h2>

		<div class="grid --one-three-one-fifth">
			<pg-player-list :players="players"></pg-player-list>

			<pg-draw-board :words="words"
				:players="players"
				:rounds="rounds"
				:round="round"
				:wordLength="wordLength"
				:turn="turn"
				:action="action"
				:turnEndsAt="turnEndsAt"
				:endsAtIsSelection="endsAtIsSelection"
				:turnEnded="turnEnded"
				:gameEnded="gameEnded"
				:selectingUser="selectingUser"
				:lobby="lobbyId"
				@wordSelected="selectWord">
			</pg-draw-board>

			<pg-chat ref="chat"
				:lobby="this.lobbyId"
				:players="players"
				@chatMessageAnalyzed="closeGuess">
			</pg-chat>
		</div>
	</div>
</template>

<script>
	const axios = require('axios')
	const moment = require('moment')

	export default {
		components: {
			'pg-draw-board': require('./DrawonaryBoard.vue')
		},

		data() {
			return {
				id: this.$route.params.id,
				lobbyId: null,

				words: null,
				wordLength: 0,

				round: null,
				rounds: null,

				turn: null,
				action: null,
				turnEndsAt: null,
				endsAtIsSelection: false,

				selectingUser: false,

				turnEnded: false,
				gameEnded: false,

				players: [],
				order: []
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
					this.lobbyId = res.data.lobby_id
					this.deck = res.data.deck
					this.turn = res.data.turn
					this.round = res.data.round
					this.rounds = res.data.rounds

					this.order = res.data.order.split(':')

					this.applyScoreboardSorted(res.data.scoreboard)

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
				.listen('Game\\Drawonary\\WordGuessed', this.onWordGuessed)
				.listen('Game\\Drawonary\\RoundAdvanced', this.onRoundAdvanced)
				.listen('Game\\Drawonary\\GameEnded', this.onGameEnded)
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
				this.turnEnded = false

				if (player && player.id != window.user.id) {
					this.selectingUser = this.turn
				}
			},

			onWordSelected(e) {
				this.wordLength = e.wordLength
				this.action = 'drawing'
				this.turnEndsAt = e.turnEndsAt
				this.endsAtIsSelection = false
				this.words = null
				this.selectingUser = false
			},

			onTurnEnded(e) {
				this.turnEnded = e.addedPoints
			},

			onWordGuessed(e) {
				this.$refs.chat.applyChatMessage({
					user: e.user,
					message: 'guessed the word!',
					isAction: true,
					time: []
				})

				this.applyScoreboardSorted(e.scoreboard)
			},

			onRoundAdvanced(e) {
				this.round = e.round
			},

			onGameEnded(e) {
				this.gameEnded = true

				this.applyScoreboardSorted(e.scoreboard)
			},

			closeGuess(e) {
				this.$refs.chat.applyChatMessage({
					user: null,
					message: e.word + ' is close!',
					time: [],
					isAction: true
				})
			},

			applyScoreboardSorted(scoreboard) {
				scoreboard = JSON.parse(scoreboard)

				let order = Object.assign({}, this.order)

				for (let i in order) {
					for (let j in scoreboard) {
						if (order[i] == scoreboard[j].id) {
							order[i] = scoreboard[j]
						}
					}

					// for (let j = 0; j < scoreboard.length; j++) {
					// 	order[i] = scoreboard[j]
					// }
				}

				// TODO is this order actually the right order?
				this.players = order
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
				for (let player in this.players) {
					if (this.players[player].id === id) {
						return this.players[player]
					}
				}
			}
		}
	}
</script>
