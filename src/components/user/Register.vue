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
      </div>
      <div class="register_part">
        {{ errors }}
        <input type="text" placeholder="Username" v-model="user.email" />
        <input type="text" placeholder="Password" v-model="user.password" />
        <button @click="registerUser">Register</button>
    </div>
</div>
</template>

<script>
import extension from '../../extendedComponenet'
import { EventBus } from '../../eventBus'

export default {
    extends: extension,
    name: 'user-register',
    data () {
        return {
            errors: 'no errors',
            user: {
                email:'admin@admin.com',
                password: 'pass@123'
            },
            bannerTitle: 'Register',
            message: 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Asperiores debitis, hic! Itaque recusandae amet aut aperiam tempora optio adipisci similique ipsum sequi aspernatur labore rerum quo natus doloribus, necessitatibus expedita?'
        }
    },
    created() {
        var self = this
        EventBus.$on('user::signUpError', function(error) {
            self.errors = error
        });
        EventBus.$on('user::signIn', function(error) {
            self.$router.replace({ name: 'UserAccount'})
        });
    },
    methods: {
        registerUser() {
            this.auth.registerUser(this.user.email, this.user.password);
        }
    }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
.hero_banner {
    background-image: url(../../assets/images/pexels-photo-534164.jpeg);
}
.hero_banner_text {
    color: yellow;
}
</style>
