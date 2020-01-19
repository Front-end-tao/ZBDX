<template>
    <el-row>
      <el-col :span="14">
        <pic />
      </el-col>
      <el-col :span="10">
        <router-view />
      </el-col>
    </el-row>
</template>

<script>
import Pic from './BeforeLogin/LeftContentPic/Pic'

export default {
  components: {
    Pic
  }
}
</script>

<style>

</style>