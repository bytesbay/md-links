<template lang="html">
  <form @submit="logIn" class="content">
    <h3>Login</h3>
    <input required class="theme-input" placeholder="DOCTOR ID" type="text" v-model="login">
    <input required class="theme-input" placeholder="Password" type="password" v-model="pass">
    <div class="btn">
      <button class="theme-button">Login</button>
    </div>
    <p class="is-clearfix">
      <span><router-link to="/recover">Forgot password?</router-link></span>
      <span>No account? <router-link to="/reg">Sign up</router-link></span>
    </p>
  </form>
</template>

<script>
export default {
  data() {
    return {
      login: '',
      pass: '',
    };
  },
  methods: {
    logIn(e) {
      axios.post('/user/login', {
        login: this.login,
        pass: this.pass,
      }).then(res => {
        this.$store.state.loaded.user = false;
        axios.get('/user/info').then(res => {
          this.$store.state.user = res.data;
          this.$store.state.user.logged = true;
          this.$store.state.loaded.user = true;
          this.$router.replace({ name: 'index' });
        });
      }).catch(res => {
        alert(res.data.error);
      });
      e.preventDefault();
    }
  }
}
</script>

<style lang="scss" scoped>

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
  p {
    text-align: center;
    font-size: 14px;
    font-weight: 300;
    span {
      &:first-child {
        float: left;
      }
      &:last-child {
        float: right;
      }
    }
  }
  .theme-input {
    margin: 10px 0;
  }
}

</style>
