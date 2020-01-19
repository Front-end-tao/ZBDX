<template>
  <el-carousel :interval="3000" class="before-login-pic-wrapper" height="100%">
    <el-carousel-item v-for="item in pictureArr" :key="item">
      <img :src="item" >
    </el-carousel-item>
  </el-carousel>
</template>

<script>
export default {
  data() {
    return {
      item1: require('../../../../static/pic/BeforeLogin1.jpeg'),
      item2: require('../../../../static/pic/BeforeLogin2.jpeg'),
      item3: require('../../../../static/pic/BeforeLogin3.jpeg'),
      item4: require('../../../../static/pic/BeforeLogin4.jpeg'),
    }
  },
  computed: {
   pictureArr() {
      return [this.item1, this.item2, this.item3, this.item4];
   }
  }
}
</script>

<style scoped>
.before-login-pic-wrapper{
  height: 100vh;
}
.before-login-pic-wrapper img{
  height: 100%;
  width: 100%;
  opacity: 0.7;
}
</style>