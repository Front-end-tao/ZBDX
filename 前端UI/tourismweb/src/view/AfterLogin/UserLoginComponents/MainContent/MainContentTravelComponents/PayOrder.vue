<template>
  <el-row 
    class="wrapper_payOrder"
    v-loading="wrapper_loading_travel_payOrder"
    element-loading-text="拼命支付中"
    element-loading-spinner="el-icon-loading"
    element-loading-background="rgba(0, 0, 0, 0.8)"
  >
    <el-row class="payOrder-wrapper-header">
      <el-col :span="12">
        <span>
          <i class="el-icon-back" style="transform: scale(2.5);cursor: pointer;color:white" @click="backToOrders"></i>
        </span>
      </el-col>
    </el-row> 
    <div 
      class="wrapper_payOrder_qrcode"
      v-loading="wrapper_loading_travel_payOrder_qrcode"
      element-loading-text="二维码拼命生成中"
      element-loading-spinner="el-icon-loading"
      element-loading-background="rgba(0, 0, 0, 0.9)"
      @click="paying"
    >
      <div class="qrcode" ref="qrcodeContainer" v-show="qrcode"></div>   
      <div class="qrcode" ref="qrcodeContainerError" v-show="!qrcode"></div>   
    </div>
  </el-row>
</template>

<script>
import QRCode from 'qrcodejs2'
export default {
  data() {
    return {
      wrapper_loading_travel_payOrder_qrcode: true,
      qrcode: true,
      wrapper_loading_travel_payOrder: false,
      orderId: null
    }
  },
  methods: {
    getQuery() {
      this.orderId = this.$router.app._route.query.orderId
      console.log(this.orderId)
    },
    showQRCode(){  
      var qrcode = new QRCode(this.$refs.qrcodeContainer, {  
        text: 'http://192.168.70.173/tp5/public/index.php/index/DestinationOrder/pay?orderId=' + this.orderId,
        width: 250,  
        height: 250,  
        colorDark: '#409EFF',  
        colorLight: '#ffffff',  
        correctLevel: QRCode.CorrectLevel.H  
      });
    },
    paying() {
      this.wrapper_loading_travel_payOrder = true
      this.$axios
        .get('http://localhost/tp5/public/index.php/index/DestinationOrder/pay' + this.toParams({
          orderId: this.orderId
        }))
        .then(res => {
          if(res.status === 200 && res.statusText === 'OK' && res.data.status ===1){
            setTimeout(_ => {
              this.wrapper_loading_travel_payOrder = false
              this.$message({
                message: '支付成功，将自动跳往我的订单页面',
                type: 'success'
              })
              this.$router.push({
                path: '/afterlogin/user/orders'
              })
            }, 2000)
          }else{
            setTimeout(_ => {
              this.wrapper_loading_travel_payOrder = false
              this.$message({
                message: res.data.message,
                type: 'error'
              })
            }, 1000)
          }
        })
        .catch(err => {
          setTimeout(_ => {
            this.wrapper_loading_travel_payOrder = false
            this.$message({
              message: '调用API失败',
              type: 'error'
            })
          }, 1000)
        })
    },
    watchPay(){
      this.$axios
        .get('http://localhost/tp5/public/index.php/index/DestinationOrder/watchPay' + this.toParams({
          orderId: this.orderId
        }))
        .then(res => {
          if(this.$route.path !== '/afterlogin/user/travel/payorder'){
            return
          }
          if(res.status === 200 && res.statusText === 'OK' && res.data.status ===1){
            this.wrapper_loading_travel_payOrder = true
            setTimeout(() => {
              this.$message({
                message: '支付成功，将自动跳往我的订单页面。',
                type: 'success'
              })
              setTimeout(() => {
                this.wrapper_loading_travel_payOrder = false
                this.$router.push({
                  path: '/afterlogin/user/orders'
                })
              }, 500);
            }, 2000);
          }else if(res.status === 200 && res.statusText === 'OK' && res.data.status ===0 && res.data.message === 'out time'){
            
            setTimeout(() => {
              this.$message({
                message: '支付超时,二维码已经失效，请刷新页面再次进行支付操作。',
                type: 'error'
              })
              this.qrcode = false
              var qrcode = new QRCode(this.$refs.qrcodeContainerError, {  
                text: '二维码失效',
                width: 250,  
                height: 250,  
                colorDark: '#000000',  
                colorLight: '#ffffff',  
                correctLevel: QRCode.CorrectLevel.H  
              });
            }, 1000);
          }else if(res.status === 200 && res.statusText === 'OK' && res.data.status ===2 && res.data.message === 'stoped'){
            console.error('我被终止了')
          }else{
            this.$message({
              message: '支付失败，未知错误',
              type: 'error'
            })
            this.qrcode = false
            var qrcode = new QRCode(this.$refs.qrcodeContainerError, {  
              text: '二维码失效',
              width: 250,  
              height: 250,  
              colorDark: '#000000',  
              colorLight: '#ffffff',  
              correctLevel: QRCode.CorrectLevel.H  
            });
          }
        })
        .catch(err => {
          this.$message({
              message: '支付失败，未知错误',
              type: 'error'
            })
           this.qrcode = false
            var qrcode = new QRCode(this.$refs.qrcodeContainerError, {  
              text: '二维码失效',
              width: 250,  
              height: 250,  
              colorDark: '#000000',  
              colorLight: '#ffffff',  
              correctLevel: QRCode.CorrectLevel.H  
            });
        })
    },
    stopLastWatch() {
      this.$axios
        .get('http://localhost/tp5/public/index.php/index/DestinationOrder/stopLastWatch' + this.toParams({
          orderId: this.orderId
        }))
        .then(res => {
          if(res.status === 200 && res.statusText === 'OK' && res.data.status ===1){
            this.wrapper_loading_travel_payOrder_qrcode = false
            this.$message({
              message: '成功获取支付二维码，请尽快支付，有效时间为30秒！',
              type: 'success'
            })
            this.watchPay()
          }else{
            console.log('stopLastWatch is error')
          }
        })
        .catch(err => {
          console.log(err)
        })
    },
    backToOrders() {
      this.$confirm('是否取消支付?', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'error'
      }).then(() => {
        this.$router.push({
          path: '/afterlogin/user/orders'
        })
      }).catch(() => {
                
      });
    }
  },
  created() {
    this.getQuery()
    // this.watchPay()
    this.stopLastWatch()
  },
  mounted() {
    this.showQRCode()
  }
}

</script>

<style>
.wrapper_payOrder{
  height: calc(100vh - 30px);
  padding: 0 40px;
  background-color: rgba(0, 0, 0, 0.596);
}
.wrapper_payOrder_qrcode{
  width: 250px;
  margin: auto;
  position: relative;
  top: 50%;
  transform: translateY(-100%);
}
.payOrder-wrapper-header{
  height: 30px;
  line-height: 30px;
  font-size: 10px;
}
</style>