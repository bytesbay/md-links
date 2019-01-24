<template lang="html">
  <modal
    name="add-doctor"
    :adaptive="true"
    height="500"
    width="400"
  >
    <div class="content">
      <button class="close-modal-btn" @click="$modal.hide('add-doctor')"></button>
      <header>
        <h3>My contacts</h3>
        <div class="theme-search-input">
          <input v-model="search" placeholder="Search" type="text">
        </div>
      </header>
      <div class="content">
        <div v-for="item in doctors" class="user">
          <div v-bind:style="{ backgroundImage: `url(${item.img})` }" class="avatar bg-img"></div>
          <span>{{ item.name }}</span>
          <button @click="invite(item)">Add</button>
        </div>
        <div v-if="!doctors.length" class="no-data">
          No contacts found
        </div>
      </div>
    </div>
  </modal>
</template>

<script>
export default {
  name: 'modal-add-doctor',
  props: {
    flow: {
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
  computed: {
    doctors() {
      var doctors = this.data.filter(n => !this.users.find(z => z.id_user == n.id_user));
      if(this.search) {
        return doctors.filter(n => n.name.match(new RegExp(this.search, 'i')));
      } else {
        return doctors;
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
    invite(user) {
      axios.put('/flow/' + this.flow.id_flow + '/invite-doctor/' + user.id_user).then(res => {
        this.$emit('added', user);
        alert('User invited to flow.');
        if(users.length > 3) {
          this.$modal.hide('add-doctor');
        }
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

.content {
  header {
    padding: 20px;
    border-bottom: 1px solid $clr-gray;
    h3 {
      margin-top: 0;
    }
  }
  .content {
    padding: 20px 20px;
    .no-data {
      color: $clr-gray;
      font-weight: 500;
    }
    .user {
      display: flex;
      flex-direction: row;
      align-items: center;
      justify-content: space-between;
      padding: 10px 0;
      .avatar {
        display: inline-block;
        width: 40px;
        height: 40px;
        border-radius: 20px;
      }
      span {
        margin-left: -110px;
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
        background-image: $i-plus-pink;
        background-repeat: no-repeat;
        background-size: 15px;
        background-position: 50px center;
        font-size: 14px;
        font-weight: 500;
      }
    }
  }
}

</style>
