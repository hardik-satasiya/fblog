import Vue from 'vue'
import Router from 'vue-router'
import auth from '../auth'

// all major components
import Home from '../components/Home'
import About from '../components/About'
import Download from '../components/Download'
// import Three3D from '../components/Three3D'



// users
import UserLogin from '../components/user/Login'
import UserRegister from '../components/user/Register'
import UserAccount from '../components/user/Account'

Vue.use(Router)

const router = new Router({
    base: '/vuejs/my_frame_work_ready/my-framework-dist/',
    mode: 'history',
    routes: [
        {
            path: '/',
            name: 'Home',
            component: Home
        },

        // main comps
        {
            path: '/about',
            name: 'About',
            component: About
        },
        {
            path: '/download',
            name: 'Download',
            component: Download
        },
        // {
        //     path: '/three3d',
        //     name: 'Three3D',
        //     component: Three3D
        // },


        // users
        {
            path: '/user/register',
            name: 'UserRegister',
            component: UserRegister
        },
        {
            path: '/user/auth',
            name: 'UserLogin',
            component: UserLogin,
        },
        {
            path: '/user/account',
            name: 'UserAccount',
            component: UserAccount,
            beforeEnter: requireAuth
        },
        {
            path: '/user/login',
            beforeEnter: preserveRedirect,
            name: 'fakelogin'
        },
        {
            path: '/user/logout',
            name:'fakelogout',
            beforeEnter (to, from, next) {
                auth.logout()
                router.replace({'name': 'UserLogin'});
                router.replace(from.fullPath);
            }
        }
    ]
})

function preserveRedirect (to, from, next) {
    if(to.name == 'fakelogin' && from.name == 'UserLogin') {
        return;
    }
    next({
        path: '/user/auth',
        query: { redirect: from.fullPath }
    })
}

function requireAuth (to, from, next) {
    if (!auth.isLoggedIn()) {
        next({
            path: '/user/auth',
            query: { redirect: to.fullPath }
        })
    } else {
        next()
    }
}


// authentication
/*router.beforeEach((to, from, next) => {
    console.log(next.auth);
    next(vm => {
        // access to component instance via `vm`
    })
})*/

export default router