<template lang="html">
  <main>
    <section class="content">
      <modal-new-patient/>
      <modal-new-flow :patient="selected_patient"/>
      <button class="theme-button" @click="$modal.show('new-patient')">Add new patient</button>
      <table>
        <thead>
          <tr>
            <th>Name</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="item in data">
            <td>{{ item.name }}</td>
            <td>{{ item.status }}</td>
            <td>
              <button class="theme-button" @click="createFlow(item.id_patient)">Create flow</button>
            </td>
          </tr>
        </tbody>
      </table>
    </section>
    <section class="side-news">
      <h2>News</h2>
      <div>
        <button class="theme-button-outline">Recent</button>
        <button class="theme-button">Popular</button>
      </div>
      <div class="news">
        <div class="item" v-for="i in [0,0,0,0,0,0,0,0,0,0,0,0,0,0]">
          <h4>Labore et dolore magna aliqua.</h4>
          <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
          <span>September 24, 2018</span>
          <button class="arrow">
            <i class="icon ion-ios-arrow-round-forward"></i>
          </button>
        </div>
      </div>
    </section>
  </main>
</template>

<script>
import NewFlow from './../modals/NewFlow.vue';
import NewPatient from './../modals/NewPatient.vue';

export default {
  data() {
    return {
      data: [],
      selected_patient: 0,
    };
  },
  methods: {
    sync() {
      axios.get('/patients').then(res => {
        this.data = res.data;
      });
    },
    createFlow(id_patient) {
      this.selected_patient = id_patient;
      this.$modal.show('new-flow')
    }
  },
  components: {
    'modal-new-flow': NewFlow,
    'modal-new-patient': NewPatient,
  },
  created() {
    this.sync();
  }
}
</script>

<style lang="scss" scoped>

@import '~@/vars.scss';

main {
  padding-right: 550px;
  .content {
    padding: 60px
  }
  .side-news {
    position: fixed;
    right: 0;
    bottom: 0;
    height: calc(100% - 80px);
    width: 500px;
    padding: 20px;
    overflow-y: scroll;
    h2 {
      font-size: 36px;
      color: $clr-d-pink;
    }
    .theme-button-outline {
      margin-right: 10px;
    }
    .news {
      .item {
        position: relative;
        padding: 10px 0;
        h4 {
          margin-bottom: 0;
          color: $clr-d-pink;
          font-weight: 500;
        }
        p {
          margin: 5px 0;
          font-size: 14px;
          color: $clr-dd-gray;
        }
        span {
          font-size: 12px;
          font-weight: 500;
          color: $clr-dd-gray;
        }
        button {
          width: 26px;
          height: 26px;
          font-size: 26px;
          position: absolute;
          right: 10px;
          bottom: 5px;
          color: $clr-d-pink;
        }
      }
    }
  }
  table {
    width: 100%;
    border-collapse: collapse;
    th {
      text-align: left;
      padding: 25px 40px;
      font-weight: 500;
    }
    tr {
      td {
        font-weight: 300;
        padding: 25px 40px;
        border: 0;
        border-bottom: 1px solid $clr-gray;
        &:last-child {
          border-right: 1px solid $clr-gray;
        }
        &:first-child {
          border-left: 1px solid $clr-gray;
        }
        &:nth-child(2) {
          font-weight: 500;
        }
      }
      &:first-child {
        td {
          border-top: 1px solid $clr-gray;
        }
      }
    }
  }
}

</style>
