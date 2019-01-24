<template lang="html">
  <section v-bind:class="{
    'reply-on': reply,
    'selection-on': selected.length,
  }">
    <div class="dropwrapper" ref="dropzone">
      <dropzone v-if="dragover"/>
      <chat-header @search="search" :chat="chat"/>
      <div class="messages">
        <message
          v-for="(item, i) in messages"
          ref="messages"
          :user="getAddresser(item.id_addresser)"
          :last="isLast(i)"
          :data="item"
          :key="i"
          :selected="selected.includes(item.id_message)"
          @reply="onReply"
          v-bind:class="{ searched: item.id_message == searching_message }"
        />
      </div>
      <writer ref="writer" :reply="reply" :chat="chat"/>
    </div>
  </section>
</template>

<script>

import Message from './Message.vue'
import Writer from './Writer.vue'
import ChatHeader from './ChatHeader.vue';
import DropZone from './DropZone.vue'

export default {
  name: 'chat',
  props: {
    chat: {
      required: true
    }
  },
  data() {
    return {
      is_far: false,
      searching_message: 0,
      group_users: [],
      messages: [],
      interval: null,
      invite_user: '',
      chat_changed: false,
      dragAndDropCapable: false,
      root_chunk: 0,
      chunk: 0,
      reply: null,
      selected: [],
      dragover: false,
      dragover_ready: false,
    };
  },
  methods: {
    onReply(data) {
      document.removeEventListener('keyup', this.closeReply);
      this.reply = data;
      if(data.data_uri) {
        var img = new Image();
        img.src = data.data_uri;
        img.onload = () => {
          this.$refs.writer.openPainter();
          this.$refs.writer.$refs.painter.renderImage(img);
        };
      }
      this.selected = [];
      document.addEventListener('keyup', this.closeReply);
    },
    closeReply(e) {
      if(e.which == 27) {
        this.reply = null;
        document.removeEventListener('keyup', this.closeReply);
      }
    },
    determineDragAndDropCapable() {
      var div = document.createElement('div');
      return ( ( 'draggable' in div )
        || ( 'ondragstart' in div && 'ondrop' in div ) )
        && 'FormData' in window
        && 'FileReader' in window;
    },
    search(value) {
      this.searching_message = 0;
      if(value) {
        axios.get('/chat/' + this.chat.id_chat + '/search/' + value).then(res => {
          if(res.data.messages.length) {
            this.is_far = true;
            this.messages = res.data.messages;
            this.searching_message = res.data.id_message;
            this.$nextTick(() => {
              this.findMessage(this.searching_message);
            });
          }
        });
      } else {
        this.is_far = false;
      }
    },
    isLast(index) {
      if(!this.messages[index + 1]) return true;
      return this.messages[index].id_addresser != this.messages[index + 1].id_addresser;
    },
    getAddresser(id) {
      if(id == this.$store.state.user.id_user)
        return this.$store.state.user;

      if(this.chat.is_group) {
        return this.group_users.find(n => n.id_user == id);
      } else {
        return this.chat;
      }
    },
    findMessage(id_message) {
      this.$refs.messages.forEach(n => {
        if(n.data.id_message == id_message) {
          document.scrollingElement.scrollTop = n.$el.offsetTop - 80;
        }
      });
    },
    getUsers(callback) {
      axios.get('/group/' + this.chat.id_chat + '/users').then(res => {
        this.group_users = res.data;
        if(callback) callback();
      });
    },
    sync() {
      axios.get('/chat/' + this.chat.id_chat + '/messages').then(this.onSyncResponce);
    },
    onPrimaryResponce(res) {
      this.messages = res.data;
      this.$nextTick(() => {
        window.scrollTo(0, document.body.scrollHeight);
      });
      this.chat_changed = false;
    },
    onSyncResponce(res) {
      if(this.chat_changed || this.is_far) return;

      if(this.messages.length) {
        var offset = 0;
        for (var i = res.data.length - 1; i >= 0; i--) {
          if(this.messages[this.messages.length - 1].id_message == res.data[i].id_message) {
            offset = (res.data.length - 1) - i;
            break;
          }
        }
        if(offset != 0) {
          this.messages.push(...res.data.slice(-offset));
          this.$nextTick(() => {
            window.scrollTo(0, document.body.scrollHeight);
          });
          this.$emit('change');
        }
      } else {
        this.messages = res.data;
        this.$nextTick(() => {
          window.scrollTo(0, document.body.scrollHeight);
        });
      }
    }
  },
  watch: {
    chat(to, from) {
      this.chat_changed = true;
      this.messages = [];
      this.selected = [];
      this.reply = null;
      if(this.chat.is_group) {
        if(to.id_chat != from.id_chat)
          clearInterval(this.interval);

        if(to.id_chat) {
          this.getUsers(() => {
            axios.get('/chat/' + this.chat.id_chat + '/messages').then(this.onPrimaryResponce);
            this.interval = setInterval(() => this.sync(), 1000);
          });
        } else {
          clearInterval(this.interval);
        }

      } else {
        if(to.id_chat != from.id_chat)
          clearInterval(this.interval);

        if(to.id_chat) {
          axios.get('/chat/' + this.chat.id_chat + '/messages').then(this.onPrimaryResponce);
          this.interval = setInterval(() => this.sync(), 1000);
        } else {
          clearInterval(this.interval);
        }
      }
    }
  },
  components: {
    ChatHeader,
    Message,
    Writer,
    'dropzone': DropZone,
  },
  destroyed() {
    clearInterval(this.interval);
  },
  created() {
    if(this.chat.is_group) {
      this.getUsers(() => {
        axios.get('/chat/' + this.chat.id_chat + '/messages').then(this.onPrimaryResponce);
        this.interval = setInterval(() => this.sync(), 1000);
      });
    } else {
      axios.get('/chat/' + this.chat.id_chat + '/messages').then(this.onPrimaryResponce);
      this.interval = setInterval(() => this.sync(), 1000);
    }
  },
  mounted() {
    this.dragAndDropCapable = this.determineDragAndDropCapable();
    if( this.dragAndDropCapable ) {
      ['drag', 'dragstart', 'dragend', 'dragover', 'dragenter', 'dragleave', 'drop'].forEach( evt => {
        this.$refs.dropzone.addEventListener(evt, e => {
          if(evt == 'dragenter')
            this.dragover = true;
          else if(evt == 'drop' && e.dataTransfer)
            this.dragover = false;
          else if(evt == 'dragleave' && e.dataTransfer) {
            var rect = this.$refs.dropzone.getBoundingClientRect();
            console.log(rect);
            console.log({ x: e.x, y: e.y });

            // Check the mouseEvent coordinates are outside of the rectangle
            if(e.x > rect.left + rect.width || e.x < rect.left
            || e.y > rect.top + rect.height || e.y < rect.top) {
              this.dragover = false;
              console.log(this.dragover);
            }
          }

          e.preventDefault();
          e.stopPropagation();
        }, false);
      });
      this.$refs.dropzone.addEventListener('drop', e => {
        for(let i = 0; i < e.dataTransfer.files.length; i++){
          this.$refs.writer.processFile(e.dataTransfer.files[i]);
        }
      });
    }
  }
}
</script>

<style lang="scss" scoped>

@import '~@/vars.scss';

section {
  padding-left: 300px;
  height: 100%;
  position: relative;
  padding-bottom: 80px;
  transition: padding 0.3s ease;
  &.selection-on {
    padding-right: 30px;
    padding-bottom: 130px;
  }
  &.reply-on {
    padding-bottom: 130px;
  }
  .dropwrapper {
    height: 100%;
  }
  .messages {
    padding: 0 15px;
    padding-top: 80px;
    height: auto;
  }
}

</style>
