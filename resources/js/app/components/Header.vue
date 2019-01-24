<template lang="html">
  <header>
    <user-widget/>
    <router-link to="/" class="logo bg-img"></router-link>
    <div class="buttons">
      <div class="calendar">
        <button class="header-btn"></button>
        <div class="content">

        </div>
      </div>
      <notifications-widget/>
      <button @click="signOut" class="sign-out header-btn"></button>
    </div>
    <nav>
      <div class="nav-item">
        <a @click="$modal.show('create-group')" href="#">
          Create new group
          <svg width="20px" height="20px" enable-background="new 0 0 45.958 45.958" version="1.1" viewBox="0 0 45.958 45.958" xml:space="preserve" xmlns="http://www.w3.org/2000/svg">
          	<path d="M22.979,0C10.288,0,0,10.289,0,22.979s10.288,22.979,22.979,22.979c12.69,0,22.979-10.289,22.979-22.979S35.67,0,22.979,0z    M32.253,26.977h-5.301v5.289c0,2.207-1.765,3.996-3.972,3.996c-2.206,0-3.971-1.789-3.971-3.996v-5.291H13.71   c-2.207,0-4.006-1.789-4.006-3.997c0-2.207,1.796-3.996,4.003-3.996h5.302v-5.289c0-2.208,1.765-3.997,3.971-3.997   c2.208,0,3.972,1.789,3.972,3.997v5.29h5.301c2.207,0,3.997,1.79,3.997,3.997C36.25,25.188,34.46,26.977,32.253,26.977z" fill="#fff"/>
          </svg>
        </a>
      </div>
    </nav>
  </header>
</template>

<script>
import UserWidget from './UserWidget.vue'
import NotificationsWidget from './NotificationsWidget.vue'

export default {
  name: 'app-header',
  data() {
    return {
      profile_opened: false,
    };
  },
  methods: {
    signOut() {
      this.$store.state.user = {};
      this.$cookie.delete('auth');
      this.$router.push({ name: 'login' });
    },
    hideNotes() {
      this.profile_opened = false;
    },
  },
  components: {
    UserWidget,
    NotificationsWidget
  }
}
</script>

<style lang="scss" scoped>

@import '~@/vars.scss';

header {
  height: 80px;
  width: 100%;
  background-color: $clr-d-blue;
  position: fixed;
  z-index: 100;
  .logo {
    float: left;
    margin: 0 25px;
    width: 200px;
    height: 100%;
    background-image: url(/images/logo.png);
    background-size: cover;
    background-color: transparent;
  }
  .buttons {
    float: right;
    width: 200px;
    margin-left: 50px;
    padding: 20px 0;
    display: flex;
    flex-direction: row;
    align-items: center;
    justify-content: space-around;
    .calendar {
      button {
        background-image: url('data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz48c3ZnIHZlcnNpb249IjEuMSIgaWQ9IkNhcGFfMSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgeD0iMHB4IiB5PSIwcHgiIHZpZXdCb3g9IjAgMCA1MTIgNTEyIiBzdHlsZT0iZW5hYmxlLWJhY2tncm91bmQ6bmV3IDAgMCA1MTIgNTEyOyIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSI+PHN0eWxlIHR5cGU9InRleHQvY3NzIj4uc3Qwe2ZpbGw6I0ZGRkZGRjt9PC9zdHlsZT48Zz48Zz48cGF0aCBjbGFzcz0ic3QwIiBkPSJNMTg2LjUsMjgyLjVjMC02LjQtNS4yLTExLjYtMTEuNi0xMS42aC00MC42Yy02LjQsMC0xMS42LDUuMi0xMS42LDExLjZWMzIzYzAsNi40LDUuMiwxMS42LDExLjYsMTEuNmg0MC42YzYuNCwwLDExLjYtNS4yLDExLjYtMTEuNkwxODYuNSwyODIuNUwxODYuNSwyODIuNXoiLz48cGF0aCBjbGFzcz0ic3QwIiBkPSJNMjg3LjksMjgyLjVjMC02LjQtNS4yLTExLjYtMTEuNi0xMS42aC00MC42Yy02LjQsMC0xMS42LDUuMi0xMS42LDExLjZWMzIzYzAsNi40LDUuMiwxMS42LDExLjYsMTEuNmg0MC42YzYuNCwwLDExLjYtNS4yLDExLjYtMTEuNlYyODIuNXoiLz48cGF0aCBjbGFzcz0ic3QwIiBkPSJNMzg5LjIsMjgyLjVjMC02LjQtNS4yLTExLjYtMTEuNi0xMS42aC00MC42Yy02LjQsMC0xMS42LDUuMi0xMS42LDExLjZWMzIzYzAsNi40LDUuMiwxMS42LDExLjYsMTEuNmg0MC42YzYuNCwwLDExLjYtNS4yLDExLjYtMTEuNlYyODIuNXoiLz48cGF0aCBjbGFzcz0ic3QwIiBkPSJNMTg2LjUsMzgzLjhjMC02LjQtNS4yLTExLjYtMTEuNi0xMS42aC00MC42Yy02LjQsMC0xMS42LDUuMi0xMS42LDExLjZ2NDAuNWMwLDYuNCw1LjIsMTEuNiwxMS42LDExLjZoNDAuNmM2LjQsMCwxMS42LTUuMiwxMS42LTExLjZMMTg2LjUsMzgzLjhMMTg2LjUsMzgzLjh6Ii8+PHBhdGggY2xhc3M9InN0MCIgZD0iTTI4Ny45LDM4My44YzAtNi40LTUuMi0xMS42LTExLjYtMTEuNmgtNDAuNmMtNi40LDAtMTEuNiw1LjItMTEuNiwxMS42djQwLjVjMCw2LjQsNS4yLDExLjYsMTEuNiwxMS42aDQwLjZjNi40LDAsMTEuNi01LjIsMTEuNi0xMS42VjM4My44eiIvPjxwYXRoIGNsYXNzPSJzdDAiIGQ9Ik0zODkuMiwzODMuOGMwLTYuNC01LjItMTEuNi0xMS42LTExLjZoLTQwLjZjLTYuNCwwLTExLjYsNS4yLTExLjYsMTEuNnY0MC41YzAsNi40LDUuMiwxMS42LDExLjYsMTEuNmg0MC42YzYuNCwwLDExLjYtNS4yLDExLjYtMTEuNkwzODkuMiwzODMuOEwzODkuMiwzODMuOHoiLz48cGF0aCBjbGFzcz0ic3QwIiBkPSJNNDYxLjgsNTd2NjEuOWMwLDI4LTIyLjcsNTAuNi01MC43LDUwLjZoLTMyYy0yOCwwLTUxLTIyLjYtNTEtNTAuNlY1Ni44SDE4My45djYyLjJjMCwyOC0yMyw1MC42LTUxLDUwLjZoLTMyYy0yOCwwLTUwLjctMjIuNi01MC43LTUwLjZWNTdDMjUuOCw1Ny43LDUuNyw3OCw1LjcsMTAyLjl2MzYzYzAsMjUuMywyMC41LDQ2LjIsNDUuOSw0Ni4yaDQwOC45YzI1LjMsMCw0NS45LTIwLjksNDUuOS00Ni4ydi0zNjNDNTA2LjMsNzgsNDg2LjIsNTcuNyw0NjEuOCw1N3ogTTQ0Ni45LDQ0My4yYzAsMTEtOC45LDE5LjgtMTkuOCwxOS44aC0zNDNjLTExLDAtMTkuOC04LjktMTkuOC0xOS44VjI1NS43YzAtMTEsOC45LTE5LjgsMTkuOC0xOS44aDM0M2MxMSwwLDE5LjgsOC45LDE5LjgsMTkuOEw0NDYuOSw0NDMuMkw0NDYuOSw0NDMuMnoiLz48cGF0aCBjbGFzcz0ic3QwIiBkPSJNMTAwLjgsMTM2LjNoMzEuNmM5LjYsMCwxNy40LTcuOCwxNy40LTE3LjRWMTcuNGMwLTkuNi03LjgtMTcuNC0xNy40LTE3LjRoLTMxLjZjLTkuNiwwLTE3LjQsNy44LTE3LjQsMTcuNHYxMDEuNUM4My40LDEyOC41LDkxLjIsMTM2LjMsMTAwLjgsMTM2LjN6Ii8+PHBhdGggY2xhc3M9InN0MCIgZD0iTTM3OC43LDEzNi4zaDMxLjZjOS42LDAsMTcuNC03LjgsMTcuNC0xNy40VjE3LjRjMC05LjYtNy44LTE3LjQtMTcuNC0xNy40aC0zMS42Yy05LjYsMC0xNy40LDcuOC0xNy40LDE3LjR2MTAxLjVDMzYxLjMsMTI4LjUsMzY5LjEsMTM2LjMsMzc4LjcsMTM2LjN6Ii8+PC9nPjwvZz48L3N2Zz4=');
      }
    }
    .sign-out {
      background-image: url('data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz48c3ZnIHZlcnNpb249IjEuMSIgaWQ9IkNhcGFfMSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgeD0iMHB4IiB5PSIwcHgiIHZpZXdCb3g9IjAgMCA1MTIgNTEyIiBzdHlsZT0iZW5hYmxlLWJhY2tncm91bmQ6bmV3IDAgMCA1MTIgNTEyOyIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSI+PHN0eWxlIHR5cGU9InRleHQvY3NzIj4uc3Qwe2ZpbGw6I0ZGRkZGRjt9PC9zdHlsZT48Zz48Zz48cGF0aCBjbGFzcz0ic3QwIiBkPSJNMjA5LDQzMy42Yy0wLjctMS43LTAuOS0zLjItMC43LTQuNGMwLjItMS4yLTAuNS0yLjItMi4zLTIuOWMtMS43LTAuOC0yLjYtMS40LTIuNi0xLjhjMC0wLjQtMS4zLTAuOC0zLjgtMWMtMi41LTAuMi0zLjgtMC4zLTMuOC0wLjNoLTQuMmgtMy42SDk0Yy0xNC40LDAtMjYuNy01LjEtMzYuOS0xNS4zYy0xMC4yLTEwLjItMTUuMy0yMi41LTE1LjMtMzYuOVYxNDEuMWMwLTE0LjQsNS4xLTI2LjcsMTUuMy0zNi45QzY3LjQsOTMuOSw3OS43LDg4LjgsOTQsODguOGgxMDQuNWMyLjgsMCw1LjEtMC43LDYuNy0yLjFjMS42LTEuNCwyLjctMy41LDMuMy02LjRjMC41LTIuOCwwLjktNS40LDEtNy43YzAuMS0yLjMsMC4xLTUuMi0wLjItOC43Yy0wLjItMy41LTAuMy01LjctMC4zLTYuNWMwLTIuOC0xLTUuMy0zLjEtNy4zYy0yLjEtMi4xLTQuNS0zLjEtNy4zLTMuMUg5NEM2OC4xLDQ3LDQ2LDU2LjIsMjcuNiw3NC42QzkuMiw5MywwLDExNS4yLDAsMTQxLjF2MjI5LjljMCwyNS45LDkuMiw0OC4xLDI3LjYsNjYuNEM0Niw0NTUuOCw2OC4xLDQ2NSw5NCw0NjVoMTA0LjVjMi44LDAsNS4xLTAuNyw2LjctMi4xYzEuNi0xLjQsMi43LTMuNSwzLjMtNi40YzAuNS0yLjgsMC45LTUuNCwxLTcuN2MwLjEtMi4zLDAuMS01LjItMC4yLTguNkMyMDkuMSw0MzYuNywyMDksNDM0LjUsMjA5LDQzMy42eiIvPjxwYXRoIGNsYXNzPSJzdDAiIGQ9Ik01MDUuOCwyNDEuM0wzMjguMiw2My43Yy00LjEtNC4xLTktNi4yLTE0LjctNi4yYy01LjcsMC0xMC42LDIuMS0xNC43LDYuMmMtNC4xLDQuMS02LjIsOS02LjIsMTQuN3Y5NEgxNDYuM2MtNS43LDAtMTAuNiwyLjEtMTQuNyw2LjJjLTQuMSw0LjEtNi4yLDktNi4yLDE0Ljd2MTI1LjRjMCw1LjcsMi4xLDEwLjYsNi4yLDE0LjdjNC4xLDQuMSw5LDYuMiwxNC43LDYuMmgxNDYuM3Y5NGMwLDUuNywyLjEsMTAuNiw2LjIsMTQuN2M0LjEsNC4xLDksNi4yLDE0LjcsNi4yYzUuNywwLDEwLjYtMi4xLDE0LjctNi4ybDE3Ny42LTE3Ny42YzQuMS00LjEsNi4yLTksNi4yLTE0LjdDNTEyLDI1MC4zLDUwOS45LDI0NS40LDUwNS44LDI0MS4zeiIvPjwvZz48L2c+PC9zdmc+');
    }
  }
  nav {
    float: right;
    display: flex;
    flex-direction: row;
    align-items: stretch;
    align-items: center;
    justify-content: space-around;
    .nav-item {
      margin: 0 20px;
      a {
        position: relative;
        display: block;
        width: 100%;
        height: 100%;
        text-align: center;
        font-size: 16px;
        color: #fff;
        line-height: 80px;
        &.active, &:hover {
          &:before {
            position: absolute;
            content: '';
            width: 100%;
            height: 5px;
            background: #fff;;
            top: 0;
            left: 0;
            border-radius: 0 0 5px 5px;
          }
        }
      }
      svg {
        width: 15px;
        height: 15px;
        vertical-align: middle;
        margin-left: 10px;
      }
    }
  }
}

</style>
