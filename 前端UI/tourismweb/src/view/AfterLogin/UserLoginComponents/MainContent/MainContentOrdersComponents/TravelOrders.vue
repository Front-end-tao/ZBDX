<template>
  <el-row
    class="wrapper_orders"
    v-loading="wrapper_loading_orders"
    element-loading-text="疯狂获取景点订单中中"
    element-loading-spinner="el-icon-loading"
    element-loading-background="rgba(0, 0, 0, 0.8)"
  >
    <div class="header-add">
      <el-button type="primary" icon="el-icon-refresh-right" @click="refresh" circle />
    </div>
    <el-table
      :data="tableDataComputed"
      border
      style="width: 100%"
      height="750px"
    >
      <el-table-column
        prop="orderId"
        label="订单号"
        width="100"
        header-align="center"
        align="center"
        fixed="left"
      >
      </el-table-column>
      <el-table-column
        prop="destination"
        label="目的地"
        width="100"
        header-align="center"
        align="center"
        fixed="left"
      >
      </el-table-column>
      <el-table-column
        prop="orderCreateTime"
        label="订单创建时间"
        width="160"
        header-align="center"
        align="center">
      </el-table-column>
      <el-table-column
        prop="orderTime"
        label="旅游时间"
        width="150"
        header-align="center"
        align="center">
      </el-table-column>
      <el-table-column
        prop="group"
        label="是否享受组团折扣"
        width="140"
        header-align="center"
        align="center">
      </el-table-column>
      <el-table-column
        prop="company"
        label="是否享受企业级服务"
        width="170"
        header-align="center"
        align="center">
      </el-table-column>
      <el-table-column
        prop="guideDays"
        label="导游服务天数"
        width="100"
        header-align="center"
        align="center">
      </el-table-column>
      <el-table-column
        prop="normalPeople.length"
        label="普通旅客人数"
        width="100"
        header-align="center"
        align="center">
      </el-table-column>
      <el-table-column
        prop="normalPeople"
        label="普通旅客详情"
        header-align="center"
        width="650"
        align="left">
        <template  slot-scope="scope">
          <div v-if="scope.row.normalPeople.length !== 0">
            <div v-for="(people, key) in scope.row.normalPeople" :key="key" style="border-bottom:solid 0.5px rgba(0, 0, 0, 0.4);margin:2px 5px;">
              {{ `姓名：${people.name}，身份证号：${people.id}，性别：${people.sex}，国家/地区：${people.country}，联系方式：${people.tel}` }}
            </div>
          </div>
          <div v-else style="text-align: center">
            无
          </div>
        </template>
      </el-table-column>
      <el-table-column
        prop="specialPeople.length"
        label="特殊旅客人数"
        width="100"
        header-align="center"
        align="center">
      </el-table-column>
      <el-table-column
        prop="specialPeople"
        label="特殊旅客详情"
        header-align="center"
        width="650"
        align="left">
        <template  slot-scope="scope">
          <div v-if="scope.row.specialPeople.length !== 0">
            <div v-for="(people, key) in scope.row.specialPeople" :key="key" style="border-bottom:solid 0.5px rgba(0, 0, 0, 0.4);margin:2px 5px;" >
              {{ `姓名：${people.name}，身份证号：${people.id}，性别：${people.sex}，国家/地区：${people.country}，联系方式：${people.tel}` }}
            </div>
          </div>
          <div v-else style="text-align: center">
            无
          </div>
        </template>
      </el-table-column>
      <el-table-column
        prop="orderMoney"
        label="总金额(元)"
        width="150"
        header-align="center"
        align="center">
      </el-table-column>
      <el-table-column
        prop="commentStatus"
        label="评论状态"
        width="150"
        header-align="center"
        align="center"
        fixed="right"
      >
        <template  slot-scope="scope">
          <div v-if="scope.row.payStatus === 0">
            您还未支付该订单
          </div>
          <div v-else-if="scope.row.commentStatus === 1" style="color:#67C23A" >已评论</div>
          <div v-else>
            <el-button type="text" @click="toComment(scope.row)">评论</el-button>
          </div>
        </template>
      </el-table-column>
      <el-table-column
        prop="payStatus"
        label="支付状态"
        width="130"
        header-align="center"
        align="center"
        fixed="right"
      >
        <template  slot-scope="scope">
          <div v-if="scope.row.payStatus === 1" style="color:#67C23A">已支付</div>
          <div v-else>
            <el-button type="text" @click="toPay(scope.row.orderId)">前去支付</el-button>
          </div>
        </template>
      </el-table-column>
      <el-table-column
        label="操作"
        width="130"
        header-align="center"
        fixed="right"
        align="center"
      >
        <template  slot-scope="scope">
          <el-button type="text" class="delete-btn" @click="table_del(scope.row.orderId)">删除</el-button>    
        </template>
      </el-table-column>
    </el-table>
    <el-dialog
      :visible.sync="commentOptions.dialogVisible"
      width="30%"
      :title="'订单' + commentOptions.title"
      class="travel-order-comment-dialog"
    >
      <el-input
        type="textarea"
        placeholder="请输入评论"
        v-model="commentOptions.value"
        maxlength="200"
        show-word-limit
      >
      </el-input>
      <el-rate
        v-model="commentOptions.grades"
        :colors="commentOptions.colors"
        style="padding:40px 0 0 20px;"
      >
      </el-rate>
      <span slot="footer" class="dialog-footer">
        <el-button @click="commentOptions.dialogVisible = false">取 消</el-button>
        <el-button type="primary" @click="commenting">提 交</el-button>
      </span>
    </el-dialog>
  </el-row>
</template>

<script>
import dayjs from 'dayjs'
export default {
  data() {
    return {
      wrapper_loading_orders: false,
      tableData: [],
      allDestination: [],
      commentOptions: {
        title: 'NaN',
        dialogVisible: false,
        value: '',
        grades: null,
        colors: ['#99A9BF', '#F7BA2A', '#FF9900'] 
      }
    }
  },
  computed: {
    tableDataComputed() {
      const timer = new Date().getTime()
      const orders = this.tableData
      const res = []
      orders.forEach(order => {
        let orderId = order.orderId
        let destination = '获取失败'
        this.allDestination.forEach(singleDestination => {
          if(singleDestination.id === order.destinationID){
            destination = singleDestination.name
          }
        })
        let destinationID = order.destinationID
        let orderCreateTime = dayjs(Number(order.orderCreateTime)).format('YYYY-MM-DD HH:mm:ss')
        let group = order.group === 1 ? '是' : '否'
        let company = order.company === 1 ? '是' : '否'
        let orderMoney = order.orderMoney
        let payStatus = order.payStatus
        let commentStatus = order.commentStatus
        let guideDays = order.guideDays
        let orderTime = dayjs(Number(order.orderTime)).format('YYYY-MM-DD')

        let normalPeopleArr = []
        let specialPeopleArr = []
        let nameArr = []
        let idArr = []
        let countryArr = []
        let sexArr = []
        let telArr = []
        let length = null
        if(order.orderNameString.split('-')[0].split(',')[0] !== ''){
          nameArr = order.orderNameString.split('-')[0].split(',')
        }
        if(order.orderIdString.split('-')[0].split(',')[0] !== ''){
          idArr = order.orderIdString.split('-')[0].split(',')
        }
        if(order.orderCountryString.split('-')[0].split(',')[0] !== ''){
          countryArr = order.orderCountryString.split('-')[0].split(',')
        }
        if(order.orderSexString.split('-')[0].split(',')[0] !== ''){
          sexArr = order.orderSexString.split('-')[0].split(',')
        }
        if(order.orderTelString.split('-')[0].split(',')[0] !== ''){
          telArr = order.orderTelString.split('-')[0].split(',')
        }
        length = nameArr.length
        if(length !== 0){
          for(let i = 0; i<length; i++){
            normalPeopleArr.push({
              name: nameArr[i],
              id: idArr[i],
              sex: sexArr[i],
              tel: telArr[i],
              country: countryArr[i]
            })
          }
        }
        nameArr = []
        idArr = []
        countryArr = []
        sexArr = []
        telArr = []
        length = null
        if(order.orderNameString.split('-')[1].split(',')[0] !== ''){
          nameArr = order.orderNameString.split('-')[1].split(',')
        }
        if(order.orderIdString.split('-')[1].split(',')[0] !== ''){
          idArr = order.orderIdString.split('-')[1].split(',')
        }
        if(order.orderCountryString.split('-')[1].split(',')[0] !== ''){
          countryArr = order.orderCountryString.split('-')[1].split(',')
        }
        if(order.orderSexString.split('-')[1].split(',')[0] !== ''){
          sexArr = order.orderSexString.split('-')[1].split(',')
        }
        if(order.orderTelString.split('-')[1].split(',')[0] !== ''){
          telArr = order.orderTelString.split('-')[1].split(',')
        }
        length = nameArr.length
        if(length !== 0){
          for(let i = 0; i<length; i++){
            specialPeopleArr.push({
              name: nameArr[i],
              id: idArr[i],
              sex: sexArr[i],
              tel: telArr[i],
              country: countryArr[i]
            })
          }
        }

        res.push({
          orderId,
          orderCreateTime,
          destination,
          destinationID,
          group,
          company,
          normalPeople: normalPeopleArr,
          specialPeople: specialPeopleArr,
          orderMoney,
          payStatus,
          guideDays,
          orderTime,
          commentStatus
        })
      })
      console.log(res)
      return res
    }
  },
  methods: {
    getMyOrders() {
      this.$axios
        .get('http://localhost/tp5/public/index.php/index/Destination/all')
        .then(res => {
          if(res.status === 200 && res.statusText === 'OK' && res.data.status ===1){
              this.allDestination = res.data.data.destinations
              this.wrapper_loading_orders = true
              const mail = this.$store.getters.loginUser.mail
              this.$axios
                .get('http://localhost/tp5/public/index.php/index/DestinationOrder/getOrders' + this.toParams({
                  orderMail: mail
                }))
                .then(res => {
                  setTimeout(() => {
                    if(res.status === 200 && res.statusText === 'OK' && res.data.status ===1){
                      this.tableData = res.data.DestinationOrders
                      this.$message({
                        message: `获取景点订单信息成功，共${res.data.Count}条。`,
                        type: 'success'
                      })
                    }else{
                      this.$message({
                        message: `获取景点订单信息失败`,
                        type: 'error'
                      })
                    }
                    this.wrapper_loading_orders = false
                  }, 500);
                })
                .catch(err => {
                  this.wrapper_loading_orders = false
                  this.$message({
                    message: `调用API失败`,
                    type: 'error'
                  })
                })
          }
        })
        .catch(err => {
          console.log(err)
        })
      
    },
    toPay(id) {
      this.$router.push({path: '/afterlogin/user/travel/payorder',
        query: {
          orderId: id
        }
      })
    },
    refresh() {
      this.getMyOrders()
    },
    toComment(target) {
      this.commentOptions.title = target.orderId
      this.commentOptions.dialogVisible = true
      this.commentOptions.destinationID = target.destinationID
      this.commentOptions.value = ''
      this.commentOptions.grades = 0
     
    },
    commenting() {
      if(this.commentOptions.value === '' || this.commentOptions.grades === 0){
        this.$message({
          message: '检测到空填项',
          type: 'error'
        })
        return
      }
      this.$axios
        .post('http://localhost/tp5/public/index.php/index/Destinationordercomment/add',{
          orderId: this.commentOptions.title,
          destinationID: this.commentOptions.destinationID,
          comment: this.commentOptions.value,
          grades: this.commentOptions.grades,
          userMail: this.$store.getters.loginUser.userName
        })
        .then(res => {
          if(res.status === 200 && res.statusText === 'OK' && res.data.status ===1){
            this.$message({
              message: '评论成功',
              type: 'success'
            })
          }else{
            this.$message({
              message: '评论失败',
              type: 'error'
            })
          }
          this.commentOptions.dialogVisible = false
          this.refresh()
        })
        .catch(err => {
          this.$message({
            message: '调用API失败',
            type: 'error'
          })
          this.commentOptions.dialogVisible = false
          this.refresh()
        })
    },
    table_del(id) {
       this
        .$confirm('是否删除该订单及其评论?', '警告', {
          confirmButtonText: '确定',
          cancelButtonText: '取消',
          type: 'error',
          cancelButtonClass: 'is-plain admin-user-confirm-button',
          confirmButtonClass: 'admin-user-confirm-button',
          customClass: 'admin-user-confirm'
        })
        .then(() => {
          this.wrapper_loading_orders = true
          this.$axios
            .delete(`http://localhost/tp5/public/index.php/index/destinationorder/del`,{
              data: {
                destinationorderid: id
              }
            })
            .then(res => {
              if(res.status === 200 && res.statusText === 'OK' && res.data.status ===1){
                this.$message({
                  message: '删除成功',
                  type: 'success'
                })
                this.refresh()
                this.wrapper_loading_orders = false
              }else{
                this.$message({
                  message: '删除失败',
                  type: 'error'
                })
                this.wrapper_loading_orders = false
              }
            })
            .catch(err => {
              this.$message({
                message: '删除失败',
                type: 'error'
              })
              this.wrapper_loading_orders = false
            })
        })
        .catch(() => {

        })
    }
  },
  mounted() {
    this.getMyOrders()
  }
}
</script>


<style>
.wrapper_orders .travel-order-comment-dialog .el-dialog .el-textarea__inner{
  height: 200px;
  min-height: 200px;
  max-height: 200px;
}
</style>
<style scoped>
.wrapper_orders {
  height: calc(100vh - 60px);
  
}
.header-add{
  text-align: right;
  padding: 10px 20px 10px 0;
}
.header-add .el-button--small.is-circle{
  padding: 3px;
}
</style>