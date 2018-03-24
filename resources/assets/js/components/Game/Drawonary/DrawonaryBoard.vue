<template>
	<div class="drawonary-board">
		<h3>Wort</h3>

		<pg-draw-drawingboard></pg-draw-drawingboard>

		<div class="grid --halves">
			<p>
				<strong>some random username</strong> is drawing
			</p>

			<p class="remaining">
				<strong>90 seconds</strong> remaining
			</p>
		</div>
	</div>
</template>

<script>
	const drawingboard = require('./Drawingboard.vue')

	export default {
		components: {
			'pg-draw-drawingboard': drawingboard
		}
	}
</script>
