<template>
    <Head title="Detail Log Audit" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-semibold">Detail Log Audit</h1>
                <Button variant="outline" @click="$inertia.visit(route('audits.index'))">
                    Kembali
                </Button>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Basic Information -->
                <div class="bg-white p-6 rounded-xl border">
                    <h2 class="text-lg font-semibold mb-4">Informasi Dasar</h2>
                    <div class="space-y-4">
                        <div class="flex justify-between">
                            <span class="text-gray-600">ID:</span>
                            <span class="font-medium">#{{ audit.id }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Pengguna:</span>
                            <span class="font-medium">{{ audit.user_name }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Event:</span>
                            <span :class="[
                                'inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium',
                                getEventColor(audit.event)
                            ]">
                                {{ audit.event_label }}
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Model:</span>
                            <span class="font-medium">{{ audit.model_name }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Model ID:</span>
                            <span class="font-medium">#{{ audit.auditable_id }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Waktu:</span>
                            <div class="text-right">
                                <div class="font-medium">{{ audit.created_at_formatted }}</div>
                                <div class="text-sm text-gray-500">{{ audit.created_at_human }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Technical Information -->
                <div class="bg-white p-6 rounded-xl border">
                    <h2 class="text-lg font-semibold mb-4">Informasi Teknis</h2>
                    <div class="space-y-4">
                        <div class="flex justify-between">
                            <span class="text-gray-600">IP Address:</span>
                            <span class="font-medium">{{ audit.ip_address || '-' }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">URL:</span>
                            <div class="mt-1 p-2 bg-gray-50 rounded text-sm break-all">
                                {{ audit.url || '-' }}
                            </div>
                        </div>
                        <div>
                            <span class="text-gray-600">User Agent:</span>
                            <div class="mt-1 p-2 bg-gray-50 rounded text-sm break-all">
                                {{ audit.user_agent || '-' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Changes Comparison -->
            <div v-if="audit.old_values || audit.new_values" class="bg-white p-6 rounded-xl border">
                <h2 class="text-lg font-semibold mb-4">Perubahan Data</h2>
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Old Values -->
                    <div v-if="audit.old_values">
                        <h3 class="text-md font-medium mb-3 text-red-600">Data Lama</h3>
                        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                            <div v-if="Object.keys(audit.old_values).length === 0" class="text-gray-500 italic">
                                Tidak ada data lama
                            </div>
                            <div v-else class="space-y-2">
                                <div v-for="(value, key) in audit.old_values" :key="key" class="flex justify-between border-b border-red-100 pb-2">
                                    <span class="font-medium">{{ formatFieldName(key) }}:</span>
                                    <span class="text-right">{{ formatValue(value) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- New Values -->
                    <div v-if="audit.new_values">
                        <h3 class="text-md font-medium mb-3 text-green-600">Data Baru</h3>
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                            <div v-if="Object.keys(audit.new_values).length === 0" class="text-gray-500 italic">
                                Tidak ada data baru
                            </div>
                            <div v-else class="space-y-2">
                                <div v-for="(value, key) in audit.new_values" :key="key" class="flex justify-between border-b border-green-100 pb-2">
                                    <span class="font-medium">{{ formatFieldName(key) }}:</span>
                                    <span class="text-right">{{ formatValue(value) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Changes Summary -->
                <div v-if="hasChanges" class="mt-6">
                    <h3 class="text-md font-medium mb-3">Ringkasan Perubahan</h3>
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="space-y-2">
                            <div v-for="change in getChanges()" :key="change.field" class="flex items-center text-sm">
                                <span class="font-medium mr-2">{{ formatFieldName(change.field) }}:</span>
                                <span class="text-red-600 line-through mr-2">{{ formatValue(change.old) }}</span>
                                <span class="text-gray-400 mr-2">→</span>
                                <span class="text-green-600">{{ formatValue(change.new) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Raw Data (for debugging) -->
            <div class="bg-white p-6 rounded-xl border">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-semibold">Data Mentah</h2>
                    <Button variant="outline" size="sm" @click="showRawData = !showRawData">
                        {{ showRawData ? 'Sembunyikan' : 'Tampilkan' }}
                    </Button>
                </div>
                
                <div v-if="showRawData" class="space-y-4">
                    <div>
                        <h3 class="font-medium mb-2">Old Values JSON:</h3>
                        <pre class="bg-gray-100 p-3 rounded text-sm overflow-auto">{{ JSON.stringify(audit.old_values, null, 2) }}</pre>
                    </div>
                    <div>
                        <h3 class="font-medium mb-2">New Values JSON:</h3>
                        <pre class="bg-gray-100 p-3 rounded text-sm overflow-auto">{{ JSON.stringify(audit.new_values, null, 2) }}</pre>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Head } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import { Button } from '@/components/ui/button'

const props = defineProps({
    audit: Object
})

const showRawData = ref(false)

const breadcrumbs = [
    { title: 'Dashboard', href: route('dashboard') },
    { title: 'Log Audit', href: route('audits.index') },
    { title: 'Detail', href: '#' }
]

const hasChanges = computed(() => {
    return props.audit.old_values && props.audit.new_values && 
           (Object.keys(props.audit.old_values).length > 0 || Object.keys(props.audit.new_values).length > 0)
})

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

function formatFieldName(field) {
    const fieldNames = {
        'name': 'Nama',
        'email': 'Email',
        'phone': 'Telepon',
        'is_active': 'Status Aktif',
        'password': 'Password',
        'created_at': 'Dibuat Pada',
        'updated_at': 'Diperbarui Pada',
        'deleted_at': 'Dihapus Pada'
    }
    return fieldNames[field] || field.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase())
}

function formatValue(value) {
    if (value === null || value === undefined) {
        return '-'
    }
    if (typeof value === 'boolean') {
        return value ? 'Ya' : 'Tidak'
    }
    if (typeof value === 'object') {
        return JSON.stringify(value)
    }
    return String(value)
}

function getChanges() {
    if (!props.audit.old_values || !props.audit.new_values) {
        return []
    }

    const changes = []
    const allKeys = new Set([
        ...Object.keys(props.audit.old_values || {}),
        ...Object.keys(props.audit.new_values || {})
    ])

    for (const key of allKeys) {
        const oldValue = props.audit.old_values[key]
        const newValue = props.audit.new_values[key]
        
        if (oldValue !== newValue) {
            changes.push({
                field: key,
                old: oldValue,
                new: newValue
            })
        }
    }

    return changes
}
</script> 