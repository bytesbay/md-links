<template lang="html">
  <div class="sidebar">
    <router-link to="/" class="item" v-bind:class="{ active: $route.name == 'index' }">
      <svg width="512px" height="512px" enable-background="new 0 0 247.789 247.789" version="1.1" viewBox="0 0 247.789 247.789" xml:space="preserve" xmlns="http://www.w3.org/2000/svg">
        <path d="m223.78 122.9c-3.313 0-6 2.687-6 6v106.9h-64.384v-67.102c0-3.313-2.687-6-6-6h-47c-3.313 0-6 2.687-6 6v53.207c0 3.313 2.687 6 6 6s6-2.687 6-6v-47.207h35v61.102h-111.38v-146.86l93.884-75.239 96.132 77.04c2.586 2.073 6.362 1.655 8.434-0.93 2.072-2.586 1.656-6.361-0.93-8.434l-99.884-80.048c-2.192-1.758-5.312-1.758-7.504 0l-99.884 80.048c-1.421 1.139-2.248 2.86-2.248 4.682v155.74c0 3.313 2.686 6 6 6h199.77c3.314 0 6-2.687 6-6v-112.89c0-3.314-2.686-6-6-6z"/>
      </svg>
    </router-link>
    <flows-widget/>
    <router-link to="/chat" class="item" v-bind:class="{ active: $route.name == 'chat' }">
      <svg enable-background="new 0 0 489.6 489.6" version="1.1" viewBox="0 0 489.6 489.6" xml:space="preserve" xmlns="http://www.w3.org/2000/svg">
        <path d="m386.8 4.15c-34.1 0-61.8 27.7-61.8 61.8s27.7 61.8 61.8 61.8 61.8-27.7 61.8-61.8-27.7-61.8-61.8-61.8zm0 99.1c-20.6 0-37.3-16.7-37.3-37.3s16.7-37.3 37.3-37.3 37.3 16.7 37.3 37.3-16.7 37.3-37.3 37.3z"/>
        <path d="m244.8 127.75c34.1 0 61.8-27.7 61.8-61.8s-27.7-61.8-61.8-61.8-61.8 27.7-61.8 61.8 27.7 61.8 61.8 61.8zm0-99.1c20.6 0 37.3 16.7 37.3 37.3s-16.7 37.3-37.3 37.3-37.3-16.7-37.3-37.3c0-20.5 16.7-37.3 37.3-37.3z"/>
        <path d="m428.3 140.25h-52.6c-6.8 0-12.3 5.5-12.3 12.3s5.5 12.3 12.3 12.3h52.6c20.3 0 36.8 16.5 36.8 36.8v78.3c0 15.3-9.7 29.2-24.2 34.5-4.8 1.8-8 6.4-8 11.5v110.6c0 13.4-10.9 24.3-24.3 24.3h-55.6c-6.8 0-12.3 5.5-12.3 12.3s5.5 12.3 12.3 12.3h55.6c26.9 0 48.8-21.9 48.8-48.8v-103c19.6-10.6 32.2-31.3 32.2-53.9v-78.3c0-33.8-27.5-61.2-61.3-61.2z"/>
        <path d="m217 485.15h55.6c26.9 0 48.8-21.9 48.8-48.8v-102.7c19.6-10.6 32.2-31.3 32.2-53.9v-78.3c0-33.8-27.5-61.3-61.3-61.3h-95.1c-33.8 0-61.3 27.5-61.3 61.3v78.3c0 22.6 12.6 43.3 32.2 53.9v102.7c0.1 26.9 22 48.8 48.9 48.8zm-56.5-205.4v-78.3c0-20.3 16.5-36.8 36.8-36.8h95.1c20.3 0 36.8 16.5 36.8 36.8v78.3c0 15.3-9.7 29.2-24.1 34.5-4.8 1.8-8 6.4-8 11.5v110.6c0 13.4-10.9 24.3-24.3 24.3h-55.6c-13.4 0-24.3-10.9-24.3-24.3v-110.6c0-5.1-3.2-9.7-8-11.5-14.7-5.3-24.4-19.2-24.4-34.5z"/>
        <path d="m164.6 65.95c0-34.1-27.7-61.8-61.8-61.8s-61.8 27.7-61.8 61.8 27.7 61.8 61.8 61.8c34 0 61.8-27.7 61.8-61.8zm-99.1 0c0-20.5 16.7-37.3 37.3-37.3s37.3 16.7 37.3 37.3-16.7 37.3-37.3 37.3-37.3-16.7-37.3-37.3z"/>
        <path d="m81 485.15h55.6c6.8 0 12.3-5.5 12.3-12.3s-5.5-12.3-12.3-12.3h-55.6c-13.4 0-24.3-10.9-24.3-24.3v-110.5c0-5.1-3.2-9.7-8-11.5-14.4-5.3-24.1-19.1-24.1-34.5v-78.3c0-20.3 16.5-36.8 36.8-36.8h52.4c6.8 0 12.3-5.5 12.3-12.3s-5.5-12.3-12.3-12.3h-52.5c-33.8 0-61.3 27.5-61.3 61.3v78.3c0 22.6 12.6 43.3 32.2 53.9v102.7c0 27 21.9 48.9 48.8 48.9z"/>
      </svg>
    </router-link>
  </div>
</template>

<script>
import FlowsWidget from './FlowsWidget.vue';
export default {
  name: 'sidebar',
  data() {
    return {
      opened_flows: false,
    }
  },
  components: {
    FlowsWidget,
  },
}
</script>

<style lang="scss" scoped>

@import '~@/vars.scss';

.sidebar {
  top: 80px;
  left: 0;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  position: fixed;
  width: 120px;
  height: calc(100% - 80px);
  z-index: 50;
  background-color: #fafafa;
  border-right: 1px solid $clr-d-gray;
  .item {
    width: 70px;
    height: 70px;
    border-radius: 35px;
    text-align: center;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    margin: 20px 0;
    cursor: pointer;
    padding: 0;
    svg {
      fill: $clr-d-gray;
      max-width: 30px;
      max-height: 30px;
    }
    i {
      color: $clr-d-gray;
      font-size: 30px;
    }
    &.active {
      background: $clr-d-blue;
      svg {
        fill: #fff;
      }
      i {
        color: #fff;
      }
    }
  }
}

</style>
