<template>
	<div class="board">
		<h2>
			<span v-show="turn">It's your turn!</span>
			<span v-show="! turn">It's their turn.</span>
		</h2>

		<div class="row" v-for="row in height + 2">
			<div class="node"
				v-for="column in width + 1"
				:data-row="row - 1"
				:data-column="column - 1"
				@click.preventDefault="onClickNode">
				<span v-if="column == 1 && row > 2">{{ row - 2 }}</span>
				<span v-if="column > 1 && row === 1">↓</span>
				<span v-if="column > 1 && row === 2">{{ String.fromCharCode(63 + column) }}</span>

				<span v-if="fields && column > 1 && fields[column - 2] && row > 2 && fields[column - 2][row - 3]">
					{{ fields[column - 2][row - 3] === id ? 'X' : 'O' }}
				</span>
			</div>
		</div>
	</div>
</template>

<script>
	export default {
		props: {
			width: {
				default: 7
			},
			height: {
				default: 6
			},
			fields: {
				required: true
			},
			turn: {
				default: false
			}
		},

		data() {
			return {
				id: window.user.id,
			}
		},

		methods: {
			onClickNode(e) {
				const x = e.target.getAttribute('data-column')
				const y = e.target.getAttribute('data-row')

				// we don't care at all anything but the arrow nodes was clicked
				if (y != 0 || x == 0) return

				this.$emit('makeMove', x - 1)
			}
		}
	}
</script>
