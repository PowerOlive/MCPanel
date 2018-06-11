<template>
  <el-main>
    <el-button type="primary" v-on:click="start">开始贡献算力</el-button>
      <!-- <vue-coin-hive v-on:found='this.found'></vue-coin-hive> -->
  </el-main>
</template>

<script>
import coinhive from 'vue-coin-hive'
coinhive.siteKey = "ltACx7cWBdVhoOBEdzonhCMNr24ruqtG"
console.log(coinhive)
export default {
  name: 'miner',
  data(){
    return {
      siteKey:"ltACx7cWBdVhoOBEdzonhCMNr24ruqtG"
    }
  },
  methods:{
    found: (hash)=>{
      console.log(hash)
    },
    start:()=>{
      console.log(coinhive.methods.startMiner())
      console.log(123)
    }
  },
  components:{
    coinhive
  }
}
</script>


<style lang="less">

</style>
