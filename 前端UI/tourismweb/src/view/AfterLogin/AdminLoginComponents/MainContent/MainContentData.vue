 <template>
    <el-tabs v-model="activeName" @tab-click="tabClick" class="main-content-data" type="border-card" >
      <el-tab-pane label="用户管理" name="tab1">
        <user-component />
      </el-tab-pane>
      <el-tab-pane label="景点管理" name="tab2">
        <destinationComponent ref="destinationComponent" />
      </el-tab-pane>
      <el-tab-pane label="用户旅游订单管理" name="tab3">
        <TravelOrdersComponent  ref="travelOrdersComponent"/>
      </el-tab-pane>
      <el-tab-pane label="待开发" name="tab4">待开发</el-tab-pane>
    </el-tabs>
</template>

<script>
import userComponent from './MainContentDataComponents/User'
import destinationComponent from './MainContentDataComponents/Destination'
import TravelOrdersComponent from './MainContentDataComponents/TravelOrders'
export default {
  data() {
    return {
      activeName: 'tab1'
    }
  },
  components: {
    userComponent,
    destinationComponent,
    TravelOrdersComponent
  },
  methods: {
    tabClick(target) {
      if(target.name === 'tab2'){
        this.$refs.destinationComponent.getDestination();
      }else if(target.name === 'tab3'){
        this.$refs.travelOrdersComponent.getDestinationOrders();
      }
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
.main-content-data .el-tabs__header .el-tabs__item.is-active{
  color: #F56C6C
}
.main-content-data .el-tabs__header .el-tabs__item:not(.is-disabled):hover {
  color: #F56C6C
}
.main-content-data > .el-tabs__content{
  padding: 0;
}
</style>