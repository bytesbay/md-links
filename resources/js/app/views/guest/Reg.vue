<template lang="html">
  <form class="content" @submit="registrate">
      <h3>Registration</h3>
      <input required class="theme-input" placeholder="DOCTOR ID" type="text" v-model="login">
      <input required class="theme-input" placeholder="E-mail" type="text" v-model="email">
      <input required class="theme-input" placeholder="Name" type="text" v-model="name">
      <input required class="theme-input" placeholder="Password" type="password" v-model="pass">
      <div class="terms">
          <input class="theme-checkbox" type="checkbox" v-model="terms">
          <span>
              <a href="#">Terms and Conditions</a>
              agree
          </span>
      </div>
      <div class="btn">
          <button
              type="submit"
              :disabled="!terms"
              class="theme-button"
          >Registration</button>
      </div>
      <p>Have an account? <router-link to="/login">Sign in</router-link></p>
  </form>
</template>

<script>
export default {
    name: 'modal-registration',
    data() {
        return {
            login: '',
            email: '',
            name: '',
            pass: '',
            terms: false,
        };
    },
    methods: {
        signIn(e) {
          this.$modal.show('login');
          this.$modal.hide('registration');
          e.preventDefault();
        },
        registrate(e) {
            axios.post('/user/registrate', {
              login: this.login,
              email: this.email,
              name: this.name,
              pass: this.pass,
            }).then(res => {
              alert('Go check email');
              this.$router.push({ name: 'login' });
            }).catch(res => {
              alert(res.data.error);
            });
            e.preventDefault();
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
    .terms {
        padding-left: 20px;
        padding-top: 10px;
        input {
            margin-right: 20px;
        }
        a {
            color: $clr-black;
            text-decoration: underline;
        }
    }
    p {
        text-align: center;
        font-size: 14px;
        font-weight: 300;
    }
    .theme-input {
        margin: 10px 0;
    }
}

</style>
