<template lang="html">
  <modal
    name="new-patient"
    :adaptive="true"
    height="auto"
    width="520"
  >
    <form @submit="newPatient" class="content">
      <h3>New patient</h3>
      <input required class="theme-input" placeholder="Patient name" type="text" v-model="name">
      <p>AVATAR</p>
      <input type="file" @change="attachDoc">
      <div v-if="progress">{{ progress }}</div>
      <select v-model="status">
        <option value="Dead">Dead</option>
        <option value="Almost dead">Almost dead</option>
        <option value="Healthy">Healthy</option>
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
  data() {
    return {
      name: '',
      id_file: 0,
      progress: null,
      status: null,
    };
  },
  methods: {
    newPatient(e) {
      if(this.progress == 'Uploading ...') {
        alert('File still uploading');
        return;
      }
      axios.post('/patient', {
        name: this.name,
        id_file: this.id_file,
        status: this.status,
      }).then(res => {
        this.$modal.hide('new-patient');
        this.$parent.sync();
      });
      e.preventDefault();
    },
    attachDoc(e) {
      var data = new FormData();
      data.append('file', e.target.files[0]);
      this.$el.focus();
      this.progress = 'Uploading ...';
      axios.post('/file', data, {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      }).then(res => {
        this.progress = 'Done uploading!';
        this.id_file = res.data.id;
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
