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
          <h4>Wishlist</h4>
          <hr />
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
              <tr v-for="(wishlist, id) in wishlists" :key="id">
                <td>{{ wishlist.buku.judul_buku }}</td>
                <td>{{ wishlist.keterangan }}</td>
                <td>{{ wishlist.status == 0 ? "Belum Dibeli" : "Dibeli" }}</td>
                <td class="text-center">
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
      //delete data post by ID
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
      logout,
      wishlistDelete,
    };
  },
};
</script>
<style></style>
