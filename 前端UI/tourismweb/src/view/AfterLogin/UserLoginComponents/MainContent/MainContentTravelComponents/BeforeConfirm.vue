<template>
  <el-row v-loading="wrapper_loading_travel"
  element-loading-text="疯狂加载中"
  element-loading-spinner="el-icon-loading"
  element-loading-background="rgba(0, 0, 0, 0.8)">
    <!-- <div>
      <el-checkbox-group v-model="checkboxGroup1">
        <el-checkbox-button v-for="city in cities" :label="city" :key="city">{{city}}</el-checkbox-button>
      </el-checkbox-group>
    </div> -->
    <el-card class="box-card user-travel-card" v-for="(itemCard, key) in cardArr" :key="key" shadow="hover" @click.native="toConfirming(itemCard.id)">
      <div slot="header" class="clearfix">
        <span>{{ itemCard.name }}</span>
      </div>
      <el-popover
        placement="bottom"
        width="240"
        trigger="hover"
        :content="itemCard.description"
        class="user-travel-card-popover"  
      >
        <div class="text item" slot="reference">
          <el-image 
            style="width: 100%; height: 124px"
            :src="itemCard.picture ? 'http://localhost/' + itemCard.picture : ''" 
          >
          </el-image>
        </div>
      </el-popover>
    </el-card>
  </el-row>
</template>

<script>
export default {
  data() {
    return {
      wrapper_loading_travel: false,
      cardArr: []
    }
  },
  methods: {
    getAllDestination() {
      this.wrapper_loading_travel = true
      this.$axios
        .get('http://localhost/tp5/public/index.php/index/Destination/all')
        .then(res => {
          if(res.status === 200 && res.statusText === 'OK' && res.data.status ===1){
              this.wrapper_loading_travel = false
              const destinationArr = []
              for(let destination_item of Object.values(res.data.data.destinations)){
                for(let price_item of Object.values(res.data.data.price)){
                  if(price_item.id === destination_item.id){
                    destinationArr.push(Object.assign(destination_item,price_item))
                  }
                }
              }
              this.cardArr = destinationArr
              this.cardArr.sort((a, b) => {
                if(b.hot > a.hot){
                  return 1
                }else{
                  return -1
                }
              })
          }
        })
        .catch(err => {
          console.log(err)
        })
    },
    toConfirming(id) {
      this.$router.push({path: '/afterlogin/user/travel/confirming/' + id })
    }
  },
  created() {
    this.getAllDestination()
  }
}
</script>
<style scoped>
.user-travel-card{
  display: inline-block;
  width: 270px;
  margin: 20px;
  height: 150px;
  overflow: hidden;
}
.user-travel-card:hover{
  cursor: pointer;
}
</style>
<style>
.user-travel-card .el-card__header{
  padding: 5px;
  font-size: 12px;
}
.user-travel-card .el-card__body{
   padding: 0px;
   height: 124px;
  /*font-size: 10px;
  text-align: center;
  height: 205px;
  line-height: 205px; */
}
.user-travel-card .el-popover__reference{
  /* height: 220px; */
}
.el-popover.el-popper.el-popover--plain{
  font-size: 10px;
}
</style>