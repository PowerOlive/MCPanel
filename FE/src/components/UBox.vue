<template>
  <div class="u-box">
    <div class="hd" :style="{ 'background-image': 'url(' + account.banner + ')' }">
      <img class="avatar" :src="account.avatar">
    </div>
    <div class="container">
      <div class="umeta">
        <p class="unickname">{{ account.nickname }}</p>
        <p class="uname">@{{ account.username }}</p>
      </div>
      <slot></slot>
    </div>
  </div>
</template>

<script>
import { mapState } from 'vuex'
export default {
  name: 'ubox',
  computed: {
    ...mapState({
      account: state => state.app.account
    })
  },
}
</script>

<style lang="less">
  .u-box {
    background: #fff;
    border: 1px solid #ddd;
    .hd {
      width: 100%;
      height: 100px;
      background: center;
      background-size: 100% auto;
      position: relative;
      .avatar {
        width: 90px;
        height: 90px;
        margin: 0 0 0 -45px;
        bottom: -50px;
        left: 50%;
        border-radius: 50%;
        box-shadow: 3px 1px 9px -1px rgba(0, 0, 0, .2);
        position: absolute;
        display: block;
        transition: .5s all ease;
        &:hover {
          transform: rotate(-360deg);
        }
      }
    }
    .container {
      margin: 50px 0 0 0;
      overflow: hidden;
      .umeta {
        margin: 8px 0 15px 0;
        text-align: center;
        p {
          margin: 4px 0;
          font-size: 12px;
          &.unickname {
            color: #8e8e8e;
            font-weight: bold;
            font-size: 16px;
          }
          &.uname {
            color: #afafaf;
          }
        }
      }
    }
  }
</style>

