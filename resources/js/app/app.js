import Vue from 'vue';
import VueRouter from 'vue-router';
import Vuex from 'vuex';
import VueCookie from 'vue-cookie';
import VueTextareaAutosize from 'vue-textarea-autosize';
import VueModal from 'vue-js-modal'
import ScrollBar from 'vue2-scrollbar'


import Authorized from './components/Authorized.vue'
import Guest from './components/Guest.vue'
import Unloaded from './components/Unloaded.vue'

window.axios = require('axios');
axios.interceptors.response.use((response) => {
  return response;
}, function (error) {
  // Do something with response error
  // if (error.response.status === 401) {
  //     console.log('unauthorized, logging out ...');
  //     auth.logout();
  //     router.replace('/auth/login');
  // }
  return Promise.reject(error.response);
});
require("vue2-scrollbar/dist/style/vue2-scrollbar.css");

Vue.use(VueRouter);
Vue.use(Vuex);
Vue.use(VueCookie);
Vue.use(VueTextareaAutosize);
Vue.use(VueModal);
Vue.component('vue-scrollbar', ScrollBar);

import Index from './views/Index.vue';
import Chat from './views/Chat.vue';
import Profile from './views/Profile.vue';
import Group from './views/Group.vue';
import Flow from './views/Flow.vue';
import Flows from './views/Flows.vue';
import Patients from './views/Patients.vue';

import Login from './views/guest/Login.vue';
import Reg from './views/guest/Reg.vue';
import RecoverPass from './views/guest/RecoverPass.vue';
import ChangePass from './views/guest/ChangePass.vue';

const router = new VueRouter({
  routes: [
    { path: '/', component: Index, name: 'index' },
    { path: '/chat', component: Chat, name: 'chat' },
    { path: '/profile/:id_user?', component: Profile, name: 'profile' },
    { path: '/groups', component: Group, name: 'groups' },
    { path: '/flow/:id_flow', component: Flow, name: 'flow' },
    { path: '/flows', component: Flows, name: 'flows' },
    { path: '/login', component: Login, name: 'login' },
    { path: '/reg', component: Reg, name: 'reg' },
    { path: '/recover', component: RecoverPass, name: 'recover' },
    { path: '/recover/:key', component: ChangePass, name: 'change-pass' },
  ]
});

const store = new Vuex.Store({
  state: {
    user: {},
    chat: {
      forward: {
        toggled: false,
        messages: [],
      },
    },
    loaded: {
      user: false,
    },
    flows: [],
  },
  mutations: {
    getUserData(state, data) {
      axios.get('/user/info').then(res => {
        state.user = res.data;
        state.user.logged = true;
      });
    },
    authUser(state, data, callback) {
      axios.post('/user/login', data).then(res => {
        this.commit('getUserData');
        if(callback) callback();
      });
    },
  }
});

var routeMiddleware = function(route) {
  const authorized = [
    'index',
    'chat',
    'profile',
    'groups',
    'flow',
    'flows',
  ];

  const guest = [
    'login',
    'reg',
    'recover',
    'change-pass',
  ];

  if(!store.state.user.logged && authorized.includes(route))
    return 'login';
  else if(store.state.user.logged && authorized.includes(route))
    return route;
  else if(store.state.user.logged && guest.includes(route))
    return 'index';
  else if(!store.state.user.logged && guest.includes(route))
    return route;
}

router.beforeEach((to, from, next) => {
  if(!store.state.loaded.user)
    next();
  else {
    var route = routeMiddleware(to.name);
    if(route != to.name) {
      next({ name: route });
    } else {
      next();
    }
  }
});

const app = new Vue({
  el: '#app',
  router,
  store,
  data: {
    loaded: {
      user: false,
    },
  },
  methods: {
    checkMiddleware() {
      var route = routeMiddleware(this.$route.name)
      if(route != this.$route.name) {
        this.$router.replace({ name: route });
      }
    }
  },
  watch: {
    $route(to, from) {
      // if(from.name == 'index' && to.name == 'article') {
      //     this.route_anim_enter = 'animated slideInLeft';
      //     this.route_anim_leave = 'animated slideOutRight';
      // }
    }
  },
  components: {
    'guest': Guest,
    'authorized': Authorized,
    'unloaded': Unloaded,
  },
  created() {
    if(this.$cookie.get('auth')) {
      axios.get('/user/info').then(res => {
        this.$store.state.user = res.data;
        this.$store.state.user.logged = true;
        this.$store.state.loaded.user = true;
        this.checkMiddleware();
      }).catch(res => {
        this.$store.state.loaded.user = true;
        this.checkMiddleware();
      });
    } else {
      this.$store.state.loaded.user = true;
      this.checkMiddleware();
    }
  },
  mounted() {

  }
});
