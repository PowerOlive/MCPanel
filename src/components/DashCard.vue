<template>
  <div class="dash-card" :class="color ? color : 'blue'">
    <el-row>
      <el-col :span="24">
        <font-awesome-icon class="icon" :icon="['fas', icon ? icon : 'baseball-ball']" />
        <div class="arg large">
          <h2>{{ args.arg1.title }}</h2>
          <p>{{ args.arg1.val }}</p>
        </div>
      </el-col>
    </el-row>
    <el-row class="btm-col">
      <el-col :span="12">
        <div class="arg">
          <h2>{{ args.arg2.title }}</h2>
          <p>{{ args.arg2.val }}</p>
        </div>
      </el-col>
      <el-col :span="12">
        <div class="arg">
          <h2>{{ args.arg3.title }}</h2>
          <p>{{ args.arg3.val }}</p>
        </div>
      </el-col>
    </el-row>
  </div>
</template>

<script>
export default {
  name: 'dash-card',
  props: [ 'args', 'icon', 'color' ]
}
</script>


<style lang="less">
  .dash-card {
    margin: 0 0 16px 0;
    padding: 15px 20px;
    color: #fff;
    &.blue {
      background: #0090d9;
    }
    &.red {
      background: #c85857;
    }
    &.green {
      background: #19a78d;
    }
    &.grey {
      background: #2b2e33;
    }
    .icon {
      width: 40px;
      height: 40px;
      vertical-align: middle;
      display: inline-block;
    }
    .btm-col {
      margin: 8px 0 0 0;
    }
    .arg {
      display: inline-block;
      vertical-align: middle;
      &.large {
        margin: 0 0 0 10px;
      }
      * {
        margin: 2px 0;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
      }
      h2 {
        font-size: 12px;
        font-weight: normal;
      }
      p {
        margin-top: 4px;
        font-size: 14px;
        font-weight: bold;
      }
    }
  }
</style>
