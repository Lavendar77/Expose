# Exposé
Guide for my Laravel Applications.

## Installation
`npm install vuex --save`

## Configuration
Open `resources/js/app.js`
```js
import Vuex from 'vuex';
Vue.use(Vuex);
```

## Usage
1. Create a store
2. Create the state object, where our 'data()' members are stored

### Stage 1 - Using Mutations to Change a State Data in the Store
> **Note:** We do not change states directly, we use mutations. This will prevent errors and let us track all changes using debugging tools.
>> Then, we commit the mutation and log it to the console

```js
const store = new Vuex.Store({
	state: {
		count: 0
	},
	mutations: {
		increment (state) {
			state.count++
		}
	}
})

// Commit the mutation
store.commit('increment');

// Log it to the console
console.log(store.state.count);
``` 

### Stage 2 - Using Computed Properties to Get State Data
```js
// app.js
const store = new Vuex.Store({
	state: {
		count: 0
	},
})

new Vue({ 
    el: '#app',
    computed: {
        count () {
            return store.state.count
        }
    }
});
```

```html
<div id="app">
	{{ count }}
</div>
```

### Stage 3 - Using the `Map State` Helper
https://vuex.vuejs.org/guide/state.html#the-mapstate-helper

```js
import { mapState } from 'vuex';

new Vue({ 
    el: '#app',
    store,

    // Array of strings
	computed: mapState([
	    'count'
	])
});
```

### Stage 4 - Using Getters
```js
// app.js
const store = new Vuex.Store({
	state: {
		tasks: [
			{
				id: 0,
				item: 'eat',
				done: true
			},
			{
				id: 1,
				item: 'dance',
				done: false
			},
			{
				id: 2,
				item: 'code',
				done: true
			},
		]
	},
	getters: {
		// Default
		doneTasks: (state) => {
			return state.tasks.filter(task => task.done)
		},

		// Passing other getters into a getter
		doneTasksCount: (state, getters) => {
			return getters.doneTasks.length
		},

		// Passing parameters into getters
		getTaskById: (state) => (id) => {
			return state.tasks.find(task => task.id === id)
		}
	}
})

console.log(store.getters.getTaskById(2))
```

### Stage 5 - Changing our Store Data with Mutations
Mutations have three important terms:
- Type
- Data (which must contain 'state', then any other form of data)
- Handler
```js
const store = new Vuex.Store({
	state: {
		count: 0
	},
	mutations: {
		type (data) {
			handler
		}
	}
})
```

#### Passing One Extra Argument to Mutations
```js
mutations: {
	incrementByN (state, n) {
		state.count += n
	}
}

store.commit('incrementByN', 7)
```

#### Passing Multiple Arguments to Mutations
```js
mutations: {
	incrementByN (state, payload) {
		state.count += payload.amount
	}
}

store.commit('incrementByN', { amount: 7 })
```
**Note:** Payload is an object.

> Another style of commiting mutations 
```js
store.commit({
	type: 'incrementByN',
	amount: 7
})
```

#### Commiting Mutations Within Our App
1. Create a method
```js
methods: {
	increment () {
		this.$store.commit({
			type: 'incrementByN',
			amount: 5
		})
	}
}
```
2. Using mapMutations
```js
import { mapMutations } from 'vuex'
.
..
...
methods: mapMutations([
	'increment',
	'decrement',
])
```

### Stage 6 - Actions
Actions are similar to mutations - they change our state. However, actions are `dispatched`, in turn commits a mutation.
> Mutations are synchronous, while Actions can be asynchronous.

#### Default Syntax
```js
actions {
	increment (context) {
		context.commit('increment')
	}
}
```
`context` is an object that exposes the same methods and properties of the store instance.

```js
this.$store.dispatch('increment')
```

#### Needing Only One Property or Method in the Store Instance
```js
actions {
	increment ({ commit }) {
		commit('increment')
	}
}
```
Here, we need to only commit some mutations in this action.

#### Using `mapActions`
```js
methods: {
    ...mapActions([
        'increment'
    ]),
}
```

#### Working with Promise and Using Composing Actions
Actions are often asynchronous, so how do we know when an action is done? And more importantly, how can we compose multiple actions together to handle more complex async flows?

1. Promise
```js
actions: {
	decrement ({ commit }) {
		return new Promise((resolve, reject) => {
			setTimeout(() => {
				commit('decrement')
				resolve()
			}, 2000)
		})
	}
}
```
2. Method
```js
decrement() {
    this.$store.dispatch('decrement')
    .then(() => {
        console.log('done')
    })
},
```

### Stage 7 - Structure and Manage Vuex Store with Modules
Modules are a way of creating reusable code within our store.

#### Creating Modules
```js
const 	moduleA = {
	state: {
		count: 3
	},
	mutations: {

	},
	getters: {

	},
	actions: {

	}
}
```
#### Register the Module in the Store
```js
const store = new Vuex.Store({
	modules: {
		moduleA: moduleA
	},
})
```

We can now access our module store as:
```js
console.log(store.state.moduleA.count)
```

#### Accessing Mutations, Getters and Actions
##### Mutations
```js
const 	moduleA = {
	state: {
		count: 3
	},
	mutations: {
		increment (state) {
			state.count++
		}
	},
}

store.commit('incrementModuleA')
console.log(store.state.moduleA.count)
```
**Note: Once a module is registered in our global store, all its mutations arre inherited as well and can be called without specification.**
> For **reusability**, we can `namespaced` our modules and then call its mutations specifically.
```js
const 	moduleA = {
	namespaced: true,
}

store.commit('moduleA/incrementModuleA')
console.log(store.state.moduleA.count)
```
##### Actions
```js
store.dispatch('moduleA/increment')
```
##### Getters
However, accessing getters from a `namespaced` module
```js
store.getters['moduleA/count']
```

#### Submodules
```js
const 	moduleB = {
	namespaced: true,
	modules: {
		moduleC: {
			namespaced: true,
			state: {
				count: 2
			},
			mutations: {

			}
		},
	},
	state: {
		count: 3
	},
	mutations: {
		
	},
}

store.commit('moduleB/moduleC/increment')
console.log(store.state.moduleB.moduleC.count)
```

#### Accessing Global Assets From Within Our Modules
Let's say we want to access a state, mutation, getter or action from within our module, but those assets are already in our store.
```js
// In our module
state: {
	tasks: ['eat', 'dance', 'sleep']
},
getters: {
	getTasks (state) {
		return state.tasks
	}
}

// Normally, we would access and log this getter with:
console.log(store.getters['moduleA/getTasks'])

// In the global store
state: {
	tasks: ['code', 'play', 'chat']
}
getters: {
	getTasks (state) {
		return state.tasks
	}
}

// Let's say we now want to access this `state.tasks` in the module
getTasks (state, getters, rootState) {
	return rootState.tasks
}
// or the `state.getTasks` getter
getTasks (state, getters, rootState, rootGetters) {
	return rootGetters.getTasks
}
```
However, `dispatching` a root action or `commiting` a root mutation from within the module takes a different approach
```js
// In our global store
actions: {
	getFirstTask ({ state }) {
		console.log(state.tasks[0])
	}
}

// In the module
actions: {
	getRootFirstTask ({ dispatch }) {
		dispatch('getFirstTask', null, { root: true })

		/**
			* Checks the action type in our module = 'getFirstTask'
			* Payload
			* If the action type is in the root store or not
		*/
	}
}
```
**Note: The same syntax is used for mutations**

#### Accessing All the Vuex Methods Withing Our Components
Best way of accessing all of our methods is through the use of **helpers**
```js
import { mapState, mapMutations, mapGetters, mapActions } from 'vuex'
```

##### mapState
```js
computed: mapState({
	// From the store
	count: state => state.count,

	// From a module
	tasks: state => state.moduleA.tasks
})
```

##### mapActions
```js
// From the store
methods: mapActions([
    'getTasks'
])

// From the module
methods: mapActions('moduleA', [
    'getTasks'
])
```

### Stage 8 - Handling Forms using `v-model`
Think about if we want to update the state of our data in the store directly... We use `get` and `set`.
```js
// In the component
computed: {
    count: {
        get () {
            return this.$store.state.count
        },
        set (newCount) {
            this.$store.commit('updateCount', newCount)
        }
    }
}

// In the store
mutations: {
	updateCount (state, newCount) {
		state.count = newCount
	}
},
```

## Tutorial on Scrimba
- https://scrimba.com/p/pnyzgAP/cMPa2Uk