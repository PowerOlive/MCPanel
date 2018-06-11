<template>
  <el-col :xs="24" :md="8">
    <u-box class="dash-user">
      <el-row class="uprofile">
        <el-col :span="8">
          <p class="val">{{ this.data.balance }}</p>
          <p class="title">{{ $t('dashboard.user_box.balance') }}</p>
        </el-col>
        <el-col :span="8">
          <p class="val">{{ this.data.level }}</p>
          <p class="title">{{ $t('dashboard.user_box.level') }}</p>
        </el-col>
        <el-col :span="8">
          <p class="val">{{ this.data.contribution }}</p>
          <p class="title">{{ $t('dashboard.user_box.contribution') }}</p>
        </el-col>
      </el-row>
    </u-box>
  </el-col>
</template>

<script>
import { UBox } from '@/components'
import { mapState } from 'vuex'
export default {
  name: 'user-box',
  components: { UBox },
  props: [ 'data' ]
}
</script>

<style lang="less">
  .dash-user {
    height: 270px;
    margin: 0 0 16px 0;
    .uprofile {
      margin: 0 0 15px 0;
      text-align: center;
      p {
        margin: 2px 0;
        color: #8e8e8e;
        font-size: 14px;
        &.title {
          margin-top: 4px;
        }
        &.val {
          color: #0090d9;
          font-size: 16px;
        }
      }
    }
  }
</style>


