import Vue from 'vue'
import Router from 'vue-router'
import Meta from 'vue-meta'
import NotFound from '@/components/NotFound'

import globals from '@/globals'

// Layouts
import Layout1 from '@/layout/Layout1'

import axios from 'axios';
import VueAxios from 'vue-axios';
Vue.use(VueAxios, axios);
axios.defaults.baseURL = 'http://constructor.test/api';

Vue.use(Router)
Vue.use(Meta)

const router = new Router({
  base: '/',
  mode: 'history',
  routes: [{
    path: '/',
    component: Layout1,
    children: [{
      path: '',
      component: () => import('@/components/Home')
    }, 
    {
      path: 'login',
      component: () => import('@/components/Login'),
      meta: { auth: false }
    },
    {
      path: 'register',
      component: () => import('@/components/Register'),
      meta: { auth: false }
    },
    {
      path: 'dashboard',
      component: () => import('@/components/Dashboard'),
      meta: { auth: true }
    },
    {
      path: 'adm/users',
      component: () => import('@/components/users/Users'),
      meta: { auth: true }
    },
    {
      path: 'adm/dpps',
      name: 'dpps',
      component: () => import('@/components/dpps/Dpps'),
      meta: { auth: true }
    },
    {
      path: 'adm/dpps/:dpp/config',
      name: 'dpp_config',
      component: () => import('@/components/dpps/DppConfig.vue'),
      meta: { auth: true }
    },
    {
      path: 'adm/dpps/:dpp/inspect',
      name: 'dpp_inspect',
      component: () => import('@/components/dpps/DppInspect.vue'),
      meta: { auth: true }
    },
    {
      path: 'my_dpps',
      name: 'my_dpps',
      component: () => import('@/components/dpps/MyDpps'),
      meta: { auth: true }
    },
    {
      path: 'adm/typologies',
      name: 'typologies',
      component: () => import('@/components/typologies/Typologies'),
      meta: { auth: true }
    },
    {
      path: 'my_dpps/:dpp/overview/:role',
      name: 'dpp_overview',
      component: () => import('@/components/dpps/DppOverview'),
      meta: { auth: true }
    },
    {
      path: 'my_dpps/:dpp/stages/:stage/work',
      name: 'dpp_stage_work',
      component: () => import('@/components/dpps/NewOC'),
      meta: { auth: true }
    },
    {
      path: 'my_dpps/:dpp/stages/:stage/work2',
      name: 'dpp_stage_work2',
      component: () => import('@/components/dpps/NewOC'),
      meta: { auth: true }
    },
    {
      path: 'my_dpps/:dpp/stages/:stage/work_ish',
      name: 'dpp_stage_work_ish',
      component: () => import('@/components/dpps/DppStageWorkIsh'),
      meta: { auth: true }
    },
    {
      path: 'my_dpps/:dpp/stages/:stage/work_om',
      name: 'dpp_stage_work_om',
      component: () => import('@/components/dpps/DppStageWorkOM'),
      meta: { auth: true }
    },
    {
      path: 'my_dpps/new_orgchart',
      name: 'new_orgchart',
      component: () => import('@/components/dpps/NewOC'),
      meta: { auth: true }
    },
  ]
  }, {
    // 404 Not Found page
    path: '*',
    component: NotFound
  }]
})

Vue.router=router
Vue.use(require('@websanova/vue-auth'), {
  auth: require('@websanova/vue-auth/drivers/auth/bearer.js'),
  http: require('@websanova/vue-auth/drivers/http/axios.1.x.js'),
  router: require('@websanova/vue-auth/drivers/router/vue-router.2.x.js'),
});



router.afterEach(() => {
  // On small screens collapse sidenav
  if (window.layoutHelpers && window.layoutHelpers.isSmallScreen() && !window.layoutHelpers.isCollapsed()) {
    setTimeout(() => window.layoutHelpers.setCollapsed(true, true), 10)
  }

  // Scroll to top of the page
  globals().scrollTop(0, 0)
})

router.beforeEach((to, from, next) => {
  // Set loading state
  document.body.classList.add('app-loading')

  // Add tiny timeout to finish page transition
  setTimeout(() => next(), 10)
})

export default router


