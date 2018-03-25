<template>
	<div class="drawonary-drawing-board">
		<div class="time-remaining" :class="{ '--active': remaining}">
			{{ remaining ? remaining : oldRemaining }}
		</div>

		<canvas ref="canvas"
			width="1280"
			height="720"
			@mousedown.prevent="canvasMouseDown"
			@mousemove.prevent="canvasMouseMove"
			@mouseup.prevent="canvasMouseUp">
		</canvas>
	</div>
</template>

<script>
	let canvas, ctx, blankCanvas
	let painting, erasing

	export default {
		props: {
			remaining: {
				default: null
			},

			drawing: {
				default: false
			}
		},

		data() {
			return {
				oldRemaining: null
			}
		},

		mounted() {
			canvas = this.$refs.canvas
			ctx = canvas.getContext('2d')

			canvas.height = canvas.clientHeight
			canvas.width = canvas.clientWidth

			blankCanvas = canvas.toDataURL()

			ctx.strokeStyle = 'black'
			ctx.lineJoin = 'round'
			ctx.lineWidth = 5
		},

		methods: {
			canvasMouseDown(e) {
				if (! this.drawing) return

				const rect = canvas.getBoundingClientRect()

				painting = true

				ctx.beginPath()
				ctx.moveTo(e.pageX - rect.left, e.pageY - rect.top)

				// draw a simple dot (useful when only clicking, not dragging)
				ctx.lineTo(e.pageX - rect.left + 2, e.pageY - rect.top + 2)
				ctx.stroke()
			},

			canvasMouseMove(e) {
				if (! this.drawing || ! painting) return

				const rect = canvas.getBoundingClientRect()

				ctx.lineTo(e.pageX - rect.left, e.pageY - rect.top)
				ctx.stroke()
			},

			canvasMouseUp() {
				painting = false
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
