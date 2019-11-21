
require('./bootstrap');

window.Vue = require('vue');

import Vuex from 'vuex'
Vue.use(Vuex)

const store = new Vuex.Store({
	state: {
		count: 0,
	},
	actions: {
		
	},
	mutations: {
		updateCount (state, newCount) {
			state.count = newCount
		}
	},
	getters: {
		
	}
})

Vue.component('example-component', require('./components/ExampleComponent.vue').default);

const app = new Vue({
    el: '#app',
    store,
});
