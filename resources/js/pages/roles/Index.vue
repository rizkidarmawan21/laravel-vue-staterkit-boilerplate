<template>
    <Head title="Manajemen Role" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-semibold">Manajemen Role</h1>
                <Link :href="route('roles.create')" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Tambah Role
                </Link>
            </div>

            <div class="bg-white p-4 rounded-xl">
                <DataTable :data="roles" :columns="columns" @update:pagination="getRoles($event.page, $event.perPage)"
                    :loading="loading">
                    <!-- Custom render untuk kolom permissions -->
                    <template #permissions="{ item }">
                        <span v-for="permission in item.permissions" :key="permission.id"
                            class="mr-2 inline-flex items-center rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-800">
                            {{ permission.name }}
                        </span>
                    </template>
                    <!-- Custom render untuk kolom actions -->
                    <template #actions="{ item }">
                        <DataDropdownActions :id="item.id" edit-route="roles.edit" @delete="confirmDelete(item)" />
                    </template>
                </DataTable>
            </div>

            <!-- Delete Confirmation Dialog -->
            <Dialog :open="showDeleteModal" @update:open="closeDeleteModal">
                <DialogContent>
                    <DialogHeader>
                        <DialogTitle>Konfirmasi Hapus</DialogTitle>
                        <DialogDescription>
                            Apakah Anda yakin ingin menghapus role ini?
                        </DialogDescription>
                    </DialogHeader>
                    <DialogFooter>
                        <Button variant="outline" @click="closeDeleteModal">
                            Batal
                        </Button>
                        <Button variant="destructive" @click="deleteRole">
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
import { useAlertStore } from '@/stores/alert'
import { useForm } from '@inertiajs/vue3'
import DataDropdownActions from '@/components/DataTable/DataDropdownActions.vue'

const breadcrumbs = [
    { title: 'Dashboard', href: route('dashboard') },
    { title: 'Manajemen Role', href: route('roles.index') }
]

const roles = ref({
    data: [],
    current_page: 1,
    per_page: 10,
    total: 0
})

const loading = ref(true)
const showFormModal = ref(false)
const showDeleteModal = ref(false)
const roleToDelete = ref(null)
const isEditing = ref(false)
const { showAlert } = useAlertStore()

const columns = [
    { key: 'no', label: 'No', class: 'text-left' },
    { key: 'name', label: 'Nama' },
    { key: 'permissions', label: 'Permissions' },
    { key: 'actions', label: 'Aksi', class: 'text-right' }
]

const form = useForm({
    name: ''
})

const sleep = (ms) => new Promise(resolve => setTimeout(resolve, ms))

async function getRoles(page = 1, perPage = roles.value.per_page) {
    loading.value = true
    try {
        await sleep(200)
        const response = await axios.get(route('roles.data', {
            page,
            per_page: perPage
        }))
        roles.value = response.data
    } catch (error) {
        console.error('Error loading roles:', error)
        showAlert({
            title: 'Gagal',
            description: 'Gagal memuat role',
            type: 'destructive'
        })
    } finally {
        loading.value = false
    }
}

const confirmDelete = (role) => {
    roleToDelete.value = role
    showDeleteModal.value = true
}

const closeDeleteModal = () => {
    showDeleteModal.value = false
    roleToDelete.value = null
}

const deleteForm = useForm({})

async function deleteRole() {
    loading.value = true
    deleteForm.delete(route('roles.destroy', roleToDelete.value.id), {
        onSuccess: () => {
            getRoles(1)
            closeDeleteModal()
        },
        onFinish: () => {
            loading.value = false
        }
    })
}

onMounted(() => {
    getRoles()
})
</script> 