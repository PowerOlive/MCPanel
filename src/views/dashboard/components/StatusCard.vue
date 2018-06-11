<template>
  <el-row :gutter="16">
    <el-col :xs="24" :md="8">
      <dash-card color="blue" icon="users" :args="member"></dash-card>
    </el-col>
    <el-col :xs="24" :md="8">
      <dash-card color="red" icon="battery-three-quarters" :args="status"></dash-card>
    </el-col>
    <el-col :xs="24" :md="8">
      <dash-card color="green" icon="rocket" :args="version"></dash-card>
    </el-col>
  </el-row>
</template>

<script>
import { DashCard } from '@/components'
export default {
  name: 'status-card',
  computed: {
    member() {
      return {
        arg1: {
          title: this.$i18n.t('dashboard.status_card.online_member'),
          val: this.data.onlineMember
        },
        arg2: {
          title: this.$i18n.t('dashboard.status_card.total_member'),
          val: this.data.totalMember
        },
        arg3: {
          title: this.$i18n.t('dashboard.status_card.online_activity'),
          val: Math.round(this.data.onlineMember / this.data.totalMember * 100) + '%'
        }
      }
    },
    status() {
      return {
        arg1: {
          title: this.$i18n.t('dashboard.status_card.server_uptime'),
          val: this.data.serverUpTime + this.$i18n.t('dashboard.status_card.server_uptime_suffix')
        },
        arg2: {
          title: this.$i18n.t('dashboard.status_card.cpu_usage'),
          val: this.data.cupUsage + '%'
        },
        arg3: {
          title: this.$i18n.t('dashboard.status_card.ram_usage'),
          val: this.data.ramUsage + '%'
        }
      }
    },
    version() {
      return {
        arg1: {
          title: this.$i18n.t('dashboard.status_card.server_version'),
          val: this.data.serverVersion
        },
        arg2: {
          title: this.$i18n.t('dashboard.status_card.os_type'),
          val: this.data.osType
        },
        arg3: {
          title: this.$i18n.t('dashboard.status_card.client_version'),
          val: this.data.clientVersion
        }
      }
    }
  },
  props: [ 'data' ],
  components: { DashCard }
}
</script>

<style lang="less">

</style>
