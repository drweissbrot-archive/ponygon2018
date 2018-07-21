<template>
	<div class="lobby-interstital">
		<h2>Welcome to Ponygon!</h2>

		<div>
			<p>
				Please enter a username.
			</p>

			<input type="text" placeholder="Enter your username..." v-model="name">
		</div>

		<p style="font-weight:bold;margin-top:2rem">
			Please note that Ponygon is still quite early in development.
			A lot of features are still missing or buggy.
			You can <a href="https://github.com/drweissbrot/ponygon" target="_blank">contribute on GitHub</a> if you want to help out.
		</p>

		<div class="grid --halves">
			<div>
				<h3>
					Create a Lobby
				</h3>

				<p>
					Create a Lobby and invite your friends to join
				</p>

				<button @click.prevent="createLobby">
					Create a Lobby
				</button>
			</div>

			<div>
				<h3>
					Join a Lobby
				</h3>

				<p>
					Join a Lobby that has already been created by your friends
				</p>

				<input type="text" placeholder="Paste your invite code or invite link">

				<button>Join a Lobby</button>
			</div>
		</div>
	</div>
</template>

<script>
	export default {
		data() {
			return {
				name: ''
			}
		},

		methods: {
			register() {
				return axios.post('/lobby/register', {
					name: this.name
				})
				.then((res) => {
					window.user = res.data
					window.Echo.options.auth.headers['X-PONYGON-USER'] = res.data.id
					window.Echo.options.auth.headers['X-PONYGON-AUTH'] = res.data.auth
				})
			},

			async createLobby() {
				await this.register()

				axios.post('/lobby/create', {
					user: window.user.id,
					auth: window.user.auth
				})
				.then((res) => {
					this.$router.push({
						name: 'lobby',
						params: {
							lobby: res.data.id
						}
					})
				})
				.catch((err) => {
					console.error(err)
				})
			}
		}
	}
</script>
