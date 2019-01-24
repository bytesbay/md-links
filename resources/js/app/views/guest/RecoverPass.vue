<template lang="html">
  <form @submit="recover" class="content">
    <h3>Forgotten your Password?</h3>
    <input required class="theme-input" placeholder="E-mail" type="text" v-model="email">
    <div class="btn">
      <button type="submit" class="theme-button">Recovery</button>
    </div>
    <p>Back to <router-link to="/login">Login!</router-link></p>
  </form>
</template>

<script>
export default {
  name: 'modal-recover',
  data() {
    return {
      email: '',
    };
  },
  methods: {
    recover(e) {
      e.preventDefault();
      axios.post('/user/recover', {
        email: this.email,
      }).then(res => {
        alert('Check email');
        this.$router.push({ name: 'login' });
      }).catch(res => {
        alert(res.data.error)
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
