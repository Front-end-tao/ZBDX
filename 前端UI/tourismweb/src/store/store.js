import Vue from 'vue'
import Vuex from 'vuex'
import getters from './getter'
import mutations from './mutations'
Vue.use(Vuex)


export const store = new Vuex.Store({
    state : {
      loginUser: {
        
      }
    },
    getters,
    mutations
})