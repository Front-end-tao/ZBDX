<template>
  <el-row v-loading="wrapper_loading" class="user_wrapper" element-loading-text="疯狂加载中"
  element-loading-spinner="el-icon-loading"
  element-loading-background="rgba(0, 0, 0, 0.8)">
    <div class="header-add">
      <el-button type="danger" icon="el-icon-refresh-right" @click="refresh" circle />
      <el-button type="danger" icon="el-icon-plus" @click="toAddDestination" circle />
    </div>
    <el-table
      :data="tableDataCoupted"
      style="width: 100%;"
      header-row-class-name="users-table-header-row-class"
      row-class-name="users-table-row-class"
      height="750px"
      border
      stripe
    >
      <el-table-column
        prop="id"
        label="id编号"
        width="110"
        header-align="center"
        align="center"
        fixed="left">
      </el-table-column>
      <el-table-column
        prop="name"
        label="景点名称"
        width="100"
        header-align="center"
        align="center"
      >
      </el-table-column>
      <el-table-column
        prop="location"
        label="景点位置"
        width="110"
        header-align="center"
        align="center">
      </el-table-column>
      <el-table-column
        prop="days"
        label="游玩天数"
        width="80"
        header-align="center"
        align="center">
      </el-table-column>
      <el-table-column
        prop="type"
        label="景点种类"
        width="70"
        header-align="center"
        align="center">
      </el-table-column>
      <el-table-column
        prop="normalPrice"
        label="标准价格/人"
        width="80"
        header-align="center"
        align="center">
      </el-table-column>
      <el-table-column
        prop="specialPrice"
        label="特殊价格/人"
        width="80"
        header-align="center"
        align="center">
      </el-table-column>
      <el-table-column
        prop="group"
        label="组团折扣"
        width="70"
        header-align="center"
        align="center">
      </el-table-column>
      <el-table-column
        prop="company"
        label="企业级服务折扣"
        width="95"
        header-align="center"
        align="center">
      </el-table-column>
      <el-table-column
        prop="aggregateLocation"
        label="集合时间与地点"
        width="95"
        header-align="center"
        align="center">
      </el-table-column>
      <el-table-column
        prop="guide"
        label="导游价格/天"
        width="100"
        header-align="center"
        align="center">
      </el-table-column>
      <el-table-column
        prop="hot"
        label="热度"
        width="50"
        header-align="center"
        align="center">
      </el-table-column>
      <el-table-column
        prop="description"
        label="景点描述"
        header-align="center"
        width="450"
        align="left"
      >
      </el-table-column>
      <el-table-column
        prop="picture"
        label="图片"
        header-align="center"
        align="center"
        width="100"
      >
        <template slot-scope="scope">
          <div v-if="scope.row.picture">
            <img :src="'http://localhost/' + scope.row.picture" style="width:80%;height:80%;">
          </div>
          <div v-else class="users-table-click-class">
            <el-upload
              action="http://localhost/tp5/public/index.php/index/Pictrue/upLoad"
              name="upfile"
              :data="{id: scope.row.id}"
              :on-success="uploadSuccess"
              :on-error="uploadFail"
            >
              <el-button size="small" type="text">点击上传</el-button>
            </el-upload>
          </div>
        </template>
      </el-table-column>
      <el-table-column
        label="操作"
        width="90"
        fixed="right"
        > 
        <template slot-scope="scope">
          <div class="users-table-click-class">
            <el-button @click="table_edit(scope.row.id)" type="text" size="small">编辑</el-button>
            <el-button class="delete-btn" @click="table_delete(scope.row.mail)" type="text" size="small">删除</el-button>
          </div>
        </template>
      </el-table-column>
    </el-table>
    <el-dialog
      top="10px"
      :visible.sync="AddDestinationDialog"
      width="480px"
      custom-class="addDestination-dialog"
      :show-close="false"
      v-loading="wrapper_loading_form"
      element-loading-text="疯狂加载中"
  element-loading-spinner="el-icon-loading"
  element-loading-background="rgba(0, 0, 0, 0.8)"
    >
      <div class="title">请输入景点信息</div>
      <el-form ref="addDestinationDialogForm" :model="addDestinationDialogForm" label-width="100px" style="width:300px;" :rules="rules" >
        <el-form-item label="景点名称" prop="name">
          <el-input v-model="addDestinationDialogForm.name" placeholder="请输入景点名称"></el-input>
        </el-form-item>
        <el-form-item label="景点位置" prop="location">
          <el-input v-model="addDestinationDialogForm.location" placeholder="请输入景点位置：省/市"></el-input>
        </el-form-item>
        <el-form-item label="游玩天数" prop="days">
          <el-input v-model="addDestinationDialogForm.days" placeholder="请输入游玩天数"></el-input>
        </el-form-item>
        <el-form-item label="标准价格" prop="singlePeople.normalPrice">
          <el-input v-model="addDestinationDialogForm.singlePeople.normalPrice" placeholder="请输入标准价格">
            <span slot="append">元（RMB）</span>
          </el-input>
        </el-form-item>
        <el-form-item label="特殊价格" prop="singlePeople.specialPrice">
          <el-input v-model="addDestinationDialogForm.singlePeople.specialPrice" placeholder="请输入特殊价格">
            <span slot="append">元（RMB）</span>
          </el-input>
        </el-form-item>
        <el-form-item label="景点类型" prop="type">
          <el-select v-model="addDestinationDialogForm.type" placeholder="请选择景点类型" popper-class="admin-desnation-wrapper">
            <el-option label="历史古迹" value="1"></el-option>
            <el-option label="大好河山" value="2"></el-option>
            <el-option label="阳光沙滩" value="3"></el-option>
            <el-option label="自然奇观" value="4"></el-option>
            <el-option label="野生动物" value="5"></el-option>
            <el-option label="辽阔草原" value="6"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="支持组团">
          <el-switch
            v-model="addDestinationDialogForm.group.value"
            active-color="#F56C6C"
            :active-value="1"
            :inactive-value="0"
          />
        </el-form-item>
        <el-form-item label="组团折扣" prop="group.price">
          <el-input v-model="addDestinationDialogForm.group.price" :disabled="!addDestinationDialogForm.group.value" placeholder="0-10">
            <span slot="append">折</span>
          </el-input>
        </el-form-item>
        <el-form-item label="支持企业级服务">
          <el-switch
            v-model="addDestinationDialogForm.company.value"
            active-color="#F56C6C"
            :active-value="1"
            :inactive-value="0"
          />
        </el-form-item>
        <el-form-item label="企业级服务折扣" prop="company.Price">
          <el-input v-model="addDestinationDialogForm.company.price" :disabled="!addDestinationDialogForm.company.value" placeholder="0-10">
            <span slot="append">折</span>
          </el-input>
        </el-form-item>
        <el-form-item label="集合时间与地点" prop="aggregateLocation">
          <el-input v-model="addDestinationDialogForm.aggregateLocation" :disabled="addDestinationDialogForm.group.value === 0 && addDestinationDialogForm.company.value === 0" placeholder="集合时间与地点">
          </el-input>
        </el-form-item>
        <el-form-item label="导游">
          <el-switch
            v-model="addDestinationDialogForm.guide.value"
            active-color="#F56C6C"
            :active-value="1"
            :inactive-value="0"
          />
        </el-form-item>
        <el-form-item label="导游价格/天" prop="guide.Price">
          <el-input v-model="addDestinationDialogForm.guide.price" :disabled="!addDestinationDialogForm.guide.value" placeholder="导游价格/天">
            <span slot="append">元（RMB）</span>
          </el-input>
        </el-form-item>
        <el-form-item label="景点简介" prop="description" class="description">
          <el-input type="textarea" v-model="addDestinationDialogForm.description" placeholder="请输入景点简介" />
        </el-form-item>
      </el-form>
      <span slot="footer" class="dialog-footer">
        <el-button type="danger" @click="AddDestinationDialog = false" plain>取 消</el-button>
        <el-button type="danger" @click="addDestination('addDestinationDialogForm')">确 定</el-button>
      </span>
    </el-dialog>
  </el-row>
</template>

<script>
export default {
  data() {
    let checkLocation = (rule, value, callBack) => {
      if (!value) {
          return callBack(new Error('请输入景点位置'));
        }
        setTimeout(() => {
          if (isNaN(Number(value))) {
            callBack();
          } else {
              callBack(new Error('禁止输入数字'));
          }
        }, 100);
    }
    let checkNormalPrice = (rule, value, callBack) => {
      if (!value) {
          return callBack(new Error('请输入标准价格'));
        }
        setTimeout(() => {
          if (isNaN(Number(value))) {
            callBack(new Error('请输入数字值'));
          } else {
            if (Number(value) < 0) {
              callBack(new Error('价格不能为负数'));
            } else {
              callBack();
            } 
          }
        }, 100);
    }
    let checkSpecialprice = (rule, value, callBack) => {
      if (!value) {
          return callBack(new Error('请输入特殊价格'));
        }
        setTimeout(() => {
          if (isNaN(Number(value))) {
            callBack(new Error('请输入数字值'));
          } else {
            if (Number(value) < 0) {
              callBack(new Error('价格不能为负数'));
            } else {
              callBack();
            } 
          }
        }, 100);
    }
    let checkDays = (rule, value, callBack) => {
      if (!value) {
          return callBack(new Error('请输入游玩天数'));
        }
      setTimeout(() => {
        if (isNaN(Number(value))) {
          callBack(new Error('请输入数字值'));
        } else {
          if (Number(value) <= 0) {
            callBack(new Error('天数必须大于零'));
          } else {
            callBack();
          } 
        }
      }, 100);
    }
    return {
      wrapper_loading: false,
      wrapper_loading_form: false,
      AddDestinationDialog: false,
      destinationDialogBtnKey: '',
      addDestinationDialogForm: {
        name: '',
        location: '',
        days: '',
        type: '',
        singlePeople: {
          normalPrice: '',
          specialPrice: ''
        },
        group: {
          value: 0,
          price: null
        },
        company: {
          value: 0,
          price: null
        },
        guide: {
          value: 0,
          price: null
        },
        description: '',
        aggregateLocation: ''
      },
      addDestinationDialogFormOld: {

      },
      rules: {
          name: [
            { required: true, message: '请输入景点名称', trigger: 'blur' }
          ],
          location: [
            { required: true, message: '请输入景点位置', trigger: 'blur' },
            {  validator: checkLocation, trigger: 'blur'}
          ],
          days: [
            { required: true, message: '请输入游玩天数', trigger: 'blur' },
            {  validator: checkDays, trigger: 'blur'}
          ],
          type: [
            { required: true, message: '请输入景点类型', trigger: 'blur' }
          ],
          'singlePeople.normalPrice': [
            { required: true, message: '请输入标准价格', trigger: 'blur' },
            {  validator: checkNormalPrice, trigger: 'blur'}
          ],
          'singlePeople.specialPrice': [
            { required: true, message: '请输入特殊价格', trigger: 'blur' },
            {  validator: checkSpecialprice, trigger: 'blur' }
          ],
          description: [
            { required: true, message: '请输入景点简介', trigger: 'blur' }
          ]
      },
      getDataKey: true,
      tableData: []
    }
  },
  computed: {
    tableDataCoupted() {
      const res = []
      this.tableData.forEach(item_tableData =>  {
        let id = item_tableData.id
        let name = item_tableData.name
        let location = item_tableData.location
        let days = item_tableData.days + '天'
        let type;
        switch(item_tableData.type){
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
        let normalPrice = item_tableData.normalPrice + '元'
        let specialPrice = item_tableData.specialPrice + '元'
        let group = item_tableData.group ? item_tableData.groupPrice + '折' : '无折扣'
        let company = item_tableData.company ? item_tableData.companyPrice + '折' : '无折扣'
        let aggregateLocation = item_tableData.aggregateLocation ? item_tableData.aggregateLocation : '无'
        let guide = item_tableData.guide ? item_tableData.guidePrice + '元' : '无导游服务'
        let hot = item_tableData.hot
        let description = item_tableData.description
        let picture = item_tableData.picture ? item_tableData.picture : null
        res.push({
          id,
          name,
          location,
          days,
          type,
          normalPrice,
          specialPrice,
          group,
          company,
          guide,
          hot,
          description,
          picture,
          aggregateLocation
        })
      })
      return res
    }
  },
  methods: {
    toAddDestination() {
      this.addDestinationDialogForm = {
        name: '',
        location: '',
        days: '',
        type: '',
        singlePeople: {
          normalPrice: '',
          specialPrice: ''
        },
        group: {
          value: 0,
          price: ''
        },
        company: {
          value: 0,
          price: ''
        },
        guide: {
          value: 0,
          price: ''
        },
        description: '',
        aggregateLocation: ''
      }
      this.AddDestinationDialog = true
      this.destinationDialogBtnKey = 'add'
    },
    addDestination(addDestinationDialogForm) {
      this.$refs.addDestinationDialogForm.validate((valid) => {
        if (valid) {
          if(this.addDestinationDialogForm.group.value  && this.addDestinationDialogForm.group.price === ''){
            this.addDestinationDialogForm.group.value = 0
          }
          if(this.addDestinationDialogForm.company.value && this.addDestinationDialogForm.company.price === ''){
            this.addDestinationDialogForm.company.value = 0
          }
          if(this.addDestinationDialogForm.guide.value && this.addDestinationDialogForm.guide.price === ''){
            this.addDestinationDialogForm.guide.value = 0
          }
          if(!this.addDestinationDialogForm.group.value  && this.addDestinationDialogForm.group.price !== ''){
            this.addDestinationDialogForm.group.price = ''
          }
          if(!this.addDestinationDialogForm.company.value && this.addDestinationDialogForm.company.price !== ''){
            this.addDestinationDialogForm.company.price = ''
          }
          if(!this.addDestinationDialogForm.guide.value && this.addDestinationDialogForm.guide.price !== ''){
            this.addDestinationDialogForm.guide.price = ''
          }
          if(!this.addDestinationDialogForm.group.value && !this.addDestinationDialogForm.company.value && this.addDestinationDialogForm.aggregateLocation !== ''){
            this.addDestinationDialogForm.aggregateLocation = ''
          }
          if(this.addDestinationDialogForm.group.value && this.addDestinationDialogForm.group.price !== '' && !this.addDestinationDialogForm.aggregateLocation){
            this.$message({
              message: '请输入集合时间与地点',
              type: 'error'
            })
            return
          }
          if(this.addDestinationDialogForm.company.value && this.addDestinationDialogForm.company.price !== '' && this.addDestinationDialogForm.aggregateLocation === ''){
            this.$message({
              message: '请输入集合时间与地点',
              type: 'error'
            })
            return
          }
          if(this.addDestinationDialogForm.group.price !== '' && (this.addDestinationDialogForm.group.price > 10 || this.addDestinationDialogForm.group.price < 1)){
            this.$message({
              message: '折扣输入有误',
              type: 'error'
            })
            return
          }
          if(this.addDestinationDialogForm.company.price !== '' && (this.addDestinationDialogForm.company.price > 10 || this.addDestinationDialogForm.company.price < 1)){
            this.$message({
              message: '折扣输入有误',
              type: 'error'
            })
            return
          }
          if(this.addDestinationDialogForm.guide.price !== ''){
            if (isNaN(Number(this.addDestinationDialogForm.guide.price))) {
              this.$message({
                message: '导游价钱不能为中文',
                type: 'error'
              })
              return
            } else {
              if (Number(this.addDestinationDialogForm.guide.price) < 0) {
                this.$message({
                  message: '导游价钱不能为负数',
                  type: 'error'
                })
                return
              }
            }
          }
          if(this.destinationDialogBtnKey === 'add'){
            const paramsObj = {
              name: this.addDestinationDialogForm.name,
              location: this.addDestinationDialogForm.location,
              type: this.addDestinationDialogForm.type,
              days: this.addDestinationDialogForm.days,
              normalPrice: this.addDestinationDialogForm.singlePeople.normalPrice,
              specialPrice: this.addDestinationDialogForm.singlePeople.specialPrice,
              groupValue: this.addDestinationDialogForm.group.value,
              groupPrice: this.addDestinationDialogForm.group.price,
              companyValue: this.addDestinationDialogForm.company.value,
              companyPrice: this.addDestinationDialogForm.company.price,
              guideValue: this.addDestinationDialogForm.guide.value,
              guidePrice: this.addDestinationDialogForm.guide.price,
              description: this.addDestinationDialogForm.description,
              aggregateLocation: this.addDestinationDialogForm.aggregateLocation
            }
            this.wrapper_loading_form = true
            this.$axios
              .post('http://localhost/tp5/public/index.php/index/Destination/add', paramsObj)
              .then(res => {
                if(res.status === 200 && res.statusText === 'OK' && res.data.status ===1){
                  this.wrapper_loading_form = false
                  this.$message({
                    message: '新建景点成功',
                    type: 'success'
                  })
                  this.AddDestinationDialog = false
                  this.refresh()
                }else{
                  this.wrapper_loading_form = false
                  this.$message({
                    message: '新建景点失败',
                    type: 'error'
                  })
                  this.AddDestinationDialog = false
                }
              })
              .catch(err => {
                this.wrapper_loading_form = false
                  this.$message({
                    message: '调用api失败',
                    type: 'error'
                  })
                  this.AddDestinationDialog = false
              })
          }else if (this.destinationDialogBtnKey === 'edit'){
              if(this.addDestinationDialogForm.group.price === '') {
                this.addDestinationDialogForm.group.price = null
              }
              if(this.addDestinationDialogForm.company.price === '') {
                this.addDestinationDialogForm.company.price = null
              }
              if(this.addDestinationDialogForm.guide.price === '') {
                this.addDestinationDialogForm.guide.price = null
              }
              switch(this.addDestinationDialogForm.type){
                case '历史古迹':
                  this.addDestinationDialogForm.type = 1
                  break
                case '大好河山':
                  this.addDestinationDialogForm.type = 2
                  break
                case '阳光沙滩':
                  this.addDestinationDialogForm.type = 3
                  break
                case '自然奇观':
                  this.addDestinationDialogForm.type = 4
                  break
                case '野生动物':
                  this.addDestinationDialogForm.type = 5
                  break
                case '辽阔草原':
                  this.addDestinationDialogForm.type = 6
                  break
              }
              let key = true
              if(
                this.addDestinationDialogForm.name == this.addDestinationDialogFormOld.name &&
                this.addDestinationDialogForm.location == this.addDestinationDialogFormOld.location &&
                this.addDestinationDialogForm.type == this.addDestinationDialogFormOld.type &&
                this.addDestinationDialogForm.singlePeople.normalPrice == this.addDestinationDialogFormOld.singlePeople.normalPrice &&
                this.addDestinationDialogForm.singlePeople.specialPrice == this.addDestinationDialogFormOld.singlePeople.specialPrice &&
                this.addDestinationDialogForm.group.value == this.addDestinationDialogFormOld.group.value &&
                this.addDestinationDialogForm.group.price == this.addDestinationDialogFormOld.group.price &&
                this.addDestinationDialogForm.company.value == this.addDestinationDialogFormOld.company.value &&
                this.addDestinationDialogForm.company.price == this.addDestinationDialogFormOld.company.price &&
                this.addDestinationDialogForm.guide.value == this.addDestinationDialogFormOld.guide.value &&
                this.addDestinationDialogForm.guide.price == this.addDestinationDialogFormOld.guide.price &&
                this.addDestinationDialogForm.description == this.addDestinationDialogFormOld.description &&
                this.addDestinationDialogForm.days == this.addDestinationDialogFormOld.days &&
                this.addDestinationDialogForm.aggregateLocation == this.addDestinationDialogFormOld.aggregateLocation 
              ){
                key = false
                switch(this.addDestinationDialogForm.type){
                  case 1:
                    this.addDestinationDialogForm.type = '历史古迹'
                    break
                  case 2:
                    this.addDestinationDialogForm.type = '大好河山'
                    break
                  case 3:
                    this.addDestinationDialogForm.type = '阳光沙滩'
                    break
                  case 4:
                    this.addDestinationDialogForm.type = '自然奇观'
                    break
                  case 5:
                    this.addDestinationDialogForm.type = '野生动物'
                    break
                  case 6:
                    this.addDestinationDialogForm.type = '辽阔草原'
                    break
                }
              }
              if(key){
                const paramsObj = {
                  id: this.addDestinationDialogFormOld.id,
                  name: this.addDestinationDialogForm.name,
                  location: this.addDestinationDialogForm.location,
                  days: this.addDestinationDialogForm.days,
                  type: this.addDestinationDialogForm.type,
                  normalPrice: this.addDestinationDialogForm.singlePeople.normalPrice,
                  specialPrice: this.addDestinationDialogForm.singlePeople.specialPrice,
                  groupValue: this.addDestinationDialogForm.group.value,
                  groupPrice: this.addDestinationDialogForm.group.price,
                  companyValue: this.addDestinationDialogForm.company.value,
                  companyPrice: this.addDestinationDialogForm.company.price,
                  guideValue: this.addDestinationDialogForm.guide.value,
                  guidePrice: this.addDestinationDialogForm.guide.price,
                  description: this.addDestinationDialogForm.description,
                  aggregateLocation: this.addDestinationDialogForm.aggregateLocation
                }
                this.wrapper_loading_form = true
                this.$axios
                .post('http://localhost/tp5/public/index.php/index/Destination/upData', paramsObj)
                .then(res => {
                  if(res.status === 200 && res.statusText === 'OK' && res.data.status ===1){
                    
                    this.wrapper_loading_form = false
                    this.$message({
                      message: '修改成功',
                      type: 'success'
                    })
                    this.refresh()
                    this.AddDestinationDialog = false
                  }else{
                    this.wrapper_loading_form = false
                    this.$message({
                      message: '修改失败',
                      type: 'error'
                    })
                  }
                })
                .catch(err => {
                  this.wrapper_loading_form = false
                  this.$message({
                      message: '修改失败',
                      type: 'error'
                    })
                })
              }else{
                this.$message({
                  message: '未检测到修改',
                  type: 'error'
                })
              }
            }
        } else {
          return false
        }
      });
    },
    getDestination(){
      if(this.getDataKey) {
        this.wrapper_loading = true
        this.$axios
          .get('http://localhost/tp5/public/index.php/index/Destination/all')
          .then(res => {
            if(res.status === 200 && res.statusText === 'OK' && res.data.status ===1){
              const destinationArr = []
              for(let destination_item of Object.values(res.data.data.destinations)){
                for(let price_item of Object.values(res.data.data.price)){
                  if(price_item.id === destination_item.id){
                    destinationArr.push(Object.assign(destination_item,price_item))
                  }
                }
              }
              this.tableData = destinationArr
              this.$message({
                message: `获取景点数据成功，共${destinationArr.length}条`,
                type: 'success'
              })
            }else{
              this.$message({
                message: `获取景点失败`,
                type: 'error'
              })
            }
            this.wrapper_loading = false
            this.getDataKey = false
          })
          .catch(err => {
            this.$message({
              message: `获取景点失败`,
              type: 'error'
            })
            this.wrapper_loading = false
            this.getDataKey = false
          })
      }
    },
    refresh(){
      this.getDataKey = true
      this.getDestination()
    },
    table_edit(id){
      this.AddDestinationDialog = true
      let target;
      this.tableData.forEach(value => {
        if(value.id === id){
          target = value
        }
      })
      this.addDestinationDialogFormOld = {
        id: id,
        name: target.name,
        location: target.location,
        days: target.days,
        type: target.type,
        singlePeople: {
          normalPrice: target.normalPrice,
          specialPrice: target.specialPrice
        },
        group: {
          value: target.group,
          price: target.groupPrice
        },
        company: {
          value: target.company,
          price: target.companyPrice
        },
        guide: {
          value: target.guide,
          price: target.guidePrice
        },
        description: target.description,
        aggregateLocation: target.aggregateLocation
      }
      let type;
      switch(target.type){
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
      }
      this.addDestinationDialogForm = {
        id: id,
        name: target.name,
        location: target.location,
        days: target.days,
        type: type,
        singlePeople: {
          normalPrice: target.normalPrice,
          specialPrice: target.specialPrice
        },
        group: {
          value: target.group,
          price: target.groupPrice
        },
        company: {
          value: target.company,
          price: target.companyPrice
        },
        guide: {
          value: target.guide,
          price: target.guidePrice
        },
        description: target.description,
        aggregateLocation: target.aggregateLocation
      }
      
      this.destinationDialogBtnKey = 'edit'
    },
    uploadSuccess(){
      this.$message({
        message: '上传成功',
        type: 'success'
      })
      this.refresh()
    },
    uploadFail(){
      this.$message({
        message: '上传失败',
        type: 'error'
      })
      this.refresh()
    }
  }
}
</script>

<style>
.user_wrapper .el-dialog__wrapper .addDestination-dialog{
  margin-bottom: 0;
}
.addDestination-dialog .el-dialog__header{
  padding: 0;
}
.addDestination-dialog .el-dialog__body{
  padding: 10px 10px 0 10px;
}
.addDestination-dialog .el-dialog__footer{
  padding-top: 0
}
.addDestination-dialog .el-dialog__body .title{
  padding-bottom: 10px;
}
.addDestination-dialog .el-form .el-form-item{
  margin-bottom: 15px;
}
.addDestination-dialog .el-form .el-form-item .el-form-item__label{
  font-size: 10px;
  line-height: 20px;
}
.addDestination-dialog .el-form .el-form-item .el-form-item__content{
  height: 20px;
  line-height: 20px;
}
.addDestination-dialog .el-form .el-form-item .el-input,
.addDestination-dialog .el-form .el-form-item .el-input .el-input__inner{
  height: 100%;
  font-size: 10px;
}
.addDestination-dialog .el-form .el-form-item .el-input--suffix .el-input__inner{
  height: 20px;
  line-height: 20px;
}
.addDestination-dialog .el-form .el-form-item.description .el-form-item__content{
  height: 70px;
}
.addDestination-dialog .el-form .el-form-item .el-textarea textarea{
  height: 70px;
  min-height: 70px;
  max-height: 70px;
  font-size: 10px;
}
.addDestination-dialog .el-form-item .el-input__suffix .el-icon-arrow-up{
  line-height: 20px;
}
.addDestination-dialog .el-form-item .el-input__suffix .el-icon-arrow-up::before{
  height: 20px;
  line-height: 20px;
  display: inline-block;
}
.admin-desnation-wrapper ul li.el-select-dropdown__item{
  height: 20px;
  line-height: 20px;
  font-size: 10px;
}
.addDestination-dialog .el-form-item__error{
  font-size: 10px;
  line-height: 15px;
}
</style>
<style scoped>
.header-add{
  text-align: right;
  padding: 5px 20px 5px 0;
}
.header-add .el-button--small.is-circle{
  padding: 3px;
}
.addDestination-dialog .el-button--danger{
  padding: 5px;
  font-size: 10px;
}
</style>