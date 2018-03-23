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
		component: require('./pages/Lobby.vue')
	},
	{
		path: '/join/:lobby',
		component: require('./pages/JoinLobby.vue')
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
