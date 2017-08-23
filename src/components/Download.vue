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
  name: 'download',
  data () {
    return {
      bannerTitle: 'Download',
      message: 'Download text.'
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
.download_view_port .hero_banner {
  background-image: url(../assets/images/crane-load-crane-skyward-in-the-height-48122.jpeg);
}
</style>