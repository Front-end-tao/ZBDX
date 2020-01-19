<template>
<!-- wrapper_loading_create_order -->
  <el-row class="create-order-wrapper" 
  v-loading="wrapper_loading_travel_createOrder"
  element-loading-text="创建订单中"
    element-loading-spinner="el-icon-loading"
    element-loading-background="rgba(0, 0, 0, 0.8)">
    <el-row class="create-order-wrapper-header">
      <el-col :span="12">
        <span>
          <i class="el-icon-back" style="transform: scale(2.5);cursor: pointer;" @click="backToBeforeConfirm"></i>
        </span>
      </el-col>
    </el-row>
    <el-row class="create-order-wrapper-main">
      <div class="content-wrapper">
        <div class="content-wrapper-header">
          <span class="spanOne">旅客信息</span>
          <span class="spanTwo">为了您能顺利出行，请务必确认所填姓名、性别与所持证件一致</span>
        </div>
        <el-form label-width="70px" :model="tableData" :rules="rules" ref="tableData" >
          <el-row>
            <el-col :span="12">
              <el-row v-for="(item, index) in queryData.normalPeopleNumber" :key="index"  class="singlePeopleClass">
                <el-col :span="8" class="singlePeopleClass-left">
                  <i class="el-icon-user"></i>
                  <span class="spanOne">旅客{{ index + 1}}</span>
                  <span class="spanTwo">标准</span>
                </el-col>
                <el-col  class="singlePeopleClass-form" :span="12">
                  <el-form-item label="中文姓名" :prop="'normalPeopleName' + (index + 1)">
                    <el-input v-model="tableData['normalPeopleName' + (index + 1)]"></el-input>
                  </el-form-item>
                  <el-form-item label="身份证号" :prop="'normalPeopleId' + (index + 1)">
                    <el-input v-model="tableData['normalPeopleId' + (index + 1)]"></el-input>
                  </el-form-item>
                  <el-form-item label="国家/地区" :prop="'normalPeopleCountry' + (index + 1)">
                    <el-input v-model="tableData['normalPeopleCountry' + (index + 1)]"></el-input>
                  </el-form-item>
                  <el-form-item label="性别" :prop="'normalPeopleSex' + (index + 1)">
                    <el-radio-group v-model="tableData['normalPeopleSex' + (index + 1)]">
                      <el-radio label="男" :value="1"></el-radio>
                      <el-radio label="女" :value="0"></el-radio>
                    </el-radio-group>
                  </el-form-item>
                  <el-form-item label="联系方式" :prop="'normalPeopleTel' + (index + 1)">
                    <el-input v-model="tableData['normalPeopleTel' + (index + 1)]"></el-input>
                  </el-form-item>
                </el-col>
              </el-row>
            </el-col>
            <el-col :span="12">
              <el-row v-for="(item, index) in queryData.specialPeopleNumber" :key="index"  class="singlePeopleClass">
                <el-col :span="8" class="singlePeopleClass-left">
                  <i class="el-icon-user"></i>
                  <span class="spanOne">旅客{{ index + 1}}</span>
                  <span class="spanTwo" style="color:#F56C6C">特殊</span>
                </el-col>
                <el-col  class="singlePeopleClass-form" :span="12">
                  <el-form-item label="中文姓名" :prop="'specialPeopleName' + (index + 1)">
                    <el-input v-model="tableData['specialPeopleName' + (index + 1)]"></el-input>
                  </el-form-item>
                  <el-form-item label="身份证号" :prop="'specialPeopleId' + (index + 1)">
                    <el-input v-model="tableData['specialPeopleId' + (index + 1)]"></el-input>
                  </el-form-item>
                  <el-form-item label="国家/地区" :prop="'specialPeopleCountry' + (index + 1)">
                    <el-input v-model="tableData['specialPeopleCountry' + (index + 1)]"></el-input>
                  </el-form-item>
                  <el-form-item label="性别" :prop="'specialPeopleSex' + (index + 1)">
                    <el-radio-group v-model="tableData['specialPeopleSex' + (index + 1)]">
                      <el-radio label="男"></el-radio>
                      <el-radio label="女"></el-radio>
                    </el-radio-group>
                  </el-form-item>
                  <el-form-item label="联系方式" :prop="'specialPeopleTel' + (index + 1)">
                    <el-input v-model="tableData['specialPeopleTel' + (index + 1)]"></el-input>
                  </el-form-item>
                </el-col>
              </el-row>
            </el-col>
          </el-row>
        </el-form>
        <div style="text-align:center">
            <el-button type="primary" style="padding:15px 40px" @click="creatingOrder">创建订单</el-button>
        </div>
      </div>
  
    </el-row>
    <el-row class="create-order-wrapper-footer">
      <div>
        <p>因服务能力有限，请您核实以下情况，以免造成出行不便。</p>
        <p>★ 本产品不可拼房。报价是按照2成人入住1间房计算的价格，请在页面中选择所需房间数或单人房差选项。如您为一位成人携带一位儿童出行，则儿童必须占床。</p>
        <p>★ 产品不提供3人间，且无加床服务，如需要3成人入住1间双人标准间，请在预订时备注清楚，后续会有专人为您处理此需求。第3人不占床不含早，早餐请自理，谢谢配合！</p>
        <p>★ 因酒店原因，本产品起价适用于持有中国有效身份证件的客人（不含中国香港、中国澳门和中国台湾），持其他国家或地区证件的游客请选择对应的选项补足差额。</p>
        <p>★ 如产品确认单中的条款约定与旅游合同主协议（示范文本）不一致的，以产品确认单中的约定为准。</p>
      </div>
    </el-row>
  </el-row>
  <!-- <div style="text-align:center;font-size:11px;color:#409EFF" v-if="queryData.normalPeopleNumber + queryData.specialPeopleNumber >= 10 && singleData.company === 1" >
            您可以享受企业级折扣
          </div>
          <div style="text-align:center;font-size:11px;color:#67C23A" v-else-if="queryData.normalPeopleNumber + queryData.specialPeopleNumber >= 3 && singleData.group === 1" >
            您可以享受组团折扣
          </div>
          <div v-else style="text-align:center;font-size:11px;color:#F56C6C">
            暂无折扣可以享受
          </div> -->
</template>

<script>
import dayjs from 'dayjs'
export default {
  data() {
    return {
      wrapper_loading_travel_createOrder: false,
      value: '',
      queryData: null,
      wrapper_loading_create_order: false,
      singleData: {

      },
      tableData: {

      },
      rules: {  }
    }
  },
  methods: {
    creatingOrder(){
      console.log(this.queryData)
      console.log(this.singleData)
      this
        .$refs['tableData'].validate((valid) => {
        if (valid) {
          let nameString = ''
          let idString = ''
          let countryString = ''
          let sexString = ''
          let telString = ''
          for(let i = 1;i<=this.queryData.normalPeopleNumber;i++){
            nameString += this.tableData['normalPeopleName' + i]
            idString += this.tableData['normalPeopleId' + i]
            countryString += this.tableData['normalPeopleCountry' + i]
            sexString += this.tableData['normalPeopleSex' + i]
            telString += this.tableData['normalPeopleTel' + i]
            if(i !== this.queryData.normalPeopleNumber){
              nameString+= ','
              idString+= ','
              countryString+= ','
              sexString+= ','
              telString+= ','
            }
          }
          nameString+= '-'
          idString+= '-'
          countryString+= '-'
          sexString+= '-'
          telString+= '-'
          for(let i = 1;i<=this.queryData.specialPeopleNumber;i++){
            nameString += this.tableData['specialPeopleName' + i]
            idString += this.tableData['specialPeopleId' + i]
            countryString += this.tableData['specialPeopleCountry' + i]
            sexString += this.tableData['specialPeopleSex' + i]
            telString += this.tableData['specialPeopleTel' + i]
            if(i !== this.queryData.specialPeopleNumber){
              nameString+= ','
              idString+= ','
              countryString+= ','
              sexString+= ','
              telString+= ','
            }
          }
          const queryData = this.queryData
          const singleData = this.singleData
          let company;
          let group;
          if(queryData.normalPeopleNumber + queryData.specialPeopleNumber >= 10 && singleData.company === 1){
            company = 1
            group = 0
          }else if(queryData.normalPeopleNumber + queryData.specialPeopleNumber >= 5 && singleData.group === 1){
            company = 0
            group = 1
          }else{
            company = 0
            group = 0
          }
          let money;
          money = queryData.normalPeopleNumber * singleData.normalPrice + queryData.specialPeopleNumber * singleData.specialPrice
          if(company === 1){
            money = money * singleData.companyPrice / 10
          }
          if(group === 1){
            money = money * singleData.groupPrice / 10
          }
          if(singleData.guidePrice){
            money = money + queryData.guideDays * singleData.guidePrice
          }
          const paramsObj = {
            orderMail: this.$store.getters.loginUser.mail,
            orderCreateTime: new Date().getTime(),
            orderTime: queryData.tourismDate,
            group,
            company,
            guideDays: queryData.guideDays,
            orderMoney: money,
            destinationID: Number(queryData.id),
            payStatus: 0,
            orderNameString: nameString,
            orderIdString: idString,
            orderCountryString: countryString,
            orderSexString: sexString,
            orderTelString: telString,
            commentStatus: 0
          }
          this.wrapper_loading_travel_createOrder = true
          this.$axios
            .post('http://localhost/tp5/public/index.php/index/DestinationOrder/add', paramsObj)
            .then(res => {
              if(res.status === 200 && res.statusText === 'OK' && res.data.status ===1){
                setTimeout(_ => {
                  this.$message({
                    message: '创建成功，将自动跳转到支付页面',
                    type: 'success'
                  })
                  this.wrapper_loading_travel_createOrder = false
                  this.$router.push({
                    path: '/afterlogin/user/travel/payorder',
                    query: {
                      orderId: res.data.orderId
                    }
                  })
                },1000)
              }else{
                setTimeout(_ => {
                  this.$message({
                    message: '创建失败',
                    type: 'error'
                  })
                  this.wrapper_loading_travel_createOrder = false
                },1000)
              }
            })
            .catch(err => {
              setTimeout(_ => {
                this.$message({
                  message: '调用API失败',
                  type: 'error'
                })
                this.wrapper_loading_travel_createOrder = false
              },1000)
            })
        } else {
          return false;
        }
        });
    },
    getSingleData() {
      this.queryData = this.$router.app._route.query.data
      for(let i = 1;i<=this.queryData.normalPeopleNumber;i++){
        this.rules['normalPeopleName' + i] =  [{required: true, message: '请输入旅客' + i + '的姓名', trigger: 'blur'}]
        this.rules['normalPeopleId' + i] =  [{required: true, message: '请输入旅客' + i + '的身份证号', trigger: 'blur'}]
        this.rules['normalPeopleCountry' + i] =  [{required: true, message: '请输入旅客' + i + '的国家/地区', trigger: 'blur'}]
        this.rules['normalPeopleSex' + i] =  [{required: true, message: '请选择旅客' + i + '的性别', trigger: 'blur'}]
        this.rules['normalPeopleTel' + i] =  [{required: true, message: '请输入旅客' + i + '的联系方式', trigger: 'blur'}]
      }
      for(let i = 1;i<=this.queryData.specialPeopleNumber;i++){
        this.rules['specialPeopleName' + i] =  [{required: true, message: '请输入旅客' + i + '的姓名', trigger: 'blur'}]
        this.rules['specialPeopleId' + i] =  [{required: true, message: '请输入旅客' + i + '的身份证号', trigger: 'blur'}]
        this.rules['specialPeopleCountry' + i] =  [{required: true, message: '请输入旅客' + i + '的国家/地区', trigger: 'blur'}]
        this.rules['specialPeopleSex' + i] =  [{required: true, message: '请选择旅客' + i + '的性别', trigger: 'blur'}]
        this.rules['specialPeopleTel' + i] =  [{required: true, message: '请输入旅客' + i + '的联系方式', trigger: 'blur'}]
      }
      this.queryData.tourismDateDayJs = dayjs(this.queryData.tourismDate).format('YYYY-MM-DD')
      this.wrapper_loading_create_order = true
      const id = this.queryData.id
      this.$axios
        .get('http://localhost/tp5/public/index.php/index/Destination/single'+ this.toParams({
        id
        }))
        .then(res => {
          if(res.status === 200 && res.statusText === 'OK' && res.data.status ===1){
            this.singleData = Object.assign(res.data.data.destinations, res.data.data.price)
          }else{
            this.$message({
              message: '获取数据失败',
              type: 'error'
            })
          }
          this.wrapper_loading_create_order = false
        })
        .catch(err => {
          this.$message({
            message: '获取数据失败',
            type: 'error'
          })
          this.wrapper_loading_create_order = false
        })
    },
    backToBeforeConfirm() {
       this.$router.push({path: '/afterlogin/user/travel/confirming/' + this.queryData.id })
    }
  },
  created() {
    this.getSingleData()
  }
}
</script>

<style>
.create-order-wrapper{
  padding: 0 40px;
}
.create-order-wrapper-header{
  height: 30px;
  line-height: 30px;
  font-size: 10px;
}
.create-order-wrapper-main{
  border-radius: 4px;
}
.create-order-wrapper-main .content-wrapper{
  box-shadow: 0 2px 4px rgba(0, 0, 0, .12), 0 0 6px rgba(0, 0, 0, .04);
  padding-bottom: 20px;
  height:100%;
}
.create-order-wrapper-main .content-wrapper .content-wrapper-header{
  background-color: rgba(124, 124, 124, 0.12);
  padding: 5px 20px;
}
.create-order-wrapper-main .content-wrapper .content-wrapper-header .spanOne{
  font-size: 20px;
  font-weight: 700;
}
.create-order-wrapper-main .content-wrapper .content-wrapper-header .spanTwo{
  font-size: 15px;
  font-weight: 700;
  color: rgba(0, 0, 0, 0.473);
  padding-left: 50px;
}
.create-order-wrapper-main .content-wrapper .el-form .singlePeopleClass{
  padding: 30px 0;
}
.create-order-wrapper-main .content-wrapper .el-form .singlePeopleClass-left{
  padding: 10px 0 0 70px;
}
.create-order-wrapper-main .content-wrapper .el-form .singlePeopleClass-left .spanOne{
  font-size: 16px;
  font-weight: 700;
}
.create-order-wrapper-main .content-wrapper .el-form .singlePeopleClass-left .spanTwo{
  font-size: 12px;
  font-weight: 700;
  color: #409EFF;
}
.create-order-wrapper-main .content-wrapper .el-form .el-form-item{
  margin-bottom: 15px;
}
.create-order-wrapper-main .content-wrapper .el-form .el-form-item .el-form-item__content{
  /* height: 20px; */
}
.create-order-wrapper-main .content-wrapper .el-form .el-form-item .el-form-item__label{
  font-size: 11px;
}
.create-order-wrapper-main .content-wrapper .el-form .el-form-item .el-input{
  height: 20px;
}
.create-order-wrapper .content-wrapper .el-form .el-form-item .el-input__inner{
  height: 20px;
  line-height: 20px;
}

.create-order-wrapper-main .content-wrapper .el-form .singlePeopleClass-form{
  display: inline-block;
}
.create-order-wrapper-footer{
  padding-top: 20px;
  padding-bottom: 40px;
  font-size: 11px;;
}
.create-order-wrapper-footer p{
  margin: 5px 0;
  color: rgb(90, 90, 90)
}
</style>