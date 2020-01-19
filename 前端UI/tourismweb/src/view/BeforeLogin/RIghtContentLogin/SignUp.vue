<template>
  <div class="before-login-rightcontent-signup-wrapper"
  v-loading="wrapper_loading"
  element-loading-text="疯狂加载中"
  element-loading-spinner="el-icon-loading"
  element-loading-background="rgba(0, 0, 0, 0.8)">
      <el-container>
        <el-header class="login-header">
          <router-link :to="{name: 'BeforeLogin_Right_Login'}" class="back-icon" tag="span">
            <i class="el-icon-back"></i>
          </router-link>
          <span>Sign Up</span>
        </el-header>
        <el-main class="login-main">
          <el-container style="padding:0 60px;">
            <el-main>
              <el-form>
                <lt-input :placeholder="'请输入用户名'" :icon="'el-icon-user-solid'" :errorMessage="signUpError.userName" @getIptValue='getUserName' />
                <lt-input :placeholder="'请输入密码'" :icon="'el-icon-s-cooperation'" :passKey="true" :errorMessage="signUpError.password" @getIptValue='getPassword' />
                <lt-input :placeholder="'请确认密码'" :icon="'el-icon-s-cooperation'" :passKey="true" :errorMessage="signUpError.confirmPassword" @getIptValue='getConfirmPassword' />
                <lt-input :placeholder="'请输入邮箱'" :icon="'el-icon-s-cooperation'" :errorMessage="signUpError.mail" :btnConfig="btnConfig" @getIptValue='getPhoneNumber' @btnClick="sendMail"/>
                <lt-input :placeholder="'请输入验证码'" :icon="'el-icon-s-cooperation'" :errorMessage="signUpError.mailCode" @getIptValue='getMailCode'/>
                <el-button @click="register">Register</el-button>
              </el-form>
            </el-main>
          </el-container>
        </el-main>
      </el-container>
  </div>
</template>

<script>
import ltInput from '../../../components/Lt_Component_Input'
import cookie from '../../../lt/cookie.js'
export default {
  data() {
    return {
      wrapper_loading: false,
      signUpInfo: {
        userName: '',
        password: '',
        confirmPassword: '',
        mail: '',
        mailCode: ''
      },
      signUpError: {
        userName: '',
        password: '',
        confirmPassword: '',
        mail: '',
        mailCode: ''
      },
      btnConfig: {
        isActive: true,
        btnMessage: '获取验证码'
      }
    }
  },
  components: {
    ltInput
  },
  methods: {
    getUserName(value) {
      this.signUpInfo.userName = value
    },
    getPassword(value) {
      this.signUpInfo.password = value
    },
    getConfirmPassword(value) {
      this.signUpInfo.confirmPassword = value
    },
    getPhoneNumber(value) {
      this.signUpInfo.mail = value
    },
    getMailCode(value) {
      this.signUpInfo.mailCode = value
    },
    register() {
      let key = true
      for(let keyValue of Object.entries(this.signUpInfo) ){
        this.signUpError[keyValue[0]] = ''
        if(keyValue[1] === ''){
          let target;
          switch(keyValue[0]){
            case 'userName':
              target = '用户名'
              break
            case 'password':
              target = '密码'
              break
            case 'confirmPassword':
              target = '确认密码'
              break
            case 'mail':
              target = '邮箱'
              break
            case 'mailCode':
              target = '验证码'
              break
          }
          this.signUpError[keyValue[0]] = target + '不能为空'
          key = false
        }
      }
      for(let keyValue of Object.entries(this.signUpInfo) ){
        if(keyValue[1].indexOf(' ') !== -1){
          let target;
          switch(keyValue[0]){
            case 'userName':
              target = '用户名'
              break
            case 'password':
              target = '密码'
              break
            case 'confirmPassword':
              target = '确认密码'
              break
            case 'mail':
              target = '邮箱'
              break
            case 'mailCode':
              target = '验证码'
              break
          }
          this.signUpError[keyValue[0]] = target + '不能含有空格'
          key = false
        }
      }
      if(this.signUpInfo.userName && this.signUpInfo.userName.indexOf(' ') === -1 && this.signUpInfo.userName.length < 4 || this.signUpInfo.userName.length > 10){
        this.signUpError.userName = '用户名长度为4-10位'
        key = false
      }
      if(this.signUpInfo.password && this.signUpInfo.password.indexOf(' ') === -1 && this.signUpInfo.password.length < 6 || this.signUpInfo.password.length > 12){
        this.signUpError.password = '密码长度为6-12位'
        key = false
      }
      if(this.signUpInfo.password !== this.signUpInfo.confirmPassword){
        this.signUpError.confirmPassword = '两次密码不一致'
        key = false
      }
      if(this.signUpInfo.mail && this.signUpInfo.mail.indexOf(' ') === -1){
        if(!(/^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/.test(this.signUpInfo.mail))){ 
          this.signUpError.mail = '邮箱格式不正确'
          key = false
        }
      }
      if(key){
        const code = cookie.getCookie(this.signUpInfo.mail)
        console.log(code, this.signUpInfo.mailCode)
        if(code === this.signUpInfo.mailCode){
          this.$message({
            message: '验证码正确',
            type: 'success'
          })
          this.wrapper_loading = true
          this.$axios
          .post('http://localhost/tp5/public/index.php/index/User/add',this.signUpInfo)
          .then(res => {
            if(res.status === 200 && res.statusText === 'OK'){
              if(res.data.status === 1) {
                this.$message({
                  message: '注册成功，将自动跳转到登陆页面',
                  type: 'success'
                })
                cookie.delCookie(this.signUpInfo.mail)
                setTimeout(() => {
                  this.wrapper_loading = false
                  this.$router.push({path:'/beforelogin/login'})
                }, 2000);
              } else if(res.data.status === 0) {
                this.wrapper_loading = false
                this.$message({
                  message: '注册失败',
                  type: 'error'
                })
              }
            }else{
              this.wrapper_loading = false
              this.$message({
                message: '请求API失败',
                type: 'error'
              });
            }
          })
          .catch(err => {
            this.wrapper_loading = false
            this.$message({
              message: '请求API失败',
              type: 'error'
            });
          })
        }else if(code === ''){
          this.$message({
            message: '验证码已失效',
            type: 'error'
          })
        }else{
          this.$message({
            message: '验证码不正确',
            type: 'error'
          })
        }
      }
    },
    sendMail() {
      this.signUpError.mail = ''
      if(!this.signUpInfo.mail){
        this.signUpError.mail = '邮箱不能为空'
        return
      }
      if(this.signUpInfo.mail.indexOf(' ') !== -1){
        this.signUpError.mail = '邮箱不能含有空格'
        return
      }
      if(!(/^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/.test(this.signUpInfo.mail))){ 
        this.signUpError.mail = '邮箱格式不正确'
        return
      }
      const params = this.toParams({
        mail: this.signUpInfo.mail
      })
      this.wrapper_loading = true
      this.$axios
          .post('http://localhost/tp5/public/index.php/index/User/checkMail',this.signUpInfo)
          .then(res => {
            if(res.status === 200 && res.statusText === 'OK'){
              if(res.data.status === 1){
                this.$axios
                  .get(`http://localhost/tp5/public/index.php/index/User/sendMail${params}`)
                  .then(res => {
                    console.log(res)
                    if(res.status === 200 && res.statusText === 'OK'){
                      if(res.data.status === 1){
                        this.wrapper_loading = false
                        cookie.setCookie(res.data.mail,res.data.code,300)
                        this.$message({
                          message: '获取验证码成功，请前往邮箱查看，有效期为5分钟',
                          type: 'success'
                        });
                      }else if (res.data.status === 0){
                        this.wrapper_loading = false
                        this.$message({
                          message: '获取验证码失败',
                          type: 'error'
                        });
                      }
                    }else{
                      this.wrapper_loading = false
                      this.$message({
                        message: '获取验证码失败',
                        type: 'error'
                      });
                    }
                  })
                  .catch(err => {
                    this.wrapper_loading = false
                    this.$message({
                      message: '获取验证码失败',
                      type: 'error'
                    });
                  })
              } else if(res.data.status === 0) {
                this.signUpError.mail = '邮箱已被注册'
                this.$message({
                  message: '邮箱已被注册',
                  type: 'error'
                });
                this.wrapper_loading = false
              }
            } else {
              this.wrapper_loading = false
              this.$message({
                message: '请求API失败',
                type: 'error'
              });
            }
          })
          .catch(err => {
            this.wrapper_loading = false
            this.$message({
              message: '请求API失败',
              type: 'error'
            });
          })
      
    }
  }
}
</script>

<style>
.before-login-rightcontent-signup-wrapper{
  height: 90vh;
  padding: 5vh 0 0;
}
.before-login-rightcontent-signup-wrapper > .el-container{
  height: 95vh;
}
.before-login-rightcontent-signup-wrapper .login-main{
  padding: 0 20px;
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
}.login-main .el-main a:hover{
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