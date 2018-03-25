<template>
	<div class="drawonary-board">
		<h3 class="word-to-guess">
			<span v-for="n in wordLength">_ </span>
			<span v-if="! wordLength" class="no-word-length">.</span>
		</h3>

		<div class="board-and-modal-wrap">
			<pg-draw-select-word-modal v-show="words"
				:words="words"
				@wordSelected="$emit('wordSelected', $event)">
			</pg-draw-select-word-modal>

			<pg-draw-turn-ended-modal v-show="! gameEnded && turnEnded"
				:addedPoints="turnEnded"
				:players="players"></pg-draw-turn-ended-modal>

			<pg-draw-selecting-word-modal v-show="selectingUser" :user="selectingUser"></pg-draw-selecting-word-modal>

			<pg-draw-game-ended-modal v-if="gameEnded" :players="players" :lobby="lobby"></pg-draw-game-ended-modal>

			<pg-draw-drawingboard :remaining="backgroundRemaining"></pg-draw-drawingboard>
		</div>

		<div class="grid --two-one-two-fifths">
			<p>
				<strong>{{ turn }}</strong> is {{ action }}
			</p>

			<p class="text-center">
				<span v-if="round && rounds">
					Round {{ round }} of {{ rounds }}
				</span>
			</p>

			<p class="remaining" v-show="turnEndsAt">
				<strong>
					<span>{{ remaining }}</span> seconds
				</strong> remaining
			</p>
		</div>
	</div>
</template>

<script>
	const moment = require('moment')

	// values for remaining seconds that should be displayed in the canvas
	const showAsBackground = [
		60, 45, 30, 15, 7, 6, 5, 4, 3, 2, 1,
	]

	export default {
		components: {
			'pg-draw-drawingboard': require('./Drawingboard.vue'),
			'pg-draw-select-word-modal': require('./SelectWordModal.vue'),
			'pg-draw-turn-ended-modal': require('./TurnEndedModal.vue'),
			'pg-draw-selecting-word-modal': require('./SelectingWordModal.vue'),
			'pg-draw-game-ended-modal': require('./GameEndedModal.vue')
		},

		data() {
			return {
				remaining: 0,
				interval: null,
				backgroundRemaining: null
			}
		},

		props: {
			words: {
				default: null
			},

			wordLength: {
				default: 0
			},

			turn: {
				default: 'no-one'
			},

			action: {
				default: 'doing anything'
			},

			turnEndsAt: {
				default: null
			},

			endsAtIsSelection: {
				default: false
			},

			round: {
				default: null
			},

			rounds: {
				default: null
			},

			turnEnded: {
				default: false
			},

			selectingUser: {
				default: false
			},

			players: {
				required: true
			},

			gameEnded: {
				default: false
			},

			lobby: {
				required: true
			}
		},

		watch: {
			turnEndsAt(endAt) {
				this.updateRemainingTime()

				this.interval = setInterval(this.updateRemainingTime, 500)
			}
		},

		methods: {
			updateRemainingTime() {
				this.remaining = Math.floor(moment(this.turnEndsAt).diff() / 1000)

				if (this.remaining < 1) {
					clearInterval(this.interval)
					this.turnEndsAt = null
				}

				if (this.endsAtIsSelection) return

				if (showAsBackground.includes(this.remaining)) {
					this.backgroundRemaining = this.remaining
				} else {
					this.backgroundRemaining = null
				}
			}
		}
	}
</script>