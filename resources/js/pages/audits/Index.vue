<template>
    <Head title="Log Audit" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-semibold">Log Audit</h1>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white p-4 rounded-xl border">
                    <div class="flex items-center">
                        <div class="p-2 bg-blue-100 rounded-lg">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-500">Total Log</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ stats.total }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-4 rounded-xl border">
                    <div class="flex items-center">
                        <div class="p-2 bg-green-100 rounded-lg">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-500">Hari Ini</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ stats.today }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-4 rounded-xl border">
                    <div class="flex items-center">
                        <div class="p-2 bg-yellow-100 rounded-lg">
                            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-500">Minggu Ini</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ stats.week }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-4 rounded-xl border">
                    <div class="flex items-center">
                        <div class="p-2 bg-purple-100 rounded-lg">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-500">Bulan Ini</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ stats.month }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white p-4 rounded-xl border">
                <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pencarian</label>
                        <Input v-model="filters.search" placeholder="Cari pengguna, IP, URL..." />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Event</label>
                        <Select v-model="filters.event">
                            <SelectTrigger>
                                <SelectValue placeholder="Pilih event" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectItem value="all">Semua Event</SelectItem>
                                <SelectItem v-for="(label, value) in eventOptions" :key="value" :value="value">
                                    {{ label }}
                                </SelectItem>
                            </SelectContent>
                        </Select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Model</label>
                        <Select v-model="filters.model">
                            <SelectTrigger>
                                <SelectValue placeholder="Pilih model" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectItem value="all">Semua Model</SelectItem>
                                <SelectItem v-for="(label, value) in (filterOptions?.models || {})" :key="value" :value="value">
                                    {{ label }}
                                </SelectItem>
                            </SelectContent>
                        </Select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai</label>
                        <Input v-model="filters.startDate" type="date" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Akhir</label>
                        <Input v-model="filters.endDate" type="date" />
                    </div>
                    <div class="flex items-end">
                        <Button @click="applyFilters" class="w-full">
                            Filter
                        </Button>
                    </div>
                </div>
            </div>

            <!-- Data Table -->
            <div class="bg-white p-4 rounded-xl border">
                <DataTable :data="audits" :columns="columns" @update:pagination="getAudits($event.page, $event.perPage)"
                    :loading="loading">
                    <!-- Custom render untuk kolom user -->
                    <template #user_name="{ item }">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center mr-2">
                                <svg class="w-4 h-4 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <span class="font-medium">{{ item.user_name || 'System' }}</span>
                        </div>
                    </template>

                    <!-- Custom render untuk kolom event -->
                    <template #event_label="{ item }">
                        <span :class="[
                            'inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium',
                            getEventColor(item.event)
                        ]">
                            {{ item.event_label }}
                        </span>
                    </template>

                    <!-- Custom render untuk kolom model -->
                    <template #model_name="{ item }">
                        <div>
                            <div class="font-medium">{{ item.model_name }}</div>
                            <div class="text-sm text-gray-500">#{{ item.auditable_id }}</div>
                        </div>
                    </template>

                    <!-- Custom render untuk kolom waktu -->
                    <template #created_at_formatted="{ item }">
                        <div>
                            <div class="font-medium">{{ item.created_at_formatted }}</div>
                            <div class="text-sm text-gray-500">{{ item.created_at_human }}</div>
                        </div>
                    </template>

                    <!-- Custom render untuk kolom actions -->
                    <template #actions="{ item }">
                        <Button variant="outline" size="sm" @click="viewDetail(item.id)">
                            Detail
                        </Button>
                    </template>
                </DataTable>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue'
import { Head, router } from '@inertiajs/vue3'
import axios from 'axios'
import AppLayout from '@/layouts/AppLayout.vue'
import DataTable from '@/components/DataTable/Index.vue'
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select'
import { useAlertStore } from '@/stores/alert'

const props = defineProps({
    filterOptions: Object
})

const breadcrumbs = [
    { title: 'Dashboard', href: route('dashboard') },
    { title: 'Log Audit', href: route('audits.index') }
]

const audits = ref({
    data: [],
    current_page: 1,
    per_page: 15,
    total: 0
})

const stats = ref({
    total: 0,
    today: 0,
    week: 0,
    month: 0
})

const loading = ref(true)
const { showAlert } = useAlertStore()

const filters = ref({
    search: '',
    event: 'all',
    model: 'all',
    startDate: '',
    endDate: ''
})

const eventOptions = {
    'created': 'Dibuat',
    'updated': 'Diperbarui',
    'deleted': 'Dihapus',
    'restored': 'Dipulihkan',
    'login': 'Login',
    'logout': 'Logout',
    'login_failed': 'Login Gagal'
}

const columns = [
    { key: 'no', label: 'No', class: 'text-left w-16' },
    { key: 'user_name', label: 'Pengguna' },
    { key: 'event_label', label: 'Event' },
    { key: 'model_name', label: 'Model' },
    { key: 'ip_address', label: 'IP Address' },
    { key: 'created_at_formatted', label: 'Waktu' },
    { key: 'actions', label: 'Aksi', class: 'text-right' }
]

const sleep = (ms) => new Promise(resolve => setTimeout(resolve, ms))

async function getAudits(page = 1, perPage = audits.value.per_page) {
    loading.value = true
    try {
        await sleep(200)
        const params = {
            page,
            per_page: perPage,
            search: filters.value.search || undefined,
            event: filters.value.event === 'all' ? undefined : filters.value.event,
            model: filters.value.model === 'all' ? undefined : filters.value.model,
            start_date: filters.value.startDate || undefined,
            end_date: filters.value.endDate || undefined
        }
        
        // Remove undefined values
        Object.keys(params).forEach(key => params[key] === undefined && delete params[key])
        
        const response = await axios.get(route('audits.data'), { params })
        audits.value = response.data
    } catch (error) {
        console.error('Error loading audits:', error)
        showAlert({
            title: 'Gagal',
            description: 'Gagal memuat log audit',
            type: 'destructive'
        })
    } finally {
        loading.value = false
    }
}

async function getStats() {
    try {
        const response = await axios.get(route('audits.stats'))
        stats.value = response.data
    } catch (error) {
        console.error('Error loading stats:', error)
    }
}

function getEventColor(event) {
    const colors = {
        'created': 'bg-green-100 text-green-800',
        'updated': 'bg-blue-100 text-blue-800',
        'deleted': 'bg-red-100 text-red-800',
        'restored': 'bg-yellow-100 text-yellow-800',
        'login': 'bg-emerald-100 text-emerald-800',
        'logout': 'bg-orange-100 text-orange-800',
        'login_failed': 'bg-red-100 text-red-800'
    }
    return colors[event] || 'bg-gray-100 text-gray-800'
}

function applyFilters() {
    getAudits(1)
}

function viewDetail(id) {
    router.visit(route('audits.show', id))
}

onMounted(() => {
    getAudits()
    getStats()
})

// Watch filters untuk real-time search
watch(() => filters.value.search, () => {
    if (filters.value.search.length === 0 || filters.value.search.length >= 3) {
        getAudits(1)
    }
}, { debounce: 500 })
</script> 