<template>
	<div class="drawonary-modal">
		<h3>Turn ended!</h3>

		<p v-for="(points, user) in addedPoints">
			<strong>{{ findPlayerNameById(user) }}</strong>:
			+{{ points }} pts
		</p>
	</div>
</template>

<script>
	export default {
		props: {
			addedPoints: {
				required: true
			},

			players: {
				required: true
			}
		},

		methods: {
			findPlayerNameById(id) {
				for (let player in this.players) {
					if (this.players[player].id === id) {
						return this.players[player].name
					}
				}
			}
		}
	}
</script>
