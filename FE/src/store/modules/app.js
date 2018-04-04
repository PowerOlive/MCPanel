// App 全局状态

const app = {
  state: {
    lang: localStorage.lang || 'zh_CN',
    account: {
      username: 'Caringor',
      nickname: '萌王千岁',
      email: 'lazywind@hotmail.com',
      avatar: require('@/static/images/default_avatar.jpg'),
      banner: require('@/static/images/default_banner.jpg'),
      token: localStorage.user_token || null,
    }
  },
  mutations: {
    setLanguage: (state, value) => {
      state.lang = value
    }
  }
}

export default app