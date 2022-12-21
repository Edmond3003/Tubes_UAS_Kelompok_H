<template>
    <div class="container mt-5">
      <div class="row">
        <div class="col-md-12">
          <div class="card border-0 rounded shadow">
            <div class="card-body">
              <h4>EDIT TRANSAKSI</h4>
              <hr />
              <form @submit.prevent="update">
                <div class="form-group mb-13">
                  <label for="content" class="form-label">JUDUL BUKU</label>
                  <select class="form-control" v-model="transaksi.id_buku">
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
                <button type="submit" class="btn btn-primary">SIMPAN</button>
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
      //state validation
      const validation = ref([]);
      let bukus = ref([]);
      //state user
      const transaksi = reactive({
        id_buku: "",
      });
  
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
  
      onMounted(() => {
        //get API from Laravel Backend
        axios
          .get(`http://localhost:8000/api/transaksis/${route.params.id}`)
          .then((response) => {
            //assign state departemen with response data
            transaksi.id_buku = response.data.id_buku;
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
        let id_buku = transaksi.id_buku;
  
        //send server with axios
        axios
          .put(`http://localhost:8000/api/transaksis/${route.params.id}`, {
            id_buku: id_buku,
          })
          .then(() => {
            //redirect ke halaman login
            return router.push({
              name: "transaksi.index",
            });
          })
          .catch((error) => {
            //set validation dari error response
            validation.value = error.response.data;
          });
      }
  
      return {
        transaksi,
        token,
        bukus,
        validation,
        route,
        logout,
        update,
      };
    },
  };
  </script>
  