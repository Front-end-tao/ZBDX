<template>
  <div>
    <router-view class="wrapper Router" />
  </div>
</template>

<script>
import BeforeLogin from './view/BeforeLogin.vue'
import AfterLogin from './view/AfterLogin.vue'
import myLocalStore from './lt/localStore'
export default {
  components: {
    BeforeLogin
  },
  created() {
    const loginUserJOSN = myLocalStore.get('loginUser')
    if(loginUserJOSN){
      this.$store.commit('setLoginUser',JSON.parse(loginUserJOSN))
    }
  }
}
</script>

<style>
*{
  padding: 0;
  margin: 0;
  list-style-type: none;
}
body .el-table th.gutter{
display: table-cell!important;
}
</style>  
