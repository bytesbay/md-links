<template lang="html">
  <div class="wrapper">
    <painter
      @close="show_painter = false"
      ref="painter"
      v-model="paint"
      :image="image"
      v-show="show_painter"
    />
    <transition
      enter-active-class="animated slideInUp"
      leave-active-class="animated slideOutDown"
    >
      <div v-if="reply" class="reply-bar">
        <button class="remove-reply" @click="$parent.reply = null">
          <i class="icon ion-ios-close-circle-outline"></i>
        </button>
        <p @click="$parent.findMessage(reply.id_message)">{{ reply.content }}</p>
      </div>
    </transition>
    <transition
      enter-active-class="animated slideInUp"
      leave-active-class="animated slideOutDown"
    >
      <div v-if="$parent.selected.length" class="selection-bar">
        <button class="forward" @click="forwardMessages">
          <i class="icon ion-ios-undo"></i>
          Forward
        </button>
        <span>{{ $parent.selected.length }} {{ parsedWord }} selected</span>
        <button :disabled="isDeletingDisabled" class="delete" @click="deleteMessages">
          Delete
          <i class="icon ion-ios-trash"></i>
        </button>
      </div>
    </transition>
    <div class="writer">
      <div class="buttons">
        <button @click="$refs.attachfile.click()" class="add-file">
          <i class="icon ion-ios-attach"></i>
        </button>
        <button
          v-if="paint"
          @click="openPainter"
          class="paint-preview bg-img"
          v-bind:style="{ backgroundImage: paint ? `url(${paint})` : 'none' }"
        >
          <button @click="deletePaint" class="delete"></button>
        </button>
        <input multiple @change="attachFile" ref="attachfile" type="file">
      </div>
      <div @click="sendMessage" class="send-button" v-bind:class="{ 'enabled': sendEnabled }">
        <i class="icon ion-ios-mail"></i>
      </div>
      <div class="text" @click="$refs.typer.$el.focus()">
        <div class="form">
          <textarea-autosize
            placeholder="Write a message..."
            @keypress.native="onKeypress"
            rows="1"
            ref="typer"
            class="typer"
            v-model="message"
            :min-height="20"
            :max-height="80"
          />
          {{ files.length }} files
          <button class="remove-files" @click="removeFiles">
            <i class="icon ion-ios-close"></i>
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Painter from './Painter.vue'

export default {
  props: {
    chat: {
      required: true,
    },
    reply: Object,
  },
  name: 'writer',
  data() {
    return {
      paint: null,
      files: [],
      file_ids: [],
      message: '',
      image: null,
      show_painter: false,
      files_loading: [],
    };
  },
  components: {
    Painter,
  },
  computed: {
    parsedWord() {
      if(this.$parent.selected.length % 10 == 1) {
        return 'message';
      } else {
        return 'messages';
      }
    },
    sendEnabled() {
      return (this.message.length || this.paint || this.file_ids.length) && true;
    },
    isDeletingDisabled() {
      return this.$parent.selected.find(
        n => this.$store.state.user.id_user != this.$parent.messages.find(
          z => z.id_message == n
        ).id_addresser
      );
    }
  },
  methods: {
    removeFiles() {
      this.files = [];
      this.file_ids = [];
    },
    onKeypress(e) {
      if(!e.shiftKey && e.which == 13) {
        this.sendMessage();
        e.preventDefault();
      }
    },
    deletePaint() {
      this.image = null;
      this.paint = null;
    },
    clearData() {
      this.message = '';
      this.paint = null;
      this.image = null;
      this.files = [];
      this.file_ids = [];
    },
    openPainter() {
      this.show_painter = true;
    },
    sendMessage() {
      console.log(this.files_loading);
      if(this.files_loading.length) {
        alert('Some files are still loading');
        return;
      }
      if(this.sendEnabled) {
        axios.post('/chat/' + this.chat.id_chat + '/message', {
          content: this.message,
          files: this.file_ids,
          paint: this.paint,
          id_reply: this.reply ? this.reply.id_message : null,
        }).then(res => {
          this.$parent.sync();
        });
        this.$parent.reply = null;
        this.clearData();
      }
    },
    attachFile(e) {
      var files = e.target.files;

      for (var i = 0; i < files.length; i++) {
        this.processFile(files[i]);
      }
      e.target.value = '';
      this.$refs.typer.$el.focus();
    },
    forwardMessages() {
      document.removeEventListener('keyup', this.closeForward);
      this.$store.state.chat.forward.toggled = true;
      this.$store.state.chat.forward.messages = this.$parent.selected;
      document.addEventListener('keyup', this.closeForward);
    },
    closeForward(e) {
      if(e.which == 27) {
        this.$store.state.chat.forward.toggled = false;
        this.$store.state.chat.forward.messages = [];
        document.removeEventListener('keyup', this.closeForward);
      }
    },
    deleteMessages() {
      axios.post('/chat/' + this.chat.id_chat + '/messages/delete', {
        ids_message: this.$parent.selected,
      }).then(res => {
        this.$parent.messages = this.$parent.messages.filter(n => !this.$parent.selected.includes(n.id_message));
        this.$parent.selected = [];
      });
    },
    processFile(file) {
      if(
        file.type == 'image/jpeg' ||
        file.type == 'image/jpg' ||
        file.type == 'image/png' ||
        file.type == 'image/jp2'
      ) {
        this.image = file;
        this.openPainter();
      } else {
        this.files.push(file);
        var data = new FormData();
        data.append('file', file);
        this.files_loading.push(null);
        axios.post('/file', data, {
          headers: {
            'Content-Type': 'multipart/form-data'
          }
        }).then(res => {
          this.files_loading.splice(0, 1);
          this.file_ids.push(res.data.id);
        });
      }
    }
  },
  watch: {
    chat() {
      this.clearData();
    },
  },
}
</script>

<style lang="scss" scoped>

@import '~@/vars.scss';

.reply-bar {
  width: calc(100% - 420px);
  height: 50px;
  padding: 13px;
  bottom: 80px;
  right: 0;
  position: fixed;
  background: #fff;
  border-top: 1px solid $clr-gray;
  z-index: 39;
  .remove-reply {
    position: absolute;
    width: 24px;
    height: 24px;
    font-size: 22px;
    line-height: 24px;
    padding: 0;
    color: $clr-d-blue;
  }
  p {
    cursor: pointer;
    line-height: 24px;
    height: 24px;
    text-overflow: ellipsis;
    overflow: hidden;
    white-space: nowrap;
    font-size: 14px;
    margin: 0;
    margin-left: 34px;
    padding-left: 10px;
    border-left: 2px solid $clr-d-blue;
  }
}

.selection-bar {
  @extend .reply-bar ;
  display: flex;
  flex-direction: row;
  align-items: stretch;
  justify-content: space-between;
  .delete {
    transition: color .3s ease;
    color: $clr-red;
    i {
      margin-left: 10px;
    }
    &[disabled] {
      color: $clr-gray;
    }
  }
  .forward {
    color: $clr-d-pink;
    i {
      margin-right: 10px;
    }
  }
  i {
    font-size: 22px;
    vertical-align: middle;
  }
  button {
    font-size: 14px;
  }
  span {
    font-size: 14px;
  }
}

.writer {
  position: fixed;
  bottom: 0;
  right: 0;
  border-top: 1px solid $clr-gray;
  height: 80px;
  width: calc(100% - 420px);
  background: #fff;
  z-index: 40;
  .buttons {
    float: left;
    width: 200px;
    height: 100%;
    padding: 20px 20px;
    display: flex;
    flex-direction: row;
    align-items: center;
    justify-content: space-around;
    .add-file {
      width: 40px;
      height: 40px;
      border: 0;
      font-size: 30px;
      color: $clr-d-gray;
    }
    .paint-preview {
      position: relative;
      width: 40px;
      height: 40px;
      border-radius: 10px;
      background-color: #eee;
      button {
        width: 14px;
        height: 14px;
        background-position: center;
        background-repeat: no-repeat;
        position: absolute;
        top: -7px;
        border-radius: 7px;
      }
      .edit {
        left: 13px;
        background-image: $i-brush;
        background-size: 8px;
        background-color: $clr-d-pink;
      }
      .delete {
        right: -7px;
        background-color: $clr-m-gray;
        background-image: $i-close;
        background-size: 12px;
      }
    }
    input {
      width: 0;
      height: 0;
      overflow: hidden;
    }
  }
  .remove-files {

  }
  .send-button {
    position: absolute;
    top: 50%;
    margin-top: -25px;
    width: 40px;
    height: 40px;
    right: 50px;
    font-size: 30px;
    z-index: 1;
    text-align: center;
    color: $clr-d-gray;
    &.enabled {
      color: $clr-d-pink;
    }
  }
  .text {
    height: 100%;
    margin-left: 200px;
    cursor: text;
    .form {
      height: 100%;
    }
    .typer {
      height: 15px;
      position: absolute;
      top: 50%;
      transform: translateY(-50%);
      font-size: 14px;
      line-height: 20px;
      border: 0;
      width: 100%;
      &::placeholder {
        font-weight: 300;
      }
    }
  }

}

</style>
