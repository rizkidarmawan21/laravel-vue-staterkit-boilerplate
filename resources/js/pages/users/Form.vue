<template>

    <Head :title="props.user?.id ? 'Edit User' : 'Create User'" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-semibold">
                    {{ props.user?.id ? 'Edit User' : 'Create User' }}
                </h1>
                <Link :href="route('users.index')"
                    class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                Kembali
                </Link>
            </div>

            <form @submit.prevent="submitForm" class="bg-white rounded-lg shadow p-6 space-y-6">
                <div class="grid gap-2">
                    <Label for="name">Nama</Label>
                    <Input
                        id="name"
                        v-model="form.name"
                        type="text"
                        class="mt-1 block w-full"
                        placeholder="Masukkan nama"
                    />
                    <InputError :message="form.errors.name" />
                </div>

                <div class="grid gap-2">
                    <Label for="email">Email</Label>
                    <Input
                        id="email"
                        v-model="form.email"
                        type="email"
                        class="mt-1 block w-full"
                        placeholder="Masukkan email"
                    />
                    <InputError :message="form.errors.email" />
                </div>

                <div class="grid gap-2">
                    <Label for="phone">Nomor Telepon</Label>
                    <Input
                        id="phone"
                        v-model="form.phone"
                        type="tel"
                        class="mt-1 block w-full"
                        placeholder="Masukkan nomor telepon"
                    />
                    <InputError :message="form.errors.phone" />
                </div>

                <div class="grid gap-2">
                    <Label for="address">Alamat</Label>
                    <Input
                        id="address"
                        v-model="form.address"
                        type="text"
                        class="mt-1 block w-full"
                        placeholder="Masukkan alamat"
                        rows="3"
                    />
                    <InputError :message="form.errors.address" />
                </div>

                <div v-if="!props.user?.id" class="grid gap-2">
                    <Label for="password">Password</Label>
                    <Input
                        id="password"
                        v-model="form.password"
                        type="password"
                        class="mt-1 block w-full"
                        placeholder="Masukkan password"
                    />
                    <InputError :message="form.errors.password" />
                </div>

                <div class="grid gap-2">
                    <Label for="roles">Role</Label>
                    <Select v-model="form.roles" multiple>
                        <SelectTrigger>
                            <SelectValue placeholder="Pilih role" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem 
                                v-for="role in roles" 
                                :key="role.id" 
                                :value="role.name"
                            >
                                {{ role.name }}
                            </SelectItem>
                        </SelectContent>
                    </Select>
                    <InputError :message="form.errors.roles" />
                </div>

                <div class="flex items-center space-x-2">
                    <Checkbox 
                        id="is_active" 
                        v-model="form.is_active"
                    />
                    <Label for="is_active">User Aktif</Label>
                    <InputError :message="form.errors.is_active" />
                </div>

                <div class="flex justify-end space-x-2">
                    <Link
                        :href="route('users.index')"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50"
                    >
                        Batal
                    </Link>
                    <Button :disabled="form.processing">
                        <Loader2 class="size-4 animate-spin" v-if="form.processing" />
                        Simpan
                    </Button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import { onMounted } from 'vue'
import { Head, Link, router, useForm } from '@inertiajs/vue3'
import { Loader2 } from 'lucide-vue-next'
import AppLayout from '@/layouts/AppLayout.vue'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import { Checkbox } from '@/components/ui/checkbox'
import { Button } from '@/components/ui/button'
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select'
import InputError from '@/components/InputError.vue'
import type { BreadcrumbItem } from '@/types'

interface Role {
    id: number
    name: string
    guard_name: string
    created_at: string
    updated_at: string
    pivot?: {
        model_type: string
        model_id: number
        role_id: number
    }
}

interface User {
    id?: number
    name: string
    email: string
    phone: string
    address: string
    password?: string
    roles: Role[]
    is_active: boolean
}

interface Props {
    user?: User
    roles?: Array<{ id: number; name: string }>
}

const props = withDefaults(defineProps<Props>(), {
    user: () => ({
        name: '',
        email: '',
        phone: '',
        address: '',
        roles: [],
        is_active: true
    }),
    roles: () => []
})

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: props.user?.id ? 'Edit User' : 'Create User',
        href: props.user?.id ? route('users.edit', props.user.id) : route('users.create')
    }
]

const form = useForm({
    id: props.user?.id,
    name: props.user?.name || '',
    email: props.user?.email || '',
    phone: props.user?.phone || '',
    address: props.user?.address || '',
    password: '',
    roles: props.user?.roles?.map(role => role.name) || [],
    is_active: props.user?.is_active ?? true
})

const submitForm = async () => {
    if (props.user?.id) {
        updateUser()
    } else {
        createUser()
    }
}

function createUser() {
    form.post(route('users.store'), {
        preserveScroll: true,
    })
}

function updateUser() {
    form.put(route('users.update', props.user.id), {
        preserveScroll: true,
    })
}

onMounted(() => {
    if (props.user?.roles) {
        form.roles = props.user.roles.map(role => role.name)
    }
})
</script>