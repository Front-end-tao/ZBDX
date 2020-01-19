<template>
  <div class="before-login-rightcontent-signup-wrapper" v-loading="wrapper_loading"
  element-loading-text="疯狂加载中"
  element-loading-spinner="el-icon-loading"
  element-loading-background="rgba(0, 0, 0, 0.8)">
      <el-container>
        <el-header class="login-header">
          <router-link :to="{name: 'BeforeLogin_Right_Login'}" class="back-icon" tag="span">
            <i class="el-icon-back"></i>
          </router-link>
          <span>Reset</span>
        </el-header>
        <el-main class="login-main">
          <el-container style="padding:0 60px;">
            <el-main>
              <el-form>
                <lt-input :placeholder="'请输入您的邮箱'" :icon="'el-icon-user-solid'" :errorMessage="forgetPwError.mail" :btnConfig="btnConfig" @getIptValue="getMail" @btnClick="sendMail"/>
                <lt-input :placeholder="'验证码'" :icon="'el-icon-s-cooperation'" :passKey="true" :errorMessage="forgetPwError.mailCode"  @getIptValue="getMailCode" />
                <lt-input :placeholder="'请输入新密码'" :icon="'el-icon-s-cooperation'" :passKey="true" :errorMessage="forgetPwError.password" @getIptValue="getPassWord" />
                <lt-input :placeholder="'确认新密码'" :icon="'el-icon-s-cooperation'" :passKey="true" :errorMessage="forgetPwError.confirmPassword"  @getIptValue="getConfirmPassWord" />
                <el-button @click="confirmChange">confirm</el-button>
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
      btnConfig: {
        isActive: true,
        btnMessage: '获取验证码'
      },
      forgetPwInfo: {
        mail: '',
        mailCode: '',
        password: '',
        confirmPassword: ''
      },
      forgetPwError: {
        mail: '',
        mailCode: '',
        password: '',
        confirmPassword: ''
      }
    }
  },
  components: {
    ltInput
  },
  methods: {
    getMail(value) {
      this.forgetPwInfo.mail = value
    },
    getMailCode(value) {
      this.forgetPwInfo.mailCode = value
    },
    getPassWord(value) {
      this.forgetPwInfo.password = value
    },
    getConfirmPassWord(value) {
      this.forgetPwInfo.confirmPassword = value
    },
    confirmChange() {
      let key = true
      for(let keyValue of Object.entries(this.forgetPwInfo) ){
        this.forgetPwError[keyValue[0]] = ''
        if(keyValue[1] === ''){
          let target;
          switch(keyValue[0]){
            case 'mail':
              target = '邮箱'
              break
            case 'mailCode':
              target = '密码'
              break
            case 'password':
              target = '邮箱'
              break
            case 'confirmPassword':
              target = '密码'
              break
          }
          this.forgetPwError[keyValue[0]] = target + '不能为空'
          key = false
        }
      }
      for(let keyValue of Object.entries(this.forgetPwInfo) ){
        if(keyValue[1].indexOf(' ') !== -1){
          let target;
          switch(keyValue[0]){
            case 'mail':
              target = '邮箱'
              break
            case 'mailCode':
              target = '密码'
              break
            case 'password':
              target = '邮箱'
              break
            case 'confirmPassword':
              target = '密码'
              break
          }
          this.forgetPwError[keyValue[0]] = target + '不能含有空格'
          key = false
        }
      }
      if(this.forgetPwInfo.password && this.forgetPwInfo.password.indexOf(' ') === -1 && this.forgetPwInfo.password.length < 6 || this.forgetPwInfo.password.length > 12){
        this.forgetPwError.password = '密码长度为6-12位'
        key = false
      }
      if(this.forgetPwInfo.password !== this.forgetPwInfo.confirmPassword){
        this.forgetPwError.confirmPassword = '两次密码不一致'
        key = false
      }
      if(this.forgetPwInfo.mail && this.forgetPwInfo.mail.indexOf(' ') === -1){
        if(!(/^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/.test(this.forgetPwInfo.mail))){ 
          this.forgetPwError.mail = '邮箱格式不正确'
          key = false
        }
      }
      if (key) {
        const code = cookie.getCookie(this.forgetPwInfo.mail)
        if(code === this.forgetPwInfo.mailCode){
          this.$message({
            message: '验证码正确',
            type: 'success'
          })
          this.wrapper_loading = true
          console.log(this.forgetPwInfo)
          this.$axios
          .post('http://localhost/tp5/public/index.php/index/User/changePassword',this.forgetPwInfo)
          .then(res => {
            if(res.status === 200 && res.statusText === 'OK'){
              if(res.data.status === 1) {
                this.$message({
                  message: '修改密码成功，将自动跳转到登陆页面',
                  type: 'success'
                });
                cookie.delCookie(this.forgetPwInfo.mail)
                setTimeout(() => {
                  this.wrapper_loading = false
                  this.$router.push({path:'/beforelogin/login'})
                }, 2000);
              } else if(res.data.status === 0 && res.data.message === 1) {
                this.wrapper_loading = false
                this.forgetPwError.password = '新密码不得与原密码相同'
                this.$message({
                  message: '新密码不得与原密码相同',
                  type: 'error'
                });
              } else if(res.data.status === 0 && res.data.message === 0) {
                this.wrapper_loading = false
                this.$message({
                  message: '修改密码失败',
                  type: 'error'
                });
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
      this.forgetPwError.mail = ''
      if(!this.forgetPwInfo.mail){
        this.forgetPwError.mail = '邮箱不能为空'
        return
      }
      if(this.forgetPwInfo.mail.indexOf(' ') !== -1){
        this.forgetPwError.mail = '邮箱不能含有空格'
        return
      }
      if(!(/^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/.test(this.forgetPwInfo.mail))){ 
        this.forgetPwError.mail = '邮箱格式不正确'
        return
      }
      this.wrapper_loading = true
      this.$axios
        .post('http://localhost/tp5/public/index.php/index/User/checkMail',this.forgetPwInfo)
        .then(res => {
          if(res.status === 200 && res.statusText === 'OK'){
            if(res.data.status === 1){
              this.wrapper_loading = false
              this.forgetPwError.mail = '该邮箱不存在'
              this.$message({
                message: '该邮箱不存在',
                type: 'error'
              })
            }else if(res.data.status === 0){
              const params = this.toParams({
                mail: this.forgetPwInfo.mail
              })
              this.$axios
                .get(`http://localhost/tp5/public/index.php/index/User/sendMail${params}`)
                .then(res => {
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
                  })
                })
            }
          }
        })
        .catch(err => {
          this.wrapper_loading = false
          this.$message({
            message: '请求API失败',
            type: 'error'
          })
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