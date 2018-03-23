require('./bootstrap')

import Vue from 'vue'
import VueRouter from 'vue-router'

Vue.use(VueRouter)

Vue.component('pg-app', require('./components/App.vue'))
Vue.component('pg-player-list', require('./components/PlayerList.vue'))
Vue.component('pg-chat', require('./components/Chat.vue'))

const routes = [
	{
		path: '/',
		component: require('./pages/Lobbies/LobbyInterstitial.vue'),
		name: 'lobby.interstitial'
	},
	{
		path: '/create-lobby',
		component: require('./pages/Lobbies/CreateLobby.vue'),
		name: 'lobby.create'
	},
	{
		path: '/join/:lobby',
		component: require('./pages/Lobbies/JoinLobby.vue'),
		name: 'lobby.join'
	},
	{
		path: '/lobby/:lobby',
		component: require('./pages/Lobbies/Lobby.vue'),
		name: 'lobby'
	}
]

const router = new VueRouter({
	routes
})

Vue.prototype.games = require('./games.json')

axios.get('/lobby/register')
.then((res) => {
	Vue.prototype.user = res.data

	const app = new Vue({
		router,
		el: '#vue-app'
	})
})
.catch((err) => {
	//
})
