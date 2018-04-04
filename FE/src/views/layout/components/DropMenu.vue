<template>
  <el-dropdown class="user-drop">
    <span class="el-dropdown-link">
      <img class="avatar" :src="avatar">
      <i class="el-icon-arrow-down el-icon--right"></i>
    </span>
    <el-dropdown-menu slot="dropdown">
      <router-link :to="{ path: '/user' }"><el-dropdown-item>{{ $t('router.user') }}</el-dropdown-item></router-link>
      <router-link :to="{ path: '/security/index' }"><el-dropdown-item>{{ $t('router.s_password') }}</el-dropdown-item></router-link>
      <router-link :to="{ path: '/logout' }"><el-dropdown-item divided>{{ $t('router.logout') }}</el-dropdown-item></router-link>
    </el-dropdown-menu>
  </el-dropdown>
</template>

<script>
import { mapState } from 'vuex'
export default {
  name: 'drop-menu',
  computed: {
    ...mapState({
      avatar: state => state.app.account.avatar
    })
  }
}
</script>

<style lang="less">
  .user-drop {
    line-height: 50px;
    float: right;
    .avatar {
      width: 28px;
      height: 28px;
      border: 1px solid #ddd;
      border-radius: 50%;
      vertical-align: middle;
    }
  }
</style>

