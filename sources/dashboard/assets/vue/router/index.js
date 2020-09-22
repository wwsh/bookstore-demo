import Vue from "vue";
import VueRouter from "vue-router";
import store from "../store";
import Categories from "../views/Categories";
import Books from "../views/Books";

Vue.use(VueRouter);

let router = new VueRouter({
  mode: "history",
  routes: [
    { path: "/categories", component: Categories },
    { path: "/books", component: Books },
    { path: "*", redirect: "/books" }
  ],
});

router.beforeEach((to, from, next) => {
  if (to.matched.some(record => record.meta.requiresAuth)) {
    // this route requires auth, check if logged in
    // if not, redirect to login page.
    if (store.getters["security/isAuthenticated"]) {
      next();
    } else {
      next({
        path: "/login",
        query: { redirect: to.fullPath }
      });
    }
  } else {
    next(); // make sure to always call next()!
  }
});

export default router;
