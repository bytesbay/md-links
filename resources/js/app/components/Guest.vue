<template lang="html">
  <div class="centered">
    <router-view id="content"></router-view>
  </div>
</template>

<script>
export default {
  components: {
    // 'modal-registration': Registration,
    // 'modal-recover': RecoverPass,
  },
  created() {

  }
}
</script>

<style lang="scss" scoped>

@import '~@/vars.scss';

#content {
  width: 500px;
  height: auto;
  box-shadow: $shadow;
  border-radius: 20px;
}
.centered {
  width: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
}

</style>
