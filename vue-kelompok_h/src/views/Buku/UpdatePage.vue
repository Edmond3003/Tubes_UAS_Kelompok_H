<template>
  <div class="container mt-5">
    <div class="row">
      <div class="col-md-12">
        <div class="card border-0 rounded shadow">
          <div class="card-body">
            <h4>EDIT WISHLIST</h4>
            <hr />
            <form @submit.prevent="update">
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
              <button type="submit" class="btn btn-primary">UPDATE</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { onMounted, reactive, ref } from "vue";
import { useRouter, useRoute } from "vue-router";
import axios from "axios";

export default {
  setup() {
    const token = localStorage.getItem("token");
    //inisialisasi vue router on Composition API
    const router = useRouter();
    const route = useRoute();
    //state user
    const buku = reactive({
      judul_buku: "",
      jenis_buku: "",
      genre_buku: "",
      harga_buku: 0,
    });

    //state validation
    const validation = ref([]);
    onMounted(() => {
      axios
        .get(`http://localhost:8000/api/bukus/${route.params.id}`)
        .then((response) => {
          //assign state posts with response data
          buku.judul_buku = response.data.data.judul_buku;
          buku.jenis_buku = response.data.data.jenis_buku;
          buku.genre_buku = response.data.data.genre_buku;
          buku.harga_buku = response.data.data.harga_buku;
        })
        .catch((error) => {
          console.log(error.response.data);
        });
    });

    //method logout
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
    //method register
    function update() {
      //define variable
      let judul_buku = buku.judul_buku;
      let pengarang = buku.pengarang;
      let penerbit = buku.penerbit;
      let harga = buku.harga;

      //send server with axios
      axios
        .put(`http://localhost:8000/api/bukus/${route.params.id}`, {
          judul_buku: judul_buku,
          pengarang: pengarang,
          penerbit: penerbit,
          harga: harga,
        })
        .then(() => {
          //redirect ke halaman login
          return router.push({
            name: "buku.index",
          });
        })
        .catch((error) => {
          //set validation dari error response
          validation.value = error.response.data;
        });
    }

    return {
      token,
      buku,
      validation,
      route,
      logout,
      update,
    };
  },
};
</script>
