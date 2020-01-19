<template>
  <el-row
    v-loading="wrapper_single_hotel"
    element-loading-text="正在获取该酒店信息"
    element-loading-spinner="el-icon-loading"
    element-loading-background="rgba(0, 0, 0, 0.8)"
    class="wrapper-single-hotel"
  >
    <el-row class="single-hotel-content">
      <el-row style="padding-bottom:15px;">
        <span>
          <i class="el-icon-back" style="transform: scale(2.5);cursor: pointer;color:black" @click="backToGetHotel"></i>
        </span>
      </el-row>
      <el-row class="header content-main-right-main">
        <el-row class="row1">
          <span class="first">{{ hotelData.chineseName }}</span>
          <span class="second">{{ hotelData.star }}星级</span>
          <span class="second">{{ hotelData.starName }}</span>
        </el-row>
        <el-row class="english-name">
          <span>{{ hotelData.englishName }}</span>
        </el-row>
        <el-row class="address">
          <el-col :span="18">
            <span>{{ hotelData.address }}</span>
            <span class="tel"> 联系电话{{ hotelData.tel}}</span>
            <span class="tel">{{ hotelData.debutYear}}年开业</span>
            <span class="tel" v-if="hotelData.decorateDate">{{ hotelData.decorateDate}}年装修</span>
          </el-col>
          <el-col :span="6" style="position:relative">
            <el-button style="height:100px;position:absolute;bottom:0;width:250px;font-size:40px;color:white;" type="primary" @click="goToConfirming">查看更多<i class="el-icon-caret-right"></i></el-button>
          </el-col>
        </el-row>
      </el-row>
      <el-row class="content-main">
        <el-col :span="14" class="content-main-left">
          <el-carousel height="600px" indicator-position="outside" class="pictures-carousel">
            <el-carousel-item v-for="(item, index) in hotelData.pictures" :key="index">
              <el-image 
                style="width: 100%; height: 100%"
                :src="item.path"
              >
              </el-image>
            </el-carousel-item>
          </el-carousel>
        </el-col>
        <el-col :span="10" class="content-main-right">
          <el-row class="content-main-right-main">
            <el-row>
              <el-row class="header">酒店特色</el-row>
              <el-tag class="header-tag" v-for="(itemTag, index) in hotelData.services" :key="index">{{ itemTag.name }}</el-tag>
            </el-row>
            <el-row>
              <el-row v-for="(value, prop)  in facilitiesList" :key="prop">
                <el-row class="header">{{ prop }}</el-row>
                <el-row class="main">
                  <span class="mian-single-item" v-for="(item, index) in value" :key="index">
                    <i class="el-icon-check" style="font-weight:700"></i>
                    <span style="color:#606266">{{ item.name }}</span>
                  </span>
                </el-row>
              </el-row>
            </el-row>
          </el-row>
        </el-col>
      </el-row>
      <el-row class="content-footer">
        <el-row class="poiInfos">
          <el-row class="title">酒店附近</el-row>
          <el-row v-for="(poiInfo, index) in  poiInfosList" :key="index">
            <el-row class="title2">{{ poiInfo.name }}</el-row>
            <span v-for="(subPoiInfo, index) in poiInfo.subPoiInfos" :key="index">
              <el-tooltip :content="subPoiInfo.distance ? subPoiInfo.distance + '公里' : '无距离信息' " placement="top">
                <el-tag effect="plain" type="info" class="info-tag">{{ subPoiInfo.name }}</el-tag>，。
              </el-tooltip>
            </span>
          </el-row>
        </el-row>
        <el-row class="discription">
          <el-row class="title">酒店介绍</el-row>
          <el-row>
            <div class="content">
              {{ hotelData.instruction }}
            </div>
          </el-row>
        </el-row>
        <el-row class="policy">
          <el-row class="title">入住政策</el-row>
          <el-row class="content">
            <el-row>
              <span class="title">是否可携带宠物:</span>
              <span>{{ hotelData.policy.pet ?  hotelData.policy.pet : '无'}}</span>
            </el-row>
            <el-row>
              <span class="title">离店时间要求:</span>
              <span>{{ hotelData.policy.checkOutTime ?  hotelData.policy.checkOutTime : '无'}}</span>
            </el-row>
            <el-row>
              <span class="title">退订规则:</span>
              <span>{{ hotelData.policy.cancel ?  hotelData.policy.cancel : '无'}}</span>
            </el-row>
            <el-row>
              <span class="title">入住离店时间:</span>
              <span>{{ hotelData.policy.arrivalDeparture ?  hotelData.policy.arrivalDeparture : '无'}}</span>
            </el-row>
            <el-row>
              <span class="title">入住需要:</span>
              <span>{{ hotelData.policy.depositPrepaid ?  hotelData.policy.depositPrepaid : '无'}}</span>
            </el-row>
            <el-row>
              <span class="title">特殊要求:</span>
              <span>{{ hotelData.policy.requirements ?  hotelData.policy.requirements : '无'}}</span>
            </el-row>
            <el-row>
              <span class="title">入住时间:</span>
              <span>{{ hotelData.policy.checkInTime ?  hotelData.policy.checkInTime : '无'}}</span>
            </el-row>
          </el-row>
        </el-row>
      </el-row>
    </el-row>
  </el-row>
</template>

<script>
export default {
  data() {
    return {
      hotelId: null,
      wrapper_single_hotel: true,
      hotelData: {
        facilities: [],
        policy: {}
      }
    }
  },
  computed: {
    facilitiesList() {
      let obj = { }
      this.hotelData.facilities.forEach(item => {
        if(!obj[item.typeName]){
          obj[item.typeName] = []
        }
        if(Number(item.status) === 1){
          obj[item.typeName].push(item)
        }
      })
      return obj
    },
    poiInfosList() {
      let arr = []
      if(!this.hotelData.poiInfos){
        return arr;
      }
      this.hotelData.poiInfos.forEach(value => {
        if(value.name){
          arr.push(value)
        }
      })
      return arr
    }
  },
  methods: {
    getSingleHotel(hotelId) {
      this.wrapper_single_hotel = true
      this.$axios
        .get(
          'http://route.showapi.com/1653-3' + this.toParams({
          showapi_appid: 121810,
          showapi_sign: '5bbea10e0e6e4567873f870d4634924e',
          hotelId,
        }))
        .then(res => {
          if(res.status === 200 && res.statusText === 'OK' && res.data.showapi_res_body.ret_code === 0){
            this.hotelData = res.data.showapi_res_body.data
          }else{
            this.$message({
              message: '查询失败',
              type: 'error'
            })
          }
          this.wrapper_single_hotel = false
        })
        .catch(err => {
          this.$message({
            message: '调用API失败',
            type: 'error'
          })
          this.wrapper_single_hotel = false
        })
    },
    backToGetHotel() {
      this.$router.push({path: '/afterlogin/user/hotel/gethotel' })
    },
    goToConfirming() {
      this.$router.push({path: '/afterlogin/user/hotel/confirming/' + this.hotelId })
    }
  },
  mounted() {
    this.hotelId = this.$router.app._route.params.hotelid
    this.getSingleHotel(this.hotelId)
  }
}
</script>

<style>
.wrapper-single-hotel{
  height: calc(100vh - 30px);
}
.single-hotel-content{
  /* border:solid 1px #909399; */
  margin: 20px;
  padding: 15px;
}
.single-hotel-content .content-main-right-main .row1{
  padding: 5px 0;
}
.single-hotel-content .content-main-right-main .first{
  font-size: 35px;
  font-weight: 700;
  color: #303133;
}
.single-hotel-content .content-main-right-main .second{
  font-size: 20px;
  color: crimson;
  font-weight: 700;
}
.single-hotel-content .content-main-right-main .address{
  padding: 7px 0;
  font-size: 16px;
  color: #606266;
  font-weight: 550;
}
.single-hotel-content .content-main-right-main .address .tel{
  padding: 0 10px;
}
.single-hotel-content .content-main-right-main .english-name{
  padding: 7px 0;
  font-size: 16px;
  color: #303133;
  font-weight: 600;
}
.single-hotel-content .content-main-right-main .header-tag{
  margin-right: 10px;
  margin-top: 13px;
}
.content-main{
}
.content-main .pictures-carousel .el-carousel__indicators--horizontal{
  display: none;
}
.content-main .content-main-right{
  border-top: 3px solid #DCDFE6;
  border-bottom: 3px solid #DCDFE6;
  height: 600px;
  padding: 5px 40px;
  overflow-y: auto;
}
.content-main .content-main-right::-webkit-scrollbar{
  display: none;
}

.content-main .content-main-right .header{
  padding: 15px 0;
  font-size: 16px;
  font-weight: 700;
  color: #303133;
}
.content-main .content-main-right .main .mian-single-item{
  padding: 2px 7px;
  color: #3e6e00;
  font-weight: 500;
}
.content-footer .poiInfos .title,
.content-footer .policy .title,
.content-footer .discription .title{
  font-size: 21px;
  color: #303133;
  font-weight: 600;
  padding: 20px 0 10px 0;
}
.content-footer .poiInfos .title2{
  font-size: 16px;
  font-weight: 600;
}
.content-footer .poiInfos .info-tag{
  margin: 5px 15px 5px 0;
  cursor: pointer;
}
.content-footer .discription .content{
  color: #909399;
  text-indent: 2em;
}
.content-footer .policy .content{
  color: #909399;
}
.content-footer .policy .content .title{
  color: #909399;
  font-size: 16px;
  font-weight: normal;
}
</style>