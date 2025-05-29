<template>

    <Head :title="props.role?.id ? 'Edit Role & Permissions' : 'Create Role & Permissions'" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-semibold">
                    {{ props.role?.id ? 'Edit Role & Permissions' : 'Create Role & Permissions' }}
                </h1>
                <Link :href="route('roles.index')"
                    class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                Kembali
                </Link>
            </div>

            <form class="bg-white rounded-lg shadow p-6 space-y-6" @submit.prevent="submitForm">
                <div class="grid gap-2">
                    <Label for="name">Nama Role</Label>
                    <Input
                        id="name"
                        v-model="form.name"
                        type="text"
                        class="mt-1 block w-full"
                        placeholder="Masukkan nama role"
                    />
                    <InputError :message="form.errors.name" />
                </div>

                <div class="grid gap-2">
                    <Label for="permissions">Permissions</Label>
                    <div class="flex flex-wrap gap-2">
                        <div v-for="permission in props.permissions" :key="permission.id" class="flex items-center space-x-2">
                            <Checkbox 
                                :id="permission.name"
                                v-model="permissionStates[permission.name]"
                            />
                            <Label :for="permission.name">{{ permission.name }}</Label>
                        </div>
                    </div>

                    <InputError :message="form.errors.permissions" />
                </div>

                <div class="flex justify-end space-x-2">
                    <Link
                        :href="route('roles.index')"
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
import { ref, onMounted, reactive } from 'vue'
import { Head, Link, useForm } from '@inertiajs/vue3'
import { Loader2 } from 'lucide-vue-next'
import AppLayout from '@/layouts/AppLayout.vue'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import { Checkbox } from '@/components/ui/checkbox'
import { Button } from '@/components/ui/button'
import InputError from '@/components/InputError.vue'
import type { BreadcrumbItem } from '@/types'

interface Permission {
    id: number
    name: string
}

interface Props {
    role?: {
        id: number
        name: string
        permissions: Permission[]
    }
    permissions: Permission[]
}

const props = withDefaults(defineProps<Props>(), {
    role: undefined,
    permissions: () => []
})

const permissionStates = reactive<Record<string, boolean>>({})

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: route('dashboard') },
    { title: 'Manajemen Role', href: route('roles.index') }
]

const form = useForm({
    name: props.role?.name ?? '',
    permissions: [] as string[]
})

const submitForm = () => {
    // Convert permission states to array of selected permissions
    form.permissions = Object.entries(permissionStates)
        .filter(([_, checked]) => checked)
        .map(([name]) => name)

    if(props.role?.id){
        update()
    }else{
        create()
    }
}

function create(){
    form.post(route('roles.store'))
}

function update(){
    form.put(route('roles.update', props.role?.id))
}

onMounted(() => {
  props.permissions.forEach(permission => {
    permissionStates[permission.name] = props.role?.permissions?.some(p => p.name === permission.name) ?? false
  })
})

</script>