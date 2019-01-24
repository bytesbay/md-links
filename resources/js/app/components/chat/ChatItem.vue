<template lang="html">
  <div
    @click="onClick"
    class="chat-item"
    v-bind:class="{
      'selected': selected,
      'is-search': data.chat.is_global
    }"
  >
    <div class="user">
      <div class="avatar bg-img" v-bind:style="{ backgroundImage: `url(${data.chat.img})` }"></div>
      <p class="name">{{ data.chat.name }}</p>
    </div>
    <template v-if="!data.chat.is_global">
      <p class="msg">
        {{ slicedText }}
      </p>
      <div class="info">
        <div v-if="data.new_messages" class="missed">
          {{ data.new_messages }}
        </div>
        <div class="time">
          {{ formattedDate }}
        </div>
      </div>
    </template>
  </div>
</template>

<script>
export default {
  name: 'chat-item',
  props: {
    data: {
      required: true
    },
    selected: {
      default: false,
      type: Boolean
    },
  },
  methods: {
    onClick() {
      this.$emit('click');
      this.data.new_messages = 0;
    },
  },
  computed: {
    formattedDate() {
      if(this.data.date) {
        var date = new Date(this.data.date * 1000);
        var hours = date.getHours();
        var minutes = "0" + date.getMinutes();
        return hours + ':' + minutes.substr(-2);
      }
    },
    slicedText() {
      if(!this.data.id_reply) {
        if(this.data.content)
          return this.data.content.length > 50
            ? this.data.content.slice(0, 50) + '...'
            : this.data.content;
      } else {
        return 'Forwarded message';
      }
    }
  }
}
</script>

<style lang="scss" scoped>

@import '~@/vars.scss';

.chat-item {
  height: 120px;
  border-bottom: 1px solid $clr-gray;
  cursor: pointer;
  padding: 20px;
  padding-right: 60px;
  position: relative;
  &.is-search {
    height: 80px;
  }
  &.selected {
    background: transparentize($clr-blue, 0.9);
    // &::before {
    //   background: #fff;
    //   top: 10%;
    //   right: -2px;
    //   content: '';
    //   height: 80%;
    //   width: 8px;
    //   position: absolute;
    //   border-radius: 8px 0 0 8px;
    // }
  }
  .user {
    .avatar {
      width: 40px;
      height: 40px;
      border-radius: 20px;
      background: $clr-gray;
      float: left;
    }
    .name {
      font-weight: bold;
      font-size: 15px;
      height: 40px;
      line-height: 40px;
      margin: 0;
      margin-left: 50px;
      text-overflow: ellipsis;
      overflow: hidden;
      white-space: nowrap;
      color: $clr-black;
    }
  }
  .msg {
    font-size: 13px;
    font-weight: 300;
    color: #999999;
    margin: 0;
    margin-top: 10px;
  }
  .info {
    .missed {
      position: absolute;
      bottom: 20px;
      right: 20px;
      width: 20px;
      height: 20px;
      border-radius: 10px;
      background-color: #e1e1e1;
      font-size: 12px;
      font-weight: 500;
      color: #fff;
      text-align: center;
      line-height: 20px;
    }
    .time {
      position: absolute;
      font-weight: 300;
      font-size: 15px;
      right: 20px;
      top: 20px;
      height: 40px;
      line-height: 40px;
      color: #c0c4cd;
    }
  }
}

</style>
