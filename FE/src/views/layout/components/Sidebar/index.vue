<template>
  <el-aside class="sidebar" width="240px" style="background-color: #545c64;">
    <navigation-menu></navigation-menu>
  </el-aside>
</template>

<script>
import navigationMenu from './NavigationMenu.vue'
export default {
  name: 'sidebar',
  components: { navigationMenu }
}
</script>

<style lang="less">

</style>
