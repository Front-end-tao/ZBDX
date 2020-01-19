<template>
  <div class="confirming-wrapper">
    <el-row class="confirming-wrapper-header">
      <el-col :span="12">
        <span>
          <i class="el-icon-back" style="transform: scale(2.5);cursor: pointer;" @click="backToBeforeConfirm"></i>
        </span>
        <span style="color:#409EFF;padding-left:30px;">
          本产品由中北大学Trioism-Web旅行社有限公司代理招徕，具体旅游服务和操作由合作旅行社提供。
        </span>
      </el-col>
    </el-row>
    <el-row class="confirming-wrapper-header">
      <el-col style="text-align: right;">
        <span class="right-span" @click="html1">
          国内旅游网预定须知
        </span>
        <span>
          |
        </span>
        <span class="right-span">
          国内旅游网预定须知
        </span>
      </el-col>
    </el-row>
    <el-row class="confirming-wrapper-main">
      <el-col :span="10">
        <div style="height:335px;">
          <el-image 
            style="width:100%;height:100%;"
            :src="singleData.picture ? 'http://localhost/' + singleData.picture : ''" 
            :preview-src-list="[singleData.picture ? 'http://localhost/' + singleData.picture : '']">
          </el-image>
        </div>
      </el-col>
      <el-col :span="14">
        <div class="right-header">
          <i class="el-icon-info"></i>
          透明行程
          <i class="el-icon-info"></i>
          食宿无忧
          <i class="el-icon-info"></i>
          严选导游
          <i class="el-icon-info"></i>
        </div>
        <el-row class="right-main">
          <el-col :span="13">
            <el-row class="singleData_item">
              <span class="title">景点名称：</span>
              <span class="content">{{ singleData.name }}</span>
            </el-row>
            <el-row class="singleData_item">
              <span class="title">景点位置：</span>
              <span class="content">{{ singleData.location }}</span>
            </el-row>
            <el-row class="singleData_item">
              <span class="title">游玩天数：</span>
              <span class="content">{{ singleData.days + '天' }}</span>
            </el-row>
            <el-row class="singleData_item">
              <span class="title">景点描述：</span>
              <div class="description-div">
                {{ singleData.description }}
              </div>
            </el-row>
            <el-row class="singleData_item">
              <span class="title">关键字：</span>
              <el-tag>{{ this.tagName }}</el-tag>
            </el-row>
          </el-col>
          <el-col :span="11">
            <el-row class="singleData_item">
              <span class="title">标准价格：</span>
              <span class="content bigger">{{ singleData.normalPrice + '元' }}</span>
            </el-row>
            <el-row class="singleData_item">
              <span class="title">特殊价格：</span>
              <span class="content bigger">
                {{ singleData.specialPrice + '元' }} 
                <el-tooltip class="item" effect="light" content="儿童(1.2米以下)、老人(65周岁以上)、退伍军人可享受该价格" placement="bottom" popper-class="confriming-popper-class">
                  <span class="other">什么是特殊价格？</span>
                </el-tooltip>
              </span>
            </el-row>
            <el-row class="singleData_item">
              <span class="title">导游价格：</span>
              <span class="content middle" v-if="singleData.guide === 0">该景点不支持导游服务</span>
              <span class="content bigger" v-else>{{ singleData.guidePrice + '元/天' }}</span>
            </el-row>
            <el-row class="singleData_item">
              <span class="title">组团价格：</span>
              <span class="content middle">
                {{ singleData.group === 0 ? '该景点不支持组团' :  singleData.groupPrice + '折' }}
                <el-tooltip class="item" effect="light" content="五人及以上同行可享受组团折扣优惠" placement="bottom" popper-class="confriming-popper-class">
                  <span class="other" v-if="singleData.group !== 0">什么是组团价格？</span>
                </el-tooltip>
              </span>
            </el-row>
            <el-row class="singleData_item">
              <span class="title">企业级服务价格：</span>
              <span class="content middle" >
                <span v-if="singleData.company === 0" style="font-size:13px;">该景点不支持企业级服务</span>
                <span v-else>{{ singleData.companyPrice + '折' }}</span>
                <el-tooltip class="item" effect="light" content="十人及以上同行可享受企业级服务折扣优惠" placement="bottom" popper-class="confriming-popper-class">
                  <span class="other" v-if="singleData.company !== 0">什么是企业级服务价格？</span>
                </el-tooltip>
              </span>
            </el-row>
            <el-row class="singleData_item">
              <span class="title">推荐指数：</span>
              <el-rate
                :value="singleData.hot/10 > 10 ? 10 : singleData.hot/10"
                :max="10"
                :disabled="true"
                :allow-half="true"
                text-color="#ff9900"
                show-score
                style="padding:5px 0"
              >
              </el-rate>
            </el-row>
          </el-col>
        </el-row>

      </el-col>
    </el-row>
    <el-row class="confirming-wrapper-footer">
      <el-col :span="14">
        <div class="footer-header">
         评论<i class="el-icon-chat-line-round"></i> 
        </div>
        <div class="footer-content" v-if="singleData.comment.length !== 0">
          <div  v-for="(singleComment, index) in singleData.comment" :key="index" style="margin:5px 0;background-color:#F2F6FC;padding:5px 20px;">
            <p style="font-size:18px;font-weight:700">{{ singleComment.comment }}</p>
            <el-rate
              v-model="singleComment.grade"
              disabled
              text-color="#ff9900"
              style="padding-top:10px;"
            >
            </el-rate>
            <p style="text-align:right;font-size:13px;">来自用户：{{ singleComment.userMail }}</p>
          </div>
        </div>
        <div v-else class="footer-content">
          <p style="height:300px;font-size:50px;color:#F2F6FC;line-height:300px;text-align:center">暂无评价</p>
        </div>
      </el-col>
      <el-col :span="10">
        <div class="footer-confirm">
          <div class="item">
            <span class="title">选择出行日期：</span>
            <el-date-picker
              v-model="tourismDate"
              type="date"
              placeholder="选择日期">
            </el-date-picker>
          </div>
          <div class="item">
            <span class="title">选择出游人数：</span>
            <span class="title">标准人数</span>
            <el-input-number size="mini"  :min="0" v-model="normalPeopleNumber"></el-input-number>
            <span class="title">特殊人数</span>
            <el-input-number size="mini"  :min="0" v-model="specialPeopleNumber"></el-input-number>
          </div>
          <div class="item">
            <span class="title">导游服务天数：</span>
            <el-input-number size="mini" :min="0" :max="singleData.days ? singleData.days : 0" v-model="guideDays" :disabled="singleData.guide === 0"></el-input-number>
          </div>
          <div style="text-align:right;margin-top:-20px">
            <el-button type="primary" @click="toCreateOrder">立即预定</el-button>
          </div>
        </div>
      </el-col>
    </el-row>
    
  </div>
</template>

<script>
export default {
  data() {
    return {
      wrapper_loadin_confirming: false,
      tourismDate: '',
      normalPeopleNumber: 0,
      specialPeopleNumber: 0,
      singleData: {
        comment: []
      },
      guideDays: 0
    }
  },
  computed: {
    tagName() {
      let type;
      switch(this.singleData.type){
        case 1:
          type = '历史古迹'
          break
        case 2:
          type = '大好河山'
          break
        case 3:
          type = '阳光沙滩'
          break
        case 4:
          type = '自然奇观'
          break
        case 5:
          type = '野生动物'
          break
        case 6:
          type = '辽阔草原'
          break
        default:
          type = '其他'
      }
      return type
    }
  },
  methods: {
    backToBeforeConfirm() {
      this.$router.push({path: '/afterlogin/user/travel/beforeconfirm'})
    },
    getSingle() {
      this.wrapper_loadin_confirming = true
      const id = this.$router.app._route.params.id
      this.$axios
        .get('http://localhost/tp5/public/index.php/index/Destination/single'+ this.toParams({
        id
        }))
        .then(res => {
          if(res.status === 200 && res.statusText === 'OK' && res.data.status ===1){
            this.singleData = Object.assign(res.data.data.destinations, res.data.data.price, {
              comment: res.data.data.comment
            })
          }else{
            this.$message({
              message: '获取数据失败',
              type: 'error'
            })
          }
          this.wrapper_loadin_confirming = false
        })
        .catch(err => {
          this.$message({
            message: '调用api失败',
            type: 'error'
          })
        })
    },
    toCreateOrder() {
      const dataObj = {
        id: this.$router.app._route.params.id,
        tourismDate: this.tourismDate ? this.tourismDate.getTime() : 0,
        normalPeopleNumber: this.normalPeopleNumber,
        specialPeopleNumber: this.specialPeopleNumber,
        guideDays: this.guideDays
      }
      
      if(dataObj.tourismDate === 0){
        this.$message({
          message: '请选择出行日期',
          type: 'error'
        })
        return
      }
      if(dataObj.normalPeopleNumber + dataObj.specialPeopleNumber === 0){
        this.$message({
          message: '人数输入有误',
          type: 'error'
        })
        return
      }
      this.$router.push({
        path: '/afterlogin/user/travel/createorder',
        query: {
          data: dataObj
        }})
    },
    html1() {
      window.open('http://localhost:80/index.html')
    }
  },
  created() {
    this.getSingle()
  }
}
  
</script>

<style>
.confirming-wrapper{
  height: calc(100vh - 30px);
  padding: 0 40px;
}
.confirming-wrapper-header{
  height: 30px;
  line-height: 30px;
  font-size: 10px;
}
.confirming-wrapper-header .right-span{
  text-decoration: underline;
  cursor: pointer;
}
.confirming-wrapper-main .right-header{
  color: white;
  background-color:#409EFF;
  font-size: 12px;
  height: 25px;
  line-height: 25px;
  padding: 0 100px;
  min-width: 490px;
}
.confirming-wrapper-main .right-main{
  background-color: #f9f9fa;
  height: 310px;
  min-width: 690px;
}
.confirming-wrapper-main .right-main .singleData_item {
  padding: 7px 20px;
}
.confirming-wrapper-main .right-main .singleData_item .title{
  font-size: 15px;
  padding: 2px;
}
.confirming-wrapper-main .right-main .singleData_item .content{
  font-size: 13px;
}
.confirming-wrapper-main .right-main .singleData_item .description-div{
  padding-top: 10px;
  font-size: 10px;
  line-height: 15px;
  text-indent: 20px;
  display: -webkit-box;
  -webkit-box-orient: vertical;
  -webkit-line-clamp: 8;
  overflow: hidden;
}
.confirming-wrapper-main .right-main .singleData_item .bigger{
  color: #409EFF;
  font-size: 30px;
}
.confirming-wrapper-main .right-main .singleData_item .middle{
  color: #67C23A;
  font-size: 15px;
}
.confirming-wrapper-main .right-main .singleData_item .content .other{
  margin-left: 20px;
  color:#F56C6C;
  font-size: 9px;
  cursor: pointer;
  text-decoration: underline
}
.confirming-wrapper-footer .footer-header{
  color: white;
  background-color:#409EFF;
  font-size: 12px;
  height: 25px;
  line-height: 25px;
  padding: 0 100px;
  text-align: center;
  /* min-width: 490px; */
}
.confirming-wrapper-footer .footer-content{
  height: calc(100vh - 450px);
  overflow-y: auto;
}
.confirming-wrapper-footer .footer-content::-webkit-scrollbar{
  display: none;
}
.confirming-wrapper-footer .footer-confirm{
  padding: 30px 30px;
}
.confirming-wrapper-footer .footer-confirm .el-date-editor{
  width: 200px;
}
.confirming-wrapper-footer .footer-confirm .el-date-editor .el-input__inner{
  height: 20px;
  line-height: 20px;
  width: 140px
}
.confirming-wrapper-footer .footer-confirm .el-date-editor .el-input__prefix i{
  line-height: 20px;
}
.confirming-wrapper-footer .footer-confirm .el-date-editor  .el-input__suffix i{
  display: none;
}
.confirming-wrapper-footer .footer-confirm .title{
  font-size: 12px;
}
.confirming-wrapper-footer .footer-confirm .item{
  padding: 10px 0;
}
.confirming-wrapper-footer .footer-confirm .item .el-input-number{
  line-height: 22px;
  width: 100px;
}
.confirming-wrapper-footer .footer-confirm .item .el-input-number span{
  line-height: 21px;
}
.confirming-wrapper-footer .footer-confirm .item .el-input-number .el-input .el-input__inner{
  line-height: 24px;
  height: 23px;
}
.confriming-popper-class{
  height: 10px;
  line-height: 10px;
  font-size: 9px!important;
  padding: 5px;
  
}
.confriming-popper-class .popper__arrow{
  display: none; 
 
}
</style>