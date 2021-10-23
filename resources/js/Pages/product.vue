<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Kelola Data Produk
            </h2>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg px-4 py-4">
                  
                    <!-- FLASH MESSAGE AKAN DITEMPATKAN DISINI -->
                    <div class="bg-green-100 border-t-4 border-green-500 rounded-b text-green-900 px-4 py-3 shadow-md my-3" role="alert" v-if="flash.message">
                        <div class="flex">
                            <div>
                                <p class="text-sm">{{ flash.message }}</p>
                            </div>
                        </div>
                    </div>
                    <button @click="openModal()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded my-3">Tambah Produk</button>
                    <table class="table-fixed w-full">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="px-4 py-2">Kode</th>
                                <th class="px-4 py-2">Nama Produk</th>
                                <th class="px-4 py-2">Harga</th>
                                <th class="px-4 py-2">Berat</th>
                                <th class="px-4 py-2">Aksi</th>
                            </tr>
                        </thead>
                        <tbody v-if="data.length > 0">
                            <tr v-for="(row, index) in data" :key="index">
                                <td class="border px-4 py-2">{{ row.code }}</td>
                                <td class="border px-4 py-2">{{ row.name }}</td>
                                <td class="border px-4 py-2">{{ row.price }}</td>
                                <td class="border px-4 py-2">{{ row.weight }} gr</td>
                                <td class="border px-4 py-2">
                                    <button @click="edit(row)" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mr-2">Edit</button>
                                    <button @click="deleteRow(row)" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">Delete</button>
                                </td>
                            </tr>
                        </tbody>
                        <tbody v-else>
                            <tr>
                                <td colspan="5" class="text-center">Tidak ada data</td>
                            </tr>
                        </tbody>
                    </table>
                    
                  	<!-- MODAL TAMBAH & EDIT PRODUK AKAN DITEMPATKAN DISINI -->
                      <div class="fixed z-10 inset-0 overflow-y-auto ease-out duration-400" v-if="isOpen">
                        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                            <div class="fixed inset-0 transition-opacity">
                                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                            </div>
                            <span class="hidden sm:inline-block sm:align-middle sm:h-screen"></span>​
                            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full" role="dialog" aria-modal="true" aria-labelledby="modal-headline">
                                <form>
                                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                        <div class="">
                                            <div class="mb-4">
                                                <label for="code" class="block text-gray-700 text-sm font-bold mb-2">Kode Produk</label>
                                                <input type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="code" v-model="form.code">
                                                <div v-if="errors.code" class="text-red-500">{{ errors.code }}</div>
                                            </div>
                                            <div class="mb-4">
                                                <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Nama Produk</label>
                                                <input type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="name" v-model="form.name">
                                                <div v-if="errors.name" class="text-red-500">{{ errors.name }}</div>
                                            </div>
                                            <div class="mb-4">
                                                <label for="price" class="block text-gray-700 text-sm font-bold mb-2">Harga</label>
                                                <input type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="price" v-model="form.price">
                                                <div v-if="errors.price" class="text-red-500">{{ errors.price }}</div>
                                            </div>
                                            <div class="mb-4">
                                                <label for="weight" class="block text-gray-700 text-sm font-bold mb-2">Berat</label>
                                                <input type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="weight" v-model="form.weight">
                                                <div v-if="errors.weight" class="text-red-500">{{ errors.weight }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                        <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                                            <button type="button" class="inline-flex justify-center w-full rounded-md border border-transparent px-4 py-2 bg-green-600 text-base leading-6 font-medium text-white shadow-sm hover:bg-green-500 focus:outline-none focus:border-green-700 focus:shadow-outline-green transition ease-in-out duration-150 sm:text-sm sm:leading-5" v-show="!editMode" @click="save">
                                                Save
                                            </button>
                                        </span>
                                        <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                                            <button type="button" class="inline-flex justify-center w-full rounded-md border border-transparent px-4 py-2 bg-green-600 text-base leading-6 font-medium text-white shadow-sm hover:bg-green-500 focus:outline-none focus:border-green-700 focus:shadow-outline-green transition ease-in-out duration-150 sm:text-sm sm:leading-5" v-show="editMode" @click="update">
                                                Update
                                            </button>
                                        </span>
                                        <span class="mt-3 flex w-full rounded-md shadow-sm sm:mt-0 sm:w-auto">
                                            <button @click="closeModal()" type="button" class="inline-flex justify-center w-full rounded-md border border-gray-300 px-4 py-2 bg-white text-base leading-6 font-medium text-gray-700 shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue transition ease-in-out duration-150 sm:text-sm sm:leading-5">
                                                Cancel
                                            </button>
                                        </span>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </app-layout>
</template>
<script>
    import AppLayout from '@/Layouts/AppLayout'
    import Welcome from '@/Jetstream/Welcome'
    export default {
        components: {
            AppLayout,
            Welcome,
        },
        props: ['data', 'errors', 'flash'],
        data() {
            return {
                editMode: false,
                isOpen: false,
                form: {
                    code: null,
                    name: null,
                    price: null,
                    weight: null,
                },
            }
        },
        methods: {
            openModal() {
                 this.isOpen = true;
            },
            closeModal() {
                this.isOpen = false; //TUTUP MODAL
                this.reset(); //RESET FORM
                this.editMode=false; //SET EDIT MODAL MENJADI FALSE JUGA
            },
            reset() {
                 this.form = {
                    code: null,
                    name: null,
                    price: null,
                    weight: null,
                }
            },
            save() {
            this.$inertia.post('/product', this.form, {
                preserveState: (page) => Object.keys(page.props.errors).length,
                onError: (page) => {
                    if (Object.keys(page).length == 0) {
                        this.reset();
                        this.closeModal();
                        this.editMode = false;  
                    }
                }
            })
            },
            edit(data) {
                    this.form = Object.assign({}, data);
                    this.editMode = true;
                    this.openModal();
            },
            update() {
                this.form._method = 'PUT';
                this.$inertia.post('/product/' + this.form.id, this.form, {
                    preserveState: (page) => Object.keys(page.props.errors).length,
                    onError: (page) => {
                        if (Object.keys(page).length == 0) {
                            this.reset();
                            this.closeModal();
                        }
                    }
                })
            },
            deleteRow(data) {
                    if (!confirm('Are you sure want to remove?')) return;
                    data._method = 'DELETE';
                    this.$inertia.post('/product/' + data.id, data)
                    this.reset();
                    this.closeModal();
            }
        }
    }
</script>
