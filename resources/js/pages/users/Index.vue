<template>

    <Head title="Manajemen Pengguna" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-semibold">Manajemen Pengguna</h1>
                <Link :href="route('users.create')"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                Tambah Pengguna
                </Link>
            </div>

            <div class="bg-white p-4 rounded-xl">
                <DataTable :data="users" :columns="columns" @update:pagination="getUsers($event.page, $event.perPage)"
                    :loading="loading">
                    <!-- Custom render untuk kolom roles -->
                    <template #roles="{ item }">
                        <span v-for="role in item.roles" :key="role.id"
                            class="mr-2 inline-flex items-center rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-800">
                            {{ role.name }}
                        </span>
                    </template>

                    <!-- Custom render untuk kolom status -->
                    <template #status="{ item }">
                        <span :class="[
                            'inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium',
                            item.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800',
                        ]">
                            {{ item.is_active ? 'Aktif' : 'Tidak Aktif' }}
                        </span>
                    </template>

                    <!-- Custom render untuk kolom actions -->
                    <template #actions="{ item }">
                        <DataDropdownActions :id="item.id" edit-route="users.edit" @delete="confirmDelete(item)" />
                    </template>
                </DataTable>
            </div>

            <!-- Delete Confirmation Dialog -->
                <Dialog :open="showDeleteModal" @update:open="closeDeleteModal">
                    <DialogContent>
                        <DialogHeader>
                            <DialogTitle>Konfirmasi Hapus</DialogTitle>
                            <DialogDescription>
                                Apakah Anda yakin ingin menghapus pengguna ini?
                            </DialogDescription>
                        </DialogHeader>
                        <DialogFooter>
                            <Button variant="outline" @click="closeDeleteModal">
                                Batal
                            </Button>
                            <Button variant="destructive" @click="deleteUser">
                                Hapus
                            </Button>
                        </DialogFooter>
                    </DialogContent>
                </Dialog>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { Head, Link } from '@inertiajs/vue3'
import axios from 'axios'
import AppLayout from '@/layouts/AppLayout.vue'
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog'
import { Button } from '@/components/ui/button'
import DataTable from '@/components/DataTable/Index.vue'
import DataDropdownActions from '@/components/DataTable/DataDropdownActions.vue'
import { useAlertStore } from '@/stores/alert'
import { useForm } from '@inertiajs/vue3'

const breadcrumbs = [
    { title: 'Dashboard', href: route('dashboard') },
    { title: 'Manajemen Pengguna', href: route('users.index') }
]

const users = ref({
    data: [],
    current_page: 1,
    per_page: 10,
    total: 0
})

const loading = ref(true)
const showDeleteModal = ref(false)
const userToDelete = ref(null)
const { showAlert } = useAlertStore()

const columns = [
    { key: 'no', label: 'No', class: 'text-left' },
    { key: 'name', label: 'Nama' },
    { key: 'email', label: 'Email' },
    { key: 'phone', label: 'Telepon' },
    { key: 'roles', label: 'Role' },
    { key: 'status', label: 'Status' },
    { key: 'actions', label: 'Aksi', class: 'text-right' }
]

const sleep = (ms) => new Promise(resolve => setTimeout(resolve, ms))

async function getUsers(page = 1, perPage = users.value.per_page) {
    loading.value = true
    try {
        await sleep(200)
        const response = await axios.get(route('users.data', {
            page,
            per_page: perPage
        }))
        users.value = response.data
    } catch (error) {
        console.error('Error loading users:', error)
        showAlert({
            title: 'Gagal',
            description: 'Gagal memuat pengguna',
            type: 'destructive'
        })
    } finally {
        loading.value = false
    }
}

const confirmDelete = (user) => {
    userToDelete.value = user
    showDeleteModal.value = true
}

const closeDeleteModal = () => {
    showDeleteModal.value = false
    userToDelete.value = null
}

const deleteForm = useForm({})

async function deleteUser() {
    loading.value = true
    deleteForm.delete(route('users.destroy', userToDelete.value.id), {
        onSuccess: () => {
            getUsers(1)
            closeDeleteModal()
        },
        onFinish: () => {
            loading.value = false
        }
    })
}

onMounted(() => {
    getUsers()
})
</script>
