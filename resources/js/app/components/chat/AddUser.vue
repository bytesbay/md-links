<template lang="html">
  <div class="add-user">
    <div class="dark-bg" @click="$emit('close')"></div>
    <div class="modal">
      <button class="close-modal-btn" @click="$emit('close')"></button>
      <header>
        <h3>My contacts</h3>
        <div class="theme-search-input">
          <input v-model="search" placeholder="Search" type="text">
        </div>
      </header>
      <div class="content">
        <div v-for="item in chats" class="user">
          <div>
            <div v-bind:style="{ backgroundImage: `url(${item.img})` }" class="avatar bg-img"></div>
            <span class="name">{{ item.name }}</span>
          </div>
          <button v-if="!item.invited" @click="invite(item.id_user)">Add</button>
          <button v-else @click="remove(item.id_user)">Remove</button>
        </div>
        <div v-if="!chats.length" class="no-data">
          No contacts found
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'add-user',
  props: {
    chat: {
      required: true,
    },
    users: {}
  },
  data() {
    return {
      loaded: false,
      search: '',
      data: [],
    };
  },
  watch: {
    search() {

    }
  },
  computed: {
    chats() {
      var chats = this.data.map(n => {
        if(this.users.find(z => z.id_user == n.id_user)) {
          n.invited = true;
        } else {
          n.invited = false;
        }
        return n;
      });
      if(this.search) {
        return chats.filter(n => n.name.match(new RegExp(this.search, 'i')));
      } else {
        return chats;
      }
    },
  },
  methods: {
    sync() {
      axios.get('/user/friends').then(res => {
        this.loaded = true;
        this.data = res.data;
      });
    },
    invite(id_user) {
      axios.put('/group/' + this.chat.id_chat + '/invite/' + id_user).then(res => {
        this.$emit('users-update');
        alert('User invited to group.');
      }).catch(res => {
        alert(res.data.error);
      });
    },
    remove(id_user) {
      axios.delete('/group/' + this.chat.id_chat + '/remove/' + id_user).then(res => {
        this.$emit('users-update');
        alert('User removed from group.');
      }).catch(res => {
        alert(res.data.error);
      });
    },
  },
  created() {
    this.sync();
  }
}
</script>

<style lang="scss" scoped>

@import '~@/vars.scss';

.add-user {
  position: fixed;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  z-index: 200;
  .dark-bg {
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.8);
    cursor: pointer;
  }
  .modal {
    background: #fff;
    border-radius: 5px;
    position: absolute;
    right: 50px;
    top: 160px;
    header {
      padding: 20px;
      border-bottom: 1px solid $clr-d-gray;
      h3 {
        margin-top: 0;
      }
    }
    .content {
      padding: 40px 20px;
      .no-data {
        color: $clr-gray;
        font-weight: 500;
      }
      .user {
        padding: 10px 0;
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: space-between;
        .avatar {
          display: inline-block;
          width: 40px;
          height: 40px;
          border-radius: 20px;
          float: left;
          margin-right: 10px;
        }
        .name {
          line-height: 40px;
          font-weight: 500;
          font-size: 14px;
        }
        button {
          height: 40px;
          width: auto;
          padding: 0 10px;
          padding-right: 40px;
          border-radius: 20px;
          border: 2px solid $clr-d-pink;
          color: $clr-d-pink;
          // background-image: $i-plus-pink;
          // background-repeat: no-repeat;
          // background-size: 15px;
          // background-position: 50px center;
          font-size: 14px;
          font-weight: 500;
        }
      }
    }
  }
}

</style>
