<template>
  <div class="container mt-5">
    <div class="row">
      <div class="col-md-4">
        <div class="card">
          <div class="card-body">
            MAIN MENU
            <hr />
            <ul class="list-group">
              <router-link
                :to="{ name: 'dashboard' }"
                class="list-group-item text-dark text-decoration-none"
                >DASHBOARD</router-link
              >
              <router-link
                :to="{ name: 'buku.index' }"
                class="list-group-item text-dark text-decoration-none"
                >BUKU</router-link
              >
              <router-link
                :to="{ name: 'wishlist.index' }"
                class="list-group-item text-dark text-decoration-none"
                >WISHLIST</router-link
              >
              <li
                @click.prevent="logout"
                class="list-group-item text-dark text-decoration-none"
                style="cursor: pointer"
              >
                LOGOUT
              </li>
            </ul>
          </div>
        </div>
      </div>
      <div class="col-md-8">
        <div class="card">
          <h4>TAMBAH WISHLIST</h4>
          <hr />
          <form @submit.prevent="store">
            <div class="form-group mb-13">
              <label for="content" class="form-label">JUDUL BUKU</label>
              <select class="form-control" v-model="wishlist.id_buku">
                <option selected disabled value="">PILIH BUKU</option>
                <option v-for="buku in bukus" :key="buku.id" :value="buku.id">
                  {{ buku.judul_buku }}
                </option>
              </select>
              <!-- validation -->
              <div v-if="validation.id_buku" class="mt-2 alert alert-danger">
                {{ validation.id_buku[0] }}
              </div>
            </div>
            <div class="form-group mb-3">
              <label for="content" class="form-label">Keterangan</label>
              <input
                class="form-control"
                v-model="wishlist.keterangan"
                placeholder="Masukkan Keterangan"
              />
              <!-- validation -->
              <div v-if="validation.keterangan" class="mt-2 alert alert-danger">
                {{ validation.keterangan[0] }}
              </div>
            </div>
            <div class="form-group mb-3">
              <label for="content" class="form-label">Status</label>
              <select class="form-control" v-model="wishlist.status">
                <option selected disabled value="">Pilih Status</option>
                <option class="py-1" value="1">Dibeli</option>
                <option class="py-1" value="0">Belum Dibeli</option>
              </select>
              <!-- validation -->
              <div v-if="validation.status" class="mt-2 alert alert-danger">
                {{ validation.status[0] }}
              </div>
            </div>
            <button type="submit" class="btn btn-primary">SIMPAN</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import { onMounted, reactive, ref } from "vue";
import { useRouter } from "vue-router";
import axios from "axios";
export default {
  setup() {
    const id_user = localStorage.getItem("id");
    const token = localStorage.getItem("token");
    //state departemen
    const wishlist = reactive({
      id_buku: "",
      keterangan: "",
      status: "",
    });
    //state validation
    const validation = ref([]);
    //vue router
    const router = useRouter();

    let bukus = ref([]);
    //mounted
    onMounted(() => {
      //get API from Laravel Backend
      axios
        .get("http://localhost:8000/api/bukus")
        .then((response) => {
          //assign state posts with response data
          bukus.value = response.data.data;
        })
        .catch((error) => {
          console.log(error.response.data);
        });
    });

    //method store
    function store() {
      let id_buku = wishlist.id_buku;
      let keterangan = wishlist.keterangan;
      let status = wishlist.status;
      axios
        .post("http://localhost:8000/api/wishlists", {
          id_user: id_user,
          id_buku: id_buku,
          keterangan: keterangan,
          status: status,
        })
        .then(() => {
          //redirect ke post index
          router.push({
            name: "wishlist.index",
          });
        })
        .catch((error) => {
          //assign state validation with error
          validation.value = error.response.data;
        });
    }
    function logout() {
      //logout
      axios.defaults.headers.common.Authorization = `Bearer ${token}`;
      axios
        .post("http://localhost:8000/api/logout")
        .then((response) => {
          if (response.data.success) {
            //remove localStorage
            localStorage.removeItem("token");

            //redirect ke halaman login
            return router.push({
              name: "login",
            });
          }
        })
        .catch((error) => {
          console.log(error.response.data);
        });
    }
    //return
    return {
      wishlist,
      bukus,
      validation,
      router,
      logout,
      store,
    };
  },
};
</script>
<style></style>
