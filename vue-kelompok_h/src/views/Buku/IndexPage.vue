<template>
  <div class="container-fluid mt-5">
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
          <h4>Buku</h4>
          <hr />
          <router-link
            :to="{ name: 'buku.create' }"
            class="btn btn-md btn-success"
            >TAMBAH BUKU</router-link
          >
          <table class="table table-striped table-bordered mt-4">
            <thead class="thead-dark">
              <tr>
                <th scope="col">JUDUL BUKU</th>
                <th scope="col">PENGARANGZ</th>
                <th scope="col">PENERBIT</th>
                <th scope="col">HARGA</th>
                <th scope="col">AKSI</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(buku, id) in bukus" :key="id">
                <td>{{ buku.judul_buku }}</td>
                <td>{{ buku.pengarang }}</td>
                <td>{{ buku.penerbit }}</td>
                <td>{{ buku.harga }}</td>
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
