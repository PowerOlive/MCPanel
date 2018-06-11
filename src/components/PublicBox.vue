<template>
  <div class="public-box">
    <div class="box-title">
      <font-awesome-icon class="icon" :icon="['fas', icon]" v-if="icon" />
      <span>{{ title }}</span>
    </div>
    <div class="box-container">
      <slot></slot>
    </div>
  </div>
</template>

<script>
export default {
  name: 'public-box',
  props: [ 'icon', 'title' ]
}
</script>


<style lang="less" scoped>
  .public-box {
    border: 1px solid #ddd;
    background: #fff;
    .box-title {
      padding: 10px 16px;
      border-bottom: 1px solid #ddd;
      color: #8e8e8e;
      font-size: 14px;
      .icon {
        margin: 0 5px 0 0;
        vertical-align: middle;
      }
    }
    .box-container {
      padding: 16px;
    }
  }
</style>
