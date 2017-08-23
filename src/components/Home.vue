<template>
  <div :class="getComponentName() + '_view_port'">
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
  </div>
</template>

<script>
import extension from '../extendedComponenet'

export default {
  extends: extension,
  name: 'home',
  data () {
    return {
      bannerTitle: 'Home',
      message: 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Asperiores debitis, hic! Itaque recusandae amet aut aperiam tempora optio adipisci similique ipsum sequi aspernatur labore rerum quo natus doloribus, necessitatibus expedita?'
    }
  },
  created() {

  },
  methods: {
    validate () {
      console.log('test')
      // if (document.contains('text to find')) {
      throw new Error('error')
      // }
    }
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style lang="scss" scoped>
.home_view_port .hero_banner {
  background-image: url(../assets/images/pexels-photo-159888.jpeg);
}
</style>