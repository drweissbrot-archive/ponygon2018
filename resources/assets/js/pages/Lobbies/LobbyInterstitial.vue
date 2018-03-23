<template>
	<div class="lobby-interstital">
		<h2>Welcome to Ponygon!</h2>

		<div class="grid --halves">
			<div>
				<h3>
					Create a Lobby
				</h3>

				<p>
					Create a Lobby and invite your friends to join
				</p>

				<router-link :to="{name: 'lobby.create'}" class="btn">
					Create a Lobby
				</router-link>
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
		//
	}
</script>
