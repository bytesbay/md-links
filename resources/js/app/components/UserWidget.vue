<template lang="html">
  <div class="profile" v-bind:class="{ opened: opened }">
    <div v-if="!$store.state.user.id_user" class="avatar bg-img"/>
    <div
        v-else
        class="avatar bg-img"
        @click="opened = !opened"
        v-bind:style="{ backgroundImage: `url(${$store.state.user.img})` }"
    />
    <transition
        enter-active-class="animated fadeInLeft"
        leave-active-class="animated fadeOutLeft"
    >
      <div class="popup" v-click-outside="hide" v-if="opened">
        <div class="head">
          <div
            v-bind:style="{ backgroundImage: `url(${$store.state.user.img})` }"
            class="photo bg-img"
          />
          <p>{{ $store.state.user.name }}</p>
        </div>
        <div class="body">
          <router-link to="/profile">
            <div @click="hide" class="button">
              <i class="icon ion-ios-contact"></i>
              Profile
            </div>
          </router-link>
          <div @click="hide" class="button">
            <i class="icon ion-ios-cog"></i>
            Settings
          </div>
          <div @click="hide" class="button">
            <i class="icon ion-ios-help-circle-outline"></i>
            Support
          </div>
        </div>
      </div>
    </transition>
  </div>
</template>

<script>
import ClickOutside from 'vue-click-outside'

export default {
  name: 'user-widget',
  data() {
    return {
      opened: false,
    };
  },
  methods: {
    hide() {
      this.opened = false;
    },
  },
  directives: {
    ClickOutside
  }
}
</script>

<style lang="scss" scoped>

@import '~@/vars.scss';

.profile {
  float: left;
  position: relative;
  width: 120px;
  background-color: $clr-dd-blue;
  height: 100%;
  &.opened {
    .avatar::before {
      transform: scale(-1);
    }
  }
  .avatar {
    user-select: none;
    margin-left: 30px;
    margin-top: 20px;
    position: relative;
    cursor: pointer;
    width: 40px;
    height: 40px;
    border-radius: 20px;
    &::before {
      position: absolute;
      right: -20px;
      top: 16px;
      content: '';
      width: 15px;
      height: 8px;
      transition: transform 0.5s ease;
      opacity: 0.5;
      background-position: center;
      background-repeat: no-repeat;
      background-size: contain;
      background-image: url('data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/PjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB2ZXJzaW9uPSIxLjEiIGlkPSJDYXBhXzEiIHg9IjBweCIgeT0iMHB4IiB3aWR0aD0iNTEycHgiIGhlaWdodD0iNTEycHgiIHZpZXdCb3g9IjAgMCAyOTIuMzYyIDI5Mi4zNjIiIHN0eWxlPSJlbmFibGUtYmFja2dyb3VuZDpuZXcgMCAwIDI5Mi4zNjIgMjkyLjM2MjsiIHhtbDpzcGFjZT0icHJlc2VydmUiPjxnPjxwYXRoIGQ9Ik0yODYuOTM1LDY5LjM3N2MtMy42MTQtMy42MTctNy44OTgtNS40MjQtMTIuODQ4LTUuNDI0SDE4LjI3NGMtNC45NTIsMC05LjIzMywxLjgwNy0xMi44NSw1LjQyNCAgIEMxLjgwNyw3Mi45OTgsMCw3Ny4yNzksMCw4Mi4yMjhjMCw0Ljk0OCwxLjgwNyw5LjIyOSw1LjQyNCwxMi44NDdsMTI3LjkwNywxMjcuOTA3YzMuNjIxLDMuNjE3LDcuOTAyLDUuNDI4LDEyLjg1LDUuNDI4ICAgczkuMjMzLTEuODExLDEyLjg0Ny01LjQyOEwyODYuOTM1LDk1LjA3NGMzLjYxMy0zLjYxNyw1LjQyNy03Ljg5OCw1LjQyNy0xMi44NDdDMjkyLjM2Miw3Ny4yNzksMjkwLjU0OCw3Mi45OTgsMjg2LjkzNSw2OS4zNzd6IiBmaWxsPSIjZmZmZmZmIi8+PC9nPjxnPjwvZz48Zz48L2c+PGc+PC9nPjxnPjwvZz48Zz48L2c+PGc+PC9nPjxnPjwvZz48Zz48L2c+PGc+PC9nPjxnPjwvZz48Zz48L2c+PGc+PC9nPjxnPjwvZz48Zz48L2c+PGc+PC9nPjwvc3ZnPg==');
    }
  }
  .popup {
    left: 10px;
    width: 240px;
    top: calc(100% + 10px);
    position: absolute;
    box-shadow: 1.3px 4.8px 29px 0 rgba(0, 0, 0, 0.2);
    background-color: #fff;
    border-radius: 10px;
    .head {
      border-radius: 10px 10px 0 0;
      width: 100%;
      height: 60px;
      background-color: $clr-d-blue;
      padding: 10px 20px;
      .photo {
        width: 40px;
        height: 40px;
        float: left;
        border-radius: 20px;
      }
      p {
        margin: 0;
        margin-left: 50px;
        height: 100%;
        line-height: 40px;
        font-size: 16px;
        font-weight: 600;
        color: #fff;
        text-overflow: ellipsis;
        overflow: hidden;
        white-space: nowrap;
      }
    }
    .body {
      padding: 10px 0;
      .button {
        // border-radius: 3px;
        width: 100%;
        cursor: pointer;
        padding: 5px 30px;
        user-select: none;
        color: $clr-black;
        i {
          vertical-align: sub;
          font-size: 26px;
          margin-right: 10px;
        }
        &:hover {
          background: transparentize($clr-pink, 0.7);
        }
      }
    }
  }
}

</style>
