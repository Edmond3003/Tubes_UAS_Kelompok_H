<template>
  <div
    class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom"
  >
    <h1 class="h2">WISHLIST</h1>
  </div>
  <div class="container mt-5">
    <div class="row">
      <div class="col-md-12">
        <div class="card border-0 rounded shadow">
          <div class="card-body">
            <router-link
              :to="{ name: 'wishlist.create' }"
              class="btn btn-md btn-success"
              >TAMBAH WISHLIST</router-link
            >
            <table class="table table-striped table-bordered mt-4">
              <thead class="thead-dark">
                <tr>
                  <th scope="col">JUDUL BUKU</th>
                  <th scope="col">KETERANGAN</th>
                  <th scope="col">STATUS</th>
                  <th scope="col">AKSI</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(wishlist, id) in wishlists" :key="id" >
                  <td v-if="wishlist.id_user == id_user">{{ wishlist.buku.judul_buku }}</td>
                  <td v-if="wishlist.id_user == id_user">{{ wishlist.keterangan }}</td>
                  <td v-if="wishlist.id_user == id_user">
                    {{ wishlist.status == 0 ? "Belum Dibeli" : "Dibeli" }}
                  </td>
                  <td class="text-center" v-if="wishlist.id_user == id_user">
                    <router-link
                      :to="{
                        name: 'wishlist.update',
                        params: { id: wishlist.id },
                      }"
                      class="btn btn-sm btn-primary ml-1"
                    >
                      EDIT</router-link
                    >
                    <button
                      @click.prevent="wishlistDelete(wishlist.id)"
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
    const id_user = localStorage.getItem("id");
    const router = useRouter();
    
    let wishlists = ref([]);
    //mounted
    onMounted(() => {
      //get API from Laravel Backend
      axios
        .get(`http://localhost:8000/api/wishlists`)
        .then((response) => {
          //assign state posts with response data
          wishlists.value = response.data.data;
        })
        .catch((error) => {
          console.log(error.response.data);
        });
    });
    //method delete
    function wishlistDelete(id) {
      
      axios
        .delete(`http://localhost:8000/api/wishlists/${id}`)
        .then(() => {
          // splice posts
          const index = this.wishlists.findIndex(
            (wishlist) => wishlist.id === id
          );
          if (~index) {
            // if the post exists in array
            this.wishlists.splice(index, 1);
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
      wishlists,
      id_user,
      logout,
      wishlistDelete,
    };
  },
};
</script>
<style></style>
