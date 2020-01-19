<template>
  <el-row 
    v-loading="wrapper_loading_confirmingHotel"
    element-loading-text="正在获取房间信息"
    element-loading-spinner="el-icon-loading"
    element-loading-background="rgba(0, 0, 0, 0.8)"
    class="wrapper_confirming"
  >
    <el-row style="padding:15px 50px;">
      <span>
        <i class="el-icon-back" style="transform: scale(2.5);cursor: pointer;color:black" @click="backToSingleHotel"></i>
      </span>
      <el-row style="text-align:right">
        <el-date-picker
          v-model="dateArr"
          type="daterange"
          range-separator="至"
          start-placeholder="开始日期"
          end-placeholder="结束日期">
        </el-date-picker>
        <el-button @click="getSingleJHotelMoreData">确认</el-button>
      </el-row>
    </el-row>
    <el-row class="content-main-wrapper">
      <el-row v-for="(room, index) in roomInfoComputed" :key="index" class="content-single-room">
        <el-col :span="6">
          <el-image 
            style="width: 100%; height: 400px"
            :src="room.pictures ? room.pictures[0].path : ''"
            :preview-src-list="[room.pictures ? room.pictures[0].path : '']"
          >
          </el-image> 
        </el-col>
        <el-col :span="18" class="right">
          <el-row class="title">{{ room.roomNameCn }}</el-row>
          <el-row class="headers">
            <span>{{ room.area}}</span>
            <span>{{ room.window === 0 ? '无窗' : ''}}</span>
            <span>{{ room.window === 1 ? '部分有窗' : ''}}</span>
            <span>{{ room.window === 2 ? '有窗' : ''}}</span>
            <span>{{ room.window === 3 ? '不确定' : ''}}</span>
            <span>{{ room.window === 4 ? '内窗' : ''}}</span>
            <span>{{ room.window === 5 ? '天窗' : ''}}</span>
            <span>{{ room.window === 6 ? '封闭窗' : ''}}</span>
            <span>{{ room.window === 7 ? '飘窗' : ''}}</span>
            <span>{{ room.floor}}</span>
            <span>{{ room.isExtraBed}}</span>
            <span>{{ room.bedDescription}}</span>
          </el-row>
          <el-row v-for="(value, key, index) in room.facilities" :key="index">
            <el-row style="padding:10px 0;">
              <span style="font-weight:600;padding:2px 0;">{{ key }}:</span>
                <span v-for="(val, index) in value" :key="index" style="padding-right:10px;"><el-tag type="info">{{ val }}</el-tag></span>
            </el-row>
          </el-row>
          <el-row>
            <el-row>
              <el-row style="padding-bottom:10px;">
                <span style="padding-right:20px;"><span style="font-weight:600">房间数量：</span>{{ room.ratePlanInfo.resNum }}间</span>
                <span style="padding-right:20px;"><span style="font-weight:600">每日价格：</span><span style="color:#F56C6C;font-weight:700">{{ room.ratePlanInfo.guidedPrice }}/元</span></span>
                <span style="padding-right:20px;"><span style="font-weight:600">床型描述：</span>{{ room.ratePlanInfo.bedInfo }}</span>
                <span style="padding-right:20px;"><span style="font-weight:600">WIFI服务：</span>{{ room.ratePlanInfo.wifi ? room.ratePlanInfo.wifi : '无' }}</span>
              </el-row>
              <el-row style="padding-bottom:10px;">
                <span style="padding-right:20px;"><span style="font-weight:600">每间房最多可住人数：</span>{{ room.ratePlanInfo.maxOccupancy }}</span>
                <span style="padding-right:20px;"><span style="font-weight:600">网络描述：</span>{{ room.ratePlanInfo.networkInfo ? room.ratePlanInfo.networkInfo : '暂无' }}</span>
                <span style="padding-right:20px;"><span style="font-weight:600">订单取消描述：</span>{{ room.ratePlanInfo.cancel.name ? room.ratePlanInfo.cancel.name : '暂无' }}</span>
                <span style="padding-right:20px;">{{ room.ratePlanInfo.mealInfo }}</span>
              </el-row>
            </el-row>
          </el-row>
          <el-row style="text-align:right;padding-right:30px;">
            <el-button type="danger" @click="toBeforeCreateOrder(room)">我要预定</el-button>
          </el-row>
        </el-col>
      </el-row>
    </el-row>
    <el-row v-if="roomInfo.length === 0" style="font-size:60px;height:400px;line-height:400px;text-align:center;color:#C0C4CC">暂无房间信息</el-row>
  </el-row>
</template>

<script>
import dayjs from 'dayjs'

export default {
  data() {
    return {
      hotelId: null,
      dateArr: [null, null],
      wrapper_loading_confirmingHotel: false,
      roomInfo: []
    }
  },
  computed: {
    inDate() {
      return this.dateArr[0] ? dayjs(this.dateArr[0]).format('YYYY-MM-DD') : ''
    },
    outDate() {
      return this.dateArr[1] ? dayjs(this.dateArr[1]).format('YYYY-MM-DD') : ''
    },
    roomInfoComputed() {
      let res = [];
      this.roomInfo.forEach(room => {
        let newRoom = {}
        newRoom.area = room.area
        newRoom.window = room.window
        newRoom.floor = room.floor
        newRoom.isExtraBed = room.isExtraBed
        newRoom.bedDescription = room.bedDescription
        newRoom.roomNameCn = room.roomNameCn
        newRoom.pictures = room.pictures
        newRoom.roomId = room.roomId
        let newFacilities = {}
        room.facilities.forEach(facil => {
          if(!newFacilities[facil.name]){
            newFacilities[facil.name] = []
          }
          newFacilities[facil.name].push(facil.tmpSubFacilities[0].subName)
        })
        newRoom.facilities = newFacilities
        newRoom.ratePlanInfo = room.ratePlanInfo[0]
        res.push(newRoom)
      })
      return res;
    }
  },
  methods: {
    getSingleJHotelMoreData() {
      this.wrapper_loading_confirmingHotel = true
      this.$axios
        .get('http://route.showapi.com/1653-4' + this.toParams({
          showapi_appid: 121810,
          showapi_sign: '5bbea10e0e6e4567873f870d4634924e',
          hotelId: this.hotelId,
          inDate: this.inDate,
          outDate: this.outDate
        }))
        .then(res => {
          if(res.status === 200 && res.statusText === 'OK' && res.data.showapi_res_body.ret_code === 0){
            this.roomInfo = res.data.showapi_res_body.roomInfo
          }else{
            this.$message({
              message: '查询失败',
              type: 'error'
            })
          }
          this.wrapper_loading_confirmingHotel = false
        })
        .catch(err => {
          this.$message({
            message: '查询失败',
            type: 'error'
          })
          this.wrapper_loading_confirmingHotel = false
        })
    },
    backToSingleHotel() {
      this.$router.push({path: '/afterlogin/user/hotel/gethotel' })
    },
    toBeforeCreateOrder(room) {
      this.$router.push({path: '/afterlogin/user/hotel/beforecreateorder',
        query: {
          data: {
            hotelId: this.hotelId,
            roomId: room.ratePlanInfo.id,
            inDate: this.inDate,
            outDate: this.outDate
          }
        }
      })
    }
  },
  created() {
    this.hotelId = this.$router.app._route.params.hotelid
    this.dateArr[0] = dayjs().add(2, 'day').format('YYYY-MM-DD')
    this.dateArr[1] = dayjs().add(3, 'day').format('YYYY-MM-DD')
    this.getSingleJHotelMoreData()
  }
}
</script>

<style>
.wrapper_confirming{
  height: calc(100vh - 30px);
}
.content-main-wrapper{
  padding: 20px 50px 20px 40px;
}
.content-single-room{
  margin: 0 0 30px 0;
  box-shadow: 0 2px 12px 0 rgba(0, 0, 0, 0.1);
}
.content-single-room .right{
  padding: 10px;
}
.content-main-wrapper .content-single-room .title{
  font-weight: 700;
  font-size: 20px;
  padding-bottom: 5px;
}
.content-main-wrapper .content-single-room .headers span{
  padding-right: 4px;
}
</style>