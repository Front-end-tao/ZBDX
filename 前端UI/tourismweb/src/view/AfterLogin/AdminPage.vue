<template>
  <el-container v-loading="wrapper_loading"
  element-loading-text="管理员退出中"
    element-loading-spinner="el-icon-loading"
    element-loading-background="rgba(0, 0, 0, 0.8)">
    <el-header class="alterlogin_wrapper_header">
      <header-content @quitAdmin="quitAdmin" />
    </el-header>
    <el-main>
      <router-view />
    </el-main>
  </el-container>
</template>

<script>
import HeaderContent from './AdminLoginComponents/HeaderContent/HeaderContent'
export default {
  data() {
    return {
      wrapper_loading: false
    }
  },
  components: {
    HeaderContent
  },
  methods: {
    quitAdmin() {
      this
        .$confirm('是否退出管理员登陆?', '提示', {
          confirmButtonText: '确定',
          cancelButtonText: '取消',
          type: 'error'
        })
        .then(() => {
          this.wrapper_loading = true
          setTimeout(() => {
            this.wrapper_loading = false
            this.$router.push({path: '/beforelogin'})
            this.$message({
              type: 'success',
              message: '退出成功'
            });
          }, 1000);
        })
        .catch(() => {
            
        });
    }
  }
}
</script>

<style scoped>
.el-header{
  height: 30px!important;
  box-shadow: 0 2px 4px rgba(0, 0, 0, .12), 0 0 6px rgba(0, 0, 0, .04)
}
.el-main{
  padding: 0 0 0 0;
  height: calc(100vh - 30px);
  overflow: hidden;
}
</style>