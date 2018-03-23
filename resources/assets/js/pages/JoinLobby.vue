<template>
	<div class="join-lobby">
		<h2>Joining Lobby {{ $route.params.lobby }}</h2>

		<p v-show="status == 'loading'">
			Loading...
		</p>

		<p v-show="status == 'lobbyDoesntExist'">
			This lobby doesn't exist.
			Do you want to <a href="#todo">create a lobby</a>?
		</p>

		<p v-show="status == 'error'">
			An error occured.
			Please try again later.
			Sorry about that :/
		</p>

		<div class="character-creation">
			<!-- TODO Avatar Creation -->

			<label for="username">
				Username
			</label>

			<input type="text" id="username" placeholder="Enter your username..." v-model="username">

			<button @click="joinLobby">
				Join Lobby
			</button>
		</div>
	</div>
</template>

<script>
	const axios = require('axios')

	export default {
		data() {
			return {
				status: 'loading',
				namesInUse: [],
				username: ''
			}
		},

		mounted() {
			// TODO check with server if lobby exists
			axios.get('/lobby/heartbeat/' + this.$route.params.lobby)
			.then((res) => {
				if (! res.data.lobby_exists) {
					return this.status = 'lobbyDoesntExist'
				}

				this.namesInUse = res.data.names_in_use
			})
			.catch((err) => {
				this.status = 'error'
			})
		},

		methods: {
			joinLobby() {
				if (this.namesInUse.includes(this.username)) {
					return alert('Your username is already in use!')
				}

				axios.post('/lobby/join/' + this.$route.params.lobby, {
					username: this.username
				})
				.then((res) => {
					//
				})
				.catch((err) => {
					this.status = 'error'
				})
			}
		}
	}
</script>
