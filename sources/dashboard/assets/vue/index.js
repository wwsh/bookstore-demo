import Vue from "vue";
import App from "./App";
import router from "./router";
import store from "./store";
import vuetify from './plugins/vuetify'

new Vue({
  vuetify,
  components: { App },
  template: "<App/>",
  router,
  store
}).$mount("#app");
