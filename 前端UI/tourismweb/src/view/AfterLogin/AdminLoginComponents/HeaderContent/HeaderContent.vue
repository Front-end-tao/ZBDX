 <template>
  <el-row>
    <el-col :span="12">
      <el-breadcrumb separator="/">
        <el-breadcrumb-item>admin</el-breadcrumb-item>
        <el-breadcrumb-item>{{ breadCrumb }}</el-breadcrumb-item>
      </el-breadcrumb>
    </el-col>
    <el-col class="header_user" :span="12">
      <i class="el-icon-s-custom"  style="color:#F56C6C"></i>
      <span class="header_user_name">管理员</span>
      <el-dropdown trigger="click"  @command="adminDo">
        <span class="el-dropdown-link">
          <i class="el-icon-caret-bottom"></i>
        </span>
        <el-dropdown-menu slot="dropdown" class="admin-el-dropdown-menu">
          <el-dropdown-item icon="el-icon-s-tools" command="data">数据管理</el-dropdown-item>
          <el-dropdown-item icon="el-icon-chat-dot-square" command="letter">查看私信</el-dropdown-item>
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
      breadCrumb: '数据管理'
    }
  },
  methods: {
    adminDo(command) {
      this.$route.path
      if (command === 'data') {
        this.breadCrumb = '数据管理'
        this.$router.push({path:'/afterlogin/admin/data'})
      } else if(command === 'letter') {
        this.breadCrumb = '查看私信'
        this.$router.push({path:'/afterlogin/admin/letter'})
      } else if(command === 'quit') {
        this.$emit('quitAdmin')
      }
    }
  }
}
</script>

<style>
.admin-el-dropdown-menu .el-dropdown-menu__item:focus,
.admin-el-dropdown-menu .el-dropdown-menu__item:not(.is-disabled):hover {
  color: #F56C6C
}
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