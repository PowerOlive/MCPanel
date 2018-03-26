// 引入依赖插件
import Vue from 'vue'
import Vuex from 'vuex'
import ElementUI from 'element-ui'

// 引入样式表
import 'normalize.css'
import 'element-ui/lib/theme-chalk/index.css'

// 引入路由配置文件
import router from '../config/router.config'

// 引入 AppView
import App from './app.vue'

// Vue 全局资源调用
Vue.use(ElementUI, { size: 'small' })
Vue.use(Vuex)

// 实例化 Vue 对象
const app = new Vue({
  el: '#app',
  router,
  template: '<App />',
  components: { App }
})