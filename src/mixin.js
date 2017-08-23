import auth from './auth'
import { EventBus } from './eventBus';

// mixin.js
export default {

    data() {
        return {
            AuthUser: 'no-user',
        }
    },

    computed: {
        auth() {
            return auth
        }
    },

    created() {
        console.log(this.$options.name + ' : ' + 'created.')
        var self = this
        this.mixSignInEventHandler = function(user) {
            self.AuthUser = self.auth.currentUser
            self.authUserIsSet()
        }
        this.mixSignOutEventHandler = function() {
            self.AuthUser = null
            self.authUserIsNotSet()
        }

        self.AuthUser = self.auth.currentUser
        if(typeof self.auth.currentUser === typeof {}) {
            self.authUserIsSet()
        }

        if(self.auth.currentUser === null) {
            self.authUserIsNotSet()
        }

        EventBus.$on('user::signIn', this.mixSignInEventHandler)
        EventBus.$on('user::signOut', this.mixSignOutEventHandler)
    },

    mounted() {
        this.prepareEventsAndBind()
    },

    destroyed() {
        console.log(this.$options.name + ' : ' + 'destroyed.')
        EventBus.$off('user::signIn', this.mixSignInEventHandler)
        EventBus.$off('user::signOut', this.mixSignOutEventHandler)
        this.prepareEventsAndRelease()
    },

    methods: {
        prepareEventsAndBind() {
        },
        prepareEventsAndRelease() {
        },
        getRouteName() {
            return _.snakeCase(this.$route.name)
        },
        getComponentName() {
            return this.$options.name
        },
        authUserIsSet() {
        },
        authUserIsNotSet() {
        }
    }
}