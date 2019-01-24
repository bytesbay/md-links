<template lang="html">
  <main v-if="total_loaded">
    <modal-add-doctor
      :users="data.doctors"
      @added="data.doctors.push($event)"
      :flow="{
        id_flow: data.id_flow,
        name: data.name,
      }"
    />
    <header>
      <div class="patient-info">
        <div class="photo bg-img" v-bind:style="{ backgroundImage: `url(${data.img})` }"></div>
        <div class="info">
          <h2>
            <span>Patient</span>
            {{ data.name }}
            <span style="font-weight: bold; font-size: 14px;" v-if="data.ended">Finished</span>
          </h2>
          <div class="date">
            <span>Date</span>
            {{ formattedDate }}
          </div>
          <div class="disease">
            <span>Diagnosis</span>
            {{ data.diagnosis }}
          </div>
        </div>
      </div>
      <div class="doctors">
        <span class="title">Involved physicians:</span>
        <div class="doctor" v-for="doctor in data.doctors">
          <div class="avatar bg-img" v-bind:style="{ backgroundImage: `url(${doctor.img})` }"></div>
          <span class="name">{{ doctor.name }}</span>
        </div>
        <button
          v-if="data.doctors.length < 4"
          @click="$modal.show('add-doctor')"
          class="add-doctor"
        >Add doctor</button>
      </div>
    </header>
    <section>
      <steps :step="data.current_check" v-model="selected"/>
      <div v-if="!data.ended || selected < 5" class="table">
        <div class="title">Discharge</div>
        <table v-if="!getSelectedStep().substeps">
          <tr>
            <td>Title:</td>
            <td class="rtl step-title">{{ title }}</td>
          </tr>
          <tr>
            <td>Description:</td>
            <td class="rtl description" v-html="description"></td>
          </tr>
        </table>
        <div v-else dir="rtl" class="checklist">
          <div v-for="substep in getSelectedStep().substeps">
            <label>
              <input type="checkbox" v-model="checks" :value="substep.substep">
              <span v-html="substep.text"></span>
            </label>
          </div>
        </div>
      </div>
      <h1 v-else>
        Everything is finished.
      </h1>
    </section>
    <footer>
      <div class="comments">
        <div class="form">
          <textarea placeholder="Your comment" maxlength="254" v-model="comment"></textarea>
          <button @click="sendComment">
            <i class="icon ion-ios-mail"></i>
          </button>
        </div>
        <div class="cnt">
          <div v-for="item in comments[selected]" :key="item.id_comment" class="comment">
            <div
              class="avatar bg-img"
              v-bind:style="{ backgroundImage: `url(${getUserById(item.id_user).img})` }"
            ></div>
            <div v-html="item.text" class="content"></div>
          </div>
        </div>
      </div>
      <div v-if="data.current_check != 3" class="doctor">

      </div>
      <!-- <div v-else class="checks">
        {{ data.checked.filter(n => n.check_num == 3).length }} checks for this step
      </div> -->
      <div class="buttons">
        {{ !check_loaded ? 'Loading...' : '' }}
        <button :disabled="selected == 3 && checks.length != getSelectedStep().substeps.length" v-if="data.current_check == selected && !data.ended" class="accept" @click="check">
          {{ data.current_check != 5 ? 'Accept' : 'Finish' }}
          <i class="icon ion-ios-checkmark"></i>
        </button>
      </div>
    </footer>
  </main>
</template>

<script>
import Steps from './../components/flow/Steps.vue';
import AddDoctor from './../components/flow/AddDoctor.vue';
export default {
  data() {
    return {
      data: {},
      comments: [],
      comment: '',
      selected: 0,
      checks: [],
      check_loaded: true,
      total_loaded: false,
    };
  },
  methods: {
    getSelectedStep() {
      return this.data.steps.find(n => n.step == this.selected);
    },
    check() {
      this.check_loaded = false;
      // if(this.data.ended) return;
      axios.put('/flow/' + this.$route.params.id_flow + '/check').then(() => {
        this.sync();
        this.check_loaded = true;
      }).catch(res => {
        alert(res.data.error);
        this.check_loaded = true;
      }).finally(() => {
        console.log(123);
      });
    },
    sync() {
      axios.get('/flow/' + this.$route.params.id_flow).then(res => {
        this.selected = res.data.current_check;
        this.total_loaded = true;
        this.data = res.data;
      });
      this.getComments();
    },
    getComments() {
      axios.get('/flow/' + this.$route.params.id_flow + '/comments').then(res => {
        this.comments = res.data;
      });
    },
    getUserById(id_user) {
      return this.data.doctors.find(n => n.id_user == id_user);
    },
    sendComment() {
      if(this.comment.length == 0) return;
      axios.post('/flow/' + this.$route.params.id_flow + '/comment', {
        text: this.comment,
        step: this.selected,
      }).then(res => {
        this.comment = '';
        this.comments = res.data;
        this.getComments();
      }).catch(res => {
        alert(res.data.error);
      });
    },
  },
  watch: {
    $route(to, from) {
      this.sync();
    }
  },
  computed: {
    description() {
      if(this.selected != 5)
        return this.data.steps.find(n => n.step == this.selected).text
      else
        return 'If everything is ready - click finish';
    },
    title() {
      if(this.selected != 5)
        return this.data.steps.find(n => n.step == this.selected).title
      else
        return 'Final step';
    },
    formattedDate() {
      if(this.data.date) {
        var date = new Date(this.data.date * 1000);
        var monthNames = [
          "January", "February", "March",
          "April", "May", "June", "July",
          "August", "September", "October",
          "November", "December"
        ];
        var day = date.getDate();
        var monthIndex = date.getMonth();
        var year = date.getFullYear();
        return day + ' ' + monthNames[monthIndex] + ' ' + year;
      }
    },
  },
  created() {
    this.sync();
  },
  components: {
    'steps': Steps,
    'modal-add-doctor': AddDoctor,
  }
}
</script>

<style lang="scss" scoped>

@import '~@/vars.scss';

main {
  header {
    padding: 20px 50px;
    padding-bottom: 0;
    border-bottom: 1px solid $clr-gray;
    .patient-info {
      padding: 20px 0;
      border-bottom: 1px solid $clr-gray;
      display: flex;
      flex-direction: row;
      align-items: center;
      justify-content: flex-start;
      .photo {
        float: left;
        width: 100px;
        height: 100px;
        border-radius: 50%;
        box-shadow: $shadow;
      }
      .info {
        color: $clr-d-blue;
        margin-left: 40px;
        h2 {
          font-size: 30px;
          font-weight: 500;
          span {
            color: $clr-dd-gray;
            font-weight: 300;
          }
          margin-bottom: 5px;
          margin-top: 0;
        }
        .date,
        .disease {
          display: inline-block;
          font-weight: 500;
          span {
            font-weight: 300;
            color: $clr-m-gray;
          }
        }
        .date {
          margin-right: 20px;
        }
        .disease {

        }
      }
    }
    .doctors {
      display: flex;
      flex-direction: row;
      align-items: center;
      justify-content: flex-start;
      padding: 20px 0;
      .title {
        float: left;
        font-weight: 500;
        margin-right: 20px;
        color: $clr-d-blue;
      }
      .doctor {
        margin: 0 10px;
        .avatar {
          width: 40px;
          height: 40px;
          border-radius: 50%;
          float: left;
          margin-right: 10px;
        }
        .name {
          color: $clr-dd-gray;
          font-weight: 500;
          line-height: 40px;
        }
      }
    }
  }
  section {
    padding: 30px 50px;
    .table {
      padding-top: 30px;
      .title {
        font-size: 26px;
        color: $clr-d-blue;
        font-weight: bold;
        border-bottom: 1px solid $clr-gray;
        padding-bottom: 15px;
        margin-bottom: 15px;
      }
      table {
        tr {
          td {
            padding: 10px;
            color: $clr-m-gray;
            &:first-child {
              color: $clr-d-blue;
              font-weight: 500;
              vertical-align: top;
            }
            &.step-title {
              color: $clr-dd-gray;
              font-weight: bold;
            }
            &.description {
              color: $clr-dd-gray;
            }
          }
        }
        .rtl {
          direction: rtl;
        }
      }
    }
  }
  footer {
    padding: 30px 50px;
    padding-top: 10px;
    display: flex;
    flex-direction: row;
    align-items: center;
    justify-content: space-between;
    .comments {
      .form {
        box-shadow: 1.3px 4.8px 19px 0 rgba(0, 0, 0, 0.2);
        border-radius: 10px;
        height: 50px;
        textarea {
          appearance: none;
          border: 0;
          resize: none;
          background: transparent;
          padding: 5px;
          height: 50px;
        }
        button {
          float: right;
          color: $clr-d-pink;
          width: 40px;
          height: 40px;
          margin: 5px;
          font-size: 30px;
          &[disabled] {
            color: $clr-d-gray;
          }
        }
      }
      .comment {
        margin: 10px 0;
        min-height: 50px;
        padding: 5px;
        max-width: 370px;
        position: relative;
        .avatar {
          width: 40px;
          height: 40px;
          border-radius: 20px;
          position: absolute;
          left: 0;
          bottom: 5px;
        }
        .content {
          padding: 5px 10px;
          min-height: 40px;
          background: $clr-l-blue;
          word-break: break-word;
          margin-left: 45px;
          border-radius: 10px 10px 10px 0;
        }
      }
    }
    .accept {
      background: $clr-green;
      color: #fff;
      height: 50px;
      padding: 0 20px;
      line-height: 50px;
      text-transform: uppercase;
      font-weight: bold;
      border-radius: 25px;
      i {
        font-size: 28px;
      }
      &[disabled] {
        background: #ccc;
      }
    }
  }
}

// temp
.checklist {
  color: $clr-dd-gray;
  div {
    padding: 2px 0;
  }
  input {
    appearance: none;
    width: 20px;
    height: 20px;
    background: $clr-gray;
    border-radius: 50%;
    margin-left: 10px;
    cursor: pointer;
    vertical-align: middle;
    &:checked {
      background: $clr-green;
      background-position: center;
      background-repeat: no-repeat;
      background-size: 18px;
      background-image: url(data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIGZpbGw9IiNmZmYiIHZpZXdCb3g9IjAgMCA1MTIgNTEyIj48cGF0aCBkPSJNMzYyLjYgMTkyLjlMMzQ1IDE3NC44Yy0uNy0uOC0xLjgtMS4yLTIuOC0xLjItMS4xIDAtMi4xLjQtMi44IDEuMmwtMTIyIDEyMi45LTQ0LjQtNDQuNGMtLjgtLjgtMS44LTEuMi0yLjgtMS4yLTEgMC0yIC40LTIuOCAxLjJsLTE3LjggMTcuOGMtMS42IDEuNi0xLjYgNC4xIDAgNS43bDU2IDU2YzMuNiAzLjYgOCA1LjcgMTEuNyA1LjcgNS4zIDAgOS45LTMuOSAxMS42LTUuNWguMWwxMzMuNy0xMzQuNGMxLjQtMS43IDEuNC00LjItLjEtNS43eiIvPjwvc3ZnPg==);
}
  }
}

</style>
