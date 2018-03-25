<template>
	<div class="drawonary-drawing-board">
		<div class="time-remaining" :class="{ '--active': remaining}">
			{{ remaining ? remaining : oldRemaining }}
		</div>

		<canvas id="canvas" width="1280" height="720"></canvas>
	</div>
</template>

<script>
	export default {
		props: {
			remaining: {
				default: null
			}
		},

		data() {
			return {
				oldRemaining: null
			}
		},

		watch: {
			remaining(remaining) {
				if (remaining === null) return

				this.oldRemaining = remaining
			}
		}
	}
</script>
