<template>
  <div class="before-login-rightcontent-login-wrapper" 
  v-loading="wrapper_loading"
  element-loading-text="疯狂加载中"
  element-loading-spinner="el-icon-loading"
  element-loading-background="rgba(0, 0, 0, 0.8)">
      <el-container class="wrapper-container">
        <el-header class="login-header">LOGIN</el-header>
        <el-main class="login-main">
          <el-container style="padding:0 60px;">
            <el-header style="height:30px;">
              Login with your account
            </el-header>
            <el-main>
              <el-form>
                <lt-input :placeholder="'请输入邮箱'" :icon="'el-icon-user-solid'" :errorMessage="loginUserError.mail" @getIptValue='getMail' />
                <lt-input :placeholder="'请输入密码'" :icon="'el-icon-s-cooperation'" :passKey="true" :errorMessage="loginUserError.password" @getIptValue='getPassword' @keypress.native.enter="login" />
                <p>
                  <router-link :to="{name: 'BeforeLogin_Right_ForgetPw'}" tag="span" >forget password?</router-link>
                </p>
                <el-button @click="login">login</el-button>
              </el-form>
            </el-main>
          </el-container>
        </el-main>
        <el-footer class="login-footer">if you do not have an account,<router-link :to="{name: 'BeforeLogin_Right_SignUp'}">Sign Up</router-link>.</el-footer>
      </el-container>
      <router-link class="toAdmin" tag="div" :to="{name: 'BeforeLogin_Right_Admin'}"><i class="el-icon-user"></i>admin</router-link>
  </div>
</template>

<script>
import ltInput from '../../../components/Lt_Component_Input'
import myLocalStore from '../../../lt/localStore'
export default {
  data() {
    return {
      wrapper_loading: false,
      loginUserInfo: {
        mail: '',
        password: ''
      },
      loginUserError: {
        mail: '',
        password: ''
      }
    }
  },
  components: {
    ltInput
  },
  methods: {
    getMail(value) {
      this.loginUserInfo.mail = value
    },
    getPassword(value) {
      this.loginUserInfo.password = value
    },
    login() {
      let key = true
      for(let keyValue of Object.entries(this.loginUserInfo) ){
        this.loginUserError[keyValue[0]] = ''
        if(keyValue[1] === ''){
          let target;
          switch(keyValue[0]){
            case 'mail':
              target = '邮箱'
              break
            case 'password':
              target = '密码'
              break
          }
          this.loginUserError[keyValue[0]] = target + '不能为空'
          key = false
        }
      }
      for(let keyValue of Object.entries(this.loginUserInfo) ){
        if(keyValue[1].indexOf(' ') !== -1){
          let target;
          switch(keyValue[0]){
            case 'mail':
              target = '邮箱'
              break
            case 'password':
              target = '密码'
              break
          }
          this.loginUserError[keyValue[0]] = target + '不能含有空格'
          key = false
        }
      }
      if(this.loginUserInfo.mail && this.loginUserInfo.mail.indexOf(' ') === -1){
        if(!(/^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/.test(this.loginUserInfo.mail))){ 
          this.loginUserError.mail = '邮箱格式不正确'
          key = false
        }
      }
      if(key){
        this.wrapper_loading = true
        const params = this.toParams(this.loginUserInfo)
        this.$axios
          .get(`http://localhost/tp5/public/index.php/index/User/login${params}`)
          .then(res=> {
            if(res.status === 200 && res.statusText === 'OK'){
              if(res.data.status === 0){
                this.$message({
                  message: '邮箱未注册，请注册后再登陆。',
                  type: 'error'
                })
                setTimeout(() => {  
                 this.wrapper_loading = false
                }, 200);
              }else if(res.data.status === 1 && res.data.message == 'login fail'){
                this.$message({
                  message: '密码不正确，登陆失败。',
                  type: 'error'
                })
                setTimeout(() => {  
                  this.wrapper_loading = false
                }, 200);
              }else if(res.data.status === 1 && res.data.message == 'login success'){
                this.$message({
                  message: '你好，' + res.data.data.userName + '!',
                  type: 'success'
                })
                myLocalStore.set('loginUser', JSON.stringify(res.data.data))
                this.$store.commit('setLoginUser',res.data.data)
                setTimeout(() => {  
                  this.wrapper_loading = false
                  this.$router.push({path:'/afterlogin/user'})
                }, 200);
              }
              
            } else {
              this.$message({
                message: '请求API失败',
                type: 'error'
              });
              setTimeout(() => {
                  this.wrapper_loading = false
                }, 200);
            }
          })
          .catch(err => {
            setTimeout(() => {
                  this.wrapper_loading = false
                }, 200);
            this.$message({
              message: '请求API失败',
              type: 'error'
            });
          })
      }
    }
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
.toAdmin{
  position: absolute;
  top: 0;
  right: 0;
  font-size: 12px;
  top: 10px;
  right: 20px;
  color: #909399;
  cursor: pointer;
}
</style>