<template>
    <div :class="getRouteName()+'_view_port'">
        <div class="hero_banner">
            <div class="hero_banner_bg"></div>
            <div class="hero_container">
                <span class="hero_banner_text">
                  <h1>{{ bannerTitle }}</h1>
              </span>
              <span class="hero_text">
                {{message}}
            </span>
        </div>
        <div class="login_part">
            {{ errors }}
            <input type="text" placeholder="Username" v-model="user.email" />
            <input type="text" placeholder="Password" v-model="user.password" />
            <button @click="login">Login</button>
        </div>
    </div>
</div>
</template>

<script>
import extension from '../../extendedComponenet'
import { EventBus } from '../../eventBus'

export default {
    extends: extension,
    name: 'user-login',
    data () {
        return {
            errors: 'no errors',
            user: {
                email:'hardik@admin.com',
                password: 'pass'
            },
            bannerTitle: 'Login',
            message: 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Asperiores debitis, hic! Itaque recusandae amet aut aperiam tempora optio adipisci similique ipsum sequi aspernatur labore rerum quo natus doloribus, necessitatibus expedita?'
        }
    },
    created() {
        var self = this
        this.signInErrorEventHandler = function(error) {

            $.oc.flashMsg({ text: error.data.message, class: 'error'})
            // self.errors = error.message
        }
        this.signInEventHandler = function() {
            // console.log(self.$router)
            // console.log(self.$route)
            // console.log(self.$route.query)
            self.$router.replace(self.$route.query.redirect || '/')
        }
        EventBus.$on('user::signInError', this.signInErrorEventHandler)
        EventBus.$on('user::signIn', this.signInEventHandler)
    },
    destroyed() {
        EventBus.$off('user::signInError', this.signInErrorEventHandler)
        EventBus.$off('user::signIn', this.signInEventHandler)
    },
    methods: {
        login () {
            this.auth.login(this.user.email, this.user.password)
        }
    }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
.hero_banner {
    background-image: url(../../assets/images/pexels-photo-273238.jpeg);
}
</style>
