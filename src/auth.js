// auth.js
import { EventBus } from './eventBus'
import axios from 'axios';

export default {
    currentUser: null,
    access_token: null,
    init () {
        var self = this
        if(localStorage.getItem('hs.api_user') !== null) {
            this.currentUser = JSON.parse(localStorage.getItem('hs.api_user'))
            this.access_token = localStorage.getItem('hs.api_access_token')
            axios.defaults.headers.common['Authorization'] = 'Bearer ' + this.access_token
            EventBus.$emit('user::signIn', this.currentUser)
        }
    },
    getUser() {

    },
    getRemoteUser() {
        var self = this
        return  axios.get(window.apiUrl + '/login/user').then(function (response) {
            self.currentUser = response.data
            EventBus.$emit('user::signIn', response.data);
        }).catch(function (error) {
            // console.log(error.response);
            $.oc.flashMsg({text: error.response.data.message, 'class': 'error'});
        });
    },
    login (email, password) {
        var self = this;
        var data = new FormData();
        data.append('email', email);
        data.append('password', password);

        // this.currentUser = null
        axios.post('login/token', data).then(response => {
            self.access_token = response.data.access_token
            axios.defaults.headers.common['Authorization'] = 'Bearer ' + response.data.access_token
            var getUserReq = self.getRemoteUser();
            getUserReq.then(() => {
                localStorage.setItem('hs.api_user', JSON.stringify(self.currentUser));
                localStorage.setItem('hs.api_access_token', self.access_token );
            })
        }).catch(function (error) {
            EventBus.$emit('user::signInError', error.response)
        });
    },
    /*registerUser(email, password) {
        firebase.auth().createUserWithEmailAndPassword(email, password).then(function() {
        }).catch(function(error) {
            EventBus.$emit('user::signUpError', error);
        });
    },*/
    getToken () {
        return this.access_token
    },
    logout () {
        this.currentUser = null
        axios.delete(window.apiUrl +'/login/logout/' + this.access_token)
        .then(response => {
            self.access_token = null
            axios.defaults.headers.common['Authorization'] = null
            localStorage.removeItem('hs.api_user')
            localStorage.removeItem('hs.api_access_token')
            EventBus.$emit('user::signOut');
        }).catch(function (error) {
            EventBus.$emit('user::signOutError', error.response);
        });
    },
    isLoggedIn () {

        if(this.currentUser !== null) {
            return true
        }
        else {
            return false
        }
    }
}