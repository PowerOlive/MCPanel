<template>
  <el-col :xs="24" :md="16">
    <public-box :title="$t('dashboard.message_box.sys_msg')" icon="envelope" class="dash-message">
      <el-table :data="data" border style="width: 100%">
        <el-table-column prop="date" :label="$t('dashboard.message_box.msg_date')"></el-table-column>
        <el-table-column prop="title" :label="$t('dashboard.message_box.msg_title')"></el-table-column>
      </el-table>
    </public-box>
  </el-col>
</template>

<script>
import { PublicBox } from '@/components'
export default {
  name: 'message-box',
  props: [ 'data' ],
  components: { PublicBox }
}
</script>

<style lang="less">
  .dash-message {
    height: 270px;
    margin: 0 0 16px 0;
  }
</style>
