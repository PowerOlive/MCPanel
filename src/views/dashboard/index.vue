<template>
  <el-main>
    <status-card :data="status"></status-card>
    <el-row :gutter="16">
      <message-box :data="messages"></message-box>
      <user-box :data="player"></user-box>
    </el-row>
  </el-main>
</template>

<script>
import { StatusCard, MessageBox, UserBox } from './components'
export default {
  name: 'dasobard',
  data() {
    return {
      status: {
        onlineMember: 501,
        totalMember: 1050,
        serverUpTime: 5,
        cupUsage: 10,
        ramUsage: 20,
        serverVersion: 'Bukkit 1.12.2',
        clientVersion: '1.13',
        osType: 'Windows'
      },
      player: {
        balance: 2.33,
        level: 9,
        contribution: 50
      },
      messages: [
        {
          date: '2018-03-31',
          title: '您的外卖到了'
        },
        {
          date: '2018-03-31',
          title: '您的外卖到了'
        },
        {
          date: '2018-03-31',
          title: '您的外卖到了'
        },
        {
          date: '2018-03-31',
          title: '您的外卖到了'
        }
      ]
    }
  },
  components: { StatusCard, MessageBox, UserBox }
}
</script>

<style lang="less">
  
</style>
