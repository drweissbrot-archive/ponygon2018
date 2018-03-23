require('./bootstrap')

const stateEl = document.querySelector('#state')
const linkArea = document.querySelector('#link-area')
const baseLink = linkArea.getAttribute('data-base')
const linkEl = linkArea.querySelector('a')
const newGameArea = document.querySelector('.new-game')
const playerArea = document.querySelector('.player-area')
const playerLabel = playerArea.querySelector('#player')

let player

class TicTacToe {
	get player() {
		return player
	}

	set player(value) {
		player = value

		playerLabel.innerHTML = player
		playerArea.style.display = 'block'
	}

	constructor() {
		this.nodes = {}
		this.parseNodes()
		this.addStartNewButtonListener()

		if (window.location.hash) {
			this.startGameFromHash()
		} else {
			this.startGame()
		}

		console.info('Initialized')
	}

	startGame() {
		console.info('Starting game...')

		axios.get('/play/tic-tac-toe/start')
		.then((res) => {
			this.auth = res.data.auth
			this.id = res.data.game_id
			this.player = 'X'

			this.applyLink(res.data.game_id)
			this.applyTurn('waiting')

			this.subscribe()
		})
		.catch((err) => {
			this.applyTurn('error')
			console.error(err.response)
		})
	}

	startGameFromHash() {
		this.player = 'X'
		this.id = window.location.hash.substr(1, window.location.hash.length - 9)
		this.subscribe()

		if (window.location.hash.substr(window.location.hash.length - 8) === '-player2') {
			this.player = 'O'
		}

		this.registerAsPlayerTwo()
	}

	registerAsPlayerTwo() {
		console.info('Registering as second player', this.player)

		axios.get('/play/tic-tac-toe/register/' + this.id + '/' + this.player)
		.then((res) => {
			this.auth = res.data.auth

			this.applyTurn('X')
		})
		.catch((err) => {
			// TODO apply error only if client side error 4xx
			this.applyTurn('error')
			console.error(err.response)
		})
	}

	subscribe() {
		if (this.channel) {
			Echo.leave(this.channel)
		}

		this.channel = 'game.ttt.' + this.id

		console.info('Subscribing to', this.channel)

		Echo.channel(this.channel)
		.listen('Game\\TicTacToe\\MoveMade', (e) => {
			this.applyTurn(e.state.turn)
			this.applyState(e.state)
		})
		.listen('Game\\TicTacToe\\UserRegistered', (e) => {
			this.hideLinkArea()

			this.applyTurn(e.state)
		})
		.listen('Game\\TicTacToe\\NewGameStarted', (e) => {
			console.info('New game started...', e.byPlayer)
			this.id = e.newId

			this.subscribe()
			this.hideNewGameButton()

			this.applyState('empty')

			this.swapPlayer()

			if (this.player === e.byPlayer) {
				this.registerAsPlayerTwo()
			}
		})
	}

	parseNodes() {
		let self = this

		for (node of document.querySelectorAll('[data-node]')) {
			let coordinate = node.getAttribute('data-node')

			this.nodes[coordinate] = node

			node.addEventListener('click', (e) => {
				this.makeMove(e.target.getAttribute('data-node'))
			})
		}
	}

	applyLink(id) {
		linkEl.href = linkEl.innerText = baseLink + '#' + id + '-player2'

		this.showLinkArea()
	}

	makeMove(move) {
		axios.post('/play/tic-tac-toe/move/' + this.id, {
			auth: this.auth,
			player: this.player,
			move,
		}).then((res) => {
			//
		}).catch((err) => {
			if (Math.floor(err.response.status / 100) !== 4) {
				this.applyTurn('error')
			}
			console.error(err.response)
		})
	}

	applyState(state) {
		for (let coordinate in this.nodes) {
			this.nodes[coordinate].innerText = (state == 'empty') ? '' : state[coordinate]
		}
	}

	applyTurnColor(color) {
		if (color == 'red') {
			stateEl.classList.add('--your-turn')
		} else {
			stateEl.classList.remove('--your-turn')
		}
	}

	applyTurn(move) {
		let color = false

		switch (move) {
			case this.player:
				color = 'red'
				stateEl.innerText = 'It\'s your turn!'
				break

			case 'winX':
				if (this.player == 'X') {
					stateEl.innerText = 'You won! Congratulations!'
					this.showNewGameButton()
					break
				}

				stateEl.innerText = 'You lost.'
				this.showNewGameButton()
				break

			case 'winO':
				if (this.player == 'O') {
					stateEl.innerText = 'You won! Congratulations!'
					this.showNewGameButton()
					break
				}

				stateEl.innerText = 'You lost.'
				this.showNewGameButton()
				break

			case 'tied':
				stateEl.innerText = 'It\'s a tie.'
				this.showNewGameButton()
				break

			case 'waiting':
				stateEl.innerText = 'Waiting for your opponent...'
				break

			case 'error':
				stateEl.innerText = 'An error occured. Please refresh and try again.'
				this.showNewGameButton()
				break

			default:
				stateEl.innerText = 'It\'s their turn.'
		}

		this.applyTurnColor(color)
	}

	showNewGameButton() {
		newGameArea.classList.add('--active')
	}

	hideNewGameButton() {
		newGameArea.classList.remove('--active')
	}

	showLinkArea() {
		linkArea.classList.add('--active')
	}

	hideLinkArea() {
		linkArea.classList.remove('--active')
	}

	startNewGame() {
		axios.post('/play/tic-tac-toe/start-new/' + this.id, {
			player: this.player,
			auth: this.auth
		})
		.then((res) => {
			this.id = res.data.game_id
			this.auth = res.data.auth

			this.applyTurn('waiting')
			this.applyState('empty')
		})
		.catch((err) => {
			console.error(err.response)
			this.applyTurn('error')
		})
	}

	addStartNewButtonListener() {
		newGameArea.querySelector('[data-action="new-game"]').addEventListener('click', (e) => {
			e.preventDefault()
			this.startNewGame()
		})
	}

	swapPlayer() {
		console.info('swapping player', this.player)

		if (this.player == 'X') {
			this.player = 'O'
		} else if (this.player == 'O') {
			this.player = 'X'
		}
	}
}

new TicTacToe()
