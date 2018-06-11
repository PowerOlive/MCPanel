<template>
  <el-main>
    <el-row :gutter="16">
      <el-col :xs="24" :md="16">
        <info-editor></info-editor>
      </el-col>
      <el-col :xs="24" :md="8">
        <balance></balance>
      </el-col>
    </el-row>
  </el-main>
</template>

<script>
import { InfoEditor, Balance } from './components'
export default {
  name: 'user',
  components: { InfoEditor, Balance }
}
</script>


<style lang="less">

</style>
