require('./bootstrap')

import Vue from 'vue'
import VueRouter from 'vue-router'

Vue.use(VueRouter)

window.games = require('./games.json')
window.user = {}

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

const app = new Vue({
	router,
	el: '#vue-app'
})
