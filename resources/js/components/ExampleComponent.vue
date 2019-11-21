<template>
    <div class="container">
        {{ count }}

        <input type="number" name="" v-model="count">
    </div>
</template>

<script>
	import { mapState, mapMutations, mapGetters, mapActions } from 'vuex'

    export default {
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
    }
</script>
