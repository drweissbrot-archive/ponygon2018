<template>
	<div class="drawonary-drawing-board">
		<div class="time-remaining" :class="{ '--active': remaining}">
			{{ remaining ? remaining : oldRemaining }}
		</div>

		<canvas ref="canvas"
			width="800"
			height="600"
			@mousedown.prevent="canvasMouseDown"
			@mousemove.prevent="canvasMouseMove">
		</canvas>

		<div class="toolbox">
			<button class="draw --black" @click="setColor('#000')"><span></span></button>
			<button class="draw --blue" @click="setColor('blue')"><span></span></button>
			<button class="draw --green" @click="setColor('green')"><span></span></button>
			<button class="draw --red" @click="setColor('red')"><span></span></button>
			<button class="tool --eraser" @click="setColor('#fff')">eraser</button>
			<button class="tool --fill" disabled>fill</button>
			<button class="tool --clear" @click="emitClearCanvas">clear</button>
		</div>
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
			canvas.height = 600

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
				if (! painting) return

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

			emitClearCanvas() {
				if (! this.drawing) return

				this.clearCanvas()

				this.$emit('clearCanvas')
			},

			clearCanvas() {
				ctx.clearRect(0, 0, canvas.width, canvas.height)
			},

			setColor(color) {
				if (! this.drawing) return

				ctx.strokeStyle = color

				this.$emit('canvasDimensions', {
					strength: 5,
					color,
				})
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
