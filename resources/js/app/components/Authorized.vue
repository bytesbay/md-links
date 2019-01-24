<template lang="html">
  <div id="templatewrapper">
    <app-header></app-header>
    <sidebar></sidebar>
    <router-view id="content"></router-view>
    <modal-create-group></modal-create-group>
  </div>
</template>

<script>
import Header from './Header.vue';
import Sidebar from './Sidebar.vue';
import CreateGroup from './../modals/CreateGroup.vue';

export default {
  name: 'authorized',
  components: {
    'app-header': Header,
    'sidebar': Sidebar,
    'modal-create-group': CreateGroup,
  },
  created() {
    axios.get('/flows').then(res => {
      this.$store.state.flows = res.data;
    });
  }
}
</script>

<style lang="scss" scoped>

#content {
  padding-top: 80px;
  width: 100%;
  padding-left: 120px;
  height: 100%;
}

</style>
