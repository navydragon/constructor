import Vue from 'vue'
import App from './App'
import router from './router'

import { BootstrapVue  } from 'bootstrap-vue'

import globals from './globals'
import Popper from 'popper.js'
import CKEditor from '@ckeditor/ckeditor5-vue2';
Vue.use( CKEditor );

import 'vue-search-select/dist/VueSearchSelect.css'
// Required to enable animations on dropdowns/tooltips/popovers
Popper.Defaults.modifiers.computeStyle.gpuAcceleration = false

Vue.config.productionTip = false

Vue.use(BootstrapVue)

// Global RTL flag
Vue.mixin({
  data: globals
})

new Vue({
  router,
  render: h => h(App)
}).$mount('#app')
