<template lang="html">
  <transition
    enter-active-class="animated fadeIn"
    leave-active-class="animated fadeOut"
  >
    <div v-if="$store.state.chat.forward.toggled" class="forward-cover">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
        <path d="M479.9 394.9c0-19.6 4.2-97.1-56.8-158.7-40.4-40.7-91.9-61.7-163.4-65.5-2.1-.1-3.8-1.9-3.8-4V84c0-3.2-3.5-5.1-6.2-3.4L33.8 222.8c-2.4 1.6-2.4 5.1 0 6.7l215.9 142.2c2.7 1.8 6.2-.1 6.2-3.4v-81.6c0-2.3 1.9-4.1 4.2-4 44.1 1.7 69.5 10.9 97.1 23.2 36.1 16.2 72.9 50.9 94.5 83.5 13.1 19.9 19.2 33.9 21.4 39.7.7 1.7 2.3 2.8 4.1 2.8h2.9c-.1-11.7-.2-26.7-.2-37z"/>
      </svg>
      <p class="header">Choose chat to forward message</p>
      <p class="cancel">Press <span>ESC</span> to cancel</p>
    </div>
  </transition>
</template>

<script>
export default {
  name: 'forward-cover',
  computed: {

  },
}
</script>

<style lang="scss" scoped>

@import '~@/vars.scss';

.forward-cover {
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
  svg {
    width: 100px;
    margin-bottom: 20px;
    height: auto;
    fill: $clr-d-blue;
  }
  .header {
    color: $clr-d-blue;
    margin: 0;
  }
  .cancel {
    font-size: 12px;
    color: $clr-d-blue;
    span {
      border: 1px solid $clr-d-blue;
      border-radius: 7px;
      padding: 0 4px;
      font-weight: bold;
      margin: 0 2px;
    }
  }
}
</style>
