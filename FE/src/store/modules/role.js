// 全局路由状态

import { routerMap } from '@/router'

const role = {
  state: {
    routerMap: routerMap,
    currentRole: ''
  },
  mutations: {
    setRole: (state, value) => {
      state.currentRole = value
    }
  },
  getters: {
    fullRole: (state) => {
      const roles = []
      state.routerMap.forEach(item => {
        const role = {}
        if(item.path === state.currentRole) {
          // 一级路由时，获取当前路由信息
          role.title = item.meta.title
          role.path = item.path
        } else if(item.children) {
          // 二级路由时，获取父子路由信息
          item.children.forEach(itemChild => {
            if(itemChild.path === state.currentRole) {
              role.title = itemChild.meta.title
              role.path = itemChild.path
              // 获取父级路由信息
              const roleParent = {}
              roleParent.title = item.meta.title
              roleParent.path = item.path
              roles.push(roleParent)
            }
          })
        }
        if(role.title && role.path) roles.push(role)
      })
      return roles
    }
  }
}

export default role