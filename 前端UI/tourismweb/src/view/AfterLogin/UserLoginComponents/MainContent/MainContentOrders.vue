 <template>
    <el-tabs v-model="activeName" @tab-click="tabClick" class="main-content-data" type="border-card" >
      <el-tab-pane label="门票" name="tab1">
        <TravelOrder />
      </el-tab-pane>
      <el-tab-pane label="火车票" name="tab2">
        火车票
      </el-tab-pane>
      <el-tab-pane label="酒店" name="tab3">
        酒店
      </el-tab-pane>
    </el-tabs>
</template>

<script>
import TravelOrder from './MainContentOrdersComponents/TravelOrders'
export default {
  data() {
    return {
      activeName: 'tab1'
    }
  },
  components: {
    TravelOrder
  },
  methods: {
    tabClick(target) {
      
    }
  }
}
</script>

<style>
.main-content-data .el-tabs__header .el-tabs__item{
  height: 28px;
  font-size: 10px;
  line-height: 28px;
}

.main-content-data > .el-tabs__content{
  padding: 0;
}
</style>