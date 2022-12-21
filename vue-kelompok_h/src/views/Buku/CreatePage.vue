<template>
  <div class="container mt-5">
    <div class="row">
      <div class="col-md-12">
        <div class="card border-0 rounded shadow">
          <div class="card-body">
            <h4>TAMBAH BUKU</h4>
            <hr />
            <form @submit.prevent="store">
              <div class="form-group mb-3">
                <label class="form-label">Judul Buku</label>
                <input
                  type="text"
                  class="form-control"
                  v-model="buku.judul_buku"
                  placeholder="Masukkan judul buku"
                />
                <!-- validation -->
                <div
                  v-if="validation.judul_buku"
                  class="mt-2 alert alert-danger"
                >
                  {{ validation.judul_buku[0] }}
                </div>
              </div>
              <div class="form-group mb-3">
                <label for="content" class="form-label">Genre</label>
                <input
                  class="form-control"
                  v-model="buku.genre_buku"
                  placeholder="Masukkan genre"
                />
                <!-- validation -->
                <div
                  v-if="validation.genre_buku"
                  class="mt-2 alert alert-danger"
                >
                  {{ validation.genre_buku[0] }}
                </div>
              </div>
              <div class="form-group mb-3">
                <label for="content" class="form-label">Harga</label>
                <input
                  class="form-control"
                  v-model="buku.harga_buku"
                  placeholder="Masukkan harga"
                />
                <!-- validation -->
                <div
                  v-if="validation.harga_buku"
                  class="mt-2 alert alert-danger"
                >
                  {{ validation.harga_buku[0] }}
                </div>
              </div>
              <button type="submit" class="btn btn-primary">SIMPAN</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import { reactive, ref } from "vue";
import { useRouter } from "vue-router";
import axios from "axios";
export default {
  setup() {
    const token = localStorage.getItem("token");
    //state departemen
    const buku = reactive({
      judul_buku: "",
      jenis_buku: "",
      genre_buku: "",
      harga_buku: 0,
    });
    //state validation
    const validation = ref([]);
    //vue router
    const router = useRouter();
    //method store
    function store() {
      let judul_buku = buku.judul_buku;
      let jenis_buku = buku.jenis_buku;
      let genre_buku = buku.genre_buku;
      let harga_buku = buku.harga_buku;
      axios
        .post("http://localhost:8000/api/bukus", {
          judul_buku: judul_buku,
          jenis_buku: jenis_buku,
          genre_buku: genre_buku,
          harga_buku: harga_buku,
        })
        .then(() => {
          //redirect ke post index
          router.push({
            name: "buku.index",
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
      buku,
      validation,
      router,
      logout,
      store,
    };
  },
};
</script>
<style></style>
