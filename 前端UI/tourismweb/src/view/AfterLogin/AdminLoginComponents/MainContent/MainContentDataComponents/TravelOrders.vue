<template>
  <el-row class="travelOrders"  element-loading-text="疯狂加载中"
  element-loading-spinner="el-icon-loading"
  element-loading-background="rgba(0, 0, 0, 0.8)"
  v-loading="wrapper_loading">
    <el-row class="header-refresh">
      <el-button type="danger" icon="el-icon-refresh-right" @click="refresh" circle />
    </el-row>
    <el-row>
     <el-table
      :data="tableDataComputed"
      border
      style="width: 100%"
      height="750px"
      class="travelOrders-table"
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
        prop="userMail"
        label="用户邮箱"
        width="200"
        header-align="center"
        align="center"
      >
      </el-table-column>
      <el-table-column
        prop="destination"
        label="目的地"
        width="100"
        header-align="center"
        align="center"
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
        prop="payStatus"
        label="支付状态"
        width="130"
        header-align="center"
        align="center"
      >
        <template  slot-scope="scope">
          <div v-if="scope.row.payStatus === 1" style="color:#67C23A">已支付</div>
          <div v-else style="color:#F56C6C">
            未支付
          </div>
        </template>
      </el-table-column>
      <el-table-column
        prop="commentStatus"
        label="评论状态"
        width="150"
        header-align="center"
        align="center"
      >
        <template  slot-scope="scope">
          <div v-if="scope.row.payStatus === 0" style="color:#F56C6C">
            未支付
          </div>
          <div v-else-if="scope.row.commentStatus === 1" style="color:#67C23A" >已评论</div>
          <div v-else style="color:#E6A23C">
            用户未评论
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
    </el-row>
  </el-row>
</template>

<script>
import dayjs from 'dayjs'
export default {
  data() {
    return {
      getDataKey: true,
      tableData: [],
      wrapper_loading: false,
      allDestination: []
    }
  },
  computed: {
    tableDataComputed() {
      const orders = this.tableData
      const res = []
      orders.forEach(order => {
        let orderId = order.orderId
        let userMail = order.orderMail
        let destination = '获取失败'
        this.allDestination.forEach(singleDestination => {
          if(singleDestination.id === order.destinationID){
            destination = singleDestination.name
          }
        })
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
          userMail,
          orderCreateTime,
          destination,
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
    getDestinationOrders() {
      if(this.getDataKey){
        this.wrapper_loading = true
        this.$axios
        .get('http://localhost/tp5/public/index.php/index/Destination/all')
        .then(res => {
          if(res.status === 200 && res.statusText === 'OK' && res.data.status ===1){
              this.allDestination = res.data.data.destinations
              const mail = this.$store.getters.loginUser.mail
              this.$axios
                .get('http://localhost/tp5/public/index.php/index/DestinationOrder/all')
                .then(res => {
                  setTimeout(() => {
                    if(res.status === 200 && res.statusText === 'OK' && res.data.status ===1){
                      this.tableData = res.data.data
                      this.$message({
                        message: `获取景点订单信息成功，共${res.data.data.length}条。`,
                        type: 'success'
                      })
                      this.wrapper_loading = false
                    }else{
                      this.$message({
                        message: `获取景点订单信息失败`,
                        type: 'error'
                      })
                      this.wrapper_loading = false
                    }
                  }, 500);
                  this.getDataKey = false
                })
                .catch(err => {
                  this.$message({
                    message: `调用API失败`,
                    type: 'error'
                  })
                  this.wrapper_loading = false
                  this.getDataKey = false
                })
          }
        })
        .catch(err => {
          console.log(err)
        })
      }
    },
    refresh() {
      this.getDataKey = true
      this.getDestinationOrders()
    },
    table_del(id) {
     this
        .$confirm('是否删除该订单及其评论?', '警告', {
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
                this.wrapper_loading = false
              }else{
                this.$message({
                  message: '删除失败',
                  type: 'error'
                })
                this.wrapper_loading = false
              }
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

        })
    }
  }
}

</script>

<style>
.header-refresh{
  text-align: right;
  padding: 5px 10px;
}
.header-refresh .el-button--small.is-circle{
  padding: 3px;
}
.travelOrders .travelOrders-table .delete-btn span{
  color: #F56C6C
}
</style>