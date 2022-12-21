<template>
    <div
      class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom"
    >
      <h1 class="h2">TRANSAKSI</h1>
    </div>
    <div class="container mt-5">
      <div class="row">
        <div class="col-md-12">
          <div class="card border-0 rounded shadow">
            <div class="card-body">
              <table class="table table-striped table-bordered mt-4">
                <thead class="thead-dark">
                  <tr>
                    <th scope="col">JUDUL BUKU</th>
                    <th scope="col">HARGA</th>
                    <th scope="col">AKSI</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(transaksi, id) in transaksis" :key="id" >
                    <td v-if="transaksi.id_user == id_user">{{ transaksi.buku.judul_buku }}</td>
                    <td v-if="transaksi.id_user == id_user">{{ transaksi.buku.harga }}</td>
                    <td class="text-center" v-if="transaksi.id_user == id_user">
                      <router-link
                        :to="{
                          name: 'transaksi.update',
                          params: { id: transaksi.id },
                        }"
                        class="btn btn-sm btn-primary ml-1"
                      >
                        EDIT</router-link
                      >
                      <button
                        @click.prevent="bayar(transaksi.id)"
                        class="btn btn-sm btn-danger ml-1"
                      >
                        BAYAR
                      </button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </template>
  <script>
  import axios from "axios";
  import { onMounted, ref } from "vue";
  import { useRouter } from "vue-router";
  export default {
    setup() {
      const token = localStorage.getItem("token");
      const id_user = localStorage.getItem("id");
      const router = useRouter();
      //reactive state
      let transaksis = ref([]);
      //mounted
      onMounted(() => {
        //get API from Laravel Backend
        axios
          .get(`http://localhost:8000/api/transaksis`)
          .then((response) => {
            //assign state posts with response data
            transaksis.value = response.data.data;
          })
          .catch((error) => {
            console.log(error.response.data);
          });
      });
      //method delete
      function bayar(id) {
        //delete data post by ID
        axios
          .delete(`http://localhost:8000/api/transaksis/${id}`)
          .then(() => {
            // splice posts
            const index = this.transaksis.findIndex(
              (transaksi) => transaksi.id === id
            );
            if (~index) {
              // if the post exists in array
              this.transaksis.splice(index, 1);
            }
          })
          .catch((error) => {
            console.log(error.response.data);
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
        transaksis,
        id_user,
        logout,
        bayar,
      };
    },
  };
  </script>
  <style></style>
  