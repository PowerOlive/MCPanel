<template>
  <div class="wrap">
    <Header></Header>
  </div>
</template>

<script>
import Header from './component/header.vue'
export default {
  name: 'app',
  components: { Header }
}
</script>

<style lang="less">
  a {
    text-decoration: none;
  }
</style>