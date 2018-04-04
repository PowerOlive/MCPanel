import Vue from 'vue'
import VueI18n from 'vue-i18n'
import locale from 'element-ui/lib/locale'

// 引入各语言文件
import zh_CN from './lang/zh_CN'

Vue.use(VueI18n)

const i18n = new VueI18n({
  locale: localStorage.lang || 'zh_CN',
  messages: {
    zh_CN
  }
})

locale.i18n((key, value) => i18n.t(key, value))

export default i18n