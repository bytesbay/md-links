<template lang="html">
  <transition
    enter-active-class="animated fadeIn"
    leave-active-class="animated fadeOut"
  >
    <div class="dropzone">
      <i class="icon ion-ios-cloud-upload"></i>
      <p class="header">Drop files here</p>
      <p class="cancel">to upload them</p>
    </div>
  </transition>
</template>

<script>
export default {
  name: 'dropzone',
  methods: {

  }
}
</script>

<style lang="scss" scoped>

@import '~@/vars.scss';

.dropzone {
  pointer-events: none;
  cursor: default;
  user-select: none;
  position: fixed;
  width: calc(100% - 420px);
  height: calc(100% - 80px);
  top: 80px;
  right: 0;
  padding: 0;
  background: transparentize(#fff, 0.1);
  backdrop-filter: blur(15px);
  z-index: 90;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  i {
    font-size: 80px;
    margin-bottom: 0px;
    height: auto;
    color: $clr-d-blue;
  }
  .header {
    color: $clr-d-blue;
    margin: 0;
  }
  .cancel {
    margin: 0;
    font-size: 12px;
    color: transparentize($clr-d-blue, 0.5);
  }
}

</style>
