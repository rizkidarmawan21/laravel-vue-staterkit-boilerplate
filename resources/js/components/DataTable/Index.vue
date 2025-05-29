<script setup>
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table'
import Pagination from './Pagination.vue'
import { LoaderCircle } from 'lucide-vue-next'
const props = defineProps({
    // Data yang akan ditampilkan
    data: {
        type: Object,
        required: true,
        default: () => ({
            data: [],
            current_page: 1,
            per_page: 10,
            total: 0
        })
    },
    // Definisi kolom
    columns: {
        type: Array,
        required: true,
        // [{ key: 'name', label: 'Nama' }]
    },
    // Custom render untuk setiap kolom (opsional)
    columnRenderers: {
        type: Object,
        default: () => ({})
    },
    loading: {
        type: Boolean,
        default: false
    }
})

// Event untuk pagination
const emit = defineEmits(['update:pagination'])

const handlePaginationUpdate = (page, perPage) => {
    emit('update:pagination', { page, perPage })
}
</script>

<template>
    <div class="border">
        <Table>
            <TableHeader class="bg-slate-50">
                <TableRow>
                    <TableHead v-for="column in columns" :key="column.key" class="h-12! px-4!" :class="column.class">
                        {{ column.label }}
                    </TableHead>
                </TableRow>
            </TableHeader>
            <TableBody class="min-h-96!">
                <template v-if="loading">
                    <TableRow>
                        <TableCell class="h-96 px-4!" :colspan="columns.length">
                            <div class="flex flex-col justify-center items-center gap-1 text-slate-400">
                                <LoaderCircle class="animate-spin size-8" />
                                <span class="text-sm">Memuat Data...</span>
                            </div>
                        </TableCell>
                    </TableRow>
                </template>

                <template v-else-if="!loading && data.data.length > 0">
                    <TableRow v-for="(item, index) in data.data" :key="item.id">

                        <!-- Kolom lainnya -->
                        <TableCell v-for="column in columns" :key="column.key" class="h-12! px-4!"
                            :class="column.class">
                            <slot v-if="$slots[column.key]" :name="column.key" :item="item" />
                            <template v-else-if="columnRenderers[column.key]">
                                <component :is="columnRenderers[column.key]" :item="item" :value="item[column.key]" />
                            </template>
                            <template v-else-if="column.key === 'no'">
                                {{ ((data.current_page - 1) * data.per_page) + index + 1 }}
                            </template>
                            <template v-else>
                                {{ item[column.key] }}
                            </template>
                        </TableCell>
                    </TableRow>
                </template>
                <TableRow v-else>
                    <TableCell class="h-96 px-4! text-center" :colspan="columns.length">
                        Tidak ada data
                    </TableCell>
                </TableRow>
            </TableBody>
        </Table>
    </div>

    <!-- Pagination Component -->
    <Pagination :current-page="data.current_page" :per-page="data.per_page" :total="data.total"
        @update="handlePaginationUpdate" />
</template>
