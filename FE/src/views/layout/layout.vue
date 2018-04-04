<template>
  <el-container class="wrap">
    <!-- 侧边栏 -->
    <sidebar></sidebar>
    <el-container>
      <!-- 顶栏 -->
      <el-header height="50px">
        <el-row type="flex" justify="space-between">
          <el-col :span="12">
            <!-- 面包屑导航 -->
            <breadcrumb></breadcrumb>
            </el-col>
          <el-col :span="12">
            <!-- 账户面板 -->
            <drop-menu></drop-menu>
          </el-col>
        </el-row>
      </el-header>
      <!-- 路由视图 -->
      <keep-alive>
        <router-view></router-view>
      </keep-alive>
      <!-- 页脚 -->
      <!-- <el-footer></el-footer> -->
    </el-container>
  </el-container>
</template>

<script>
import { Breadcrumb, DropMenu, Sidebar, Copyright } from './components'
export default {
  components: { Breadcrumb, DropMenu, Sidebar, Copyright }
}
</script>

<style lang="less">
  html, body {
    height: 100%;
    .wrap {
      height: 100%;
      background: #e1e4eb;
      .el-header {
        width: 100%;
        line-height: 50px;
        background: #fff;
        border-bottom: 1px solid #ddd;
      }
    }
  }
</style>
