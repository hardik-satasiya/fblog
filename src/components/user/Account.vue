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

        <div class="login_part">
            {{ errors }}
            <input type="text" placeholder="Display Name" v-model="userDetails.displayName" />
            <img class="profile_pic" :src="userDetails.photoURL" />
            <button @click="updateUserDetails">update</button>
        </div>
    </div>
</template>

<script>
import extension from '../../extendedComponenet'

export default {
    extends: extension,
    name: 'user-account',
    data () {
        return {
            errors: 'no error',
            userDetails: {},
            bannerTitle: 'Account',
            message: 'User Account'
        }
    },
    created() {

    },
    methods: {
        updateUserDetails() {
            var self = this
            this.auth.currentUser.updateProfile(this.userDetails).then(function() {
                self.errors = 'Data Updated.'
            }).catch(function(error) {
                self.errors = error
            });
        },
        authUserIsSet() {
            this.userDetails = this.AuthUser.providerData[0]
        }
    }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
.hero_banner {
    background-image: url(../../assets/images/pexels-photo-269435.jpeg);
}
.profile_pic {
    height: 50px;
    width: 50px;
    border-radius: 50%;
}
</style>
