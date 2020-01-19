 <template>
  <el-row>
    <el-col :span="12">
      <el-breadcrumb separator="/">
        <el-breadcrumb-item>tourism旅游网站</el-breadcrumb-item>
        <el-breadcrumb-item>{{ breadCrumb }}</el-breadcrumb-item>
      </el-breadcrumb>
    </el-col>
    <el-col class="header_user" :span="12">
      <i class="el-icon-s-custom"  style="color:#409EFF"></i>
      <span class="header_user_name">{{ userMessage.userName }}</span>
      <el-dropdown trigger="click"  @command="adminDo">
        <span class="el-dropdown-link">
          <i class="el-icon-caret-bottom"></i>
        </span>
        <el-dropdown-menu slot="dropdown" class="admin-el-dropdown-menu">
          <el-dropdown-item icon="el-icon-s-grid" command="travel">订购门票</el-dropdown-item>
          <el-dropdown-item icon="el-icon-truck" command="ticket">订购车票</el-dropdown-item>
          <el-dropdown-item icon="el-icon-discount" command="hotel">订购酒店</el-dropdown-item>
          <el-dropdown-item icon="el-icon-setting" command="message">个人信息</el-dropdown-item>
          <el-dropdown-item icon="el-icon-s-data" command="orders">我的订单</el-dropdown-item>
          <el-dropdown-item icon="el-icon-circle-close" command="quit">退出登陆</el-dropdown-item>
        </el-dropdown-menu>
      </el-dropdown>
    </el-col>
  </el-row>
</template>

<script>
export default {
  data() {
    return {
      breadCrumb: '订购门票'
    }
  },
  methods: {
    adminDo(command) {
      this.$route.path
      if (command === 'travel') {
        this.breadCrumb = '订购门票'
        this.$router.push({path:'/afterlogin/user/travel'})
      } else if(command === 'ticket') {
        this.breadCrumb = '订购车票'
        this.$router.push({path:'/afterlogin/user/ticket'})
      }else if(command === 'hotel') {
        this.breadCrumb = '订购酒店'
        this.$router.push({path:'/afterlogin/user/hotel'})
      }else if(command === 'message') {
        this.breadCrumb = '个人信息'
        this.$router.push({path:'/afterlogin/user/message'})
      } else if(command === 'orders') {
        this.breadCrumb = '我的订单'
        this.$router.push({path:'/afterlogin/user/orders'})
      } else if(command === 'quit') {
        this.$emit('quitUser')
      }
    }
  },
  computed: {
    userMessage() {
      return this.$store.getters.loginUser
    }
  }
}
</script>

<style>

</style>
<style scoped>
.el-breadcrumb{
  height: 30px;
  line-height: 30px;
}
.header_user{
  text-align: right;
  height: 30px;
  line-height: 30px;
}
.header_user .el-icon-s-custom{
  font-size: 15px;
}
.header_user_name{
  font-size: 12px;
  color: #909399
}
.el-dropdown-link{
  cursor: pointer;
}
</style>