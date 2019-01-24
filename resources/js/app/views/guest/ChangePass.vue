<template lang="html">
  <form @submit="change" class="content">
    <h3>Change password</h3>
    <input required class="theme-input" placeholder="Password" type="password" v-model="pass">
    <input required class="theme-input" placeholder="Confirm password" type="password" v-model="confirm">
    <div class="btn">
      <button type="submit" class="theme-button">Change</button>
    </div>
  </form>
</template>

<script>
export default {
  data() {
    return {
      pass: '',
      confirm: '',
    };
  },
  methods: {
    change(e) {
      e.preventDefault();
      axios.post('/user/new-pass', {
        pass: this.pass,
        code: this.$route.params.key,
        confirm: this.confirm
      }).then(res => {
        this.$router.push({ name: 'login' });
      }).catch(res => {
        alert(res.data.error);
      });
    }
  }
}
</script>

<style lang="scss" scoped>

@import '~@/vars.scss';

.content {
    padding: 50px 70px;
    h3 {
        font-size: 18px;
        text-align: center;
        margin: 0;
        margin-bottom: 50px;
    }
    .btn {
        padding: 40px 0;
        text-align: center;
    }
    .theme-input {
        margin: 10px 0;
    }
    p {
        text-align: center;
    }
}

</style>
