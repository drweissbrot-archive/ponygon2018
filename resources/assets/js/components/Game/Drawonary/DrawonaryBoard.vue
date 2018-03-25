<template>
	<div class="drawonary-board">
		<h3 class="word-to-guess">
			<span v-for="n in wordLength">_ </span>
		</h3>

		<pg-draw-select-word-modal v-show="words"
			:words="words"
			@wordSelected="$emit('wordSelected', $event)">
		</pg-draw-select-word-modal>

		<pg-draw-drawingboard :remaining="backgroundRemaining"></pg-draw-drawingboard>

		<div class="grid --halves">
			<p>
				<strong>{{ turn }}</strong> is {{ action }}
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

	const drawingboard = require('./Drawingboard.vue')
	const selectWordModal = require('./SelectWordModal.vue')

	const showAsBackground = [
		60, 45, 30, 15, 7, 6, 5, 4, 3, 2, 1,
	]

	export default {
		components: {
			'pg-draw-drawingboard': drawingboard,
			'pg-draw-select-word-modal': selectWordModal
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

				if (showAsBackground.includes(this.remaining)) {
					this.backgroundRemaining = this.remaining
				} else {
					this.backgroundRemaining = null
				}
			}
		}
	}
</script>
