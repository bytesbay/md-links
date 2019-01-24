<template lang="html">
  <main>
    <section class="content">
      <table>
        <thead>
          <tr>
            <th>Name</th>
            <th>Diagnosis</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="item in data">
            <td>
              <router-link :to="'/flow/' + item.id_flow">
                {{ item.name }}
              </router-link>
            </td>
            <td>{{ item.diagnosis }}</td>
          </tr>
        </tbody>
      </table>
    </section>
  </main>
</template>

<script>

export default {
  data() {
    return {
      data: [],
    };
  },
  methods: {
    sync() {
      axios.get('/flows/dis').then(res => {
        this.data = res.data;
      });
    }
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
