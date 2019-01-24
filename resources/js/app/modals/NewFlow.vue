<template lang="html">
  <modal
    name="new-flow"
    :adaptive="true"
    height="auto"
    width="520"
  >
    <form @submit="newFlow" class="content">
      <h3>New flow</h3>
      <input required class="theme-input" placeholder="Diagnosis" type="text" v-model="diagnosis">
      <b>PDF FILE</b>
      <input ref="file" type="file" accept="application/pdf" @change="attachDoc">
      <div v-if="progress">{{ progress }}</div>
      <div>
        type
      </div>
      <select v-model="type">
        <option value="dis">Discharge</option>
      </select>
      <div class="btn">
        <button class="theme-button">Create</button>
      </div>
    </form>
  </modal>
</template>

<script>
export default {
  name: 'modal-new-patient',
  props: {
    patient: {},
  },
  data() {
    return {
      diagnosis: '',
      id_file: 0,
      progress: null,
      type: '',
    };
  },
  methods: {
    newFlow(e) {
      if(this.progress == 'Uploading ...') {
        alert('File still uploading');
        return;
      }

      axios.post('/flow', {
        diagnosis: this.diagnosis,
        id_file: this.id_file,
        id_patient: this.patient,
        type: this.type,
      }).then(res => {
        this.$parent.sync();
        this.$modal.hide('new-flow');
        this.$router.push('/flow/' + res.data.id_flow);
      }).catch(res => {
        alert(res.data.error);
      });
      e.preventDefault();
    },
    attachDoc(e) {
      var data = new FormData();
      data.append('file', e.target.files[0]);
      this.progress = 'Uploading ...';
      this.$el.focus();
      axios.post('/file', data, {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      }).then(res => {
        this.progress = 'Done uploading!';
        this.id_file = res.data.id;
      }).catch(() => {
        this.id_file = 0;
        this.progress = null;
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
