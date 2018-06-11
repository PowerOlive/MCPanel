// 引入依赖插件
import Vue from 'vue'
import ElementUI from 'element-ui'
import { mapState } from 'vuex'

// 引入样式表
import 'normalize.css/normalize.css'
import 'element-ui/lib/theme-chalk/index.css'

// 引入 Font-Awesome
import FontAwesome from '@fortawesome/fontawesome'
import FontAwesomeIcon from '@fortawesome/vue-fontawesome'
import FontAwesomeSolid from '@fortawesome/fontawesome-free-solid'
FontAwesome.library.add(FontAwesomeSolid)

// 引入路由、Vuex、多语言 配置文件
import router from './router'
import store from './store'
import i18n from './i18n'

// 引入 AppView
import App from './app.vue'

// Vue 全局资源调用
Vue.use(ElementUI, { size: 'small' })
Vue.component('font-awesome-icon', FontAwesomeIcon)

// 实例化 Vue 对象
const app = new Vue({
  el: '#app',
  router,
  store,
  i18n,
  template: '<App />',
  components: { App },
  computed: {
    ...mapState({
      lang: state => state.app.lang
    })
  },
  created() {
    // 设置全局语言状态
    const currLang = localStorage.lang || 'zh_CN'
    this.$store.commit('setLanguage', currLang)
    // 设置当前路由位置
    const currentRole = this.$route.path
    this.$store.commit('setRole', currentRole)
  },
  watch: {
    // 重新加载系统语言
    lang: function(value) {
      localStorage.setItem('lang', value)
      window.location.reload()
    }
  }
})

// 动态更新当前路由状态
router.beforeEach(function(to, from, next) {
  app.$store.commit('setRole', to.path)
  next()
})

export default app