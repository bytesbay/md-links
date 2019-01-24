<template lang="html">
  <main>
    <chat-list ref="list" v-model="chat"/>
    <forward-cover/>
    <start-chat
      @started="onStart"
      :chat="chat"
      v-if="chat && chat.is_global"
    />
    <chat-main
      v-else-if="chat && !chat.is_global"
      @change="$refs.list.sync()"
      :chat="chat"
    />
  </main>
</template>

<script>

import Sidebar from './../components/Sidebar.vue';
import ChatList from './../components/chat/ChatList.vue';
import ChatMain from './../components/chat/ChatMain.vue';
import ForwardCover from './../components/chat/ForwardCover.vue';
import StartChat from './../components/chat/StartChat.vue';

export default {
  data() {
    return {
      chat: 0
    };
  },
  methods: {
    onStart() {
      this.$refs.list.sync(() => {
        this.chat = this.$refs.list.data.find(n => n.chat.id_user == this.chat.id_user).chat;
        this.$refs.list.search.data = [];
        this.$refs.list.search.string = '';
      });
    }
  },
  components: {
    Sidebar,
    ChatList,
    ChatMain,
    ForwardCover,
    StartChat,
  }
}
</script>

<style lang="scss" scoped>

main {
  padding-left: 120px;
}

</style>
