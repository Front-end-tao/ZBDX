<template>
  <div class="lt-components-wrapper input">
		<el-input v-model="iptValue"
      :placeholder="placeholder"
      :show-password="passKey"
      :prefix-icon="icon"
      clearable
      @input="sendIptValue"
    >
      <el-button slot="append" v-if="btnConfig.isActive" @click="btnClick">{{ btnConfig.btnMessage }}</el-button>
		</el-input>
		<div class="error-message">{{ errorMessage }}</div>
  </div>
</template>

<script>
export default {
  props: {
    placeholder: {
      type: String,
      default: ''
    },
    passKey: {
      type: Boolean,
      default: false
    },
    errorMessage: {
      type: String,
      default: ''
    },
    icon: {
      type: String,
      default: ''
    },
    btnConfig: {
      type: Object,
      default: () => {
        return {
          isActive: false,
          btnMessage: ''
        }
      }
    }
  },
  data() {
    return {
      iptValue: ''
    }
  },
  methods: {
    sendIptValue() {
      this.$emit('getIptValue', this.iptValue)
    },
    btnClick() {
      this.$emit('btnClick')
    }
  }
}
</script>

<style>
.lt-components-wrapper.input .error-message{
	height: 40px;
  font-size: 12px;
	line-height: 40px;
  color: red;
}
</style>