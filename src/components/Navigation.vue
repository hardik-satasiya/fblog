<template>
    <div id="main-navigation" class="container">
        <div class="Gnav-spacing has-subnav"></div>
        <div class="Gnav-sticky Gnav-active has-subnav">
            <header id="AppPrimaryNav" class="Gnav-wrapper" role="banner">
                <div class="Gnav-search-curtain"></div>
                <nav class="Gnav" role="navigation">
                    <a class="Gnav-logo" href="/" title="hswapp">
                       Cool Logo
                       <!--  <svg class="Gnav-logo-image" xmlns="http://www.w3.org/2000/svg" width="20" height="17" viewBox="0 0 24 20" focusable="false">
                            <path fill="#FF0000" d="M15.1,0H24v20L15.1,0z M8.9,0H0v20L8.9,0z M12,7.4L17.6,20h-3.8l-1.6-4H8.1L12,7.4z"></path>
                        </svg> -->
                        <span class="Gnav-logo-text">{{ navigation.logo.name }}</span>
                    </a>
                    <div class="Gnav-menu-wrapper">
                        <div class="Gnav-menu-content">
                            <ul class="Gnav-menu" data-gnav-scrollable="mobile">
                                <li v-for="item in navigation.mainnavi" class="Gnav-menu-item has-submenu">
                                    <router-link :to="item.action" :title="item.name" class="Gnav-menu-label">
                                        {{ item.name }}
                                    </router-link>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="Gnav-actions">
                        <div v-if="isLoggedIn"> {{ AuthUser.email }} </div>
                        <div v-else> No. User </div>
                        <!-- <a href="#" title="Search " class="Gnav-action-search" aria-haspopup="true" aria-expanded="false" role="button" data-adobe-analytics-linkname="Globalnav:Menu:Search">
                            <span class="Gnav-action-search-icons">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" focusable="false">
                                    <path d="M14 2A8 8 0 0 0 7.4 14.5L2.4 19.4a1.5 1.5 0 0 0 2.1 2.1L9.5 16.6A8 8 0 1 0 14 2Zm0 14.1A6.1 6.1 0 1 1 20.1 10 6.1 6.1 0 0 1 14 16.1Z"></path>
                                </svg>
                                <span class="Gnav-action-close"></span>
                            </span>
                        </a> -->
                        <router-link v-for="item in navigation.mainrightnavi" v-if="getMenuVisiblity(item)" :key="item.action" :to="item.action" :title="item.name" class="Gnav-action-login">{{item.name}}</router-link>
                    </div>
                </nav>
            </header>

            <div v-show="navigation.subnavi.visible" id="AppSecondaryNav" class="Subnav-wrapper is-dark has-gradient">
                <nav class="Subnav" data-signedin-force-trial="true">
                    <button class="Subnav-trigger" aria-expanded="false" aria-haspopup="true" aria-label="Adobe Experience Design CC (Beta)"></button>

                    <div class="Subnav-logo">
                        <i class="adobe-icon-color-xd-experience-design icon-spacing Subnav-logo-image"></i>
                        <h1 class="Subnav-logo-text">{{ navigation.subnavi.title }}</h1>
                    </div>
                    <div class="Subnav-menu-wrapper">

                        <ul class="Subnav-menu" data-gnav-scrollable="mobile-layout">

                            <li v-for="item in navigation.subnavi.items" class="Subnav-menu-item">
                                <router-link class="Subnav-menu-label" :to="item.action" :title="item.name" data-type="item">{{ item.name }}</router-link>
                            </li>

                        </ul>

                        <div class="Subnav-menu-buttons">
                             <router-link :to="navigation.subnavi.rightButton.action" :title="navigation.subnavi.rightButton.title" class="Subnav-menu-button fadeIn">
                                {{ navigation.subnavi.rightButton.title }}
                            </router-link>
                        </div>
                    </div>
                </nav>
            </div>
        </div>
    </div>
</template>

<script>
import extension from '../extendedComponenet'
import { EventBus } from '../eventBus'

export default {
    extends: extension,
    name: 'router-navigation',
    data () {
        return {
            isLoggedIn: false,
            msg: 'Home View',
            navigation: {
                logo:{
                    name: 'wpapp'
                },
                mainnavi: [
                    {
                        'name': 'Home',
                        'action': '/'
                    },
                    {
                        'name': 'Download',
                        'action': '/download'
                    },
                    {
                        'name': 'About',
                        'action': '/about'
                    },
                    {
                        'name': '3D',
                        'action': '/three3d'
                    },
                ],
                mainrightnavi: [
                    {
                        'name': 'Register',
                        'action': '/user/register',
                        'showInAuth': false
                    },
                    {
                        'name': 'Login',
                        'action': '/user/login',
                        'showInAuth': false
                    },
                    {
                        'name': 'Account',
                        'action': '/user/account',
                        'showInAuth': true
                    },
                    {
                        'name': 'Logout',
                        'action': '/user/logout',
                        'showInAuth': true
                    },
                ],
                subnavi: {
                    title: 'join us we are cool guys !',
                    visible: true,
                    rightButton: {
                        title: 'Download',
                        action: '/download'
                    },
                    items: [
                        {
                            'name': 'Account',
                            'action': '/user/account'
                        },
                    ]
                }

            }
        }
    },
    mounted() {
        this.initMagicNavigation();
    },
    created() {
        var self = this
        this.navigationShowEventListener = function(event) {
            self.navigation.subnavi.visible = true
        }

        this.navigationhideEventListener = function(event) {
            self.navigation.subnavi.visible = false
        }
        // this.isLoggedIn = this.auth.isLoggedIn();
        // EventBus.$on('user::signIn', function(user) {
        //     self.isLoggedIn = true;
        // })
        // EventBus.$on('user::signOut', function() {
        //     self.isLoggedIn = false;
        // })
    },
    methods: {
        prepareEventsAndBind() {
            EventBus.$on('Navigation::showSubnav', this.navigationShowEventListener)
            EventBus.$on('Navigation::hideSubnav', this.navigationhideEventListener)
        },
        prepareEventsAndRelease() {
            EventBus.$off('Navigation::showSubnav', this.navigationShowEventListener)
            EventBus.$off('Navigation::hideSubnav', this.navigationhideEventListener)
        },
        authUserIsSet() {
            this.isLoggedIn = true;
        },

        authUserIsNotSet() {
            this.isLoggedIn = false;
        },

        getMenuVisiblity(item) {
            if(item.showInAuth && this.isLoggedIn) {
                return true
            }

            if(!item.showInAuth && !this.isLoggedIn) {
                return true
            }

            return false;

        },

        initMagicNavigation() {
            var magicHeader = {
                init: function() {

                    if(this.hasInitialised) {
                        return true;
                    }

                    this.mainElement = document.querySelector('.Gnav-sticky'),

                    this.currentScrollPosition = 0
                    this.currentScrollDirection = void 0
                    this.previousScrollPosition = 0
                    this.previousScrollDirection = void 0
                    this.updating = !1
                    this.listenerAdded = !1
                    this.scrollTolerance = 10
                    this.instantCallbacks = {}
                    this.instantCallbacks.downScrollCallbacks = []
                    this.instantCallbacks.upScrollCallbacks = []
                    this.debounceCallbacks = {}
                    this.debounceCallbacks.downScrollCallbacks = []
                    this.debounceCallbacks.upScrollCallbacks = []
                    this.hasInitialised = !0

                    var _this = this
                    var initializer = {
                        onInstantUpScroll: function() {
                            _this.mainElement.classList.remove("is-retracted")
                        },
                        onInstantDownScroll: function() {
                            ((1 === _this.mainElement.children.length && window.pageYOffset > _this.mainElement.offsetHeight || _this.mainElement.children.length > 1)
                                && _this.mainElement.classList.add("is-retracted"), _this.mainElement.classList.add("is-scrolled"))
                        },
                        onDebouncedUpScroll: function() {
                            0 === window.pageYOffset && (_this.mainElement.classList.remove("is-scrolled"), _this.mainElement.classList.remove("is-retracted"))
                        }
                    }

                    initializer && (initializer.onInstantDownScroll && this.addDownScrollCallback(initializer.onInstantDownScroll),
                    initializer.onInstantUpScroll && this.addUpScrollCallback(initializer.onInstantUpScroll),
                    initializer.onDebouncedDownScroll && this.addDownScrollCallback(initializer.onDebouncedDownScroll, "debounce"),
                    initializer.onDebouncedUpScroll && this.addUpScrollCallback(initializer.onDebouncedUpScroll, "debounce")),
                    this.listenerAdded || (this.listen(), this.listenerAdded = !0)
                },
                requestUpdate: function() {
                    this.updating || requestAnimationFrame(this.update), this.updating = !0
                },
                update: function() {
                    magicHeader.getCurrentScrollDirection(), magicHeader.isToleranceExceeded() && ("up" === magicHeader.currentScrollDirection ? magicHeader.instantCallbacks.upScrollCallbacks.forEach(function(e) {
                        e()
                    }) : magicHeader.instantCallbacks.downScrollCallbacks.forEach(function(e) {
                        e()
                    }), magicHeader.previousScrollPosition = magicHeader.currentScrollPosition), magicHeader.previousScrollDirection = magicHeader.currentScrollDirection, magicHeader.updating = !1
                },
                isPageFullyScrolled: function() {
                    return this.currentScrollPosition + window.innerHeight >= document.height
                },
                isPageNotScrolled: function() {
                    return this.currentScrollPosition <= 0
                },
                getCurrentScrollDirection: function() {
                    this.isPageNotScrolled()
                        ? this.currentScrollDirection = "up" :
                            this.isPageFullyScrolled() ? this.currentScrollDirection = "down" :
                                this.previousScrollDirection && this.currentScrollPosition === this.previousScrollPosition ? this.currentScrollDirection = this.previousScrollDirection :
                                    this.currentScrollDirection = this.currentScrollPosition > this.previousScrollPosition ? "down" :
                                        "up"
                },
                isToleranceExceeded: function() {
                    return this.currentScrollPosition >= this.previousScrollPosition + this.scrollTolerance || this.currentScrollPosition <= this.previousScrollPosition - this.scrollTolerance
                },
                listen: function() {
                    var e;
                    var _this = this;
                    window.addEventListener("scroll", function() {
                        _this.currentScrollPosition = window.pageYOffset, _this.currentScrollPosition !== _this.previousScrollPosition && (_this.requestUpdate(), clearTimeout(e), e = setTimeout(function() {
                            _this.getCurrentScrollDirection(), "up" === _this.currentScrollDirection ? _this.debounceCallbacks.upScrollCallbacks.forEach(function(e) {
                                e()
                            }) : "down" === _this.currentScrollDirection && _this.debounceCallbacks.downScrollCallbacks.forEach(function(e) {
                                e()
                            }), _this.previousScrollPosition = _this.currentScrollPosition
                        }, 50))
                    })
                },
                addDownScrollCallback: function(t, i) {
                    ("debounce" === i ? this.debounceCallbacks.downScrollCallbacks.push(t) : this.instantCallbacks.downScrollCallbacks.push(t))
                },
                addUpScrollCallback: function(t, i) {
                    ("debounce" === i ? this.debounceCallbacks.upScrollCallbacks.push(t) : this.instantCallbacks.upScrollCallbacks.push(t))
                }
            }
            magicHeader.init();
        }
    }
}
</script>

<style scoped lang="scss">
.Gnav-spacing {
    height: 60px;
    background-color: #fff
}
.Gnav-spacing.has-subnav {
    height: 40px
}
.Gnav-spacing.is-hidden {
    display: none
}
.Gnav-active {
    position: relative
}
.Gnav-sticky {
    position: fixed;
    top: 0;
    right: 0;
    left: 0;
    width: 100%;
    z-index: 10;
    transition: -webkit-transform .3s ease;
    transition: transform .3s ease;
    transition: transform .3s ease, -webkit-transform .3s ease
}
.Gnav-sticky.is-retracted {
    -webkit-transform: translateY(-60px);
    transform: translateY(-60px)
}
.Gnav-sticky.is-retracted.has-subnav {
    -webkit-transform: translateY(-40px);
    transform: translateY(-40px)
}
@media screen and (min-width: 600px) {
    .Gnav-spacing {
        height: 80px
    }
    .Gnav-spacing.has-subnav {
        height: 60px
    }
    .Gnav-sticky.is-retracted {
        -webkit-transform: translateY(-80px);
        transform: translateY(-80px)
    }
    .Gnav-sticky.is-retracted.has-subnav {
        -webkit-transform: translateY(-60px);
        transform: translateY(-60px)
    }
}
#AppPrimaryNav .Gnav-logo {
    position: absolute;
    top: 0;
    left: 50%;
    height: 100%;
    padding: 0 20px;
    display: -webkit-flex;
    display: -ms-flexbox;
    display: flex;
    -webkit-align-items: center;
    -ms-flex-align: center;
    align-items: center;
    -webkit-flex-shrink: 0;
    -ms-flex-negative: 0;
    flex-shrink: 0;
    -webkit-transform: translateX(-50%);
    transform: translateX(-50%)
}
#AppPrimaryNav .Gnav-logo-text {
    margin-left: 10px;
    display: none;
    font-size: 16px;
    font-weight: 700;
    color: #2d2d2d
}
@media screen and (min-width: 600px) {
    #AppPrimaryNav .Gnav-logo {
        position: static;
        height: auto;
        -webkit-transform: none;
        transform: none
    }
    #AppPrimaryNav .Gnav-logo-text {
        display: block
    }
}
#AppPrimaryNav.Gnav-wrapper {
    position: relative;
    height: 60px;
    display: -webkit-flex;
    display: -ms-flexbox;
    display: flex;
    z-index: 2;
    background-color: #fff;
    -webkit-font-smoothing: antialiased;
    color: #2d2d2d;
    fill: #2d2d2d;
    transition: opacity 1s ease;
    opacity: 0;
    overflow: hidden
}
.Gnav-active #AppPrimaryNav.Gnav-wrapper {
    opacity: 1;
    overflow: visible
}
#AppPrimaryNav.Gnav-wrapper:nth-last-child(2) {
    height: 40px
}
#AppPrimaryNav.Gnav-wrapper:nth-last-child(2):before {
    top: 40px
}
.Gnav-sticky.is-retracted #AppPrimaryNav.Gnav-wrapper {
    -webkit-animation: toggle-gnav-visibility .3s ease both;
    animation: toggle-gnav-visibility .3s ease both
}
#AppPrimaryNav.Gnav-wrapper *,
#AppPrimaryNav.Gnav-wrapper:after,
#AppPrimaryNav.Gnav-wrapper:before {
    box-sizing: border-box
}
@media screen and (min-width: 600px) {
    #AppPrimaryNav.Gnav-wrapper {
        height: 80px
    }
    #AppPrimaryNav.Gnav-wrapper.show-searchOverlay .Gnav-search-curtain {
        display: block
    }
    #AppPrimaryNav.Gnav-wrapper.showing-searchOverlay .Gnav-search-curtain {
        -webkit-animation: curtain-show .1s ease-in;
        animation: curtain-show .1s ease-in
    }
    #AppPrimaryNav.Gnav-wrapper.hiding-searchOverlay .Gnav-search-curtain {
        -webkit-animation: curtain-show .1s ease-out reverse;
        animation: curtain-show .1s ease-out reverse
    }
    #AppPrimaryNav.Gnav-wrapper:nth-last-child(2) {
        height: 60px;
        color: #c7c7c7;
        fill: #c7c7c7
    }
    #AppPrimaryNav.Gnav-wrapper:nth-last-child(2) .Gnav-search-curtain {
        top: 60px
    }
    #AppPrimaryNav.Gnav-wrapper:hover:nth-last-child(2) {
        color: #2d2d2d;
        fill: #2d2d2d
    }
}
#AppPrimaryNav .Gnav-search-curtain {
    position: fixed;
    top: 80px;
    right: 0;
    bottom: 0;
    left: 0;
    width: 100%;
    display: none;
    background-color: rgba(0, 0, 0, .5)
}
#AppPrimaryNav .Gnav {
    width: 100%;
    max-width: 1440px;
    margin: 0 auto;
    display: -webkit-flex;
    display: -ms-flexbox;
    display: flex;
    -webkit-justify-content: space-between;
    -ms-flex-pack: justify;
    justify-content: space-between;
    -webkit-flex-shrink: 0;
    -ms-flex-negative: 0;
    flex-shrink: 0;
    font-family: adobe-clean, sans-serif
}
@media screen and (min-width: 1440px) {
    #AppPrimaryNav .Gnav.is-large {
        max-width: none;
        -webkit-justify-content: center;
        -ms-flex-pack: center;
        justify-content: center
    }
}
#AppPrimaryNav .Gnav-menu-wrapper {
    position: relative;
    display: -webkit-flex;
    display: -ms-flexbox;
    display: flex;
    -webkit-order: -1;
    -ms-flex-order: -1;
    order: -1;
    -webkit-flex-shrink: 0;
    -ms-flex-negative: 0;
    flex-shrink: 0
}
#AppPrimaryNav .Gnav-menu-wrapper.is-open .Gnav-menu-content {
    display: -webkit-flex;
    display: -ms-flexbox;
    display: flex
}
@media screen and (min-width: 600px) {
    #AppPrimaryNav .Gnav-menu-wrapper {
        -webkit-flex-grow: 1;
        -ms-flex-positive: 1;
        flex-grow: 1;
        -webkit-justify-content: center;
        -ms-flex-pack: center;
        justify-content: center;
        -webkit-order: 0;
        -ms-flex-order: 0;
        order: 0
    }
}
#AppPrimaryNav .Gnav-menu-content {
    position: absolute;
    top: 100%;
    left: 0;
    width: 100vw;
    border-top: 1px solid #f3f3f3;
    display: none;
    -webkit-flex-direction: column;
    -ms-flex-direction: column;
    flex-direction: column;
    background-color: #fff;
    box-shadow: 1px 3px 3px rgba(0, 0, 0, .2)
}
@media screen and (min-width: 600px) {
    #AppPrimaryNav .Gnav-menu-content {
        position: static;
        width: auto;
        border-top: none;
        display: -webkit-flex;
        display: -ms-flexbox;
        display: flex;
        -webkit-flex-direction: row;
        -ms-flex-direction: row;
        flex-direction: row;
        box-shadow: none
    }
}
#AppPrimaryNav .Gnav-menu {
    padding: 0;
    margin: 0;
    max-height: 75vh;
    display: -webkit-flex;
    display: -ms-flexbox;
    display: flex;
    -webkit-flex-direction: column;
    -ms-flex-direction: column;
    flex-direction: column;
    overflow-y: auto
}
#AppPrimaryNav .Gnav-menu.is-hidden {
    display: none;
    visibility: hidden
}
@media screen and (min-width: 600px) {
    #AppPrimaryNav .Gnav-menu {
        max-height: none;
        -webkit-flex-direction: row;
        -ms-flex-direction: row;
        flex-direction: row;
        overflow-y: visible
    }
}
#AppPrimaryNav .Gnav-menu-item {
    position: relative;
    padding-top: 1px;
    display: -webkit-flex;
    display: -ms-flexbox;
    display: flex;
    -webkit-flex-direction: column;
    -ms-flex-direction: column;
    flex-direction: column;
    -webkit-flex-shrink: 0;
    -ms-flex-negative: 0;
    flex-shrink: 0
}
#AppPrimaryNav .Gnav-menu-item:before {
    position: absolute;
    top: 0;
    right: 0;
    left: 0;
    margin: 0 5px;
    border-top: 1px solid #f3f3f3;
    content: ""
}
#AppPrimaryNav .Gnav-menu-item.has-submenu .Gnav-menu-label {
    padding-right: 35px
}
#AppPrimaryNav .Gnav-menu-item.has-submenu .Gnav-menu-label:after {
    display: block
}
#AppPrimaryNav .Gnav-menu-item.is-open>.Gnav-menu-label {
    background-color: #f4f4f4;
    color: #2d2d2d
}
#AppPrimaryNav .Gnav-menu-item.is-open>.Gnav-menu-label:after {
    -webkit-transform: rotate(-180deg);
    transform: rotate(-180deg)
}
#AppPrimaryNav .Gnav-menu-item.is-open>.Gnav-submenus {
    max-height: none;
    visibility: visible
}
@media screen and (min-width: 600px) {
    #AppPrimaryNav .Gnav-menu-item.has-submenu .Gnav-menu-label:after,
    #AppPrimaryNav .Gnav-menu-item:before {
        display: none
    }
    #AppPrimaryNav .Gnav-menu-item {
        padding-top: 0;
        -webkit-flex-direction: row;
        -ms-flex-direction: row;
        flex-direction: row
    }
    #AppPrimaryNav .Gnav-menu-item.has-submenu .Gnav-menu-label {
        padding-right: 20px
    }
    #AppPrimaryNav .Gnav-menu-item.has-submenu .Gnav-menu-label:focus:after,
    #AppPrimaryNav .Gnav-menu-item.has-submenu .Gnav-menu-label:hover:after {
        display: block
    }
    #AppPrimaryNav .Gnav-menu-item.is-open>.Gnav-menu-label:after {
        -webkit-transform: none;
        transform: none
    }
}
#AppPrimaryNav .Gnav-menu-label {
    position: relative;
    height: 55px;
    padding: 0 20px;
    display: -webkit-flex;
    display: -ms-flexbox;
    display: flex;
    -webkit-align-items: center;
    -ms-flex-align: center;
    align-items: center;
    font-size: 16px;
    font-weight: 700;
    color: inherit;
    transition: color .1s ease, background-color .1s ease
}
#AppPrimaryNav .Gnav-menu-label:after {
    /*position: absolute;
    right: 20px;
    top: 50%;
    margin-top: -1.33px;
    border-width: 4px 4px 0;
    border-style: solid;
    border-color: #b2b2b2 transparent transparent;
    display: none;
    transition: -webkit-transform .1s ease;
    transition: transform .1s ease;
    transition: transform .1s ease, -webkit-transform .1s ease;
    -webkit-transform-origin: 50% 33%;
    transform-origin: 50% 33%;
    content: ""*/
}


#AppPrimaryNav .Gnav-submenu-wrapper+.Gnav-submenu-wrapper:before {
    position: absolute;
    top: 0;
    right: 0;
    left: 0;
    margin: 0 5px;
    border-top: 1px solid #f3f3f3;
    content: ""
}
@media screen and (min-width: 600px) {
    #AppPrimaryNav .Gnav-submenu-wrapper {
        min-width: 240px;
        padding: 25px 0;
        -webkit-flex-shrink: 0;
        -ms-flex-negative: 0;
        flex-shrink: 0;
        white-space: nowrap
    }
    #AppPrimaryNav .Gnav-submenu-wrapper+.Gnav-submenu-wrapper {
        border-left: 1px solid #f3f3f3
    }
    #AppPrimaryNav .Gnav-submenu-wrapper+.Gnav-submenu-wrapper:before {
        display: none
    }
}
@media all and (-ms-high-contrast: none),
(-ms-high-contrast: active) {
    #AppPrimaryNav .Gnav-submenu-wrapper {
        -webkit-flex-basis: 50%;
        -ms-flex-preferred-size: 50%;
        flex-basis: 50%;
        white-space: normal
    }
}
#AppPrimaryNav .Gnav-submenu-headline {
    margin: 0 0 15px;
    padding: 10px 35px 0;
    display: none;
    overflow: hidden;
    font-size: 16px;
    font-weight: 300;
    color: #767676
}
#AppPrimaryNav .Gnav-submenu {
    padding: 0
}
#AppPrimaryNav .Gnav-submenu-link {
    padding: 10px 35px;
    display: block;
    font-size: 15px;
    color: #2d2d2d
}
@media screen and (min-width: 600px) {
    #AppPrimaryNav .Gnav-submenu-headline {
        display: block
    }
    #AppPrimaryNav .Gnav-submenu-link {
        font-size: 14px;
        color: #2d2d2d;
        transition: color .1s ease, background-color .1s ease
    }
    #AppPrimaryNav .Gnav-submenu-link:focus,
    #AppPrimaryNav .Gnav-submenu-link:hover {
        color: #2b9af3;
        background-color: #f4f4f4
    }
}
#AppPrimaryNav .Gnav-submenu-cta {
    margin: 10px 35px;
    display: inline-block;
    padding: 0 12px;
    font-size: 14px;
    line-height: 32px;
    font-weight: 700;
    background-color: #1473e6;
    color: #fff;
    border-radius: 16px;
    transition: background-color .1s ease, box-shadow .1s ease
}
#AppPrimaryNav .Gnav-submenu-cta:focus,
#AppPrimaryNav .Gnav-submenu-cta:hover {
    background-color: #005abe
}
#AppPrimaryNav .Gnav-submenu-cta:focus {
    box-shadow: 0 0 0 1px #005abe
}
#AppPrimaryNav .Gnav-submenu-title {
    display: block;
    overflow: hidden
}
#AppPrimaryNav .Gnav-submenu-description {
    display: none;
    overflow: hidden;
    color: #656565;
    font-size: 13px;
    font-weight: 300
}
@media screen and (min-width: 600px) {
    #AppPrimaryNav .Gnav-submenu-description {
        display: block
    }
}
#AppPrimaryNav .Gnav-moreMenu {
    max-height: 75vh;
    min-width: 240px;
    padding: 15px 0 0
}
#AppPrimaryNav .Gnav-moreMenu-item.has-submenu .Gnav-moreMenu-label:after {
    display: block
}
#AppPrimaryNav .Gnav-moreMenu-item.is-open .Gnav-moreMenu-label {
    border-bottom-color: #f3f3f3
}
#AppPrimaryNav .Gnav-moreMenu-item.is-open .Gnav-moreMenu-label:after {
    -webkit-transform: rotate(-180deg);
    transform: rotate(-180deg)
}
#AppPrimaryNav .Gnav-moreMenu-item.is-open>.Gnav-submenus {
    max-height: none;
    visibility: visible
}
#AppPrimaryNav .Gnav-moreMenu-item+.Gnav-moreMenu-item {
    border-top: 1px solid #f3f3f3
}
#AppPrimaryNav .Gnav-moreMenu-item .Gnav-submenus {
    position: static;
    border: 0;
    display: block;
    transition: none;
    box-shadow: none
}
#AppPrimaryNav .Gnav-moreMenu-item .Gnav-submenu-wrapper {
    padding: 15px 0
}
#AppPrimaryNav .Gnav-moreMenu-item .Gnav-submenu-wrapper+.Gnav-submenu-wrapper {
    border-left: none;
    border-top: 1px solid #f3f3f3
}
#AppPrimaryNav .Gnav-moreMenu-item .Gnav-submenu-headline {
    display: none
}
#AppPrimaryNav .Gnav-moreMenu-label {
    position: relative;
    display: block;
    padding: 20px 35px;
    border-bottom: 1px solid transparent;
    font-size: 14px;
    color: #2d2d2d;
    transition: color .1s ease, background-color .1s ease
}
#AppPrimaryNav .Gnav-moreMenu-label:after {
    position: absolute;
    right: 20px;
    top: 50%;
    margin-top: -1.33px;
    border-width: 4px 4px 0;
    border-style: solid;
    border-color: #b2b2b2 transparent transparent;
    display: none;
    transition: border-top-color .1s ease;
    -webkit-transform-origin: 50% 33%;
    transform-origin: 50% 33%;
    content: ""
}
#AppPrimaryNav .Gnav-moreMenu-label:focus,
#AppPrimaryNav .Gnav-moreMenu-label:hover {
    color: #2b9af3;
    background-color: #f4f4f4
}
#AppPrimaryNav .Gnav-moreMenu-label:focus:after,
#AppPrimaryNav .Gnav-moreMenu-label:hover:after {
    border-top-color: #2b9af3
}
@media screen and (min-width: 600px) {
    #AppPrimaryNav .Gnav-menu-item.is-moreMenu {
        display: -webkit-flex;
        display: -ms-flexbox;
        display: flex
    }
}
#AppPrimaryNav .Gnav-actions {
    padding: 0 20px;
    display: -webkit-flex;
    display: -ms-flexbox;
    display: flex;
    -webkit-align-items: center;
    -ms-flex-align: center;
    align-items: center;
    -webkit-flex-shrink: 0;
    -ms-flex-negative: 0;
    flex-shrink: 0
}
#AppPrimaryNav .Gnav-action-search {
    padding: 10px;
    display: none
}
#AppPrimaryNav .Gnav-action-search svg {
    width: 20px;
    height: 20px;
    display: block
}
#AppPrimaryNav .Gnav-action-search svg path {
    fill: inherit;
    -webkit-transform-origin: center center;
    transform-origin: center center;
    transition: all .1s ease
}
#AppPrimaryNav .Gnav-action-search:hover svg path {
    fill: #2b9af3
}
#AppPrimaryNav .Gnav-action-search:hover .Gnav-action-close:after,
#AppPrimaryNav .Gnav-action-search:hover .Gnav-action-close:before {
    background-color: #2b9af3
}
#AppPrimaryNav .Gnav-action-search.is-open svg path {
    opacity: 0;
    -webkit-transform: rotateY(180deg);
    transform: rotateY(180deg)
}
#AppPrimaryNav .Gnav-action-search.is-open .Gnav-action-close {
    opacity: 1;
    -webkit-transform: rotateY(180deg);
    transform: rotateY(180deg)
}
@media screen and (min-width: 600px) {
    #AppPrimaryNav .Gnav-action-search {
        display: block
    }
}
#AppPrimaryNav .Gnav-action-search-icons {
    position: relative;
    display: block
}
#AppPrimaryNav .Gnav-action-close {
    position: absolute;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    width: 100%;
    height: 100%;
    opacity: 0;
    transition: all .1s ease
}
#AppPrimaryNav .Gnav-action-close:after,
#AppPrimaryNav .Gnav-action-close:before {
    position: absolute;
    top: 50%;
    left: 0;
    right: 0;
    width: 100%;
    height: 2px;
    display: block;
    background-color: #2d2d2d;
    border-radius: 1px;
    transition: background-color .1s ease;
    -webkit-transform-origin: center;
    transform-origin: center;
    content: ""
}
#AppPrimaryNav .Gnav-action-close:before {
    -webkit-transform: rotate(45deg);
    transform: rotate(45deg)
}
#AppPrimaryNav .Gnav-action-close:after {
    -webkit-transform: rotate(-45deg);
    transform: rotate(-45deg)
}
#AppPrimaryNav .Gnav-action-login {
    height: 100%;
    display: -webkit-flex;
    display: -ms-flexbox;
    display: flex;
    -webkit-align-items: center;
    -ms-flex-align: center;
    align-items: center;
    font-size: 14px;
    font-weight: 700;
    color: inherit
}
#AppPrimaryNav .Gnav-search {
    display: -webkit-flex;
    display: -ms-flexbox;
    display: flex;
    -webkit-flex-direction: column;
    -ms-flex-direction: column;
    flex-direction: column
}
@media screen and (min-width: 600px) {
    #AppPrimaryNav .Gnav-action-login {
        height: auto;
        white-space: nowrap;
        transition: color .1s ease
    }
    #AppPrimaryNav .Gnav-action-login:hover {
        color: #2b9af3
    }
    #AppPrimaryNav .Gnav-action-login:not(:first-child),
    #AppPrimaryNav .Gnav-actions .Gnav-profile:not(:first-child) {
        margin-left: 12px
    }
    #AppPrimaryNav .Gnav-search {
        position: absolute;
        top: 0;
        left: 50%;
        width: 100%;
        height: 100%;
        min-width: 280px;
        max-width: 750px;
        display: none;
        -webkit-flex-direction: row;
        -ms-flex-direction: row;
        flex-direction: row;
        -webkit-align-items: center;
        -ms-flex-align: center;
        align-items: center;
        -webkit-transform: translateX(-50%);
        transform: translateX(-50%);
        z-index: 11;
        background-color: #f4f4f4
    }
    #AppPrimaryNav .Gnav-search.is-open {
        display: -webkit-flex;
        display: -ms-flexbox;
        display: flex
    }
}
#AppPrimaryNav .Gnav-search-form {
    position: relative;
    width: 100%;
    height: 55px;
    padding: 0 20px;
    display: -webkit-flex;
    display: -ms-flexbox;
    display: flex;
    -webkit-align-items: center;
    -ms-flex-align: center;
    align-items: center
}
#AppPrimaryNav .Gnav-search-form.has-input .Gnav-search-icons svg path {
    opacity: 0;
    -webkit-transform: rotateY(180deg);
    transform: rotateY(180deg)
}
#AppPrimaryNav .Gnav-search-form.has-input .Gnav-search-close {
    visibility: visible;
    opacity: 1;
    -webkit-transform: rotateY(180deg);
    transform: rotateY(180deg);
    z-index: 3
}
@media screen and (min-width: 600px) {
    #AppPrimaryNav .Gnav-search-form {
        height: auto;
        padding: 0 30px
    }
    #AppPrimaryNav .Gnav-search-form.has-input .Gnav-search-icons svg path {
        opacity: 1;
        -webkit-transform: none;
        transform: none
    }
}
#AppPrimaryNav .Gnav-search-input {
    min-width: auto;
    padding: 0;
    border: none;
    -webkit-flex-grow: 1;
    -ms-flex-positive: 1;
    flex-grow: 1;
    font-size: 18px;
    font-weight: 300;
    font-family: inherit;
    background-color: transparent;
    box-shadow: none
}
#AppPrimaryNav .Gnav-search-input:active,
#AppPrimaryNav .Gnav-search-input:focus,
#AppPrimaryNav .Gnav-search-input:hover {
    outline: 0
}
#AdobeFooterNav .Footernav-menu-label:focus,
#AppSecondaryNav .Subnav-logo:focus,
#AppSecondaryNav .Subnav-menu-label:focus,
#AppSecondaryNav .Subnav-submenu-link:focus,
.Profile-header:focus,
.Profile-menu-link:focus,
.Profile-thumbnail:focus,
.accessibility-focus:focus {
    outline-offset: -3px
}
#AppPrimaryNav .Gnav-search-input::-ms-clear {
    width: 0;
    height: 0;
    display: none
}
#AppPrimaryNav .Gnav-search-icons {
    position: relative;
    margin-left: 10px
}
#AppPrimaryNav .Gnav-search-icons svg {
    position: relative;
    width: 20px;
    height: 20px;
    display: block;
    z-index: 2
}
#AppPrimaryNav .Gnav-search-icons svg path {
    fill: #2d2d2d;
    -webkit-transform-origin: center center;
    transform-origin: center center;
    transition: all .1s ease
}
#AppPrimaryNav .Gnav-search-close {
    position: absolute;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    width: 100%;
    height: 100%;
    display: block;
    visibility: hidden;
    opacity: 0;
    transition: opacity .1s ease, -webkit-transform .1s ease;
    transition: opacity .1s ease, transform .1s ease;
    transition: opacity .1s ease, transform .1s ease, -webkit-transform .1s ease;
    z-index: 1;
    cursor: pointer
}
#AppPrimaryNav .Gnav-search-close:after,
#AppPrimaryNav .Gnav-search-close:before {
    position: absolute;
    top: 50%;
    left: 0;
    right: 0;
    width: 100%;
    height: 2px;
    display: block;
    background-color: #2d2d2d;
    border-radius: 1px;
    transition: background-color .1s ease;
    -webkit-transform-origin: center;
    transform-origin: center;
    content: ""
}
#AppPrimaryNav .Gnav-search-close:before {
    -webkit-transform: rotate(45deg);
    transform: rotate(45deg)
}
#AppPrimaryNav .Gnav-search-close:after {
    -webkit-transform: rotate(-45deg);
    transform: rotate(-45deg)
}
@media screen and (min-width: 600px) {
    #AppPrimaryNav .Gnav-search-close {
        display: none
    }
}
#AppPrimaryNav .Gnav-search-results {
    position: relative;
    max-height: 75vh;
    display: none;
    background-color: #fff;
    overflow-y: auto
}
#AppPrimaryNav .Gnav-search-results:before {
    position: absolute;
    top: 0;
    right: 0;
    left: 0;
    margin: 0 5px;
    border-top: 1px solid #f3f3f3;
    content: ""
}
#AppPrimaryNav .Gnav-search-results.is-visible {
    display: block
}
@media screen and (min-width: 600px) {
    #AppPrimaryNav .Gnav-search-results {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        width: 100%
    }
    #AppPrimaryNav .Gnav-search-results:before {
        display: none
    }
    #AppPrimaryNav.Gnav-wrapper:nth-last-child(2) .Gnav-search-results {
        min-height: 80px
    }
    #AppPrimaryNav .Gnav-hubblets:not(:only-child) {
        border-bottom: 1px solid #f3f3f3
    }
    #AppPrimaryNav .Gnav-hubblets:not(:only-child):after {
        display: none
    }
}
#AppPrimaryNav .Gnav-hubblets {
    position: relative;
    padding: 22px 0;
    margin: 0
}
#AppPrimaryNav .Gnav-hubblets:not(:only-child):after {
    position: absolute;
    bottom: 0;
    right: 0;
    left: 0;
    margin: 0 5px;
    border-top: 1px solid #f3f3f3;
    content: ""
}
#AppPrimaryNav .Gnav-hubblet {
    width: 100%;
    padding: 8px 30px;
    display: -webkit-flex;
    display: -ms-flexbox;
    display: flex;
    -webkit-align-items: center;
    -ms-flex-align: center;
    align-items: center
}
#AppPrimaryNav .Gnav-hubblet-image-wrapper {
    margin-right: 20px;
    display: block
}
#AppPrimaryNav .Gnav-hubblet-image {
    width: 30px;
    -webkit-flex-shrink: 0;
    -ms-flex-negative: 0;
    flex-shrink: 0;
    -webkit-flex-grow: 0;
    -ms-flex-positive: 0;
    flex-grow: 0;
    display: block
}
#AppPrimaryNav .Gnav-hubblet-content {
    -webkit-flex-grow: 1;
    -ms-flex-positive: 1;
    flex-grow: 1
}
#AppPrimaryNav .Gnav-hubblet-headline {
    font-size: 15px;
    color: #2d2d2d;
    transition: color .1s ease
}
#AppPrimaryNav .Gnav-hubblet-headline:focus,
#AppPrimaryNav .Gnav-hubblet-headline:hover {
    color: #2b9af3
}
#AppPrimaryNav .Gnav-hubblet-description {
    margin: 0;
    padding: 0;
    display: none;
    -webkit-flex-wrap: wrap;
    -ms-flex-wrap: wrap;
    flex-wrap: wrap
}
@media screen and (min-width: 600px) {
    #AppPrimaryNav .Gnav-hubblet-description {
        display: -webkit-flex;
        display: -ms-flexbox;
        display: flex
    }
}
#AppPrimaryNav .Gnav-hubblet-description-item {
    display: -webkit-flex;
    display: -ms-flexbox;
    display: flex;
    font-size: 13px;
    font-weight: 300;
    line-height: 1.8
}
#AppPrimaryNav .Gnav-hubblet-description-item:after {
    margin: 0 5px;
    content: "|";
    color: #656565
}
#AppPrimaryNav .Gnav-hubblet-description-item:last-child:after {
    display: none
}
#AppPrimaryNav .Gnav-hubblet-description-link {
    color: #656565;
    transition: color .1s ease
}
#AppPrimaryNav .Gnav-hubblet-description-link:focus,
#AppPrimaryNav .Gnav-hubblet-description-link:hover,
#AppPrimaryNav .Gnav-suggestion-link:focus .Gnav-suggestion-headline,
#AppPrimaryNav .Gnav-suggestion-link:hover .Gnav-suggestion-headline {
    color: #2b9af3
}
#AppPrimaryNav .Gnav-suggestions {
    padding: 22px 0;
    margin: 0
}
#AppPrimaryNav .Gnav-suggestions:nth-last-child(2) {
    padding-bottom: 0
}
#AppPrimaryNav .Gnav-suggestion-link {
    padding: 8px 30px;
    display: block
}
#AppPrimaryNav .Gnav-suggestion-headline {
    display: block;
    font-size: 15px;
    color: #2d2d2d;
    transition: color .1s ease
}
#AppPrimaryNav .Gnav-suggestion-description {
    font-size: 13px;
    font-weight: 300;
    color: #656565
}
#AppPrimaryNav .Gnav-advancedSearch {
    padding-bottom: 22px
}
#AppPrimaryNav .Gnav-advancedSearch:only-child {
    padding: 22px 0
}
#AppPrimaryNav .Gnav-advancedSearch-link {
    padding: 8px 30px;
    display: block;
    font-size: 15px;
    color: #2d2d2d;
    transition: color .1s ease
}
#AppPrimaryNav .Gnav-advancedSearch-link:focus,
#AppPrimaryNav .Gnav-advancedSearch-link:hover {
    color: #2b9af3
}
[dir=rtl] #AppPrimaryNav .Gnav-menu-content {
    left: auto;
    right: 0
}
[dir=rtl] #AppPrimaryNav .Gnav-menu-item.is-open>.Gnav-menu-label:after {
    -webkit-transform: rotate(180deg);
    transform: rotate(180deg)
}
[dir=rtl] #AppPrimaryNav .Gnav-menu-item.has-submenu .Gnav-menu-label {
    padding-right: 20px;
    padding-left: 35px
}
[dir=rtl] #AppPrimaryNav .Gnav-menu-label:after {
    right: auto;
    left: 20px
}
[dir=rtl] #AppPrimaryNav .Gnav-hubblet-image-wrapper {
    margin-left: 20px;
    margin-right: 0
}
@media screen and (min-width: 600px) {
    [dir=rtl] #AppPrimaryNav .Gnav-logo-text {
        margin-left: 0;
        margin-right: 10px
    }
    [dir=rtl] #AppPrimaryNav .Gnav-menu-item.has-submenu .Gnav-menu-label {
        padding-left: 20px
    }
    [dir=rtl] #AppPrimaryNav .Gnav-menu-item.has-submenu .Gnav-menu-label:after {
        left: 50%
    }
    [dir=rtl] #AppPrimaryNav .Gnav-submenus {
        left: auto;
        right: 0
    }
    [dir=rtl] #AppPrimaryNav .Gnav-submenu-wrapper+.Gnav-submenu-wrapper {
        border-left: none;
        border-right: 1px solid #f3f3f3
    }
    [dir=rtl] #AppPrimaryNav .Gnav-moreMenu-item.is-open .Gnav-moreMenu-label:after {
        -webkit-transform: rotate(180deg);
        transform: rotate(180deg)
    }
    [dir=rtl] #AppPrimaryNav .Gnav-moreMenu-label:after {
        right: auto;
        left: 20px
    }
    [dir=rtl] #AppPrimaryNav .Gnav-action-login,
    [dir=rtl] #AppPrimaryNav .Gnav-actions .Gnav-profile {
        margin-left: 0;
        margin-right: 12px
    }
    [dir=rtl] #AppPrimaryNav .Gnav-search-icons {
        margin-left: auto;
        margin-right: 10px
    }
}
#AppSecondaryNav.Subnav-wrapper {
    position: absolute;
    top: 100%;
    right: 0;
    left: 0;
    min-height: 60px;
    display: -webkit-flex;
    display: -ms-flexbox;
    display: flex;
    -webkit-justify-content: center;
    -ms-flex-pack: center;
    justify-content: center;
    font-family: adobe-clean, sans-serif;
    -webkit-font-smoothing: antialiased;
    transition: height .3s ease, opacity 1s ease;
    z-index: 1;
    opacity: 0;
    -webkit-transform: translateZ(0);
    transform: translateZ(0);
    overflow: hidden
}
.Subnav-wrapper.has-gradient::before {
    background-image: linear-gradient(90deg, rgb(45, 0, 29), rgb(45, 0, 29));
    opacity: 0.4;
}
#AppSecondaryNav.Subnav-wrapper:before {
    position: absolute;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: #f8f8f8;
    content: "";
    transition: opacity .3s ease
}
.Gnav-active #AppSecondaryNav.Subnav-wrapper {
    opacity: 1;
    overflow: visible
}
.Gnav-sticky #AppSecondaryNav.Subnav-wrapper {
    position: relative;
    top: auto;
    right: auto;
    left: auto
}
#AppSecondaryNav.Subnav-wrapper *,
#AppSecondaryNav.Subnav-wrapper:after,
#AppSecondaryNav.Subnav-wrapper:before {
    box-sizing: border-box
}
@media screen and (min-width: 600px) {
    #AppSecondaryNav.Subnav-wrapper {
        height: 80px;
        min-height: auto
    }
    .Gnav-sticky.is-retracted #AppSecondaryNav.Subnav-wrapper {
        height: 60px
    }
}
#AppSecondaryNav .Subnav {
    position: relative;
    width: 100%;
    min-height: inherit;
    display: -webkit-flex;
    display: -ms-flexbox;
    display: flex;
    -webkit-justify-content: center;
    -ms-flex-pack: center;
    justify-content: center;
    -webkit-flex-shrink: 0;
    -ms-flex-negative: 0;
    flex-shrink: 0
}
#AppSecondaryNav .Subnav.is-open .Subnav-logo:after {
    -webkit-transform: rotate(-450deg);
    transform: rotate(-450deg)
}
#AppSecondaryNav .Subnav.is-open .Subnav-menu-wrapper {
    display: -webkit-flex;
    display: -ms-flexbox;
    display: flex;
    visibility: visible
}
@media screen and (min-width: 600px) {
    #AppSecondaryNav.Subnav-wrapper:not(.has-mobileLayout) .Subnav {
        width: auto
    }
    #AppSecondaryNav.Subnav-wrapper:not(.has-mobileLayout) .Subnav-trigger {
        display: none
    }
}
#AppSecondaryNav .Subnav-trigger {
    position: absolute;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    width: 100%;
    height: 100%;
    padding: 0;
    margin: 0;
    border: 0;
    cursor: pointer;
    z-index: 1;
    background-color: transparent;
    border-radius: 0;
    box-shadow: none
}
#AppSecondaryNav .Subnav-trigger:active,
#AppSecondaryNav .Subnav-trigger:focus,
#AppSecondaryNav .Subnav-trigger:hover {
    background-color: transparent
}
#AppSecondaryNav .Subnav-logo {
    max-width: 100%;
    display: -webkit-flex;
    display: -ms-flexbox;
    display: flex;
    padding: 0 25px;
    -webkit-align-items: center;
    -ms-flex-align: center;
    align-items: center;
    -webkit-flex-shrink: 0;
    -ms-flex-negative: 0;
    flex-shrink: 0;
    transition: background-color .1s ease
}
#AppSecondaryNav .Subnav-logo:after {
    margin: -4px 0 0 10px;
    display: none;
    color: #2d2d2d;
    font: 300 30px/1 adobe-clean, sans-serif;
    -webkit-transform-origin: 50% 60%;
    transform-origin: 50% 60%;
    -webkit-transform: rotate(-270deg);
    transform: rotate(-270deg);
    transition: -webkit-transform .1s ease;
    transition: transform .1s ease;
    transition: transform .1s ease, -webkit-transform .1s ease;
    content: "\203a"
}
#AppSecondaryNav .Subnav-logo:nth-last-child(2):after {
    display: block
}
@media screen and (min-width: 600px) {
    #AppSecondaryNav.Subnav-wrapper:not(.has-mobileLayout) a.Subnav-logo:focus,
    #AppSecondaryNav.Subnav-wrapper:not(.has-mobileLayout) a.Subnav-logo:hover {
        background-color: rgba(0, 0, 0, .1)
    }
    #AppSecondaryNav.Subnav-wrapper:not(.has-mobileLayout) .Subnav-logo {
        padding: 0 25px 0 20px
    }
    #AppSecondaryNav.Subnav-wrapper:not(.has-mobileLayout) .Subnav-logo:nth-last-child(2):after {
        display: none
    }
}
#AppSecondaryNav .Subnav-logo-image,
#AppSecondaryNav .Subnav-logo-image:after,
#AppSecondaryNav .Subnav-logo-image:before {
    height: 25px;
    font-size: 25px;
    line-height: 1;
    -webkit-flex-shrink: 0;
    -ms-flex-negative: 0;
    flex-shrink: 0
}
@media all and (-ms-high-contrast: none),
(-ms-high-contrast: active) {
    #AppSecondaryNav .Subnav-logo-image,
    #AppSecondaryNav .Subnav-logo-image:after,
    #AppSecondaryNav .Subnav-logo-image:before {
        -webkit-flex-basis: 25px;
        -ms-flex-preferred-size: 25px;
        flex-basis: 25px
    }
}
#AppSecondaryNav .Subnav-logo-text {
    padding: 10px 0;
    font-size: 15px;
    font-weight: 900;
    line-height: 1;
    color: #2d2d2d;
    text-transform: uppercase
}
#AppSecondaryNav .Subnav-logo-text:nth-child(2) {
    margin-left: 15px
}
#AppSecondaryNav .Subnav-menu-wrapper {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    display: none;
    visibility: hidden;
    -webkit-flex-direction: column;
    -ms-flex-direction: column;
    flex-direction: column;
    -webkit-flex-shrink: 0;
    -ms-flex-negative: 0;
    flex-shrink: 0;
    background-color: #fff;
    box-shadow: 1px 3px 3px rgba(0, 0, 0, .2)
}
#AppSecondaryNav .Subnav-menu {
    width: 100%;
    max-height: 50vh;
    padding: 10px 0 0;
    margin: 0;
    overflow-y: auto
}
@media screen and (min-width: 600px) {
    #AppSecondaryNav .Subnav-logo-text {
        font-size: 13px
    }
    #AppSecondaryNav.Subnav-wrapper:not(.has-mobileLayout) .Subnav-menu-wrapper {
        position: static;
        display: -webkit-flex;
        display: -ms-flexbox;
        display: flex;
        visibility: visible;
        -webkit-flex-direction: row;
        -ms-flex-direction: row;
        flex-direction: row;
        background-color: transparent;
        box-shadow: none
    }
    #AppSecondaryNav.Subnav-wrapper:not(.has-mobileLayout) .Subnav-menu {
        width: auto;
        max-height: none;
        padding: 0;
        display: -webkit-flex;
        display: -ms-flexbox;
        display: flex;
        overflow-y: visible
    }
}
#AppSecondaryNav .Subnav-menu-item {
    position: relative;
    padding-bottom: 1px;
    display: -webkit-flex;
    display: -ms-flexbox;
    display: flex;
    -webkit-flex-direction: column;
    -ms-flex-direction: column;
    flex-direction: column;
    -webkit-flex-shrink: 0;
    -ms-flex-negative: 0;
    flex-shrink: 0
}
#AppSecondaryNav .Subnav-menu-item:before {
    position: absolute;
    bottom: 0;
    right: 0;
    left: 0;
    margin: 0 40px;
    border-top: 1px solid #f3f3f3;
    content: ""
}
#AppSecondaryNav .Subnav-menu-item.has-submenu .Subnav-menu-label {
    padding-right: 65px
}
#AppSecondaryNav .Subnav-menu-item.has-submenu .Subnav-menu-label:after {
    display: block
}
#AppSecondaryNav .Subnav-menu-item.is-open>.Subnav-menu-label {
    background-color: #f4f4f4
}
#AppSecondaryNav .Subnav-menu-item.is-open>.Subnav-menu-label:after {
    -webkit-transform: rotate(-180deg);
    transform: rotate(-180deg)
}
#AppSecondaryNav .Subnav-menu-item.is-open>.Subnav-submenus {
    max-height: none;
    visibility: visible
}
@media screen and (min-width: 600px) {
    #AppSecondaryNav.Subnav-wrapper:not(.has-mobileLayout) .Subnav-menu-item.has-submenu .Subnav-menu-label:after,
    #AppSecondaryNav.Subnav-wrapper:not(.has-mobileLayout) .Subnav-menu-item:before {
        display: none
    }
    #AppSecondaryNav.Subnav-wrapper:not(.has-mobileLayout) .Subnav-menu-item {
        -webkit-flex-direction: row;
        -ms-flex-direction: row;
        flex-direction: row;
        padding-bottom: 0
    }
    #AppSecondaryNav.Subnav-wrapper:not(.has-mobileLayout) .Subnav-menu-item.has-submenu .Subnav-menu-label {
        padding-right: 18px
    }
    #AppSecondaryNav.Subnav-wrapper:not(.has-mobileLayout) .Subnav-menu-item.has-submenu .Subnav-menu-label:focus:after,
    #AppSecondaryNav.Subnav-wrapper:not(.has-mobileLayout) .Subnav-menu-item.has-submenu .Subnav-menu-label:hover:after,
    #AppSecondaryNav.Subnav-wrapper:not(.has-mobileLayout) .Subnav-menu-item.is-open>.Subnav-menu-label:before {
        display: block
    }
    #AppSecondaryNav.Subnav-wrapper:not(.has-mobileLayout) .Subnav-menu-item.is-open>.Subnav-menu-label {
        background-color: rgba(0, 0, 0, .1)
    }
    #AppSecondaryNav.Subnav-wrapper:not(.has-mobileLayout) .Subnav-menu-item.is-open>.Subnav-menu-label:focus:after,
    #AppSecondaryNav.Subnav-wrapper:not(.has-mobileLayout) .Subnav-menu-item.is-open>.Subnav-menu-label:hover:after {
        display: none
    }
    #AppSecondaryNav.Subnav-wrapper:not(.has-mobileLayout) .Subnav-menu-item.is-open>.Subnav-submenus {
        max-height: 50vh;
        display: -webkit-flex;
        display: -ms-flexbox;
        display: flex
    }
    #AppSecondaryNav.Subnav-wrapper:not(.has-mobileLayout) .Subnav-menu-item.is-hiddenOnDesktopLayout {
        display: none;
        visibility: hidden
    }
}
#AppSecondaryNav .Subnav-menu-label {
    position: relative;
    height: 55px;
    padding: 0 50px;
    display: -webkit-flex;
    display: -ms-flexbox;
    display: flex;
    -webkit-align-items: center;
    -ms-flex-align: center;
    align-items: center;
    font-size: 16px;
    font-weight: 700;
    color: #2d2d2d;
    transition: background-color .1s ease
}
#AppSecondaryNav .Subnav-menu-label.is-active:not([aria-haspopup]) {
    cursor: default;
    pointer-events: none
}
#AppSecondaryNav .Subnav-menu-label:after {
    position: absolute;
    right: 50px;
    top: 50%;
    margin-top: -1.33px;
    border-width: 4px 4px 0;
    border-style: solid;
    border-color: #b2b2b2 transparent transparent;
    display: none;
    transition: -webkit-transform .1s ease;
    transition: transform .1s ease;
    transition: transform .1s ease, -webkit-transform .1s ease;
    -webkit-transform-origin: 50% 33%;
    transform-origin: 50% 33%;
    content: ""
}
#AppSecondaryNav .Subnav-submenus {
    max-height: 0;
    visibility: hidden;
    overflow: hidden
}
@media screen and (min-width: 600px) {
    #AppSecondaryNav.Subnav-wrapper:not(.has-mobileLayout) .Subnav-menu-label {
        height: auto;
        padding: 0 18px;
        font-size: 14px
    }
    #AppSecondaryNav.Subnav-wrapper:not(.has-mobileLayout) .Subnav-menu-label.is-active,
    #AppSecondaryNav.Subnav-wrapper:not(.has-mobileLayout) .Subnav-menu-label:focus,
    #AppSecondaryNav.Subnav-wrapper:not(.has-mobileLayout) .Subnav-menu-label:hover {
        background-color: rgba(0, 0, 0, .1)
    }
    #AppSecondaryNav.Subnav-wrapper:not(.has-mobileLayout) .Subnav-menu-label:before {
        position: absolute;
        left: 50%;
        bottom: -4px;
        margin-left: -10px;
        border-style: solid;
        border-width: 0 10px 10px;
        border-color: transparent transparent #fff;
        display: none;
        content: "";
        pointer-events: none
    }
    #AppSecondaryNav.Subnav-wrapper:not(.has-mobileLayout) .Subnav-menu-label:after {
        bottom: 9px;
        left: 50%;
        right: auto;
        top: auto;
        margin-left: -3px;
        margin-top: auto;
        border-width: 3px 3px 0;
        display: none
    }
    #AppSecondaryNav.Subnav-wrapper:not(.has-mobileLayout) .Subnav-submenus {
        position: absolute;
        top: calc(100% + 4px);
        left: 0;
        display: none;
        transition: none;
        background-color: #fff;
        overflow-y: auto;
        box-shadow: 1px 3px 3px rgba(0, 0, 0, .2)
    }
}
#AppSecondaryNav .Subnav-submenu-wrapper {
    position: relative;
    padding: 15px 0
}
@media all and (-ms-high-contrast: none),
(-ms-high-contrast: active) {
    #AppSecondaryNav .Subnav-submenu-wrapper {
        -webkit-flex-basis: 50%;
        -ms-flex-preferred-size: 50%;
        flex-basis: 50%;
        white-space: normal
    }
}
#AppSecondaryNav .Subnav-submenu-wrapper+.Subnav-submenu-wrapper:before {
    position: absolute;
    top: 0;
    right: 0;
    left: 0;
    margin: 0 40px;
    border-top: 1px solid #f3f3f3;
    content: ""
}
@media screen and (min-width: 600px) {
    #AppSecondaryNav.Subnav-wrapper:not(.has-mobileLayout) .Subnav-submenu-wrapper {
        min-width: 240px;
        padding: 25px 0;
        -webkit-flex-shrink: 0;
        -ms-flex-negative: 0;
        flex-shrink: 0;
        white-space: nowrap
    }
    #AppSecondaryNav.Subnav-wrapper:not(.has-mobileLayout) .Subnav-submenu-wrapper+.Subnav-submenu-wrapper {
        border-left: 1px solid #f3f3f3
    }
    #AppSecondaryNav.Subnav-wrapper:not(.has-mobileLayout) .Subnav-submenu-wrapper+.Subnav-submenu-wrapper:before {
        display: none
    }
}
#AppSecondaryNav .Subnav-submenu-headline {
    margin: 0 0 15px;
    padding: 10px 35px 0;
    display: none;
    overflow: hidden;
    font-size: 16px;
    font-weight: 300;
    color: #767676
}
#AppSecondaryNav .Subnav-submenu {
    padding: 0
}
#AppSecondaryNav .Subnav-submenu-link {
    padding: 10px 65px;
    display: block;
    font-size: 15px;
    color: #2d2d2d
}
@media screen and (min-width: 600px) {
    #AppSecondaryNav.Subnav-wrapper:not(.has-mobileLayout) .Subnav-submenu-headline {
        display: block
    }
    #AppSecondaryNav.Subnav-wrapper:not(.has-mobileLayout) .Subnav-submenu-link {
        padding: 10px 35px;
        font-size: 14px;
        color: #2d2d2d;
        transition: color .1s ease, background-color .1s ease
    }
    #AppSecondaryNav.Subnav-wrapper:not(.has-mobileLayout) .Subnav-submenu-link:focus,
    #AppSecondaryNav.Subnav-wrapper:not(.has-mobileLayout) .Subnav-submenu-link:hover {
        color: #2b9af3;
        background-color: #f4f4f4
    }
}
#AppSecondaryNav .Subnav-submenu-title {
    display: block;
    overflow: hidden
}
#AppSecondaryNav .Subnav-submenu-description {
    display: none;
    overflow: hidden;
    color: #656565;
    font-size: 13px;
    font-weight: 300
}
@media screen and (min-width: 600px) {
    #AppSecondaryNav.Subnav-wrapper:not(.has-mobileLayout) .Subnav-submenu-description {
        display: block
    }
}
#AppSecondaryNav .Subnav-menu-buttons {
    padding: 20px 50px;
    display: -webkit-flex;
    display: -ms-flexbox;
    display: flex;
    -webkit-justify-content: center;
    -ms-flex-pack: center;
    justify-content: center
}
@media screen and (min-width: 600px) {
    #AppSecondaryNav.Subnav-wrapper:not(.has-mobileLayout) .Subnav-menu-buttons {
        padding: 0 20px 0 25px;
        -webkit-align-items: center;
        -ms-flex-align: center;
        align-items: center
    }
    #AppSecondaryNav.Subnav-wrapper.is-dark:not(.has-mobileLayout) .Subnav-menu-label {
        color: #fff
    }
}
#AppSecondaryNav .Subnav-menu-button {
    padding: 0 12px;
    font-size: 14px;
    line-height: 32px;
    font-weight: 700;
    background-color: #1473e6;
    color: #fff;
    border-radius: 16px;
    transition: background-color .1s ease, box-shadow .1s ease
}
#AppSecondaryNav .Subnav-menu-button:focus,
#AppSecondaryNav .Subnav-menu-button:hover {
    background-color: #005abe
}
#AppSecondaryNav .Subnav-menu-button:focus {
    box-shadow: 0 0 0 1px #005abe
}
#AppSecondaryNav .Subnav-menu-button+.Subnav-menu-button {
    margin-left: 15px
}
#AppSecondaryNav.Subnav-wrapper.is-dark:before {
    background-color: rgba(0, 0, 0, .7)
}
#AppSecondaryNav.Subnav-wrapper.is-dark .Subnav-logo-text,
#AppSecondaryNav.Subnav-wrapper.is-dark .Subnav-logo:after {
    color: #fff
}
.Gnav-sticky.is-scrolled #AppSecondaryNav.Subnav-wrapper.has-gradient:before {
    opacity: 1
}
[dir=rtl] #AppSecondaryNav .Subnav.is-open .Subnav-logo:after {
    -webkit-transform: rotate(90deg);
    transform: rotate(90deg)
}
[dir=rtl] #AppSecondaryNav .Subnav-logo:after {
    margin: -4px 10px 0 0;
    -webkit-transform: rotate(-90deg);
    transform: rotate(-90deg)
}
@media screen and (min-width: 600px) {
    #AppSecondaryNav.Subnav-wrapper.is-dark .Subnav-menu-item.is-open .Subnav-menu-label,
    #AppSecondaryNav.Subnav-wrapper.is-dark .Subnav-menu-label.is-active,
    #AppSecondaryNav.Subnav-wrapper.is-dark .Subnav-menu-label:focus,
    #AppSecondaryNav.Subnav-wrapper.is-dark .Subnav-menu-label:hover,
    #AppSecondaryNav.Subnav-wrapper.is-dark a.Subnav-logo.is-active,
    #AppSecondaryNav.Subnav-wrapper.is-dark a.Subnav-logo:focus,
    #AppSecondaryNav.Subnav-wrapper.is-dark a.Subnav-logo:hover {
        background-color: rgba(255, 255, 255, .1)
    }
    [dir=rtl] #AppSecondaryNav.Subnav-wrapper:not(.has-mobileLayout) .Subnav-logo {
        padding: 0 20px 0 25px
    }
}
[dir=rtl] #AppSecondaryNav .Subnav-logo-text:nth-child(2) {
    margin-left: 0;
    margin-right: 15px
}
[dir=rtl] #AppSecondaryNav .Subnav-menu-item.is-open>.Subnav-menu-label:after {
    -webkit-transform: rotate(180deg);
    transform: rotate(180deg)
}
[dir=rtl] #AppSecondaryNav .Subnav-menu-item.has-submenu .Subnav-menu-label {
    padding-right: 50px;
    padding-left: 65px
}
@media screen and (min-width: 600px) {
    [dir=rtl] #AppSecondaryNav.Subnav-wrapper:not(.has-mobileLayout) .Subnav-menu-item.has-submenu .Subnav-menu-label {
        padding: 0 18px
    }
    [dir=rtl] #AppSecondaryNav.Subnav-wrapper:not(.has-mobileLayout) .Subnav-menu-item.has-submenu .Subnav-menu-label:after {
        left: 50%
    }
}
[dir=rtl] #AppSecondaryNav .Subnav-menu-label:after {
    right: auto;
    left: 50px
}
@media screen and (min-width: 600px) {
    [dir=rtl] #AppSecondaryNav.Subnav-wrapper:not(.has-mobileLayout) .Subnav-submenus {
        left: auto;
        right: 0
    }
    [dir=rtl] #AppSecondaryNav.Subnav-wrapper:not(.has-mobileLayout) .Subnav-submenu-wrapper+.Subnav-submenu-wrapper {
        border-left: none;
        border-right: 1px solid #f3f3f3
    }
    [dir=rtl] #AppSecondaryNav.Subnav-wrapper:not(.has-mobileLayout) .Subnav-menu-buttons {
        padding: 0 25px 0 20px
    }
}
#AdobeFooterNav.Footernav-wrapper {
    display: -webkit-flex;
    display: -ms-flexbox;
    display: flex;
    -webkit-flex-direction: column;
    -ms-flex-direction: column;
    flex-direction: column;
    padding: 20px 0;
    background-color: #000;
    font-family: adobe-clean, sans-serif
}
#AdobeFooterNav.Footernav-wrapper.is-small {
    padding: 0
}
#AdobeFooterNav.Footernav-wrapper *,
#AdobeFooterNav.Footernav-wrapper:after,
#AdobeFooterNav.Footernav-wrapper:before {
    box-sizing: border-box
}
#AdobeFooterNav .Footernav {
    width: 100%;
    max-width: 1440px;
    margin: 0 auto
}
#AdobeFooterNav .Footernav-breadcrumbs-wrapper {
    padding: 20px;
    display: -webkit-flex;
    display: -ms-flexbox;
    display: flex;
    -webkit-align-items: center;
    -ms-flex-align: center;
    align-items: center
}
#AdobeFooterNav .Footernav-breadcrumbs-logo {
    -webkit-align-self: flex-start;
    -ms-flex-item-align: start;
    align-self: flex-start;
    -webkit-flex-shrink: 0;
    -ms-flex-negative: 0;
    flex-shrink: 0
}
#AdobeFooterNav .Footernav-breadcrumbs-logo svg {
    width: 100%;
    max-width: 25px;
    max-height: 25px;
    display: block
}
#AdobeFooterNav .Footernav-breadcrumbs {
    margin: 0;
    padding: 0;
    display: -webkit-flex;
    display: -ms-flexbox;
    display: flex;
    -webkit-flex-wrap: wrap;
    -ms-flex-wrap: wrap;
    flex-wrap: wrap;
    -webkit-align-items: center;
    -ms-flex-align: center;
    align-items: center
}
#AdobeFooterNav .Footernav-breadcrumbs:only-child .Footernav-breadcrumb:first-child:before {
    display: none
}
#AdobeFooterNav .Footernav-breadcrumb {
    color: #999
}
#AdobeFooterNav .Footernav-breadcrumb:before {
    padding: 0 10px;
    content: "\203a"
}
#AdobeFooterNav .Footernav-breadcrumb-link {
    font-size: 13px;
    color: inherit;
    transition: color .1s ease
}
#AdobeFooterNav .Footernav-breadcrumb-link:hover {
    color: #fff
}
#AdobeFooterNav .Footernav-menu {
    padding: 20px 0;
    overflow: hidden
}
@media screen and (min-width: 1080px) {
    #AdobeFooterNav .Footernav-menu {
        padding: 20px
    }
}
#AdobeFooterNav .Footernav-menu-items {
    padding: 0;
    margin: 0;
    display: -webkit-flex;
    display: -ms-flexbox;
    display: flex;
    -webkit-flex-direction: column;
    -ms-flex-direction: column;
    flex-direction: column
}
@media screen and (min-width: 1080px) {
    #AdobeFooterNav .Footernav-menu-items {
        margin: 0 -30px;
        -webkit-flex-direction: row;
        -ms-flex-direction: row;
        flex-direction: row
    }
}
#AdobeFooterNav .Footernav-menu-item {
    position: relative;
    padding-bottom: 1px;
    display: -webkit-flex;
    display: -ms-flexbox;
    display: flex;
    -webkit-flex-direction: column;
    -ms-flex-direction: column;
    flex-direction: column
}
#AdobeFooterNav .Footernav-menu-item:first-child {
    padding-top: 1px
}
#AdobeFooterNav .Footernav-menu-item:after,
#AdobeFooterNav .Footernav-menu-item:first-child:before {
    position: absolute;
    right: 0;
    left: 0;
    margin: 0 20px;
    border-top: 1px solid #333;
    content: ""
}
#AdobeFooterNav .Footernav-menu-item:first-child:before {
    top: 0
}
#AdobeFooterNav .Footernav-menu-item:after {
    bottom: 0
}
#AdobeFooterNav .Footernav-menu-item.is-open>.Footernav-menu-label {
    background-color: #333
}
#AdobeFooterNav .Footernav-menu-item.is-open>.Footernav-menu-label:after {
    -webkit-transform: rotate(-180deg);
    transform: rotate(-180deg)
}
#AdobeFooterNav .Footernav-menu-item.is-open>.Footernav-submenu-wrapper {
    max-height: none;
    visibility: visible
}
@media screen and (min-width: 1080px) {
    #AdobeFooterNav .Footernav-menu-item {
        padding: 5px 30px;
        -webkit-flex-grow: 1;
        -ms-flex-positive: 1;
        flex-grow: 1;
        -webkit-flex-basis: 50%;
        -ms-flex-preferred-size: 50%;
        flex-basis: 50%
    }
    #AdobeFooterNav .Footernav-menu-item+.Footernav-menu-item {
        border-left: 1px solid #333
    }
    #AdobeFooterNav .Footernav-menu-item:first-child {
        padding-top: 5px
    }
    #AdobeFooterNav .Footernav-menu-item:after,
    #AdobeFooterNav .Footernav-menu-item:first-child:before {
        display: none
    }
    #AdobeFooterNav .Footernav-menu-item.is-open>.Footernav-menu-label {
        background-color: transparent
    }
    #AdobeFooterNav .Footernav-menu-item.is-social {
        -webkit-flex-grow: 2;
        -ms-flex-positive: 2;
        flex-grow: 2;
        -webkit-flex-basis: 100%;
        -ms-flex-preferred-size: 100%;
        flex-basis: 100%
    }
    #AdobeFooterNav .Footernav-menu-item.is-social .Footernav-submenu {
        -webkit-columns: 2;
        columns: 2;
        -moz-columns: 2
    }
    #AdobeFooterNav .Footernav-menu-item.is-social .Footernav-submenu-item {
        -webkit-column-break-inside: avoid
    }
    #AdobeFooterNav .Footernav-menu-item.is-social .Footernav-social-wrapper {
        display: -webkit-flex;
        display: -ms-flexbox;
        display: flex
    }
}
#AdobeFooterNav .Footernav-menu-label {
    position: relative;
    height: 55px;
    padding: 0 35px 0 20px;
    display: -webkit-flex;
    display: -ms-flexbox;
    display: flex;
    -webkit-align-items: center;
    -ms-flex-align: center;
    align-items: center;
    font-size: 15px;
    font-weight: 700;
    color: #fff;
    background-color: transparent
}
#AdobeFooterNav .Footernav-menu-label:after {
    position: absolute;
    right: 20px;
    top: 50%;
    margin-top: -1.33px;
    border-width: 4px 4px 0;
    border-style: solid;
    border-color: #fff transparent transparent;
    transition: -webkit-transform .1s ease;
    transition: transform .1s ease;
    transition: transform .1s ease, -webkit-transform .1s ease;
    -webkit-transform-origin: 50% 33%;
    transform-origin: 50% 33%;
    content: ""
}
#AdobeFooterNav .Footernav-submenu-wrapper {
    max-height: 0;
    visibility: hidden
}
@media screen and (min-width: 1080px) {
    #AdobeFooterNav .Footernav-menu-label {
        height: auto;
        padding: 0;
        margin-bottom: 12px;
        cursor: default
    }
    #AdobeFooterNav .Footernav-menu-label:after {
        display: none
    }
    #AdobeFooterNav .Footernav-submenu-wrapper {
        max-height: none;
        visibility: visible
    }
}
#AdobeFooterNav .Footernav-submenu {
    padding: 15px 0;
    margin: 0
}
#AdobeFooterNav .Footernav-submenu-item {
    min-height: 30px;
    display: -webkit-flex;
    display: -ms-flexbox;
    display: flex;
    -webkit-align-items: stretch;
    -ms-flex-align: stretch;
    align-items: stretch
}
@media screen and (min-width: 1080px) {
    #AdobeFooterNav .Footernav-submenu {
        padding: 0
    }
    #AdobeFooterNav .Footernav-submenu-item {
        min-height: 24px
    }
}
#AdobeFooterNav .Footernav-submenu-link {
    width: 100%;
    padding: 3px 35px;
    display: -webkit-flex;
    display: -ms-flexbox;
    display: flex;
    -webkit-align-items: center;
    -ms-flex-align: center;
    align-items: center;
    font-size: 14px;
    color: #999;
    transition: color .1s ease
}
#AdobeFooterNav .Footernav-submenu-link:hover {
    color: #fff
}
@media screen and (min-width: 1080px) {
    #AdobeFooterNav .Footernav-submenu-link {
        width: auto;
        padding: 3px 0
    }
    #AdobeFooterNav .Footernav-submenu-link:focus {
        outline-offset: -3px
    }
}
#AdobeFooterNav .Footernav-social-wrapper {
    margin: 0 35px;
    padding: 20px 0;
    border-top: 1px solid #333;
    display: -webkit-flex;
    display: -ms-flexbox;
    display: flex
}
@media screen and (min-width: 1080px) {
    #AdobeFooterNav .Footernav-social-wrapper {
        margin: 20px 0 0
    }
}
#AdobeFooterNav .Footernav-social+.Footernav-social {
    margin-left: 10px
}
#AdobeFooterNav .Footernav-social-link {
    display: block;
    transition: fill .1s ease;
    fill: #999
}
#AdobeFooterNav .Footernav-social-link:hover {
    fill: #fff
}
#AdobeFooterNav .Footernav-social-icon {
    width: 100%;
    max-width: 20px;
    max-height: 20px;
    display: block;
    fill: inherit
}
#AdobeFooterNav .Footernav-products {
    min-height: 60px;
    margin: 20px;
    padding: 10px 0;
    border-style: solid;
    border-color: #333;
    border-width: 1px 0;
    display: none;
    -webkit-align-items: center;
    -ms-flex-align: center;
    align-items: center;
    -webkit-justify-content: center;
    -ms-flex-pack: center;
    justify-content: center;
    -webkit-flex-wrap: wrap;
    -ms-flex-wrap: wrap;
    flex-wrap: wrap
}
@media screen and (min-width: 600px) {
    #AdobeFooterNav .Footernav-products {
        display: -webkit-flex;
        display: -ms-flexbox;
        display: flex
    }
}
#AdobeFooterNav .Footernav-menu+.Footernav-products {
    margin-top: -20px;
    border-top-width: 0
}
@media screen and (min-width: 1080px) {
    #AdobeFooterNav .Footernav-menu+.Footernav-products {
        margin-top: 20px;
        border-top-width: 1px
    }
    [dir=rtl] #AdobeFooterNav .Footernav-menu-item+.Footernav-menu-item {
        border-left: none;
        border-right: 1px solid #333
    }
}
#AdobeFooterNav .Footernav-product {
    padding: 10px
}
#AdobeFooterNav .Footernav-product-link {
    display: -webkit-flex;
    display: -ms-flexbox;
    display: flex;
    -webkit-align-items: center;
    -ms-flex-align: center;
    align-items: center;
    color: #999;
    fill: #999;
    font-size: 12px;
    transition: color .1s ease, fill .1s ease
}
#AdobeFooterNav .Footernav-product-link:hover {
    color: #fff;
    fill: #fff
}
#AdobeFooterNav .Footernav-svg-icons {
    width: 0;
    height: 0;
    display: none;
    visibility: hidden
}
#AdobeFooterNav .Footernav-product-icon {
    width: 20px;
    height: 20px;
    margin: 0 7px
}
#AdobeFooterNav .Footernav-product-icon svg {
    max-width: 100%;
    max-height: 100%;
    display: block;
    fill: inherit
}
#AdobeFooterNav .Footernav-misc {
    padding: 20px;
    display: -webkit-flex;
    display: -ms-flexbox;
    display: flex;
    -webkit-align-items: center;
    -ms-flex-align: center;
    align-items: center;
    -webkit-justify-content: space-between;
    -ms-flex-pack: justify;
    justify-content: space-between;
    -webkit-flex-wrap: wrap;
    -ms-flex-wrap: wrap;
    flex-wrap: wrap
}
#AdobeFooterNav .Footernav-region {
    padding: 5px 20px 0 0;
    display: -webkit-flex;
    display: -ms-flexbox;
    display: flex;
    -webkit-align-items: center;
    -ms-flex-align: center;
    align-items: center;
    color: #999;
    fill: #999;
    font-size: 11px;
    line-height: 1;
    transition: color .1s ease, fill .1s ease;
    cursor: pointer;
    overflow: hidden
}
#AdobeFooterNav .Footernav-region:after {
    margin: -6px 0 0 10px;
    color: inherit;
    font-size: 30px;
    font-weight: 300;
    -webkit-transform-origin: 50% 60%;
    transform-origin: 50% 60%;
    -webkit-transform: rotate(90deg);
    transform: rotate(90deg);
    content: "\203a"
}
#AdobeFooterNav .Footernav-region:hover {
    color: #fff;
    fill: #fff
}
#AdobeFooterNav .Footernav-region:hover:after {
    color: #fff
}
#AdobeFooterNav .Footernav-region-icon {
    width: 20px;
    height: 20px;
    margin-right: 10px
}
#AdobeFooterNav .Footernav-region-icon svg {
    width: 100%;
    height: 100%;
    display: block;
    fill: inherit
}
#AdobeFooterNav .Footernav-disclaimers {
    padding: 5px 0;
    margin: 0;
    display: -webkit-flex;
    display: -ms-flexbox;
    display: flex;
    -webkit-flex-wrap: wrap;
    -ms-flex-wrap: wrap;
    flex-wrap: wrap;
    font-size: 11px;
    color: #999
}
#AdobeFooterNav .Footernav-disclaimers.has-adChoices .Footernav-disclaimer:nth-last-child(2):after {
    display: none
}
@media screen and (min-width: 720px) and (min-height: 560px) {
    #AdobeFooterNav .Footernav-disclaimers.has-adChoices .Footernav-disclaimer:nth-last-child(2):after {
        display: inline-block
    }
}
#AdobeFooterNav .Footernav-disclaimer {
    padding: 5px 0;
    color: inherit
}
#AdobeFooterNav .Footernav-disclaimer:after {
    margin: 0 8px;
    content: '/'
}
#AdobeFooterNav .Footernav-disclaimer.is-adChoices,
#AdobeFooterNav .Footernav-disclaimer:last-child:after {
    display: none
}
@media screen and (min-width: 720px) and (min-height: 560px) {
    #AdobeFooterNav .Footernav-disclaimer.is-adChoices {
        display: -webkit-flex;
        display: -ms-flexbox;
        display: flex
    }
}
#AdobeFooterNav .Footernav-disclaimer.is-adChoices .Footernav-disclaimer-link {
    display: -webkit-flex;
    display: -ms-flexbox;
    display: flex;
    -webkit-align-items: center;
    -ms-flex-align: center;
    align-items: center;
    fill: #115262
}
#AdobeFooterNav .Footernav-disclaimer.is-adChoices .Footernav-disclaimer-link:hover {
    fill: #209bb9
}
#AdobeFooterNav .Footernav-disclaimer-link {
    color: inherit;
    transition: color .1s ease, fill .1s ease
}
.Profile-button,
.Profile-header-cta,
.Profile-menu-icon,
.Profile-menu-link {
    transition: all 125ms ease-in-out
}
#AdobeFooterNav .Footernav-disclaimer-link:hover {
    color: #fff
}
#AdobeFooterNav .Footernav-adChoices-icon {
    width: 10px;
    max-height: 10px;
    margin-right: 5px;
    fill: inherit
}
[dir=rtl] #AdobeFooterNav .Footernav-menu-label {
    padding: 0 20px 0 35px
}
[dir=rtl] #AdobeFooterNav .Footernav-menu-label:after {
    right: auto;
    left: 20px
}
@media screen and (min-width: 1080px) {
    [dir=rtl] #AdobeFooterNav .Footernav-menu-label {
        padding: 0
    }
}
[dir=rtl] #AdobeFooterNav .Footernav-social+.Footernav-social {
    margin-left: 0;
    margin-right: 10px
}
[dir=rtl] #AdobeFooterNav .Footernav-adChoices-icon {
    margin-left: 5px;
    margin-right: 0
}
[dir=rtl] #AdobeFooterNav .Footernav-region {
    padding-right: 0;
    padding-left: 20px
}
[dir=rtl] #AdobeFooterNav .Footernav-region:after {
    margin: -6px 10px 0 0;
    -webkit-transform: rotate(-90deg);
    transform: rotate(-90deg)
}
[dir=rtl] #AdobeFooterNav .Footernav-region-icon {
    margin-right: 0;
    margin-left: 5px
}
.Profile-thumbnail {
    display: block;
    width: 30.1px;
    height: 30px;
    border-radius: 4px;
    cursor: pointer
}
.Profile-thumbnail.has-notification {
    position: relative
}
.Profile-thumbnail.has-notification:after {
    position: absolute;
    top: -6px;
    right: -6px;
    width: 12px;
    height: 12px;
    border-width: 2px;
    border-style: solid;
    border-color: inherit;
    border-radius: 50%;
    box-sizing: border-box;
    content: ''
}
.Profile-thumbnail.has-notification.is-warning:after {
    background-color: #F53C3C;
    background-clip: content-box
}
.Profile-header {
    padding: 20px;
    display: -webkit-flex;
    display: -ms-flexbox;
    display: flex;
    -webkit-flex-direction: row;
    -ms-flex-direction: row;
    flex-direction: row;
    border-bottom: 1px solid #E1E1E1
}
.Profile-header:active .Profile-header-cta,
.Profile-header:hover .Profile-header-cta {
    color: #4B4B4B;
    box-shadow: inset 0 -1px 0 0 #4B4B4B
}
.Profile-avatar {
    width: 64px;
    height: 64px;
    border-radius: 4px;
    -ms-flex-negative: 0;
    -webkit-flex-shrink: 0;
    flex-shrink: 0
}
.Profile-data {
    -webkit-flex-grow: 1;
    -ms-flex-positive: 1;
    flex-grow: 1;
    margin-left: 16px;
    overflow: hidden
}
.Profile-name {
    font-size: 18px;
    line-height: 1.25;
    font-weight: 700;
    color: #2C2C2C;
    padding: 0;
    margin: -3px 0 0;
    word-wrap: break-word;
    overflow: hidden;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    text-overflow: ellipsis;
    max-height: 36px
}
.Profile-name_long {
    font-size: 14px;
    line-height: 16px;
    max-height: 32px
}
.Profile-email {
    font-size: 14px;
    margin: -1px 0 12px;
    padding: 0;
    line-height: 1.25;
    color: #4B4B4B;
    word-break: break-all
}
.Profile-header-cta {
    font-size: 14px;
    padding-bottom: 1px;
    display: inline-block;
    color: #707070;
    box-shadow: inset 0 -1px 0 0 #707070
}
.Profile-notification {
    padding: 20px;
    border-bottom: 1px solid #E1E1E1;
    text-align: center
}
.Profile-notification-details {
    display: -webkit-flex;
    display: -ms-flexbox;
    display: flex;
    margin-bottom: 16px;
    -webkit-align-items: flex-start;
    -ms-flex-align: start;
    align-items: flex-start
}
.Profile-notification-message {
    padding: 0;
    margin: 0;
    -webkit-flex-grow: 1;
    -ms-flex-positive: 1;
    flex-grow: 1;
    color: #4B4B4B;
    line-height: 1.42;
    text-align: left
}
.Profile-button {
    font-size: 15px;
    font-weight: 700;
    display: inline-block;
    padding: 7.5px 16px 9.5px;
    text-transform: capitalize;
    color: #4B4B4B;
    border-radius: 16px;
    background-color: transparent
}
.Profile-button:active,
.Profile-button:hover {
    color: #2C2C2C;
    background-color: #F5F5F5
}
.Profile-button.is-warning {
    padding: 5.5px 14px 7.5px;
    color: #F53C3C;
    border: 2px solid #F53C3C
}
.Profile-button.is-warning:active,
.Profile-button.is-warning:hover {
    color: #FFF;
    background-color: #F53C3C
}
.Profile-menu {
    margin: 6px 0;
    padding: 0
}
.Profile-menu-link {
    display: -webkit-flex;
    display: -ms-flexbox;
    display: flex;
    -webkit-flex-direction: row;
    -ms-flex-direction: row;
    flex-direction: row;
    -webkit-align-items: center;
    -ms-flex-align: center;
    align-items: center;
    color: #4B4B4B;
    padding: 8px 20px;
    font-size: 14px
}
.Profile-menu-link:active,
.Profile-menu-link:hover {
    color: #2C2C2C;
    background-color: #F5F5F5
}
.Profile-menu-link:active svg path,
.Profile-menu-link:hover svg path {
    fill: #4B4B4B
}
.Profile-menu-text {
    -webkit-flex-grow: 1;
    -ms-flex-positive: 1;
    flex-grow: 1
}
.Profile-icon {
    -ms-flex-negative: 0;
    -webkit-flex-shrink: 0;
    flex-shrink: 0
}
.Profile-icon svg {
    max-width: 100%;
    display: block
}
.Profile-notification-icon {
    width: 20px;
    height: 20px;
    margin-right: 10px
}
.Profile-notification-icon.is-warning path {
    fill: #F53C3C
}
.Profile-menu-icon {
    width: 24px;
    height: 24px;
    margin-right: 16px
}
.Profile-menu-icon path {
    fill: #707070
}
.Profile.theme-dark .Profile-dropdown {
    background-color: #161616
}
.Profile.theme-dark .Profile-name {
    color: #FCFCFC
}
.Profile.theme-dark .Profile-email,
.Profile.theme-dark .Profile-notification-message {
    color: #999
}
.Profile.theme-dark .Profile-applications,
.Profile.theme-dark .Profile-content,
.Profile.theme-dark .Profile-dropdown,
.Profile.theme-dark .Profile-header,
.Profile.theme-dark .Profile-notification {
    border-color: #313131
}
.Profile.theme-dark .Profile-button,
.Profile.theme-dark .Profile-menu-link {
    color: #B9B9B9
}
.Profile.theme-dark .Profile-button:active,
.Profile.theme-dark .Profile-button:hover,
.Profile.theme-dark .Profile-menu-link:active,
.Profile.theme-dark .Profile-menu-link:hover {
    color: #CDCDCD;
    background-color: #232323
}
.Profile.theme-dark .Profile-button:active path,
.Profile.theme-dark .Profile-button:hover path,
.Profile.theme-dark .Profile-menu-link:active path,
.Profile.theme-dark .Profile-menu-link:hover path {
    fill: #C2C2C2
}
.Profile.theme-dark .Profile-button.is-warning {
    color: #F53C3C
}
.Profile.theme-dark .Profile-button.is-warning:active,
.Profile.theme-dark .Profile-button.is-warning:hover {
    color: #161616;
    background-color: #F53C3C
}
.Profile.theme-dark .Profile-menu-icon path {
    fill: #999
}
.Profile.theme-dark .Profile-header-cta {
    color: #FFF;
    box-shadow: inset 0 -1px 0 0 #FFF
}
.Profile.theme-dark .Profile-header:active .Profile-header-cta,
.Profile.theme-dark .Profile-header:hover .Profile-header-cta {
    color: #E2E2E2;
    box-shadow: inset 0 -1px 0 0 #E2E2E2
}
.Profile.theme-grey .Profile-dropdown {
    background-color: #282828
}
.Profile.theme-grey .Profile-name {
    color: #FCFCFC
}
.Profile.theme-grey .Profile-email,
.Profile.theme-grey .Profile-notification-message {
    color: #999
}
.Profile.theme-grey .Profile-applications,
.Profile.theme-grey .Profile-content,
.Profile.theme-grey .Profile-dropdown,
.Profile.theme-grey .Profile-header,
.Profile.theme-grey .Profile-notification {
    border-color: #3E3E3E
}
.Profile.theme-grey .Profile-button,
.Profile.theme-grey .Profile-menu-link {
    color: #CDCDCD
}
.Profile.theme-grey .Profile-button:active,
.Profile.theme-grey .Profile-button:hover,
.Profile.theme-grey .Profile-menu-link:active,
.Profile.theme-grey .Profile-menu-link:hover {
    color: #FDFDFE;
    background-color: #323232
}
.Profile.theme-grey .Profile-button:active path,
.Profile.theme-grey .Profile-button:hover path,
.Profile.theme-grey .Profile-menu-link:active path,
.Profile.theme-grey .Profile-menu-link:hover path {
    fill: #E1E1E1
}
.Profile.theme-grey .Profile-button.is-warning {
    color: #F53C3C
}
.Profile.theme-grey .Profile-button.is-warning:active,
.Profile.theme-grey .Profile-button.is-warning:hover {
    color: #282828;
    background-color: #F53C3C
}
.Profile.theme-grey .Profile-menu-icon path {
    fill: #C2C2C2
}
.Profile.theme-grey .Profile-header-cta {
    color: #FFF;
    box-shadow: inset 0 -1px 0 0 #FFF
}
.Profile.theme-grey .Profile-header:active .Profile-header-cta,
.Profile.theme-grey .Profile-header:hover .Profile-header-cta {
    color: #E2E2E2;
    box-shadow: inset 0 -1px 0 0 #E2E2E2
}
[dir=rtl] .Profile {
    text-align: right
}
[dir=rtl] .Profile-data {
    margin-left: 0;
    margin-right: 16px
}
[dir=rtl] .Profile-menu-icon {
    margin-right: 0;
    margin-left: 16px
}
[dir=rtl] .Profile-dropdown {
    right: auto;
    left: 0
}
.Profile {
    font: 400 14px/1 adobe-clean, sans-serif;
    color: #4B4B4B;
    text-align: left;
    position: relative;
    width: 30px;
    height: 30px;
    display: block;
    -webkit-font-smoothing: antialiased
}
.Profile *,
.Profile:after,
.Profile:before {
    box-sizing: inherit
}
.Profile.Profile-hidden .Profile-dropdown {
    display: none
}
.Profile.Profile-collapsed .Profile-dropdown {
    -webkit-animation: profile-collapsed .2s ease 0s 1 both;
    animation: profile-collapsed .2s ease 0s 1 both
}
.Profile.Profile-expanded .Profile-dropdown {
    -webkit-animation: profile-expanded .2s ease 0s 1 both;
    animation: profile-expanded .2s ease 0s 1 both
}
@-webkit-keyframes profile-collapsed {
    0% {
        opacity: 1
    }
    100% {
        opacity: 0
    }
}
@keyframes profile-collapsed {
    0% {
        opacity: 1
    }
    100% {
        opacity: 0
    }
}
@-webkit-keyframes profile-expanded {
    0% {
        opacity: 0
    }
    100% {
        opacity: 1
    }
}
@keyframes profile-expanded {
    0% {
        opacity: 0
    }
    100% {
        opacity: 1
    }
}
.Profile-dropdown {
    width: 320px;
    position: absolute;
    top: 30px;
    right: 0;
    border: 1px solid #E1E1E1;
    border-bottom-left-radius: 4px;
    border-bottom-right-radius: 4px;
    background-color: #FFF;
    overflow: hidden;
    -webkit-font-smoothing: antialiased;
    box-shadow: 0 2px 6px -1px rgba(0, 0, 0, .1)
}
.Profile-applications,
.Profile-content {
    border-bottom: 1px solid #E1E1E1
}
#AppPrimaryNav.Gnav-wrapper .Gnav-profile .Profile-dropdown {
    top: 45px
}
#AppPrimaryNav.Gnav-wrapper:nth-last-child(2) .Gnav-profile .Profile-dropdown {
    top: 35px
}
@media screen and (min-width: 600px) {
    #AppPrimaryNav.Gnav-wrapper .Gnav-profile .Profile-dropdown {
        top: 55px
    }
    #AppPrimaryNav.Gnav-wrapper:nth-last-child(2) .Gnav-profile .Profile-dropdown {
        top: 45px
    }
}
@media screen and (width: 320px) {
    #AppPrimaryNav.Gnav-wrapper .Gnav-profile .Profile-dropdown {
        right: -20px
    }
    [dir=rtl] #AppPrimaryNav.Gnav-wrapper .Gnav-profile .Profile-dropdown {
        right: auto;
        left: -20px
    }
}
</style>