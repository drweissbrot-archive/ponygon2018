<template>
	<div class="lobby">
		<h2>
			Lobby
			<span class="lobby-id">{{ lobby.id }}</span>
		</h2>

		<div v-show="lobby.inviteLink" class="invite-wrap">
			To invite people to join your lobby, send them this link:
			<a :href="lobby.inviteLink" @click.prevent="copyInviteLink">
				{{ lobby.inviteLink }}
			</a>
		</div>

		<div class="grid --one-one-one-third">
			<pg-player-list
				:players="players"
				:currentPlayer="currentPlayer"
				@changeLeader="changeLeader">
			</pg-player-list>

			<pg-chat ref="chat" :lobby="this.lobby.id" :players="players"></pg-chat>

			<pg-game-select :players="players" :isLeader="currentPlayer.leader" @startGame="startGame"></pg-game-select>
		</div>
	</div>
</template>

<script>
	export default {
		components: {
			'pg-game-select': require('../../components/GameSelect.vue')
		},

		mounted() {
			this.getLobbyState()

			this.subscribe()
		},

		data() {
			return {
				players: [],

				currentPlayer: {
					name: window.user.name,
					id: window.user.id,
					leader: false,
				},

				lobby: {
					id: this.$route.params.lobby,
					inviteLink: null,
				}
			}
		},

		methods: {
			getLobbyState() {
				axios.post('/lobby/status/' + this.$route.params.lobby, {
					user: window.user.id,
					auth: window.user.auth
				})
				.then((res) => {
					this.lobby.inviteLink = res.data.invite_link
					this.players = res.data.players
					this.lobby.leader = res.data.leader

					this.currentPlayer.leader = (res.data.leader == this.currentPlayer.id)
				})
				.catch((err) => {
					if (err.response.data.message == 'You are not a member of this lobby.') {
						return this.$router.replace({
							name: 'lobby.join',
							params: {
								lobby: this.$route.params.lobby
							}
						})
					}

					console.error(err.response)
				})
			},

			copyInviteLink() {
				//
			},

			subscribe() {
				Echo.channel('lobby:' + this.lobby.id)
				.listen('Game\\Lobby\\UserJoined', this.addPlayer)
				.listen('Game\\Lobby\\LeaderChanged', this.updateLeader)
				.listen('Game\\Lobby\\GameStarted', this.gameStarted)
			},

			addPlayer(user) {
				this.$set(this.players, user.id, {
					id: user.id,
					name: user.name
				})
			},

			updateLeader(e) {
				// make previous leader a normal user
				this.players[this.lobby.leader].leader = false

				// change leader
				this.lobby.leader = e.user

				// make new leader a leader
				this.players[this.lobby.leader].leader = true

				// find out if current player is leader
				this.currentPlayer.leader = (this.lobby.leader == this.currentPlayer.id)

				this.$refs.chat.applyChatMessage({
					user: this.lobby.leader,
					message: 'is now lobby leader',
					time: [],
					isAction: true
				})
			},

			changeLeader(id) {
				axios.post('/lobby/change-leader/' + this.lobby.id, {
					user: window.user.id,
					auth: window.user.auth,
					newLeader: id
				})
				.then((res) => {
					//
				})
				.catch((err) => {
					console.error(err)
				})
			},

			startGame(game) {
				axios.post('/lobby/start/' + this.lobby.id, {
					user: window.user.id,
					auth: window.user.auth,
					game
				})
				.then((res) => {
					//
				})
				.catch((err) => {
					console.error(err)
				})
			},

			gameStarted(e) {
				this.$router.push({
					name: 'play.' + e.game,
					params: {
						id: e.id
					}
				})
			}
		}
	}
</script>
