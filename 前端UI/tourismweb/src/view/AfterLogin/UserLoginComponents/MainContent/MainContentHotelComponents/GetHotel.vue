<template>
  <div 
    v-loading="wrapper_loading_hotel.wrapper_loading_hotel_getHotelList"
    :element-loading-text="wrapper_loading_hotel.wrapper_text"
    element-loading-spinner="el-icon-loading"
    element-loading-background="rgba(0, 0, 0, 0.8)"
    class="wrapper_hotel"
    ref="wrapper_hotel"
  >
    <div class="wrapper_hotel_content">
      <div class="wrapper-hotel-content-header">
        <el-row class="header" style="padding-left:70px">
          <el-col style="width:150px;padding:0 10px;">
            <el-select v-model="hotelOptions.city" filterable placeholder="请选择">
              <el-option
                v-for="(cityName, index) in cityNameList"
                :key="index"
                :label="cityName"
                :value="cityName">
              </el-option>
            </el-select>
          </el-col>
          <el-col style="width:180px;padding:0 10px;">
            <el-select v-model="hotelOptions.sortKey" filterable placeholder="请选择">
              <el-option
                v-for="(sortKey, index) in sortKeyList"
                :key="index"
                :label="sortKey.label"
                :value="sortKey.value">
              </el-option>
            </el-select>
          </el-col>
          <el-col style="width:130px;padding:0 10px;">
            <el-select v-model="hotelOptions.star" filterable placeholder="请选择">
              <el-option
                v-for="(star, index) in starList"
                :key="index"
                :label="star.label"
                :value="star.value">
              </el-option>
            </el-select>
          </el-col>
          <el-col style="width:150px;padding:0 10px;">
            <el-input placeholder="最低价格" v-model="hotelOptions.minPrice">
              <el-button slot="append">元</el-button>
            </el-input>
          </el-col>
          <el-col style="width:150px;padding:0 10px;">
            <el-input placeholder="最高价格" v-model="hotelOptions.maxPrice">
              <el-button slot="append">元</el-button>
            </el-input>
          </el-col>
          <el-col style="width:300px;padding:0 10px;">
            <el-select v-model="singleFilter" filterable placeholder="请选择地理位置" class="area">
              <el-option
                v-for="(singleFilter, index) in filterAreaList"
                :key="index"
                :label="singleFilter.name"
                :value="JSON.stringify(singleFilter)">
              </el-option>
            </el-select>
          </el-col>
          <el-col :span="3">
            <el-button type="primary" icon="el-icon-search" @click="getHotelList">筛选</el-button>
          </el-col>
        </el-row>
      </div>
      <div class="card-wrapper">
        <el-card class="box-card hotel-list-card" v-for="(itemHotel, index) in hotelList" :key="index">
          <div class="hotel-card-message-wrapper">
            <div class="hotel-card-name" @click="toSingleHotel(itemHotel.hotelId)">
            {{ itemHotel.chineseName + itemHotel.englishName }}
            </div>
            <div class="hotel-card-address">
            {{ itemHotel.address }}
            </div>
            <el-row>
              <el-col :span="18">
                <el-image 
                  fit="fill"
                  style="height:280px;width:100%;"
                  :src="itemHotel.picture"
                  :preview-src-list="[itemHotel.picture]"
                >
                </el-image>
              </el-col>
              <el-col :span="6">
                <div class="star">{{ itemHotel.star + '星级'}}</div>
                <div class="starName">{{ itemHotel.starName }}</div>
                <div class="price">{{ '￥' + itemHotel.price + '起' }}</div>
                <div class="facilities">
                  <div v-if="itemHotel.facilities.includes(1)">免费wifi</div>
                  <div v-if="itemHotel.facilities.includes(2)">餐厅</div>
                  <div v-if="itemHotel.facilities.includes(3)">停车场</div>
                  <div v-if="itemHotel.facilities.includes(4)">接机服务</div>
                  <div v-if="itemHotel.facilities.includes(5)">免费洗漱用品</div>
                  <div v-if="itemHotel.facilities.includes(7)">游泳池</div>
                  <div v-if="itemHotel.facilities.includes(8)">健身房</div>
                  <div v-if="itemHotel.facilities.includes(9)">会议室</div>
                  <div v-if="itemHotel.facilities.includes(10)">SPA</div>
                  <div v-if="itemHotel.facilities.includes(12)">24小时热水</div>
                </div>
              </el-col>
            </el-row>
          </div>
        </el-card>
      </div>
      <div class="hotel-pagination">
        <el-pagination
          background
          layout="prev, pager, next"
          :page-size="paginationOptions.pageSize"
          :total="paginationOptions.total"
          @current-change="currentChange"
          hide-on-single-page
        >
        </el-pagination>
      </div>
      <el-backtop target=".wrapper_hotel_content" @click="backTop" ref="backtop"/>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      wrapper_loading_hotel: {
        wrapper_loading_hotel_getHotelList: false,
        wrapper_text: '正在获取城市列表'
      },
      cityNameList: [],
      hotelList: [],
      sortKeyList: [
        {
          label: '按推荐值排序',
          value: 'recommend'
        },
        {
          label: '按口碑排序',
          value: 'satisfaction'
        },
        {
          label: '按起价升序',
          value: 'price-asc'
        },
        {
          label: '按起价降序',
          value: 'price-desc'
        }
      ],
      starList: [
        {
          label: '全部星级',
          value: ''
        },
        {
          label: '二星级',
          value: 'TWO'
        },
        {
          label: '三星级',
          value: 'THREE'
        },
        {
          label: '四星级',
          value: 'FOUR'
        },
        {
          label: '五星级',
          value: 'FIVE'
        },
        {
          label: '经济型',
          value: 'BUDGET'
        },
        {
          label: '舒适型',
          value: 'CONFORT'
        },
        {
          label: '高档型',
          value: 'HIGHEND'
        },
        {
          label: '豪华型',
          value: 'LUXURY'
        }
      ],
      paginationOptions: {
        currentPage: 1,
        pageSize: 30,
        total: 30
      },
      hotelOptions: {
        city: '北京',
        sortKey: 'recommend',
        star: '',
        minPrice: '',
        maxPrice: '',
        inDate: '',
        outDate: ''
      },
      filterAreaList: [],
      singleFilter: null
    }
  },
  methods: {
    clearHotelOptions() { 
  
    },
    getCityNameList() {
      this.wrapper_loading_hotel.wrapper_loading_hotel_getHotelList = true
      this.$axios
        .get('http://route.showapi.com/1653-2'+ this.toParams({
          showapi_appid: 121810,
          showapi_sign: '5bbea10e0e6e4567873f870d4634924e'
        }))
        .then(res => {
          if(res.status === 200 && res.statusText === 'OK' && res.data.showapi_res_body.ret_code === 0){
            this.cityNameList = res.data.showapi_res_body.cityNameList
            this.getHotelList()
          }else{
            this.$message({
              message: '获取城市列表失败'
            })
            this.wrapper_loading_hotel.wrapper_loading_hotel_getHotelList = false
          }
        })
        .catch(err => {
          this.$message({
            message: '获取城市列表失败'
          })
          this.wrapper_loading_hotel.wrapper_loading_hotel_getHotelList = false
        })
    },
    getHotelList() {
      console.log(this.filter)
      this.wrapper_loading_hotel.wrapper_text = '正在获取酒店信息'
      this.wrapper_loading_hotel.wrapper_loading_hotel_getHotelList = true
      this.$axios
        .get(
          'http://route.showapi.com/1653-1' + this.toParams({
          limit: 20,
          showapi_appid: 121810,
          showapi_sign: '5bbea10e0e6e4567873f870d4634924e',
          page: this.paginationOptions.currentPage - 1,
          sortKey: this.hotelOptions.sortKey,
          star: this.hotelOptions.star,
          cityName: this.hotelOptions.city,
          minPrice: String(this.hotelOptions.minPrice) ? String(this.hotelOptions.minPrice) : 0,
          maxPrice: String(this.hotelOptions.maxPrice) ? String(this.hotelOptions.maxPrice) : 99999,
          poiCode: this.singleFilter ? JSON.parse(this.singleFilter).poiType : '',
          longitude: this.singleFilter ? JSON.parse(this.singleFilter).longitude : '',
          latitude: this.singleFilter ? JSON.parse(this.singleFilter).latitude : ''
          })
        )
        .then(res => {
          if(res.status === 200 && res.statusText === 'OK' && res.data.showapi_res_body.ret_code === 0){
            this.hotelList = res.data.showapi_res_body.data.hotelList
            this.paginationOptions.total = res.data.showapi_res_body.data.count
            this.filterAreaList = [...res.data.showapi_res_body.data.filter[0].pros[0].filter,...res.data.showapi_res_body.data.filter[0].pros[1].filter,...res.data.showapi_res_body.data.filter[0].pros[2].filter,...res.data.showapi_res_body.data.filter[0].pros[4].filter,]
            this.wrapper_loading_hotel.wrapper_loading_hotel_getHotelList = false
          }else if(res.data.showapi_res_body.remark === '当前查询条件未查询到信息！'){
            this.hotelList = []
            this.$message({
              message: '当前查询条件未查询到信息！',
              type: 'info'
            })
            this.wrapper_loading_hotel.wrapper_loading_hotel_getHotelList = false
          }else{
            this.hotelList = []
            this.$message({
              message: '获取酒店列表失败'
            })
            this.wrapper_loading_hotel.wrapper_loading_hotel_getHotelList = false
          }
        })
        .catch(err => {
          this.$message({
            message: '获取酒店列表失败'
          })
          this.wrapper_loading_hotel.wrapper_loading_hotel_getHotelList = false
        })
    },
    currentChange(data) {
      this.paginationOptions.currentPage = data
      this.backTop()
      this.getHotelList()
    },
    backTop() {
      this.$refs.backtop.scrollToTop()
    },
    toSingleHotel(hotelId){
      this.$router.push({path: '/afterlogin/user/hotel/singlehotel/' + hotelId })
    }
  },
  mounted() {
    this.getCityNameList()
  },
  watch: {
    'hotelOptions.city' : {
      handler() {
        this.getHotelList()
      }
    }
  }
}
</script>

<style>
.wrapper_hotel{
  height: calc(100vh - 30px);
}
.wrapper_hotel_content{
  height: 100%;
  overflow-y: auto;
}
.hotel-list-card{
  display: inline-block;
  width: 700px;
  margin: 20px 70px;
  height: 400px;
  overflow: hidden;
}
.hotel-list-card .el-card__body{
   padding: 0px;
   height: 124px;
}
.hotel-list-card .hotel-card-message-wrapper{
  padding: 10px 30px;
}
.hotel-list-card .hotel-card-name{
  color: #36648B;
  text-decoration: underline;
  font-size: 18px;
  line-height: 25px;
  font-weight: 700;
  padding: 5px 0;
  cursor: pointer;
}
.hotel-list-card .hotel-card-address{
  padding: 10px 0;
  font-size: 16px;
  color: rgb(61, 61, 61);
}
.hotel-list-card .star,
.hotel-list-card .starName{
  text-align: center;
  padding: 10px 0;
  color: #E6A23C;
  font-size: 20px;
  font-weight: 600;
}
.hotel-list-card .star{
  color: #F56C6C;
}
.hotel-list-card .price{
  text-align: center;
  padding: 20px 0;
  color: #F56C6C;
  font-size: 30px;
  font-weight: 600;
}
.hotel-list-card .facilities{
  color: #36648B;
  padding: 5px 0;
  text-align: center;
}
.hotel-pagination{
  text-align: center;
  padding: 20px;
  padding-bottom: 50px;
}
.hotel-pagination .el-pagination{
  display: inline-block;
}
.area .el-input--small{
  width: 120%;
}
.wrapper-hotel-content-header .header{
  padding-top: 20px;
}
</style>