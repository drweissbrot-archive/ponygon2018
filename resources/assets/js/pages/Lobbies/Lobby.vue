<template>
	<div class="lobby">
		<h2>
			Lobby
			<span class="lobby-id">{{ lobby.id }}</span>
		</h2>

		<div v-show="lobby.inviteLink" class="invite-wrap">
			To invite people to join your lobby, send them this link:
			<a :href="lobby.inviteLink" @click="copyInviteLink">
				{{ lobby.inviteLink }}
			</a>
		</div>

		<div class="grid --one-one-one-third">
			<pg-player-list
				:players="players"
				:currentPlayer="currentPlayer"
				@changeLeader="changeLeader">
			</pg-player-list>

			<pg-chat></pg-chat>

			<pg-game-select :players="players"></pg-game-select>
		</div>
	</div>
</template>

<script>
	const gameSelect = require('../../components/GameSelect.vue')

	export default {
		components: {
			'pg-game-select': gameSelect
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
					//
				})
			},

			copyInviteLink(e) {
				e.preventDefault()

				//
			},

			subscribe() {
				Echo.channel('lobby:' + this.lobby.id)
				.listen('Game\\Lobby\\UserJoined', this.addPlayer)
				.listen('Game\\Lobby\\LeaderChanged', this.updateLeader)
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
			}
		}
	}
</script>
