<template>

    <div class="container-fluid mt-5">
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        MAIN MENU
                        <hr>
                        <ul class="list-group">
                            <router-link :to="{name: 'dashboard'}"
                                class="list-group-item text-dark text-decoration-none">DASHBOARD</router-link>
                            <router-link :to="{name: 'buku.index'}"
                                class="list-group-item text-dark text-decoration-none">BUKU</router-link>
                            <li @click.prevent="logout" class="list-group-item text-dark text-decoration-none"
                                style="cursor:pointer">LOGOUT</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card">
                    <h4>Update</h4>
                        <hr>
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
                                <label for="content" class="form-label">Pengarang</label>
                                <input
                                class="form-control"
                                v-model="buku.pengarang"
                                placeholder="Masukkan pengarang"
                                />
                                <!-- validation -->
                                <div
                                v-if="validation.pengarang"
                                class="mt-2 alert alert-danger"
                                >
                                {{ validation.pengarang[0] }}
                                </div>
                            </div>
                            <div class="form-group mb-3">
                                <label for="content" class="form-label">Penerbit</label>
                                <input
                                class="form-control"
                                v-model="buku.penerbit"
                                placeholder="Masukkan penerbit"
                                />
                                <!-- validation -->
                                <div
                                v-if="validation.penerbit"
                                class="mt-2 alert alert-danger"
                                >
                                {{ validation.penerbit[0] }}
                                </div>
                            </div>
                            <div class="form-group mb-3">
                                <label for="content" class="form-label">Harga</label>
                                <input
                                class="form-control"
                                type="number"
                                v-model="buku.harga"
                                placeholder="Masukkan harga"
                                />
                                <!-- validation -->
                                <div
                                v-if="validation.harga"
                                class="mt-2 alert alert-danger"
                                >
                                {{ validation.harga[0] }}
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block">UPDATE</button>
                        </form>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
    import { onMounted, reactive, ref } from 'vue'
    import { useRouter, useRoute } from 'vue-router'
    import axios from 'axios'

    export default {

        setup() {
            const token = localStorage.getItem('token')
            //inisialisasi vue router on Composition API
            const router = useRouter()
            const route = useRoute()
            //state user
            const buku = reactive({
                judul_buku: "",
                pengarang: "",
                penerbit: "",
                harga: 0
            })

            //state validation
            const validation = ref([])
            onMounted(() =>{

                axios
                .get(`http://localhost:8000/api/bukus/${route.params.id}`)
                .then((response) => {
                //assign state posts with response data
                buku.judul_buku=response.data.data.judul_buku
                buku.pengarang=response.data.data.pengarang
                buku.penerbit=response.data.data.penerbit
                buku.harga=response.data.data.harga
                })
                .catch((error) => {
                console.log(error.response.data);
                });
            })

                //method logout
            function logout() {

                //logout
                axios.defaults.headers.common.Authorization = `Bearer ${token}`
                axios.post('http://localhost:8000/api/logout')
                .then(response => {

                    if(response.data.success) {

                        //remove localStorage
                        localStorage.removeItem('token')

                        //redirect ke halaman login
                        return router.push({
                            name: 'login'
                        })

                    }

                })
                .catch(error => {
                    console.log(error.response.data)
                })

            }
            //method register
            function update() {

                //define variable 
                let judul_buku = buku.judul_buku;
                let pengarang = buku.pengarang;
                let penerbit = buku.penerbit;
                let harga = buku.harga;

                //send server with axios
                axios.put(`http://localhost:8000/api/bukus/${route.params.id}`, {
                        judul_buku: judul_buku,
                        pengarang: pengarang,
                        penerbit: penerbit,
                        harga: harga
                })
                .then(() => {

                    //redirect ke halaman login
                    return router.push({
                        name: 'buku.index'
                    })

                }).catch(error => {
                    //set validation dari error response
                    validation.value = error.response.data
                })

            }

            return {
                token,
                buku,
                validation,
                route,
                logout,
                update
            }

        }

    }
</script>