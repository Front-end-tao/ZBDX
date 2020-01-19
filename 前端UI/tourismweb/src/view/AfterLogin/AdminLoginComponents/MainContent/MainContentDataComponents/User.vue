<template>
  <el-row v-loading="wrapper_loading" class="user_wrapper" element-loading-text="疯狂加载中"
  element-loading-spinner="el-icon-loading"
  element-loading-background="rgba(0, 0, 0, 0.8)">
    <el-row class="search-input-wrapper header-add">
        <el-button type="danger" icon="el-icon-refresh-right" @click="refresh" circle />
        <el-input placeholder="邮箱/用户名搜索" v-model="searchContent" class="input-with-select" @clear="getAllUserInfoByClear" clearable>
        <el-button slot="append" icon="el-icon-search" type="primary" @click="getAllUserInfoByClick"></el-button>
      </el-input>
    </el-row>
    <el-table
      :data="tableData"
      style="width: 100%;"
      header-row-class-name="users-table-header-row-class"
      row-class-name="users-table-row-class"
      height="750px"
      border
      stripe
    >
      <el-table-column
        prop="mail"
        label="邮箱"
        width="200">
      </el-table-column>
      <el-table-column
        prop="userName"
        label="用户名"
      >
      </el-table-column>
      <el-table-column
        prop="password"
        label="密码"
        width="300">
      </el-table-column>
      
      <el-table-column
      label="操作"
      width="90"
      > 
        <template slot-scope="scope">
          <div  class="users-table-click-class">
          <el-button @click="table_edit(scope.row)" type="text" size="small">编辑</el-button>
          <el-button class="delete-btn" @click="table_delete(scope.row.mail)" type="text" size="small">删除</el-button>
          </div>
        </template>
    </el-table-column>
    </el-table>
    <el-dialog :visible.sync="editUserDislog" custom-class="editUserDislog-dialog" width="380px" :show-close="false" v-loading="wrapper_loading_singleUser_dialog" element-loading-text="疯狂加载中"
  element-loading-spinner="el-icon-loading"
  element-loading-background="rgba(0, 0, 0, 0.8)">
      <el-form
        label-width="60px"
        style="width:300px;"
        label-position="left"
        :rules="rules"
        ref="singleUser"
        :model="singleUser"
      >
        <el-form-item label="邮箱">
          <el-input :value="singleUser.mail"></el-input>
        </el-form-item>
        <el-form-item label="用户名" prop="userName">
          <el-input v-model="singleUser.userName"></el-input>
        </el-form-item>
        <el-form-item label="密码" prop="password">
          <el-input v-model="singleUser.password"></el-input>
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="editUserDislog = false" type="danger">取 消</el-button>
        <el-button type="danger"  @click="editUser">修 改</el-button>
      </div>
    </el-dialog>
  </el-row>
</template>

<script>
export default {
  data() {
    let checkUserName = (rule, value, callBack) => {
      if (!value) {
          return callBack(new Error('用户名不可为空'));
        }
        setTimeout(() => {
          if (value.indexOf(' ') !== -1) {
            callBack(new Error('禁止包含空格'));
          } else {
              if(value.length < 4 || value.length > 10){
                callBack(new Error('用户名长度为4-10位'));
              }else{
                 if(value === this.singleUserOld.userName && this.singleUserOld.password === this.singleUser.password){
                  callBack(new Error('用户名与密码均未检测到改动'));
                 }else{
                  callBack();
                 }
              }
          }
        }, 100);
    }
    let checkPassword = (rule, value, callBack) => {
      if (!value) {
          return callBack(new Error('密码不可为空'));
        }
        setTimeout(() => {
          if (value.indexOf(' ') !== -1) {
            callBack(new Error('禁止包含空格'));
          } else {
              if(value.length < 6 || value.length > 12){
                callBack(new Error('密码长度为6-12位'));
              }else{
                if(value === this.singleUserOld.password && this.singleUserOld.userName === this.singleUser.userName){
                  callBack(new Error('用户名与密码均未检测到改动'));
                 }else{
                   callBack();
                 }
              }
          }
        }, 100);
    }
    return {
      wrapper_loading_singleUser_dialog: false,
      editUserDislog: false,
      searchContent: '',
      getDataKey: true,
      wrapper_loading: false,
      tableData: [],
      singleUserOld: {
        mail: '',
        userName: '',
        password: ''
      },
      singleUser: {
        mail: '',
        userName: '',
        password: ''
      },
      rules: {
        userName: [
          { validator: checkUserName, trigger: 'blur' }
        ],
        password: [
          { validator: checkPassword, trigger: 'blur' }
        ]
      }
    }
  },
  methods: {
    getAllUserInfo() {
      if(this.getDataKey){
        this.wrapper_loading = true
        let params;
        if (this.searchContent) {
          if(/.*[\u4e00-\u9fa5]+.*$/.test(this.searchContent)){
            params = {
              userName: this.searchContent
            }
          }else{
            params = {
              mail: this.searchContent
            }
          }
        }else{
          params = {

          }
        }
        params =this.toParams(params)
        this.$axios
          .get(`http://localhost/tp5/public/index.php/index/User/getUsers${params}`)
          .then(res => {
            if(res.status === 200 && res.statusText === 'OK' && res.data.status ===1){
              this.tableData = res.data.data.users
              this.$message({
                message: `获取用户信息成功，共${res.data.data.count}条`,
                type: 'success'
              })
            } else {
              this.$message({
                messsage: '获取用户信息失败',
                type: 'error'
              })
            }
            this.wrapper_loading = false
          })
          .catch(err => {
            this.$message({
                messsage: '获取用户信息失败',
                type: 'error'
              })
            this.wrapper_loading = false
          })
        this.getDataKey = false
      }
    },
    getAllUserInfoByClick() {
      if(this.searchContent){
        this.getDataKey = true
        this.getAllUserInfo()
      }
    },
    getAllUserInfoByClear() {
      this.refresh()
    },
    table_edit(mail) {
      this.editUserDislog = true
      this.singleUser.mail = mail.mail
      this.singleUser.userName = mail.userName
      this.singleUser.password = mail.password
      this.singleUserOld = Object.assign({},this.singleUser)
    },
    table_delete(mail) {
      this
        .$confirm('是否删除该用户?', '警告', {
          confirmButtonText: '确定',
          cancelButtonText: '取消',
          type: 'error',
          cancelButtonClass: 'el-button--danger is-plain admin-user-confirm-button',
          confirmButtonClass: 'el-button--danger admin-user-confirm-button',
          customClass: 'admin-user-confirm'
        })
        .then(() => {
          this.wrapper_loading = true
          this.$axios
          .delete('http://localhost/tp5/public/index.php/index/User/del', {
            data: {
              mail
            }
          })
          .then(res => {
            if(res.status === 200 && res.statusText === 'OK' && res.data.status ===1){
              this.$message({
                message: '删除成功',
                type: 'success'
              })
              this.refresh()
            }else{
              this.$message({
                message: '删除失败',
                type: 'error'
              })
            }
            this.wrapper_loading = false
          })
          .catch(err => {
            this.$message({
              message: '删除失败',
              type: 'error'
            })
            this.wrapper_loading = false
          })
        })
        .catch(() => {
          this.wrapper_loading = false
        });
      
    },
    refresh(){
      this.getDataKey = true
      this.searchContent = ''
      this.getAllUserInfo()
    },
    editUser(){
      this.$refs.singleUser.validate((valid) => {
        if (valid) {
          this.wrapper_loading_singleUser_dialog = true
          this.$axios
            .post('http://localhost/tp5/public/index.php/index/User/upData', this.singleUser)
            .then(res => {
              if(res.status === 200 && res.statusText === 'OK' && res.data.status ===1){
                this.$message({
                  message: '修改成功',
                  type: 'success'
                })
                this.wrapper_loading_singleUser_dialog = false
                this.getDataKey = true
                this.getAllUserInfo()
              }else{
                this.$message({
                  message: '修改失败',
                  type: 'error'
                })
                this.wrapper_loading_singleUser_dialog = false
              }
              this.editUserDislog = false
            })
            .catch(err => {
              this.$message({
                message: '修改失败',
                type: 'error'
              })
              this.wrapper_loading_singleUser_dialog = false
              this.editUserDislog = false
            })
        } else {
          return false
        }
      });
    }
  },
  mounted() {
    this.getAllUserInfo()
  }
}
</script>

<style>
.admin-user-confirm{
  width: 320px;
}
.admin-user-confirm-button{
  padding: 5px;
  font-size: 10px;
}
.users-table-header-row-class th{
  padding: 0px;
  font-size: 10px;
  color: #606266;
  font-weight: 600;
}
.users-table-row-class td{
  padding: 0px;
  font-size: 9px;
}
.users-table-row-class .users-table-click-class .el-button--text span{
  font-size: 10px;
}
.search-input-wrapper .el-input__inner{
  height: 25px;
  font-size: 10px;
}
.users-table-row-class .users-table-click-class .delete-btn span{
  color: #F56C6C
}
.search-input-wrapper .el-input__suffix i{
  line-height: 26px;
}
.search-input-wrapper .el-input-group{
  width: 80%;
}
.editUserDislog-dialog .el-dialog__header{
  padding: 10px;
}
.editUserDislog-dialog .el-dialog__body{
  padding: 10px;
}
.editUserDislog-dialog .el-dialog__body .title{
  padding-bottom: 10px;
}
.editUserDislog-dialog .el-form .el-form-item{
  margin-bottom: 15px;
}
.editUserDislog-dialog .el-form .el-form-item .el-form-item__label{
  font-size: 10px;
  line-height: 20px;
}
.editUserDislog-dialog .el-form .el-form-item .el-form-item__content{
  height: 20px;
  line-height: 20px;
}
.editUserDislog-dialog .el-form .el-form-item .el-input,
.editUserDislog-dialog .el-form .el-form-item .el-input .el-input__inner{
  height: 100%;
  font-size: 10px;
}
.editUserDislog-dialog .el-form .el-form-item .el-input--suffix .el-input__inner{
  height: 20px;
  line-height: 20px;
}
.editUserDislog-dialog .el-form .el-form-item.description .el-form-item__content{
  height: 70px;
}
.editUserDislog-dialog .el-dialog__footer{
  padding: 0 20px 10px 0;
}
.editUserDislog-dialog .el-form-item__error{
  font-size: 10px;
  line-height: 15px;
}
</style>
<style scoped>
.search-input-wrapper{
  float: right;
  padding: 10px;
  width: 20%;
}
.header-add{
  text-align: right;
}
.header-add .el-button--small.is-circle{
  padding: 3px;
}
.editUserDislog-dialog .el-button--danger{
  padding: 5px;
  font-size: 10px;
}
</style>