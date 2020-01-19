// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'
import App from './App'
import router from './router'
import axios from 'axios'
import {store} from './store/store'
import ElementUI from 'element-ui';
import 'element-ui/lib/theme-chalk/index.css';

Vue.use(ElementUI, { size: 'small'})
Vue.prototype.$axios = axios
Vue.config.productionTip = false



Vue.prototype.toParams = (targetObj) => {
  const count = Object.keys(targetObj).length
  let params = '?'
  let targetCount = 0
  for (const item of Object.entries(targetObj)) {
    targetCount++
    params = params + item[0] + '=' + item[1]
    if (targetCount !== count) {
      params += '&'
    }
  }
  return params
}
/* eslint-disable no-new */
new Vue({
  el: '#app',
  router,
  components: { App },
  template: '<App/>',
  store : store
})
