//import vue router
import { createRouter, createWebHistory } from "vue-router";

//define a routes
const routes = [
  {
    path: "/",
    name: "welcome",
    component: () => import("@/components/WelcomeLayout.vue"),
  },
  {
    path: "/login",
    name: "login",
    component: () => import("@/views/auth/Login.vue"),
  },
  {
    path: "/register",
    name: "register",
    component: () => import("@/views/auth/Register.vue"),
  },
  {
    path: "/beranda",
    name: "beranda",
    component: () => import("@/components/DashboardLayout.vue"),
    children: [
      {
        path: "/dashboard",
        name: "dashboard",
        component: () => import("@/views/dashboard/Index.vue"),
      },
      {
        path: "/update",
        name: "update",
        component: () => import("@/views/auth/Update.vue"),
      },
      {
        path: "/bukuIndex",
        name: "buku.index",
        component: () => import("@/views/Buku/IndexPage.vue"),
      },
      {
        path: "/bukuUpdate",
        name: "buku.edit",
        component: () => import("@/views/Buku/UpdatePage.vue"),
      },
      {
        path: "/bukuCreate",
        name: "buku.create",
        component: () => import("@/views/Buku/CreatePage.vue"),
      },
      {
        path: "/wishlistIndex",
        name: "wishlist.index",
        component: () => import("@/views/Wishlist/IndexPage.vue"),
      },
      {
        path: "/wishlistCreate",
        name: "wishlist.create",
        component: () => import("@/views/Wishlist/CreatePage.vue"),
      },
      {
        path: "/wishlistUpdate",
        name: "wishlist.update",
        component: () => import("@/views/Wishlist/UpdatePage.vue"),
      },
    ],
  },
];

//create router
const router = createRouter({
  history: createWebHistory(),
  routes, // config routes
});

export default router;
