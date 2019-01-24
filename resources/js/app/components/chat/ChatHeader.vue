<template lang="html">
  <div class="wrapper">
    <add-user
      v-if="opened_invites"
      :users="$parent.group_users"
      :chat="chat"
      @close="opened_invites = false"
      @users-update="$parent.getUsers"
    />
    <header>
      <router-link v-if="!this.chat.is_group" :to="avatarLink">
        <div class="avatar bg-img" v-bind:style="{ backgroundImage: `url(${chat.img})` }"></div>
      </router-link>
      <div v-else class="avatar bg-img" v-bind:style="{ backgroundImage: `url(${chat.img})` }"></div>
      <p class="name">{{ chat.name }}</p>

      <div class="search" v-bind:class="{ opened: search_opened }">
        <button class="pop" @click="popSearch"></button>
        <input ref="input" type="text" @input="$emit('input', $event.target.value)" placeholder="Search messages">
        <transition
          enter-active-class="animated fadeIn"
          leave-active-class="animated fadeOut"
        >
          <button v-if="search_opened" @click="search_opened = false" class="close bg-img"></button>
        </transition>
      </div>
      <div v-if="this.chat.is_group" @click="opened_invites = true" class="add-user">
        <button class="bg-img"></button>
        <span>Users</span>
      </div>

      <!-- <input v-if="group" v-model="invite_user"/>
      <button v-if="group" @click="invite">Add user</button> -->
    </header>
  </div>
</template>

<script>
import AddUser from './AddUser.vue';

export default {
  name: 'chat-header',
  data() {
    return {
      search_opened: false,
      opened_invites: false,
    };
  },
  props: {
    chat: {},
  },
  methods: {
    popSearch($event) {
      if(this.search_opened) {
        this.$emit('input', $event.target.value);
      } else {
        this.$refs.input.focus();
        this.search_opened = true;
      }
    },
  },
  computed: {
    avatarLink() {
      return !this.chat.is_group ? '/profile/' + this.chat.id_user : '/group/' + this.chat.id_chat;
    }
  },
  components: {
    AddUser,
  },
}
</script>

<style lang="scss" scoped>

@import '~@/vars.scss';

header {
  padding: 15px 30px;
  font-weight: bold;
  position: fixed;
  top: 80px;
  right: 0;
  width: calc(100% - 420px);
  height: 80px;
  border-bottom: 1px solid $clr-gray;
  background: #fff;
  z-index: 40;
  .avatar {
    height: 50px;
    width: 50px;
    border-radius: 25px;
    background: $clr-gray;
    float: left;
  }
  .name {
    font-size: 15px;
    margin: 0;
    height: 50px;
    line-height: 50px;
    margin-left: 25px;
    float: left;
  }
  .search .pop {
    width: 50px;
    height: 50px;
    background-position: center;
    background-repeat: no-repeat;
  }
  .add-user {
    margin-right: 10px;
    cursor: pointer;
    float: right;
    display: flex;
    flex-direction: row;
    align-items: center;
    user-select: none;
    button {
      background-image: $i-plus-pink;
      background-size: 20px;
      width: 50px;
      height: 50px;
      background-color: transparent;
    }
    span {
      color: $clr-d-gray;
      font-size: 14px;
      font-weight: 400;
    }
  }
  .search {
    display: flex;
    flex-direction: row;
    align-items: center;
    float: right;
    position: relative;
    transition: background 0.3s ease;
    input {
      padding: 0;
      border: 0;
      line-height: 50px;
      width: 0;
      opacity: 0;
      transition: width 0.3s ease, padding-right 0.3s ease;
      font-size: 14px;
      background: transparent;
      &::placeholder {
        font-weight: 300;
      }
    }
    .pop {
      background-size: 20px;
      background-image: url('data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz48c3ZnIHZlcnNpb249IjEuMSIgaWQ9IkxheWVyXzEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHg9IjBweCIgeT0iMHB4IiB2aWV3Qm94PSIwIDAgNTEyIDUxMiIgc3R5bGU9ImVuYWJsZS1iYWNrZ3JvdW5kOm5ldyAwIDAgNTEyIDUxMjsiIHhtbDpzcGFjZT0icHJlc2VydmUiPjxzdHlsZSB0eXBlPSJ0ZXh0L2NzcyI+LnN0MHtmaWxsOiNDNEM0QzQ7fTwvc3R5bGU+PGc+PHBhdGggY2xhc3M9InN0MCIgZD0iTTQ5NSw0NjYuMkwzNzcuMiwzNDguNGMyOS4yLTM1LjYsNDYuOC04MS4yLDQ2LjgtMTMwLjlDNDI0LDEwMy41LDMzMS41LDExLDIxNy41LDExQzEwMy40LDExLDExLDEwMy41LDExLDIxNy41UzEwMy40LDQyNCwyMTcuNSw0MjRjNDkuNywwLDk1LjItMTcuNSwxMzAuOC00Ni43TDQ2Ni4xLDQ5NWM4LDgsMjAuOSw4LDI4LjksMEM1MDMsNDg3LjEsNTAzLDQ3NC4xLDQ5NSw0NjYuMnogTTIxNy41LDM4Mi45QzEyNi4yLDM4Mi45LDUyLDMwOC43LDUyLDIxNy41UzEyNi4yLDUyLDIxNy41LDUyQzMwOC43LDUyLDM4MywxMjYuMywzODMsMjE3LjVTMzA4LjcsMzgyLjksMjE3LjUsMzgyLjl6Ii8+PC9nPjwvc3ZnPg==');
    }
    .close {
      position: absolute;
      right: 10px;
      background-image: $i-close-circle;
      top: 50%;
      margin-top: -15px;
      height: 30px;
      width: 30px;
      background-color: transparent;
      background-size: 24px;
    }
    &.opened {
      background: $clr-light;
      border-radius: 5px;
      input {
        opacity: 1;
        width: 300px;
        padding-right: 40px;
      }
    }
  }
}

</style>
