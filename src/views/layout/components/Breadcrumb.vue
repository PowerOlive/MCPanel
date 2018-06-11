<template>
  <el-breadcrumb class="navicat" separator-class="el-icon-arrow-right">
    <el-breadcrumb-item :to="{ path: '/' }" :title="$t('router.index')">{{ $t('router.index') }}</el-breadcrumb-item>
    <template v-for="(item, key) in fullRole">
      <el-breadcrumb-item :key="key" :to="{ path: item.path }" :title="navigationTitle(item.title)">{{ navigationTitle(item.title) }}</el-breadcrumb-item>
    </template>
  </el-breadcrumb>
</template>

<script>
import i18n from '@/utils/i18n'
import { mapState, mapGetters } from 'vuex'
export default {
  name: 'breadcrumb',
  methods: {
    navigationTitle: title => i18n.navigationTitle(title)
  },
  computed: {
    ...mapState({
      routerMap: state => state.role.routerMap,
      currentRole: state => state.role.currentRole
    }),
    ...mapGetters({
      fullRole: 'fullRole'
    })
  }
}
</script>


<style lang="less">
  .navicat {
    height: 100%;
    line-height: 50px;
  }
</style>
