import Vue from 'vue'
import _ from 'lodash';

import App from './App'
import mixin from './mixin'
import auth from './auth'
import router from './router'
import axios from 'axios';

// globally adding lodash
window.apiUrl = 'http://7.localhost/vuejs/my_frame_work_ready/my-framework-dist/v1/api';

axios.defaults.baseURL = window.apiUrl;
axios.defaults.headers.common['Authorization'] = 'AUTH_TOKEN';
// axios.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded; charset=UTF-8';

window._ = _
window.VueExtension = [mixin]

var ExtendedVue = Vue.extend({
    mixins: window.VueExtension
})

// initializing authentication
auth.init()

/* eslint-disable no-new */
new ExtendedVue({
    el: '#app',
    router,
    template: '<App/>',
    components: { App }
})

