import Vue from 'vue'
import Vuex from 'vuex'
import i18n from '@/utils/i18n'

import app from './modules/app'
import role from './modules/role'

Vue.use(Vuex)

// 实例化 Vuex 对象
export default new Vuex.Store({
  modules: {
    app,
    role
  }
})