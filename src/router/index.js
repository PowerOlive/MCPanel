import Vue from 'vue'
import VueRouter from 'vue-router'

Vue.use(VueRouter)

// 引入路由页面
import Dashboard from '@/views/dashboard/index.vue'
import User from '@/views/user/index.vue'
import Miner from '@/views/miner/index.vue'

export const routerMap = [
  {
    path: '/',
    redirect: '/dashboard',
    hidden: true,
  },
  {
    path: '/dashboard',
    name: 'dashboard',
    component: Dashboard,
    meta: {
      title: 'dashboard',
      icon: 'tachometer-alt'
    }
  },
  {
    path: '/user',
    name: 'user',
    component: User,
    meta: {
      title: 'user',
      icon: 'user-circle'
    }
  },
  {
    path: '/skin',
    name: 'skin',
    meta: {
      title: 'skin',
      icon: 'puzzle-piece'
    }
  },
  {
    path: '/miner',
    component: Miner,
    name: 'miner',
    meta: {
      title: 'miner',
      icon: 'coins'
    }
  },
  {
    path: '/security',
    name: 'security',
    redirect: '/security/index',
    meta: {
      title: 'security',
      icon: 'lock'
    },
    children: [
      {
        path: '/security/index',
        name: 'password',
        meta: {
          title: 's_password',
          icon: 'key'
        }
      },
      {
        path: '/security/two-step',
        name: 'twostep',
        meta: {
          title: 's_two_step',
          icon: 'check-circle'
        }
      }
    ]
  }
]

export default new VueRouter({
  routes: routerMap
})