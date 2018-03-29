<template>
	<div class="drawonary">
		<h2>{{ title }}</h2>

		<div class="grid --one-three-one-fifth">
			<pg-player-list :players="players"></pg-player-list>

			<pg-draw-board ref="board"
				:words="words"
				:players="players"
				:rounds="rounds"
				:round="round"
				:wordLength="wordLength"
				:wordToGuess="wordToGuess"
				:turn="turn"
				:action="action"
				:turnEndsAt="turnEndsAt"
				:endsAtIsSelection="endsAtIsSelection"
				:turnEnded="turnEnded"
				:gameEnded="gameEnded"
				:selectingUser="selectingUser"
				:lobby="lobbyId"
				:drawing="drawing"
				@wordSelected="selectWord"
				@startDrawing="startDrawing"
				@continueDrawing="continueDrawing"
				@stopDrawing="stopDrawing"
				@canvasDimensions="canvasDimensions">
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

	let drawingChannel

	export default {
		components: {
			'pg-draw-board': require('./DrawonaryBoard.vue')
		},

		data() {
			return {
				id: this.$route.params.id,
				lobbyId: null,

				words: null,
				wordToGuess: null,
				wordLength: 0,

				round: null,
				rounds: null,

				turn: null,
				drawing: false,

				action: null,
				turnEndsAt: null,
				endsAtIsSelection: false,

				selectingUser: false,

				turnEnded: false,
				gameEnded: false,

				players: [],
				order: [],

				title: 'Donnerstagsmaler'
			}
		},

		async mounted() {
			this.updateTitle()

			await this.getStatus()
			this.subscribe()
		},

		methods: {
			updateTitle() {
				moment.locale('de')
				this.title = moment().format('dddd') + 'smaler'

				setTimeout(this.updateTitle, 120000)
			},

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
					this.drawing = (res.data.turn == window.user.id)
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

				drawingChannel = Echo.private('game:draw:' + this.id)
				.listenForWhisper('startDrawing', this.onRemoteStartDrawing)
				.listenForWhisper('continueDrawing', this.onRemoteContinueDrawing)
				.listenForWhisper('stopDrawing', this.onRemoteStopDrawing)
				.listenForWhisper('canvasDimensions', this.onRemoteCanvasDimensions)
			},

			onSelectingWord(e) {
				this.wordToGuess = null
				this.wordLength = null

				if (e.user == window.user.id) {
					// current user is selecting word! PANIC!
					this.getWords()
				}

				let player = this.findPlayerById(e.user)

				this.turnEndsAt = e.selectionEndsAt
				this.endsAtIsSelection = true
				this.turn = (player) ? player.name : 'someone'
				this.drawing = (player.id == window.user.id)
				this.action = 'selecting a word'
				this.turnEnded = false

				if (player && player.id != window.user.id) {
					this.selectingUser = this.turn
				}
			},

			onWordSelected(e) {
				this.action = 'drawing'
				this.turnEndsAt = e.turnEndsAt
				this.endsAtIsSelection = false
				this.words = null
				this.selectingUser = false

				if (this.turn != window.user.id || ! this.wordToGuess) {
					this.wordLength = e.wordLength
				}
			},

			onTurnEnded(e) {
				console.log(e.word)
				this.turnEnded = JSON.parse(e.addedPoints)

				this.applyScoreboardSorted(e.scoreboard)

				this.wordToGuess = e.word

				this.$refs.chat.applyChatMessage({
					message: 'The word was ' + e.word + '!',
					isAction: true
				})
			},

			onWordGuessed(e) {
				this.$refs.chat.applyChatMessage({
					user: e.user,
					message: 'guessed the word!',
					isAction: true,
					time: []
				})

				// TODO not doing this after every guess prevents this
				// scoreboard from overwriting the scoreboard from
				// turnEnd -- find a nicer way to do this
				// this.applyScoreboardSorted(e.scoreboard)
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
				console.log(scoreboard)

				scoreboard = JSON.parse(scoreboard)

				console.log(scoreboard)

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
					this.wordToGuess = word
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
			},

			startDrawing(e) {
				drawingChannel.whisper('startDrawing', e)
			},

			continueDrawing(e) {
				drawingChannel.whisper('continueDrawing', e)
			},

			stopDrawing() {
				drawingChannel.whisper('stopDrawing')
			},

			canvasDimensions(e) {
				if (! drawingChannel) return

				drawingChannel.whisper('canvasDimensions', e)
			},

			onRemoteStartDrawing(e) {
				this.$refs.board.$refs.drawingboard.startDrawing(e.x, e.y)
			},

			onRemoteContinueDrawing(e) {
				this.$refs.board.$refs.drawingboard.continueDrawing(e.x, e.y)
			},

			onRemoteStopDrawing(e) {
				this.$refs.board.$refs.drawingboard.stopDrawing()
			},

			onRemoteCanvasDimensions(e) {
				this.$refs.board.$refs.drawingboard.canvasDimensions(e.width, e.height)
			}
		}
	}
</script>
