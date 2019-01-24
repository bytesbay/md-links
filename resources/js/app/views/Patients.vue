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
