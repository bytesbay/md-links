<template lang="html">
  <main>
    <chat-list group ref="list" v-model="chat"/>
    <chat-main group v-if="chat" @change="$refs.list.sync()" :chat="chat"/>
    <forward-cover/>
  </main>
</template>

<script>

import Sidebar from './../components/Sidebar.vue';
import ChatList from './../components/chat/ChatList.vue';
import ChatMain from './../components/chat/ChatMain.vue';
import ForwardCover from './../components/chat/ForwardCover.vue';

export default {
  data() {
    return {
      chat: 0
    };
  },
  components: {
    Sidebar,
    ChatList,
    ChatMain,
    ForwardCover,
  }
}
</script>

<style lang="scss" scoped>

main {
  padding-left: 120px;
}

</style>
