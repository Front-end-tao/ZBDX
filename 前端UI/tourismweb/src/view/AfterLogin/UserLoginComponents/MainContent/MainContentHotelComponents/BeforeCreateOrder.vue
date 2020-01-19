<template>
  <el-row 
    class="before-create-order-wrapper"
    v-loading="loadingOptions.loading"
    :element-loading-text="loadingOptions.text"
    element-loading-spinner="el-icon-loading"
    element-loading-background="rgba(0, 0, 0, 0.8)"
  >
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>您的订单</span>
        <el-button type="primary" icon="el-icon-refresh-right" style="float: right; padding: 3px" @click="reForm" circle></el-button>
      </div>
      <el-row>
        <el-form label-position="top" label-width="80px" :model="formData" :rules="rules" ref="formData">
          <el-form-item label="请输入成人数" prop="manNumber">
            <el-input v-model="formData.manNumber" :disabled="iptDisabled"></el-input>
          </el-form-item>
          <el-form-item label="请输入儿童数" prop="childNumber">
            <el-input v-model="formData.childNumber" :disabled="iptDisabled"></el-input>
          </el-form-item>
          <el-form-item label="请输入房间数（儿童不可单独一个房间）" prop="roomNumber">
            <el-input v-model="formData.roomNumber" :disabled="iptDisabled"></el-input>
          </el-form-item>
        </el-form>
        <el-row>
          <el-row v-show="APIRes.key && APIRes.totalPrice" style="padding:10px 0;">
            <span style="font-size:20px;font-weight:700;">总价：</span>
            <span style="color:#F56C6C;font-size:40px;font-weight:700;">{{ APIRes.totalPrice }}</span>
            <span style="font-size:20px;font-weight:700;">元</span>
          </el-row>
          <el-row v-show="APIRes.key && APIRes.desc">
            <span style="font-size:20px;font-weight:700;">取消政策：</span>
            <span style="font-size:16px;font-weight:700;">{{ APIRes.desc }}</span>
          </el-row>
        </el-row>
        <el-row style="text-align:right">
          <el-button v-if="!APIRes.key" :type="buttonOptions.type" @click="before_success_button">{{ buttonOptions.content }}</el-button>
          <el-button v-else :type="buttonOptions.type" @click="after_success_button">去创建订单</el-button>
        </el-row>
      </el-row>
    </el-card>
  </el-row>
</template>

<script>
export default {
  data() {
    let manNumberCheck = (rule, value, callback) => {
      
      if (value === '') {
        callback(new Error('请输入成人数'));
      } else {
        if(isNaN(Number(value))){
          callback(new Error('请输入数字'));
        }else if(Number(value) < 1){
          callback(new Error('成人人数不得小于1'));
        }else{
          callback()
        }
      }
    };
    let childNumberCheck = (rule, value, callback) => {
      
      if (value === '') {
        callback(new Error('请输入儿童数'));
      } else {
        if(isNaN(Number(value))){
          callback(new Error('请输入数字'));
        }else if(Number(value) < 0){
          callback(new Error('人数不得小于0'));
        }else if(Number(this.formData.manNumber < value)){
          callback(new Error('儿童数不得大于成人数'))
        }else{
          callback()
        }
      }
    };
    let roomNumberCheck = (rule, value, callback) => {
      if (value === '') {
        callback(new Error('请输入房间数'));
      } else {
        if(isNaN(Number(value))){
          callback(new Error('请输入数字'));
        }else if(Number(value) < 1){
          callback(new Error('人数不得小于1'));
        }else{
          callback()
        }
      }
    };
    return {
      iptDisabled: false,
      loadingOptions: {
        loading: false,
        text: '正在获取预览订单信息',
      },
      roomData: null,
      formData: {
        manNumber: '',
        childNumber: '',
        roomNumber: ''
      },
      buttonOptions: {
        content: '预览订单',
        type: 'primary'
      },
      rules: {
        manNumber: [
          { validator: manNumberCheck, trigger: 'blur' },
          { required: true, trigger: 'blur' },
        ],
        childNumber: [
          { validator: childNumberCheck, trigger: 'blur' },
          { required: true, trigger: 'blur' },
        ],
        roomNumber: [
          { validator: roomNumberCheck, trigger: 'blur' },
          { required: true, trigger: 'blur' }
        ]
      },
      APIRes: {
        key: false,
        totalPrice: null,
        desc: null
      }
    }
  },
  mounted() {
    this.roomData = this.$router.app._route.query.data
    console.log(this.roomData)
  },
  methods: {
    before_success_button() {
      this.$refs['formData'].validate((valid) => {
        if (valid) {
          let childAges = '';
          for(let i = 1;i <= this.formData.childNumber;i++){
            childAges += '3'
            if(i !== this.formData.childNumber){
              childAges += ','
            }
          }
          let obj = {
            showapi_appid: 121810,
            showapi_sign: '5bbea10e0e6e4567873f870d4634924e',
            hotelId: this.roomData.hotelId,
            roomId: this.roomData.roomId,
            numberOfRooms: this.formData.roomNumber,
            inDate: this.roomData.inDate,
            outDate: this.roomData.outDate,
            man: this.formData.manNumber,
            child: this.formData.childNumber,
            childAges
          }
          this.loadingOptions.loading = true
          this.loadingOptions.text = '正在获取预览订单信息'
          this.$axios
            .get('http://route.showapi.com/1653-5'+ this.toParams(obj))
            .then(res => {
              if(res.status === 200 && res.statusText === 'OK' && res.data.showapi_res_body.ret_code === 0){
                this.$message({
                  message: '获取订单信息成功',
                  type: 'success'
                })
                console.log(res.data.showapi_res_body.data)
                this.APIRes.key = true,
                this.APIRes.totalPrice = res.data.showapi_res_body.data.totalPrice
                this.APIRes.desc = res.data.showapi_res_body.data.cancel.desc
                this.iptDisabled = true
                this.loadingOptions.loading = false
              }else{
                this.$message({
                  message: '请检查您输入的信息，并重新获取预览订单',
                  type: 'error'
                })
                this.loadingOptions.loading = false
              }
            })
            .catch(err => {
              console.log(err)
            })
        } else {
          return false;
        }
      });
    },
    reForm() {
      this.formData = {
        manNumber: '',
        childNumber: '',
        roomNumber: ''
      },
       this.APIRes ={
        key: false,
        totalPrice: null,
        desc: null
      }
      this.iptDisabled = false
    },
    after_success_button() {
      alert(1)
    }
  }
}
</script>

<style>
.before-create-order-wrapper{
    height: calc(100vh - 30px);
}
.before-create-order-wrapper .box-card{
    width: 700px;
    margin: auto;
    margin-top: 20px;
}
</style>