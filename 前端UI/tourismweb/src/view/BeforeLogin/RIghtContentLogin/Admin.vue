<template>
  <div class="before-login-rightcontent-login-wrapper"
    v-loading="wrapper_loading" 
    element-loading-text="管理员登陆中"
    element-loading-spinner="el-icon-loading"
    element-loading-background="rgba(0, 0, 0, 0.8)"
  >
    <el-container class="wrapper-container">
      <el-header class="login-header">
        <router-link :to="{name: 'BeforeLogin_Right_Login'}" class="back-icon" tag="span">
          <i class="el-icon-back"></i>
        </router-link>
        <span>Admin</span>
      </el-header>
      <el-main class="login-main">
        <el-container style="padding:0 60px;">
          <el-header style="height:30px;">
            Login with your admin account
          </el-header>
          <el-main>
            <el-form>
              <lt-input :placeholder="'请输入管理员账号'" :icon="'el-icon-user-solid'" :passKey="true" :errorMessage="adminError.adminCount" @getIptValue='getAdminCount' />
              <lt-input :placeholder="'请输入管理员密码'" :icon="'el-icon-s-cooperation'" :passKey="true" :errorMessage="adminError.password" @getIptValue='getAdminPassword' />
              <el-button @click="login">login</el-button>
            </el-form>
          </el-main>
        </el-container>
      </el-main>
    </el-container>
  </div>
</template>

<script>
import ltInput from '../../../components/Lt_Component_Input'
export default {
  data() {
    return {
      wrapper_loading: false,
      adminInfo: {
        adminCount: '',
        password: ''
      },
      adminError: {
        adminCount: '',
        password: ''
      }
    }
  },
  components: {
    ltInput
  },
  methods: {
    getAdminCount(value) {
      this.adminInfo.adminCount = value
    },
    getAdminPassword(value) {
      this.adminInfo.password = value
    },
    login() {
      this.wrapper_loading = true
      if(this.adminInfo.adminCount === '' && this.adminInfo.password === ''){
        setTimeout(() => {
          this.$message({
            message: '管理员登陆成功',
            type: 'success'
          })
          this.$router.push({path:'/afterlogin/admin'})
          this.wrapper_loading = false
        }, 2000);
      }else{
        setTimeout(() => {
          this.$message({
            message: '管理员登陆失败',
            type: 'error'
          })
          this.wrapper_loading = false
        }, 2000);
      }
    }
  },
  created() {
    this.$message({
      message: '管理员入口',
      type: 'info'
    })
  }
}
</script>

<style>
.before-login-rightcontent-login-wrapper{
  height: 80vh;
  padding: 10vh 0;
}
.before-login-rightcontent-login-wrapper > .el-container.wrapper-container{
  height: 80vh;
}
</style>
<style scoped>
.login-header{
  position: relative;
  line-height: 60px;
  text-align: center;
  font-size: 30px;
  font-weight: 500;
}
.login-main{
  min-height: 300px;
}
.login-footer{
  text-align: center;
  line-height: 50px;
  font-size: 12px;
  color: rgb(158, 153, 153);
}
.el-footer a{
  color: rgb(192, 189, 189);
}
.login-main .el-header{
  color: gray;
}
.login-main .el-main p{
  display: block;
  text-align: right;
  color: gray;
  font-size: 12px;
}
.login-main .el-main span:hover{
  color: rgb(160, 156, 156);
  cursor: pointer;
}
.login-main .el-main button{
  width: 100%;
  margin-top: 20px;
}
.back-icon{
  position: absolute;
  left: 20px;
  cursor: pointer;
}
</style>