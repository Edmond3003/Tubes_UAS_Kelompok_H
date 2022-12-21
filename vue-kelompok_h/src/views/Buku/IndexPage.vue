<template>
  <div
    class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom"
  >
    <h1 class="h2">BUKU</h1>
  </div>
  <div class="container mt-5">
    <div class="row">
      <div class="col-md-12">
        <div class="card border-0 rounded shadow">
          <div class="card-body">
            <router-link
              :to="{ name: 'buku.create' }"
              class="btn btn-md btn-success"
              >TAMBAH BUKU</router-link
            >
            <table class="table table-striped table-bordered mt-4">
              <thead class="thead-dark">
                <tr>
                  <th scope="col">JUDUL BUKU</th>
                  <th scope="col">JENIS BUKU</th>
                  <th scope="col">GENRE BUKU</th>
                  <th scope="col">HARGA BUKU</th>
                  <th scope="col">AKSI</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(buku, id) in bukus" :key="id">
                  <td>{{ buku.judul_buku }}</td>
                  <td>{{ buku.jenis_buku }}</td>
                  <td>{{ buku.genre_buku }}</td>
                  <td>{{ buku.harga_buku }}</td>
                  <td class="text-center">
                    <router-link
                      :to="{
                        name: 'buku.edit',
                        params: { id: buku.id },
                      }"
                      class="btn btn-sm btn-primary mr-1"
                    >
                      EDIT</router-link
                    >
                    <button
                      @click.prevent="bukuDelete(buku.id)"
                      class="btn btn-sm btn-danger ml-1"
                    >
                      DELETE
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
    const router = useRouter();
    //reactive state
    let bukus = ref([]);
    //mounted
    onMounted(() => {
      //get API from Laravel Backend
      axios
        .get(`http://localhost:8000/api/bukus`)
        .then((response) => {
          //assign state posts with response data
          bukus.value = response.data.data;
        })
        .catch((error) => {
          console.log(error.response.data);
        });
    });
    //method delete
    function bukuDelete(id) {
      //delete data post by ID
      axios
        .delete(`http://localhost:8000/api/bukus/${id}`)
        .then(() => {
          // splice posts
          const index = this.bukus.findIndex((buku) => buku.id === id);
          if (~index) {
            // if the post exists in array
            this.bukus.splice(index, 1);
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
      bukus,
      logout,
      bukuDelete,
    };
  },
};
</script>
<style></style>
