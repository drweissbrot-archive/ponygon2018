<template>
	<div class="drawonary-drawing-board">
		<div class="time-remaining" :class="{ '--active': remaining}">
			{{ remaining ? remaining : oldRemaining }}
		</div>

		<canvas ref="canvas"
			width="800"
			height="600"
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

			canvas.width = 800
			canvas.height = 800

			this.canvasDimensions({
				color: '#000',
				strength: 5,
			})

			blankCanvas = canvas.toDataURL()

			document.addEventListener('mouseup', this.canvasMouseUp)
		},

		destroy() {
			document.removeEventListener('mouseup', this.canvasMouseUp)
		},

		methods: {
			canvasMouseDown(e) {
				if (! this.drawing) return

				painting = true

				const rect = canvas.getBoundingClientRect()
				let x = e.pageX - rect.left
				let y = e.pageY - rect.top - window.scrollY

				this.startDrawing(x, y)

				this.$emit('startDrawing', {
					x, y,
				})
			},

			canvasMouseMove(e) {
				if (! this.drawing || ! painting) return

				const rect = canvas.getBoundingClientRect()
				let x = e.pageX - rect.left
				let y = e.pageY - rect.top - window.scrollY

				this.continueDrawing(x, y)

				this.$emit('continueDrawing', {
					x, y,
				})
			},

			canvasMouseUp() {
				this.stopDrawing()

				this.$emit('stopDrawing')
			},

			startDrawing(x, y, width, height) {
				if (width) {
					canvas.width = width
				}

				if (height) {
					canvas.height = height
				}

				ctx.beginPath()
				ctx.moveTo(x, y)

				// draw a simple dot (useful when only clicking, not dragging)
				ctx.lineTo(x + 2, y + 2)
				ctx.stroke()
			},

			continueDrawing(x, y) {
				ctx.lineTo(x, y)
				ctx.stroke()
			},

			stopDrawing() {
				painting = false
			},

			canvasDimensions(e) {
				ctx.strokeStyle = e.color
				ctx.lineJoin = 'round'
				ctx.lineWidth = e.strength
			},

			clearCanvas() {
				ctx.clearRect(0, 0, canvas.width, canvas.height)
			}
		},

		watch: {
			remaining(remaining) {
				if (remaining === null) return

				this.oldRemaining = remaining
			},

			drawing(drawing) {
				if (! drawing) return

				this.canvas = blankCanvas

				this.canvasDimensions(canvas.clientWidth, canvas.clientHeight)
			}
		}
	}
</script>
