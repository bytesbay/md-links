<template lang="html">
  <div class="chats">
    <div class="search">
      <div class="theme-search-input">
        <input @input="searchChats" placeholder="Search" type="text" v-model="search.string">
      </div>
    </div>
    <vue-scrollbar class="list-scroll" ref="scrollbar">
      <div class="list">
        <chat-item
          v-for="item in chats"
          :key="item.chat.id_chat"
          :data="item"
          :selected="value.id_chat == item.chat.id_chat"
          @click="selectChat(item)"
        />
        <template v-if="search.data.length">
          <div class="global-search">Global search</div>
          <chat-item
            v-for="item in search.data"
            :key="item.chat.id_chat"
            :data="item"
            :selected="value.id_searchable == item.chat.id_searchable"
            @click="selectChat(item)"
          />
        </template>
      </div>
    </vue-scrollbar>
    <div class="footer">
      <a href="#" class="contacts">Contacts</a>
      <a href="#" class="support">Support</a>
      <button></button>
    </div>
  </div>
</template>

<script>
import ChatItem from './ChatItem.vue';

export default {
  name: 'chat-col',
  props: {
    value: {
      required: true,
    },
  },
  data() {
    return {
      search: {
        string: '',
        data: [],
      },
      data: [],
    };
  },
  components: {
    ChatItem,
  },
  methods: {
    searchChats() {
      axios.get('/chat/search/' + this.search.string).then(res => {
        this.search.data = res.data.filter(n => {
          var is_guest = n.chat.id_user == this.$store.state.user.id_user;
          var is_duplicated = this.data.find(z => z.chat.id_user == n.chat.id_user);
          return !(is_guest || is_duplicated);
        });
        this.search.data = this.search.data.map(n => {
          n.chat.id_searchable = n.chat.id_user;
          n.chat.is_global = true;
          return n;
        });
      });
    },
    selectChat(item) {
      // if have to forward message
      if(this.$store.state.chat.forward.toggled) {
        axios.put('/chat/' + item.chat.id_chat + '/messages/forward', {
          ids_message: this.$store.state.chat.forward.messages
        });
        this.$store.state.chat.forward.toggled = false;
        this.$store.state.chat.forward.messages = [];
      }
      this.$emit('input', item.chat);
    },
    sync(callback) {
      axios.get('/chats').then(res => {
        console.log(res.data.length);
        this.data = res.data;
        if(callback) callback();
      });
    }
  },
  computed: {
    chats() {
      var chats = this.data.sort((a, b) => {
        if(a.date < b.date)
          return 1;
        else if(a.date > b.date)
          return -1;
        else
          return 0;
      });
      if(this.search.string) {
        return chats.filter(n => n.chat.name.match(new RegExp(this.search.string, 'i')));
      } else {
        return chats;
      }
    }
  },
  created() {
    this.sync();
  }
}
</script>

<style lang="scss" scoped>

@import '~@/vars.scss';

.chats {
  width: 300px;
  border-right: 1px solid $clr-gray;
  height: calc(100% - 80px);
  position: fixed;
  left: 120px;
  z-index: 40;
  top: 80px;
  .search {
    height: 80px;
    padding: 20px;
    border-bottom: 1px solid $clr-gray;
  }
  .list-scroll {
    width: 100%;
    height: calc(100% - 160px);
  }
  .global-search {
    user-select: none;
    width: 100%;
    padding: 2px 0;
    text-align: center;
    border-bottom: 1px solid $clr-gray;
    color: $clr-d-gray;
    background: #fafafa;
    font-size: 10px;
  }
  .footer {
    position: absolute;
    bottom: 0;
    left: 0;
    height: 80px;
    width: 100%;
    border-top: 1px solid $clr-gray;
    display: flex;
    flex-direction: row;
    align-items: center;
    justify-content: space-between;
    padding: 20px 30px;
    background: #fff;
    button {
      width: 40px;
      height: 40px;
      background-position: center;
      background-size: 25px;
      background-repeat: no-repeat;
      border: 0;
      background-image: url('data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz48c3ZnIHZlcnNpb249IjEuMSIgaWQ9IkNhcGFfMSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgeD0iMHB4IiB5PSIwcHgiIHZpZXdCb3g9IjAgMCA1MTIgNTEyIiBzdHlsZT0iZW5hYmxlLWJhY2tncm91bmQ6bmV3IDAgMCA1MTIgNTEyOyIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSI+PHN0eWxlIHR5cGU9InRleHQvY3NzIj4uc3Qwe2ZpbGw6I0FDQUVCOTt9PC9zdHlsZT48Zz48cGF0aCBjbGFzcz0ic3QwIiBkPSJNNDg1LjYsMTk5LjFoLTQ3LjljLTcuNywwLTE0LTQuMi0xNy0xMS4zYy0yLjktNy4xLTEuNS0xNC42LDQtMjBsMzMuOS0zMy45YzUtNSw3LjctMTEuNiw3LjctMTguNmMwLTctMi43LTEzLjctNy43LTE4LjdsLTQzLjItNDMuMmMtMTAtMTAtMjcuMy0xMC0zNy4zLDBsLTMzLjksMzMuOWMtNS40LDUuNC0xMyw2LjktMjAsNGMtNy4xLTIuOS0xMS4zLTkuMy0xMS4zLTE3VjI2LjRjMC0xNC41LTExLjgtMjYuNC0yNi40LTI2LjRoLTYxLjFjLTE0LjUsMC0yNi40LDExLjgtMjYuNCwyNi40djQ3LjljMCw3LjctNC4yLDE0LTExLjMsMTdjLTcuMSwzLTE0LjYsMS40LTIwLTRsLTMzLjktMzMuOWMtMTAtMTAtMjcuMy0xMC0zNy4zLDBMNTMuNCw5Ni42Yy01LDUtNy43LDExLjYtNy43LDE4LjdjMCw3LDIuNywxMy43LDcuNywxOC42bDMzLjksMzMuOWM1LjQsNS40LDYuOSwxMi45LDQsMjBjLTIuOSw3LjEtOS4zLDExLjMtMTcsMTEuM0gyNi40QzExLjgsMTk5LjEsMCwyMTAuOSwwLDIyNS41djYxLjFjMCwxNC41LDExLjgsMjYuNCwyNi40LDI2LjRoNDcuOWM3LjcsMCwxNCw0LjIsMTcsMTEuM2MyLjksNy4xLDEuNSwxNC42LTQsMjBsLTMzLjksMzMuOWMtNSw1LTcuNywxMS42LTcuNywxOC42YzAsNywyLjcsMTMuNyw3LjcsMTguN2w0My4yLDQzLjJjMTAsMTAsMjcuMywxMCwzNy4zLDBsMzMuOS0zMy45YzUuNC01LjQsMTIuOS02LjksMjAtNGM3LjEsMi45LDExLjMsOS4zLDExLjMsMTd2NDcuOWMwLDE0LjUsMTEuOCwyNi40LDI2LjQsMjYuNGg2MS4xYzE0LjUsMCwyNi40LTExLjgsMjYuNC0yNi40di00Ny45YzAtNy43LDQuMi0xNCwxMS4zLTE3YzcuMS0zLDE0LjYtMS41LDIwLDRsMzMuOSwzMy45YzEwLDEwLDI3LjMsMTAsMzcuMywwbDQzLjItNDMuMmM1LTUsNy43LTExLjYsNy43LTE4LjdjMC03LTIuNy0xMy43LTcuNy0xOC42bC0zMy45LTMzLjljLTUuNC01LjQtNi45LTEyLjktNC0yMHM5LjMtMTEuMywxNy0xMS4zaDQ3LjljMTQuNSwwLDI2LjQtMTEuOCwyNi40LTI2LjR2LTYxLjFDNTEyLDIxMC45LDUwMC4yLDE5OS4xLDQ4NS42LDE5OS4xeiBNNDkzLDI4Ni41YzAsNC4xLTMuMyw3LjQtNy40LDcuNGgtNDcuOWMtMTUuNCwwLTI4LjYsOC44LTM0LjUsMjMuMWMtNS45LDE0LjItMi44LDI5LjgsOC4xLDQwLjdsMzMuOSwzMy45YzIuOSwyLjksMi45LDcuNiwwLDEwLjVMNDAyLDQ0NS4yYy0yLjksMi45LTcuNiwyLjktMTAuNSwwbC0zMy45LTMzLjljLTEwLjktMTAuOS0yNi41LTE0LTQwLjctOC4xYy0xNC4yLDUuOS0yMy4xLDE5LjEtMjMuMSwzNC41djQ3LjljMCw0LjEtMy4zLDcuNC03LjQsNy40aC02MS4xYy00LjEsMC03LjQtMy4zLTcuNC03LjR2LTQ3LjljMC0xNS40LTguOC0yOC42LTIzLjEtMzQuNWMtNC44LTItOS43LTIuOS0xNC41LTIuOWMtOS42LDAtMTguOSwzLjgtMjYuMiwxMWwtMzMuOSwzMy45Yy0yLjksMi45LTcuNiwyLjktMTAuNSwwTDY2LjgsNDAyYy0yLjktMi45LTIuOS03LjYsMC0xMC41bDMzLjktMzMuOWMxMC45LTEwLjksMTQtMjYuNSw4LjEtNDAuN2MtNS45LTE0LjItMTkuMS0yMy0zNC41LTIzSDI2LjRjLTQuMSwwLTcuNC0zLjMtNy40LTcuNHYtNjEuMWMwLTQuMSwzLjMtNy40LDcuNC03LjRoNDcuOWMxNS40LDAsMjguNi04LjgsMzQuNS0yMy4xYzUuOS0xNC4yLDIuOC0yOS44LTguMS00MC43bC0zMy45LTMzLjljLTIuOS0yLjktMi45LTcuNiwwLTEwLjVMMTEwLDY2LjhjMi45LTIuOSw3LjYtMi45LDEwLjUsMGwzMy45LDMzLjljMTAuOSwxMC45LDI2LjUsMTQsNDAuNyw4LjFjMTQuMi01LjksMjMuMS0xOS4xLDIzLjEtMzQuNVYyNi40YzAtNC4xLDMuMy03LjQsNy40LTcuNGg2MS4xYzQuMSwwLDcuNCwzLjMsNy40LDcuNHY0Ny45YzAsMTUuNCw4LjgsMjguNiwyMy4xLDM0LjVjMTQuMiw1LjksMjkuOCwyLjgsNDAuNy04LjFsMzMuOS0zMy45YzIuOS0yLjksNy42LTIuOSwxMC41LDBsNDMuMiw0My4yYzIuOSwyLjksMi45LDcuNiwwLDEwLjVsLTMzLjksMzMuOWMtMTAuOSwxMC45LTE0LDI2LjUtOC4xLDQwLjdjNS45LDE0LjIsMTkuMSwyMy4xLDM0LjUsMjMuMWg0Ny45YzQuMSwwLDcuNCwzLjMsNy40LDcuNFYyODYuNXoiLz48cGF0aCBjbGFzcz0ic3QwIiBkPSJNMjU2LDE3MC43Yy00Ny4xLDAtODUuMywzOC4zLTg1LjMsODUuM3MzOC4zLDg1LjMsODUuMyw4NS4zczg1LjMtMzguMyw4NS4zLTg1LjNTMzAzLjEsMTcwLjcsMjU2LDE3MC43eiBNMjU2LDMyMi40Yy0zNi42LDAtNjYuNC0yOS44LTY2LjQtNjYuNHMyOS44LTY2LjQsNjYuNC02Ni40czY2LjQsMjkuOCw2Ni40LDY2LjRTMjkyLjYsMzIyLjQsMjU2LDMyMi40eiIvPjwvZz48L3N2Zz4=');
    }
    a {
      font-size: 14px;
      color: #acaeb9;
      font-weight: 300;
    }
  }
}

</style>
