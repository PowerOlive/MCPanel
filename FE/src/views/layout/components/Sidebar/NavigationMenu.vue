<template>
  <el-menu :default-active="currentRole" class="sidebar-menu" background-color="#545c64" text-color="#fff" active-text-color="#ffd04b" router>
    <template v-for="(item, key) in routerMap">
      <!-- 一级菜单 -->
      <template  v-if="!item.children && !item.hidden">
        <el-menu-item :key="key" :index="item.path" :title="navigationTitle(item.meta.title)">
          <font-awesome-icon :icon="['fas', item.meta.icon]"  v-if="item.meta.icon" />
          {{ navigationTitle(item.meta.title) }}
          </el-menu-item>
      </template>
      <!-- 二级菜单 -->
      <template v-else-if="item.children && !item.hidden">
        <el-submenu :key="key" :index="item.path">
          <template slot="title">
            <font-awesome-icon :icon="['fas', item.meta.icon]"  v-if="item.meta.icon" />
            <span>{{ navigationTitle(item.meta.title) }}</span>
          </template>
          <el-menu-item-group>
            <template v-for="(itemChild, keyChild) in item.children">
              <el-menu-item :key="keyChild" :index="itemChild.path" :title="navigationTitle(itemChild.meta.title)">
                <font-awesome-icon :icon="['fas', itemChild.meta.icon]"  v-if="itemChild.meta.icon" />
                {{ navigationTitle(itemChild.meta.title) }}
              </el-menu-item>
            </template>
          </el-menu-item-group>
        </el-submenu>
      </template>
    </template>
  </el-menu>
</template>

<script>
import i18n from '@/utils/i18n'
import { mapState } from 'vuex'
export default {
  name: 'sidebar',
  data() {
    return {}
  },
  methods: {
    navigationTitle: title => i18n.navigationTitle(title)
  },
  computed: {
    ...mapState({
      routerMap: state => state.role.routerMap,
      currentRole: state => state.role.currentRole
    })
  }
}
</script>

<style lang="less">
  .sidebar-menu {
    border: none;
    svg {
      width: 14px !important;
      height: 14px !important;
      margin: 0 8px 0 0;
    }
  }
</style>
