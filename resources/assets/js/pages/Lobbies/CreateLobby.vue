<template>
	<div>
		<p>
			Creating a Lobby...
		</p>
	</div>
</template>

<script>
	export default {
		mounted() {
			axios.post('/lobby/create', {
				user: this.user.id,
				auth: this.user.auth
			})
			.then((res) => {
				console.log(this)
				this.$router.replace({
					name: 'lobby.join',
					params: {
						lobby: res.data.lobby_id
					}
				})
			})
			.catch((err) => {
				console.error(err)
			})
		}
	}
</script>
