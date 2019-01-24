<template lang="html">
  <div class="start-chat">
    <button v-if="!is_clicked" @click="startChat">Start chat</button>
  </div>
</template>

<script>
export default {
  name: 'start-chat',
  props: {
    chat: {
      required: true,
    }
  },
  data() {
    return {
      is_clicked: false,
    };
  },
  methods: {
    startChat() {
      this.is_clicked = true;
      axios.post('/chat', {
        id_user: this.chat.id_user,
      }).then(res => {
        this.$emit('started');
      });
    }
  },
}
</script>

<style lang="scss" scoped>

@import '~@/vars.scss';

.start-chat {
  cursor: default;
  user-select: none;
  position: fixed;
  width: calc(100% - 420px);
  height: calc(100% - 80px);
  top: 80px;
  right: 0;
  padding: 0;
  z-index: 90;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  button {
    padding: 10px 20px;
    border-radius: 10px;
    font-weight: 700;
    font-size: 20px;
    letter-spacing: 0;
    text-transform: uppercase;
    color: $clr-d-blue;
    background: transparent;
    border: 2px solid $clr-d-blue;
    transition: color 0.3s ease;
  }
}

</style>
