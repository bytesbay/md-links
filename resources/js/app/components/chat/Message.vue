<template lang="html">
  <div
    class="msg-cnt is-clearfix"
    v-bind:class="{
      'right-side': $store.state.user.id_user == user.id_user,
      'left-side': $store.state.user.id_user != user.id_user,
    }"
    @mousedown="onMouseDown"
    @mouseup="onMouseUp"
  >
    <router-link v-if="last" :to="link">
      <div class="avatar bg-img" v-bind:style="{ backgroundImage: `url(${user.img})` }"></div>
    </router-link>

    <transition
        enter-active-class="animated fadeInRight"
        leave-active-class="animated fadeOutRight"
    >
      <button v-if="selected" class="selected-check">
        <i class="icon ion-ios-checkmark"></i>
      </button>
    </transition>

    <div class="message">

      <div @click="$parent.findMessage(data.id_reply)" v-if="data.type === 'rpl'" class="reply-source elipsed">
        <p>{{ replied }}</p>
      </div>

      <button @click="$emit('reply', data)" class="reply-btn bg-img"></button>
      <div class="date">{{ formattedDate }}</div>

      <router-link class="forward-from" v-if="data.type === 'fwd'" :to="link">
        <p>From: {{ data.from.name }}</p>
      </router-link>

      <span>{{ data.content }}</span>

      <div v-if="data.data_uri" class="paint" v-bind:class="{ 'pad-top': data.content.length }">
        <img :src="data.data_uri" width="300" height="300">
      </div>

      <div v-if="data.files" class="files" v-bind:class="{ 'pad-top': data.content.length || data.paint }">
        <p>Files:</p>
        <div class="list">
          <a :download="file.name" v-for="file in data.files" :href="file.url">
            <div class="item">
              <svg enable-background="new 0 0 512 512" version="1.1" viewBox="0 0 512 512" xml:space="preserve" xmlns="http://www.w3.org/2000/svg">
              	<path fill="#604FE9" class="st0" d="m365.6 285.9h-325.3v133.9h276.3l49-133.9zm-217.5 101.7h-44v-70h43.7v9.4h-32v19.8h27.4v9.4h-27.4v22.2h32.3v9.2zm52.8 0l-15.2-26.3-15.2 26.3h-13.8l22-35.3-21.5-34.7h13.8l14.6 25.9 14.8-25.9h13.8l-21.5 34.7 22.4 35.3h-14.2zm70.2 0h-44v-70h43.7v9.4h-32v19.8h27.4v9.4h-27.4v22.2h32.3v9.2z"/>
              	<path fill="#604FE9" class="st0" d="m352.9 6h-245.5c-7.3 0-13.3 6-13.3 13.3v256.8h26.6v-243.5h232.2v92.2h92.2v354.6h-324.4v-49.8h-26.6v63.1c0 7.3 6 13.3 13.3 13.3h351c7.3 0 13.3-6 13.3-13.3v-367.9l-118.8-118.8z"/>
              	<rect fill="#604FE9" class="st0" x="156.2" y="171.4" width="253.4" height="19.6"/>
              	<rect fill="#604FE9" class="st0" x="156.2" y="209.5" width="213.3" height="19.6"/>
              </svg>
              <div class="name">{{ file.name }}</div>
            </div>
          </a>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'message',
  props: {
    data: {},
    user: {},
    last: {
      default: true,
    },
    selected: false,
  },
  data() {
    return {
      timer: null,
      ignore_next_click: false,
    };
  },
  methods: {
    onMouseDown() {
      if(!this.$parent.selected.length) {
        this.timer = setTimeout(() => {
          this.$parent.reply = null;
          document.removeEventListener('keyup', this.closeSelection);
          this.ignore_next_click = true;
          this.$parent.selected.push(this.data.id_message);
          document.addEventListener('keyup', this.closeSelection);
        }, 500);
      }
    },
    onMouseUp() {
      if(this.ignore_next_click) {
        this.ignore_next_click = false;
        return;
      }

      if(!this.$parent.selected.length) {
        clearTimeout(this.timer);
      } else {
        if(!this.selected) {
          this.$parent.selected.push(this.data.id_message);
        } else {
          this.$parent.selected = this.$parent.selected.filter(n => {
            return n != this.data.id_message;
          });
        }
      }
    },

    closeSelection(e) {
      if(e.which == 27) {
        this.$parent.selected = [];
        document.removeEventListener('keyup', this.closeSelection);
      }
    }
  },
  computed: {
    formattedDate() {
      var date = new Date(this.data.date * 1000);
      var hours = date.getHours();
      var minutes = "0" + date.getMinutes();
      return hours + ':' + minutes.substr(-2);
    },
    link() {
      return '/profile/' + this.user.id_user;
    },
    replied() {
      if(this.data.reply_source.length) {
        return this.data.reply_source;
      } else {
        return 'Replied message';
      }

    }
  }
}
</script>

<style lang="scss" scoped>

@import '~@/vars.scss';

@keyframes lightSearched {
  0% {
    background: transparentize($clr-l-blue, 0.1);
  }
  100% {
    background: transparent;
  }
}

.msg-cnt {
  padding: 15px 0;
  position: relative;
  .selected-check {
    right: -30px;
    position: absolute;
    top: 50%;
    width: 20px;
    height: 20px;
    border-radius: 10px;
    margin-top: -10px;
    border: 1px solid $clr-d-blue;
    font-size: 18px;
    line-height: 18px;
    padding: 0;
    color: $clr-d-blue;
  }
  &.searched {
    animation: lightSearched 2s ease;
  }
  .avatar {
    width: 50px;
    height: 50px;
    border-radius: 25px;
    background: $clr-gray;
    position: absolute;
    bottom: 15px;
  }
  .message {
    padding: 20px;
    border-radius: 15px;
    font-weight: 300;
    font-size: 14px;
    position: relative;
    max-width: 600px;
    .reply-source {
      width: 100%;
      cursor: pointer;
      border-left: 2px solid $clr-d-pink;
      padding-left: 10px;
      font-size: 14px;
      margin-bottom: 10px;
      p {
        margin: 0;
        width: 100%;
      }
    }
    .forward-from {
      p {
        margin: 0;
        font-weight: 500;
        margin-top: -15px;
      }
    }
    .reply-btn {
      position: absolute;
      top: 50%;
      width: 30px;
      height: 30px;
      margin-top: -15px;
      border-radius: 15px;
      border: 1px solid #ddd;
      display: none;
      background-image: $i-reply;
      background-size: 15px;
      background-color: #fff;
    }
    .paint {
      img {
        border-radius: 5px;
        vertical-align: middle;
      }
      &.pad-top {
        padding-top: 10px;
      }
    }
    .date {
      position: absolute;
      top: 50%;
      transform: translateY(-50%);
      color: #808080;
      font-size: 12px;
      display: block;
    }
    .files {
      &.pad-top {
        padding-top: 10px;
      }
      p {
        float: left;
        width: 55px;
        height: 30px;
        font-size: 14px;
        color: #464646;
        font-weight: 600;
        margin: 0;
        line-height: 30px;
      }
      .list {
        margin-left: 55px;
        a {
          .item {
            height: 30px;
            font-size: 14px;
            font-weight: 600;
            color: $clr-d-pink;
            text-decoration: underline;
            line-height: 30px;
            margin-bottom: 10px;

            svg {
              display: block;
              height: 30px;
              width: 30px;
              float: left;
            }
            .name {
              margin-left: 35px;
            }
          }
          &:last-child {
            .item {
              margin-bottom: 0;
            }
          }
        }
      }
    }
  }
  &:hover {
    .reply-btn {
      display: block;
    }
    .date {
      display: none;
    }
  }
  &.right-side {
    .avatar {
      right: 0;
    }
    .message {
      float: right;
      margin-right: 65px;
      background-color: $clr-pink;
      border-bottom-right-radius: 0;
      .date {
        left: -50px;
      }
      .reply-btn {
        left: -50px;
      }
    }
  }
  &.left-side {
    .avatar {
      left: 0;
    }
    .message {
      margin-left: 65px;
      float: left;
      background-color: $clr-l-blue;
      border-bottom-left-radius: 0;
      .date {
        right: -50px;
      }
      .reply-btn {
        right: -50px;
      }
    }
  }
}

</style>
